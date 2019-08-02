


<?php

		/*Бинарный поиск значения по ключу в текстовом файле.
			
		Пограмма реализует поиск ключа следующим образом.

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
			- Позиция вычисляется исходя из данных о размере сканируемого участка, начала и конца подстроки. Чтобы попасть в середину левой или правой части массива.
		11. Рекурсивно вызываем туже функцию и передаем позиции курсора, размера сканируемой части массива и др.



		Программа не доработанна. Находит не все значения.

		*/

	$handle = fopen('lorem1.txt', rb) or die("Mistake");

					$filesize = filesize('lorem1.txt');
		echo 'filesize: '. $range = filesize('lorem1.txt');
		echo  '<br>startPosition: '.$startPosition = floor($range / 2);
		echo  '<br>key: '.$key = 'ключ184';
		 position($startPosition,  $range, $handle, $key, $filesize);


		function position($startPosition,  $range, $handle, $key, $filesize)
		{		


				fseek($handle, $startPosition);
			echo '<br>string: '. $string = fread($handle, 100);

				$subStringPosition = strpos($string, '\x0A') + 4;
			echo '<br>startPosition: '. $startPosition = $startPosition + $subStringPosition;

				fseek($handle, $startPosition);
				echo '<br>string after position: '.$string = fread($handle, 100);
				

				$subStringPosition = strpos($string, '\x0A');
				$endPosition = $startPosition + $subStringPosition;


				fseek($handle, $startPosition);
			 	$lenth = $endPosition - $startPosition;
				$string = fread($handle, $lenth);

				$arr = explode('\t', $string);


				if (strnatcmp($key, $arr[0]) == 0){
					echo '<br><br><br> Есть совпадение Ключ: '.$arr[0].'  Значение:'.$arr[1];
					return true;
				} 

				if (strnatcmp($key, $arr[0]) < 0 ){ //Идем налево
			
					echo '<br>range: '.$range = $startPosition;
					$nextPosition = floor($startPosition / 2);

			echo '<br><br>Налево nextPosition = '.$nextPosition.'  range = '.$range.'   handle = '.$handle;
					return position($nextPosition,  $range, $handle, $key, $filesize);
					
					}
				if (strnatcmp($key, $arr[0]) > 0 ){ //Идем направо
		
					 	$nextPosition = floor($endPosition + (($range - $endPosition) / 2));
			 echo '<br><br>Направо nextPosition = '.$nextPosition.'  endPosition = '.$endPosition.'  range = '.$range.'   handle = '.$handle;
				 if ($nextPosition > $filesize){
				 	echo '<br><br><br>Совпадений не найдено.';
					 return false;
				 } 
					  	return position($nextPosition,  $range, $handle, $key, $filesize);

				 }
				

				
		}

			


 ?>