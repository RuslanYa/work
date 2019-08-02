


<?php

		/*Бинарный поиск значения по ключу в текстовом файле.
			
		Пограмма реализует поиск ключа следующим образом.
		
		Из соображений занимаемой памяти (не более размера возможной максимальной записи) чтени файла проводится блочно.
		Т.к. записи не фиксированы позиция в файле при делении на два может разрывать отдельные записи(в дальнейшем 
		создает проблему которую я не решил). После позиционирования алгоритм реализует поиск ближайшей целой записи, считывания ее и сравниения с искомым ключом. В соответствии с принципом бинарного поиска переходит на соответствующую половину и все повторяется.

		1. Получает общий размер файла в байтах, вычисляем середину.
		2. Открываем файл переходим в середину файла (функция seek()).
		3. Блочно прочитываем 100 байт с текущей позиции(по условию задачи максимальная запись 4000 байт, можно 100 поменять на 4000 байт).
		4. Находим в прочитанной строке позицию '\x0A'.($startPosition).
		5. Находим  позицию следующего '\x0A'. ($endPosition).
		6. Вычисляем длину строки между символами '\x0A'.
		7. Прочитываем строку по найденой позиции и длине. ($endPosition, $lenth).
		8. Разделяем строку по симвову '/t' функцией explode().
		9. Проверяем соответствие ключа в массиве искомому(strnatcmp()).
		10.Блок проверок. 
			- Есть совпадения - возвращаем значения.
			- Если значение меньше идем по текстовому массиву налево, иначе направо, предварительно вычислив следующую позицию.
			- Позиция вычисляется исходя из данных о размере сканируемого участка, начала и конца подстроки, чтобы попасть в середину левой или правой части массива.
		11. Рекурсивно вызываем туже функцию и передаем позиции курсора, размера сканируемой части массива и др.



		Программа не доработанна. Находит не все значения потому, что входит в "колебательный режим". Искомая строчка с ключем\значением постоянно рвется и остается в левой и правой части.

		*/

		// Открываем файл, рассчитываем начальные значения для функции
		$handle = fopen('lorem1.txt', rb) or die("Mistake");
		$filesize = filesize('lorem1.txt');
		echo 'filesize: '. $range = filesize('lorem1.txt');
		echo  '<br>startPosition: '.$startPosition = floor($range / 2);
		echo  '<br>key: '.$key = 'ключ184';
		// Вызываем функцию
		position($startPosition,  $range, $handle, $key, $filesize);


		function position($startPosition,  $range, $handle, $key, $filesize)
		{		

				// Переходим в середину файла
				fseek($handle, $startPosition);
				// Считываем первый блок
				echo '<br>string: '. $string = fread($handle, 100);
				// Находим в прочитанной строке позицию '\x0A'
				$subStringPosition = strpos($string, '\x0A') + 4;
				echo '<br>startPosition: '. $startPosition = $startPosition + $subStringPosition;
				// Меняем позицию на конец символа '\x0A'
				fseek($handle, $startPosition);
				// Считываем блок текста с найденного начала записи ('\x0A')
				echo '<br>string after position: '.$string = fread($handle, 100);
				
				// Находим  позицию следующего '\x0A'
				$subStringPosition = strpos($string, '\x0A');
				// Фиксируем конец найденной записи
				$endPosition = $startPosition + $subStringPosition;

				// Возвращаемся к началку записи и считываем ее
				fseek($handle, $startPosition);
			 	$lenth = $endPosition - $startPosition;
				$string = fread($handle, $lenth);
				// Извлекаем запись в массив и проводим сравнения
				$arr = explode('\t', $string);

				// Вывод ключа и значения в случае совпадения
				if (strnatcmp($key, $arr[0]) == 0){
					echo '<br><br><br> Есть совпадение Ключ: '.$arr[0].'  Значение: '.$arr[1];
					return true;
				} 
				// Идем налево
				if (strnatcmp($key, $arr[0]) < 0 ){ 
			
					echo '<br>range: '.$range = $startPosition;
					$nextPosition = floor($startPosition / 2);

					echo '<br><br>Налево nextPosition = '.$nextPosition.'  range = '.$range.'   handle = '.$handle;
					return position($nextPosition,  $range, $handle, $key, $filesize);

				}
				//Идем направо
				if (strnatcmp($key, $arr[0]) > 0 ){ 
		
					 	$nextPosition = floor($endPosition + (($range - $endPosition) / 2));
				 		echo '<br><br>Направо nextPosition = '.$nextPosition.'  endPosition = '.$endPosition.'  range = '.$range.'   handle = '.$handle;
					 // Это костыль. Если Ключ не будет найден и уйдет за пределы размера файла, 
					 // выполнение прервется с выводом соответствующего сообщения
					if ($nextPosition > $filesize){
					 	echo '<br><br><br>Совпадений не найдено.';
						return false;
				 	} 
				 	// Рекурсивный вызов функции самой себя
					 return position($nextPosition,  $range, $handle, $key, $filesize);

				}
				

				
		}

			


 ?>
