# Для Wikidata:
# Создаёт список для создания элементов и их свойств, для использования в интструменте http://tools.wmflabs.org/wikidata-todo/quick_statements

# Списки свойств.
# Формат: '["свойство", "значение"]' в двойных кавычках. Текст
# Квалификаторы продолжаются на той же строке внутри того же "[,]", в том же формате через запятую.

# Общие свойства для всех элементов (чтобы не дублировать).
common_claims = [
		# ['P31', 'Q3331189'],    # это частный случай понятия : издание
		# ['P361', 'Q23705304'],  # часть от : Толковый словарь В. Даля
		# ['P407', 'Q7737'],      # язык произведения или его названия : русский язык
		# ['P291', 'Q656'],       # место публикации : Санкт-Петербург
		# ['P98', 'Q335092'],     # редактор : Иван Александрович Бодуэн де Куртенэ
		# ['P123', 'Q26202353'],  # издатель : Издательство М.О. Вольфа
		# ['P50', 'Q326499'],     # автор : Владимир Иванович Даль
		# 'P629']  : "Q1970746",  # является изданием или переводом : Толковый словарь В. Даля
		# ['P1476', 'ru:"Толковый словарь живого великорусского языка"'],  # название
]

# Создавать ли новые элементы? False - редактировать существующие указанные в массиве ниже ниже, True - создавать новые, id-номера ниже в private_claims[][0]
do_create_new_items = False

# Уникальные свойства для каждого элемента. Сколько списков столько будет элементов.
private_claims = [
	[
		'Q23705304',	# издание
		# Для редактирования уже существующих элементов их id. Игнорируется если do_create_new_items = False.
		[
			# ['P393', "3"],            # номер издания, для томов и статей не надо
			# ['Lru', "Толковый словарь В. Даля 3-е изд."],  # название элемента
			# P1476 : 'ru:"Толковый словарь живого великорусского языка"',  # название
			# ['P577', '+1903-01-17T00:00:00Z/09'],  # дата публикации
			# ['P1815', '01003972235'],  # идентификатор сканированного издания РГБ
			# ['P361', 'Q23705304', 'P155', '', 'P156', 'Q26203084'],
			# часть от : Толковый словарь В. Даля  # предыдущее по порядку   # следующее по порядку
			['P527', 'Q26203083'],  # состоит из, для списков томов в элементах изданий
			['P527', 'Q26203084'],
			['P527', 'Q26203085'],
			['P527', 'Q26203086'],
		]
	],
	# ---------------------------- ниже элементы сущностей более мелкого разряда
	# [
	# 	'Q26203083',  # Для редактирования уже существующих элементов их id. Игнорируется если do_create_new_items = False.
	# 	[
	# 		# ['Lru', "Толковый словарь В. Даля 3-е изд., том 1"],  # название элемента
	# 		# P1476 : 'ru:"Толковый словарь живого великорусского языка"',  # название
	# 		# ['P478', '1'],     # том
	# 		# ['P577', '+1903-01-17T00:00:00Z/09'],  # дата публикации
	# 		# ['P996', 'ru:"Толковый словарь. Том 1 (Даль 1903).djvu"'],  # файл с отсканированными данными
	# 		# ['P1957', "https://ru.wikisource.org/wiki/Индекс:Толковый_словарь._Том_1_(Даль_1903).djvu"],  # индексная страница Викитеки
	# 		# ['P1815', '01003972235'],  # идентификатор сканированного издания РГБ
	# 		['P361', 'Q23705304', 'P155', '', 'P156', 'Q26203084'],  # часть от : Толковый словарь В. Даля  # предыдущее по порядку   # следующее по порядку
	# 	]
	# ],
	# [
	# 	'Q26203084',
	# 	[
	# 		# ['Lru', "Толковый словарь В. Даля 3-е изд., том 2"],  # название элемента
	# 		# ['P478', '2'],     # том
	# 		# ['P577', '+1905-01-17T00:00:00Z/09'],  # дата публикации
	# 		# ['P996', 'ru:"Толковый словарь. Том 2 (Даль 1905).djvu"'],  # файл с отсканированными данными
	# 		# ['P1957', "https://ru.wikisource.org/wiki/Индекс:Толковый_словарь._Том_2_(Даль_1905).djvu"],  # индексная страница Викитеки
	# 		# ['P1815', '01003972234'],  # идентификатор сканированного издания РГБ
	# 		['P361', 'Q23705304', 'P155', 'Q26203083', 'P156', '01003972235'],  # часть от : Толковый словарь В. Даля  # предыдущее по порядку   # следующее по порядку
	# 	]
	# ],
	# [
	# 	'Q26203085',
	# 	[
	# 		# ['Lru', "Толковый словарь В. Даля 3-е изд., том 3"],  # название элемента
	# 		# ['P478', '3'],     # том
	# 		# ['P577', '+1907-01-17T00:00:00Z/09'],  # дата публикации
	# 		# ['P996', 'ru:"Толковый словарь. Том 3 (Даль 1907).djvu"'],  # файл с отсканированными данными
	# 		# ['P1957', "https://ru.wikisource.org/wiki/Индекс:Толковый_словарь._Том_3_(Даль_1907).djvu"],  # индексная страница Викитеки
	# 		# ['P1815', '01003972233'],  # идентификатор сканированного издания РГБ
	# 		['P361', 'Q23705304', 'P155', 'Q26203084', 'P156', 'Q26203086'],  # часть от : Толковый словарь В. Даля  # предыдущее по порядку   # следующее по порядку
	# 	]
	# ],
	# [
	# 	'Q26203086',
	# 	[
	# 		# ['Lru', "Толковый словарь В. Даля 3-е изд., том 4"],  # название элемента
	# 		# ['P478', '4'],     # том
	# 		# ['P577', '+1909-01-17T00:00:00Z/09'],  # дата публикации
	# 		# ['P996', 'ru:"Толковый словарь. Том 4 (Даль 1909).djvu"'],  # файл с отсканированными данными
	# 		# ['P1957', "https://ru.wikisource.org/wiki/Индекс:Толковый_словарь._Том_4_(Даль_1909).djvu"],  # индексная страница Викитеки
	# 		# ['P1815', '01003972232'],  # идентификатор сканированного издания РГБ
	# 		['P361', 'Q23705304', 'P155', 'Q26203085', 'P156', ''],  # часть от : Толковый словарь В. Даля  # предыдущее по порядку   # следующее по порядку
	# 	]
	# ],
]

result = ''
tab = '\t'  # Табуляция - разделитель значений внутри строки, по формату инструмента Quick_statements
br = '\n'
for private_claims_item in private_claims:
	list = []
	if do_create_new_items:
		list = ['CREATE']                              # Создаёт новый элемент
		item_number = 'LAST'
	else:
		item_number = private_claims_item[0]
	for claim in common_claims:
		list.append(item_number + tab + tab.join(claim))  # 'LAST' - добавление свойства к созданному элементу
	for claim in private_claims_item[1]:
		list.append(item_number + tab + tab.join(claim))
	result += br + br.join(list)                 # Новая строка - разделить команд и claims
print(result)