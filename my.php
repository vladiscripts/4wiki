﻿<?php // <head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /></head>

// вывод на экран форматированный
function showarray($t) {echo '<pre>'.print_r($t,1).'</pre>';}

// В верхний регистр 1-ю букву строки
function mb_ucfirst ($word) {
	return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr(mb_convert_case($word, MB_CASE_LOWER, 'UTF-8'), 1, mb_strlen($word), 'UTF-8'); 
}


// запись файла csv
function fsave_csv($fout, $text, $fields_separator=null) {
	$fp = fopen($fout,'w'); if ($fp) {
	//fputcsv($fp, $titles);
	foreach ($text as $line) { 
		fputcsv($fp, $line); //explode($fields_separator, $line)); 
	}
	fclose($fp);}
}

// запись файла
function fsave($fname, $text) {
	$fs=fopen($fname,'wt');
	 if (fwrite($fs, $text) === FALSE) {echo "Не могу произвести запись в файл ($fname)";exit;}
	fclose($fs);
}

// mysql инициация
$mysql_init = "SET NAMES 'utf8'; SET CHARACTER SET 'utf8'; SET SESSION collation_connection = 'utf8_general_ci'; SET TIME_ZONE = '+03:00'";

// в дореформенную орфографию
function toDO($t) {
	$t = str_ireplace('{{акут}}','́', $t);	$t = str_ireplace('{{акут3}}','ˊ', $t); // замена {{акут}} на символ ударений, чтобы регуляркам не мешали '{' и много символов.

	$s = 'цкнгшщзхфвпрлджчсмтб'; 					// согласные буквы, кроме 'ьъ'
	$a = 'ёйцукенгшщзхъфывапролджэячсмитьбюѣѵіѳ'; 	// весь алфавит
	$pr = '\b(?:при|на|пере|по|под|за|м|из|в)'; 		// приставки

	// ъ в конце слов после согласных
	$t = preg_replace("/([".$s."])([][ ,?!:;'\"()*<])/u", "$1ъ$2", $t);
	$t = preg_replace("~([".$s."])(\.) *$~u", "$1ъ$2", $t); // перед точкой в конце текста
		$t = preg_replace("~([".$s."])(\.)( *\{\{tsdbr)~u", "$1ъ$2$3", $t); // исключения
	$t = preg_replace("~([".$s."])(\. +)(?!</small|[".$a."])~u", "$1ъ$2", $t); // ъ и перед точками (в конце предложений), за исключением что это сокращение. Сокращение определяется: если за словом идёт слово со строчной буквы (т.е. это не конец предложения), или слово заключено в тэг </small>.
		$t = preg_replace('~\b(с)([мв])ъ\.~ui','$1$2.', $t); // иногда проскакивающие сокращения
		$t = preg_replace('~астенъ\.~u','астен.', $t);
		$t = preg_replace("/(г)лагъ./ui", "$1лаг.", $t);
		$t = preg_replace("/\bпадъ./u", "пад.", $t);
		$t = preg_replace("/\bюж(н)?ъ./u", "юж$1.", $t);
		$t = preg_replace("/\b(ж|м|ср|мн)ъ\./u", "$1.", $t);
		$t = preg_replace("~\((\w?\w?[".$s."])ъ\)(\w)~u", "($1)$2", $t); // убирание ъ перед скобками
	$t = preg_replace("~ъ([()])~u", "$1", $t); // исключение: перед или в скобках посреди слов ъ не ставить
	$t = preg_replace("~([Ии])з-за~u", "$1зъ-за", $t); // исключение: перед или в скобках посреди слов ъ не ставить


	// окончания
	// і славянская
	$t = preg_replace("/(\w)ий(ся)?\b/u", "$1ій$2", $t);
	$t = preg_replace("/(\w)кие\b/u", "$1кіе", $t);
	$t = preg_replace("/(\w)ни([яю])\b/u", "$1ні$2", $t);
	$t = preg_replace("/(\w)и([ияе])\b/u", "$1і$2", $t);
	// ѣ
	$t = preg_replace("/(\w)еть(ся)?\b/u", "$1ѣть$2", $t);
	$t = preg_replace("/(\w)евать\b/u", "$1ѣвать", $t);
	$t = preg_replace("/(\w)енный\b/u", "$1ѣнный", $t);
	$t = preg_replace("/(\w)ого\b/u", "$1аго", $t);
		// исключения
		$t = preg_replace("/\b(в)сякаго\b/ui", "$1сякого", $t);
		$t = preg_replace("/\b(э)таго\b/ui", "$1того", $t);
		$t = preg_replace("/\b(?<!вся)(к)аго\b/ui", "$1ого", $t);
		$t = preg_replace("/\b(м)наго\b/ui", "$1ного", $t);
		$t = preg_replace("/\b((?:от)?т)аго\b/ui", "$1ого", $t);
	$t = preg_replace("/стве/u", "ствѣ", $t);
	$t = preg_replace("/ели([аи])\b/u", "ѣли$1", $t);
	$t = preg_replace("/евать\b/u", "ѣвать", $t);


	//приставки
	$t = preg_replace("/(и)сс/u", "$1зс", $t);
	$t = preg_replace("/\b(р)асс/ui", "$1азс", $t);
	$t = preg_replace("/\b(б)есс/ui", "$1езс", $t);


	// ѣ
	// ѣ в конце слов после согласных
	$t = preg_replace("~([".$s."у])е(?![-{])\b~u", "$1ѣ", $t); // ѣ в конце слов
		// исключения
		$t = preg_replace("~\b([НнЖж])ѣ\b~u", "$1е", $t);
		$t = preg_replace("~\b([Чч]етыр)ѣ\b~u", "$1е", $t);
		$t = preg_replace("~\b([Вв]ообщ)ѣ\b~u", "$1е", $t);
		$t = preg_replace("~\b([Пп]режд)ѣ\b~u", "$1е", $t);
		$t = preg_replace("~\b([Бб]ож)ѣ~u", "$1е", $t);
		$t = preg_replace("~\b([Бб]ольш)ѣ\b~u", "$1е", $t);
		$t = preg_replace("~\b([Лл]учш)ѣ\b~u", "$1е", $t);
		$t = preg_replace("~\b([Ее]щ)ѣ\b~u", "$1е", $t);
		$t = preg_replace("~\b((?:[Пп]о)?[Нн]иж)ѣ\b~u", "$1е", $t);
	$t = preg_replace("~и([оею])~u", "і$1", $t);	// іо   - i перед глассной
	$t = preg_replace("~(\w)е([е])~u", "$1ѣ$2", $t);	// ѣе   - ѣ перед е
		$t = preg_replace("~(\w)дущѣе~u", "$1дущее", $t);	// иключения
	$t = preg_replace("/\b(с)ев(ер)?\b/ui", "$1ѣв$2", $t);
	$t = preg_replace("/\b(н)ем(ецк)?\b/ui", "$1ѣм$2", $t);
	$t = preg_replace("/\bи пр\b/u", "ипр", $t);
	$t = preg_replace("/\b(в)иде\b/ui", "видѣ", $t);
	$t = preg_replace("/\b(м)ехъ\b/ui", "мѣхъ", $t);
	$t = preg_replace("/\b(б)олее\b/ui", "$1олѣе", $t);
	$t = preg_replace("/\b(м)енее\b/ui", "$1енѣе", $t);
	$t = preg_replace("/\b(е)[ёе]\b/ui", "$1я", $t);
	$t = preg_replace("/\b([кчт])емъ\b/ui", "$1ѣмъ", $t);
		$t = preg_replace("/\b(г)де\b/ui", "$1дѣ", $t);
	//$t = preg_replace("/\b(в)се\b/ui", "$1сѣ", $t);
	$t = preg_replace("/\b((?:во)?в)сѣ\b/ui", "$1се", $t);
	$t = preg_replace("/(в)се([хм])ъ\b/ui", "$1сѣ$2ъ", $t);
	$t = preg_replace("/(н)иче([мг])/ui", "$1ичѣ$2", $t);
	$t = preg_replace("/\b(д)ве\b/ui", "$1вѣ", $t);
	$t = preg_replace("/\b(д)ейст/ui", "$1ѣйст", $t);
	$t = preg_replace("/(с)ве([тч])/ui", "$1вѣ$2", $t);
	$t = preg_replace("/\bИордан\b/u", "Іордан", $t);
	$t = preg_replace("/\bИоанн\b/u", "Іоанн", $t);
	$t = preg_replace("/(г)ре([хш])/ui", "$1рѣ$2", $t);
	$t = preg_replace("/\b(н)едел\b/ui", "$1едѣл", $t);
	$t = preg_replace("/\b(н)еделе\b/ui", "$1едѣлѣ", $t);
	$t = preg_replace("/\b(х)леб/ui", "$1лѣб", $t);
	$t = preg_replace("/\b(з)вѣр/ui", "$1вѣр", $t);
	$t = preg_replace("/\b(у)мер[ѣе]т/ui", "$1мерет", $t);
	$t = preg_replace("/\b(р)ек[еѣ]/ui", "$1ѣкѣ", $t);
		$t = preg_replace("/\b([Рр])ек(а|ой|у)/u", "$1ѣк$2", $t);
		$t = preg_replace("/\b([Рр])еч(е?н|к|уш)/u", "$1ѣч$2", $t);
	$t = preg_replace("/ристиан/u", "ристіан", $t);
	$t = preg_replace("/(ч)еловек/ui", "$1еловѣк", $t);
	$t = preg_replace("/(с)осед/ui", "$1осѣд", $t);
	$t = preg_replace("/(т)акжѣ/ui", "$1акже", $t);
	$t = preg_replace("/(з)атем/ui", "$1атѣм", $t);
	$t = preg_replace("/(з)де(сь|ш|в)/ui", "$1дѣ$2", $t);
	$t = preg_replace("/(в)[еѣ]зд[еѣ]/ui", "$1ѣзде", $t);
	$t = preg_replace("/(н)ескольк/ui", "$1ѣскольк", $t);
	$t = preg_replace("/(в)ечн/ui", "$1ѣчн", $t);
	$t = preg_replace("/(д)иавол/ui", "$1іавол", $t);
	$t = preg_replace("/(з)авет/ui", "$1авѣт", $t);
	$t = preg_replace("/(?<!се)(в)ерн/ui", "$1ѣрн", $t);
	$t = preg_replace("/(в)ет(е?)р/ui", "$1ѣт$2р", $t);
	$t = preg_replace("/(п)овер/ui", "$1овѣр", $t);
	$t = preg_replace("/(к)олен/ui", "$1олѣн", $t);
	$t = preg_replace("/(в)месте/ui", "$1мѣстѣ", $t);
	$t = preg_replace("/\b(м)есто\b/ui", "$1ѣсто", $t);
	$t = preg_replace("/(м)ест/ui", "$1ѣст", $t);
	$t = preg_replace("/(м)ещ/ui", "$1ѣщ", $t);
	$t = preg_replace("/\b(д)оме\b/ui", "$1омѣ", $t);	
	$t = preg_replace("/(ц)ве([тлс])/ui", "$1вѣ$2", $t);
	$t = preg_replace("/(с)емен/ui", "$1ѣмен", $t);
	$t = preg_replace("/(ж)елез/ui", "$1елѣз", $t);	
	$t = preg_replace("/(д)ел/ui", "$1ѣл", $t);
	$t = preg_replace("/(д)[еѣ]ле/ui", "$1ѣлѣ", $t);
	$t = preg_replace("/(с)евер/ui", "$1ѣвер", $t);
	$t = preg_replace("/(п)римет/ui", "$1римѣт", $t);
	$t = preg_replace("/(?<![Кк]о|х)([Лл])ес/u", "$1ѣс", $t);
	$t = preg_replace("/([Сс])е([яю])/u", "$1ѣ$2", $t);
	$t = preg_replace("/(м)едвед/ui", "$1едвѣд", $t);
	$t = preg_replace("/(?<!че|бе)(р)ез/ui", "$1ѣз", $t);
	$t = preg_replace("/(д)ет(с?к|е)/ui", "$1ѣт$2", $t);
	$t = preg_replace("/(д)ев(к|уш)/ui", "$1ѣв$2", $t);
	$t = preg_replace("/Спасе/u", "Спасѣ", $t);
	$t = preg_replace("/\b(?<!к)(л)ет/ui", "$1ѣт", $t);
	$t = preg_replace("/(".$pr."с)тен/ui", "$1тѣн", $t);
	$t = preg_replace("/(п)ривет/ui", "$1ривѣт", $t);
	$t = preg_replace("/(с)леп/ui", "$1лѣп", $t);
	$t = preg_replace("/(с)ле([дж])/ui", "$1лѣ$2", $t);
	$t = preg_replace("/(с)пел/ui", "$1пѣл", $t);
	$t = preg_replace("/(б)есед/ui", "$1есѣд", $t);
	$t = preg_replace("/(о)днихъ/ui", "$1днѣхъ", $t);
	$t = preg_replace("/\b([Нн])[еѣ]тъ?\b/u", "$1ѣтъ", $t);
	$t = preg_replace("/\b(в)ек/ui", "$1ѣк", $t);
	$t = preg_replace("/\b(?<!у)(".$pr."м)ер([аыуе]\b|[ио])/ui", "$1ѣр$2", $t);
	$t = preg_replace("/(у)бедит/ui", "$1бѣдит", $t);
	$t = preg_replace("/(л)ев(о|ш|ы)/ui", "$1ѣв$2", $t);
	$t = preg_replace("/(с)пев/ui", "$1пѣв", $t);
	$t = preg_replace("/(с)иден/ui", "$1идѣн", $t);
	$t = preg_replace("/(р)ек([аиеѣу])/ui", "$1ѣк$2", $t);
	$t = preg_replace("/(?<!г|огу)(р)ечн/ui", "$1ѣчн", $t);
	$t = preg_replace("/(с)ъед/ui", "$1ъѣд", $t);
	//$t = preg_replace("/\bест([ъь])/u", "ѣст$1", $t);	$t = preg_replace("/\bЕст([ъь])/u", "Ѣст$1", $t);
	$t = preg_replace("/\bестъ\b/u", "ѣстъ", $t);	$t = preg_replace("/\bЕстъ\b/u", "Ѣстъ", $t);
	$t = preg_replace("/\bемъ\b/u", "ѣмъ", $t);	$t = preg_replace("/\bЕмъ\b/u", "Ѣмъ", $t);
	$t = preg_replace("/\bед([уеаояиюъ])/u", "ѣд$1", $t);	$t = preg_replace("/\bЕд([уеаояиюъ])/u", "Ѣд$1", $t);
	$t = preg_replace("/\bешь/u", "ѣшь", $t);	$t = preg_replace("/\bЕшь/u", "Ѣшь", $t);
	$t = preg_replace("/(?<!вб)езд/u", "ѣзд", $t);	$t = preg_replace("/Езд/u", "Ѣзд", $t);
	$t = preg_replace("/(м)ена/ui", "$1ѣна", $t);
	$t = preg_replace("/(с)ме([хшя])/ui", "$1мѣ$2", $t);
	$t = preg_replace("/(с)не([жг])/ui", "$1нѣ$2", $t);
	$t = preg_replace("/(с)веж/ui", "$1вѣж", $t);
	$t = preg_replace("/(б)лед/ui", "$1лѣд", $t);
	$t = preg_replace("/(?<!колы)(б)ел/ui", "$1ѣл", $t);
	$t = preg_replace("/(г)нев/ui", "$1нѣв", $t);
	$t = preg_replace("/(о)твет/ui", "$1твѣт", $t);
	$t = preg_replace("/(о)бед/ui", "$1бѣд", $t);
	$t = preg_replace("/(з)вер/ui", "$1вѣр", $t);
	$t = preg_replace("/(к)реп/ui", "$1рѣп", $t);
	$t = preg_replace("/(с)есть/ui", "$1ѣсть", $t);
	$t = preg_replace("/(р)ез/ui", "$1ѣз", $t);
	$t = preg_replace("/(т)ѣл([оаеу])/ui", "$1ѣл$2", $t);
	$t = preg_replace("/(б)ед([ноаеѣу])/ui", "$1ѣд$2", $t);
	$t = preg_replace("/(в)стреч/ui", "$1стрѣч", $t);
	$t = preg_replace("/(в)ест/ui", "$1ѣст", $t);
	$t = preg_replace("/\b(т)ест/ui", "$1ѣст", $t);
	$t = preg_replace("/(к)опейк/ui", "$1опѣйк", $t);
	$t = preg_replace("/(б)олез/ui", "$1олѣз", $t);
	$t = preg_replace("/(".$pr."в)ѣст/ui", "$1ѣст", $t);



	$t = str_replace("на ть и на ся", "на ''ть'' и на ''ся''", $t);
	$t = str_replace("действ.", "дѣйст.", $t);
	$t = str_replace("по глаг.", "по гл.", $t);


	$t = str_replace('́','{{акут}}', $t);	$t = str_replace('ˊ','{{акут3}}', $t); // замена ударений на {{акут}}
	return $t;
}

?>