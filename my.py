﻿# -*- coding: utf-8  -*-
import re
import mwparserfromhell


def removeTplParameters(tpl, keys):
	# if type(keys) == str: keys = (keys,)
	for k in keys:
		if tpl.has(k): tpl.remove(k)

def parametersNamesList(tpl):
	pnamelist = []
	for p in tpl.params:
		pnamelist.append(p.name.strip())
	return pnamelist

def removeTplParametersExceptThis(tpl, keys):
	pnamelist = parametersNamesList(tpl)
	toRemoveList = [p for p in pnamelist  if p not in keys]
	removeTplParameters(tpl, toRemoveList)

def renameParam(tpl, name, newname):
	if tpl.has(name):
		tpl.get(name).name = newname

def renameTemlate(tpl, newname):
	tpl.name = newname

def findLink(tpl, link2remove, linkparameters = ('',)):
	linkparameters = ('ссылка', 'url', 'часть', 'ссылка часть', 'часть ссылка') + linkparameters
	# print (linkparameters)
	for p in linkparameters:
		if not tpl.has(p): continue
		s = str(tpl.get(p).value)
		if re.search(link2remove, s):
			return True

def findAndDeleteLink(tpl, link2remove, linkparameters = ('',)):
	linkparameters = ('ссылка', 'url', 'ссылка часть', 'часть ссылка') + linkparameters
	# print (linkparameters)
	for p in linkparameters:
		if not tpl.has(p): continue
		s = str(tpl.get(p).value)
		if re.search(link2remove, s):
			tpl.remove(p, True)
			return True

def deleteParamArhiveurlDateIfWebarchive(tpl):
	l = 'web.archive.org'
	p = 'archiveurl'
	if not tpl.has(p): return
	s = str(tpl.get(p).value)
	if re.search(l, s):
		removeTplParameters(tpl, ('archiveurl', 'archivedate'))

def link2template (code, regexpSearchLink, newTplName, TplUrlParameter, TplLinktitleParameter, reRemoveFromTitles):	
	# code = mwparserfromhell.parse(text)
	reLink = re.compile(regexpSearchLink)	
	for link in code.filter_external_links():
		
		if not reLink.search(str(link.url)): continue
		tpl = mwparserfromhell.nodes.template.Template(newTplName)
		tpl.add(TplUrlParameter, link.url)
		if reRemoveFromTitles:
			for r in reRemoveFromTitles:
				link.title = re.sub(r, '', str(link.title))
		if not re.match('^\s*$', str(link.title)):
			tpl.add(TplLinktitleParameter, str(link.title))
		# newstr = '{{' + newTplName + '|' + TplUrlParameter + '=' + str(link.url) + '|name=' + str(link.title) + '}}'
		code.replace(link, str(tpl))
		# print(str(tpl))
	# return str(code)
	
	

def deleteEmptyParam(tpl, keys):
	for k in keys:
		if tpl.has(k) and re.match('^\s*$', str(tpl.get(k).value)):		# and re.match('^(?:\s*|БСЭ|Большая советская энциклопедия)$', str(tpl.get(k).value)):
			tpl.remove(k)

def removeSpacesBreaks(tpl):
	# удаление лишних пробелов
	p = tpl.params

	reBr = re.compile(r'\n *')
	reSp = re.compile(' {2,}')
	for i in range(len(p)):
		s = str(p[i])
		s = reBr.sub('', s)
		s = reSp.sub(' ', s)
		p[i] = s

def replaceParamValue(tpl, parameter, rePattern, repl):
	if not tpl.has(parameter): return
	s = str(tpl.get(parameter).value)
	tpl.get(parameter).value = re.sub(rePattern, repl, s)

def paramIsEmpty (tpl, parameter):
	if re.match('^\s*$', str(tpl.get(parameter).value)):  return True

def pagenameFromParameter(regexp, s):
	n = re.findall(regexp, s)
	if n:
		n = n[0]
		if not re.match('^[\d\s]+$', n): return n

def paramValueFromLinkOrPagename(tpl, parameter, link, regexp, PageNameArg=False):
	links = ('ссылка', 'url', 'ссылка часть', link)
	print(link)
	for link in links:
		if tpl.has(link):
			import urllib.request
			link = str(tpl.get(link).value)
			link = urllib.request.unquote(link)
			n = pagenameFromParameter(regexp, link)
			if not n: return

			# n = re.findall(regexp, link)
	# if n:
		# n = n[0]
		# if re.match('^[\d\s]+$', n): return
	# # else:	n = sys.argv[1]			
			# if PageNameArg:				
			tpl.add(parameter, n)
			# else: 	tpl.get(parameter).value = PageNameArg
	# print(n)
	# print(link)

def separateLinkFromPartParameter(tpl):
	part = 'часть'
	link = 'ссылка часть'
	regexp = r'\[(http[^]\s]+)\s+(.+?)\]'
	if tpl.has(part):
		s = str(tpl.get(part).value)
		n = re.findall(regexp, s)
		if n:
			tpl.add(link, n[0][0])
			tpl.get(part).value = n[0][1]