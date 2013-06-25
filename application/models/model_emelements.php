<?php

class Model_Emelements extends Model
{

	/////////////////////Загрузка файла измерений//////////////////
	public function upload()
	{
		if ($this->check_session()) 
		{
			$user_status = $_SESSION['user_status'];
			if ($user_status>3)
			{
				$uploadStatus['code'] = 0;
				$uploadStatus['label']="";
				$filePath="";
				 if($_FILES["filename"]["size"] > 1024*1*1024)
				 {
					 $uploadStatus['label']="Размер файла превышает 1 Мб";
				 }
				  // Проверяем загружен ли файл
				  if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
				  {
					 // Если файл загружен успешно, перемещаем его
					 // из временной директории в конечную
					 if (move_uploaded_file($_FILES["filename"]["tmp_name"], "files/measures/".iconv("UTF-8",  "CP1251", $_FILES["filename"]["name"])))
					 {
					 	 $filePath = "files/measures/".iconv("UTF-8",  "CP1251", $_FILES["filename"]["name"]);
					 	 $uploadStatus['label'] = "Файл успешно сохранён на сервере";
						 $uploadStatus['code'] = 1;
						 //Передаём файл функции-парсеру, которая запишет данные в БД
						 $uploadStatus['parsingResult'] = $this->readMeasureFile($filePath, $_POST['emelementId'], $_POST['fileType'],$_POST['rewrite']);
					 }
					 else
					 {
					 	$uploadStatus['label'] = "Ошибка при сохранении файла на сервере";
					 }
				  } 
				  else 
				  {
					  $uploadStatus['label']="Ошибка загрузки файла";
				  }
				  return $uploadStatus;
			};
		};
	}



	////////////////////////////////////////////////////////////
	public function get_data()
	{
		if ($this->check_session()) 
		{
			$user_status = $_SESSION['user_status'];
			if ($user_status>$SUPERUSER)
			{
				
				// 1) Модифицируем запрос  при заданых параметрах фильтра
				// 2) Когда без фильтра - выводим последние 30
						
				//Запросим счетчики по очереди: 1) Частные 2) По подъезду  3) По дому
				$emelements = $this->evaluate_Query('	(SELECT emelement.id, emelement.sn, c_emelementtype.label, emelement.descr, emelement.cRatio, 
														emelement.vRatio, street.name AS streetName, porch.no AS porchNo, flat.no AS flatNo
														FROM emelement, building, street, porch, flat, c_emelementtype
														WHERE street.id=building.street_id AND emelement.building_id=building.id
														AND emelement.porch_id = porch.id AND emelement.flat_id = flat.id)
														UNION
														(SELECT DISTINCT emelement.id, emelement.sn, c_emelementtype.label, emelement.descr, emelement.cRatio,
														emelement.vRatio, street.name AS streetName, porch.no AS porchNo, "-" AS flatNo
														FROM emelement, building, street, porch, flat, c_emelementtype
														WHERE street.id=building.street_id AND emelement.building_id=building.id
														AND (emelement.porch_id = porch.id) AND (emelement.flat_id IS NULL))
														UNION
														(SELECT DISTINCT emelement.id, emelement.sn, c_emelementtype.label, emelement.descr, emelement.cRatio, 
														emelement.vRatio, street.name AS streetName, "-" AS porchNo, "-" AS flatNo
														FROM emelement, building, street, porch, flat, c_emelementtype
														WHERE street.id=building.street_id AND emelement.building_id=building.id
														AND (emelement.porch_id IS NULL) AND (emelement.flat_id IS NULL))');

																										
				$data = $emelements;

			}
			else
			{
				header('Location:/login');;
			}
		}
		else
		{
			header('Location:/login');
		}
		return $data;
		
	}
	
	//////////////////////////Парсинг файла с измерениями////////////////////////////////////////
	private function readMeasureFile($filePath, $emelementId, $fileType = NULL, $rewrite = FALSE)
	{
		$handle = fopen ($filePath, "r");
		//Переменные для статистики по файлу
		$count = 0; 		//Количество считанных записей
		$ignoredCount = 0; 	//Количество тех, то не перезаписали
		$startDate = 0;		//Первая дата в файле
		$endDate = 0;		//Последняя дата в файле
		
		//На вход могут поступать разные типы файлов, с различной структурой
		if  ($fileType == "HTML") 
		{	
			//Далее последует множество запросов к БД, использовать нашу evaluate_Query здесь слишком затратно по времени
			//так как в этой функции подключение к БД создаётся и закрывается при каждом вызове. 
			//Поэтому создадим отдельный линк до конца алгоритма и будем пользоваться им
			$link = mysql_connect($this->db_loc,$this->db_user_adm,$this->db_pass_adm);
			mysql_query('SET NAMES utf8');
			mysql_select_db($this->db_name, $link);
		
			//Построчное чтение файла
			//Чтобы выиграть во времени, будем формировать INSERT строку запроса из нескольких строк файла
			//Поэтому значения из строки файла записываем в базу не сразу, а по накоплении N штук (10, 50, 100...)
			$IC = 0; //Здесь количество строк, добавляемых в таблицу
			$INSERT = ""; //Здесь итоговая строка с VALUES для SQL запроса INSERT
			
			while (!feof ($handle))
			{
				//Считываемые данные
				$Ap=0; $Am=0; $Rp=0; $Rm=0; $time=0; $date=0;		
					
				$buffer = fgets($handle, 4096);
				if (substr($buffer, 0, 3) == "<TD")	
				{
					//Нашли начало строки, разнесём содержимое ячеек по переменным
					$buffer = fgets($handle, 4096);	$Ap = str_replace("\r\n","",strip_tags($buffer));			//А+
					$buffer = fgets($handle, 4096);	$Am = str_replace("\r\n","",strip_tags($buffer));			//А-	
					$buffer = fgets($handle, 4096);	$Rp = str_replace("\r\n","",strip_tags($buffer));			//R+	
					$buffer = fgets($handle, 4096);	$Rm = str_replace("\r\n","",strip_tags($buffer));			//R-
					//Получаем время и немного исправляем
					$buffer = fgets($handle, 4096); $time = str_replace("\r\n","",strip_tags($buffer).":00");	//Time
					$buffer = fgets($handle, 4096); 															//Date
					//Преобразуем дату в формат MySQL
					$date =  $this->ToMySQLDate($buffer);		
					//Пропускаем оставшиеся строчки	
					$buffer = fgets($handle, 4096);	$buffer = fgets($handle, 4096);
					$buffer = fgets($handle, 4096); 
					$count++;
					if ($startDate == 0) {$startDate=$date." ".$time;};
					$endDate = $date." ".$time;
					
					$IC++;
					$INSERT = $INSERT.'(NULL, MD5(CONCAT("'.$emelementId.'","'.$date.'","'.$time.'")),"'.$emelementId.'","'.$date.'","'.$time.'","'.$Ap.'"),';
					//Если собрали 50 значений, то отдадим функции, производящей запись в БД и обнулим всё...
					if ($IC>50) 
					{	
						$this->AddRecord($INSERT, $link);
						$IC=0;
						$INSERT="";
					}
								
				}	
			} //End While
			$this->AddRecord($INSERT, $link); //После цикла While остался обрезок INSERT-строки, запишем и эти значения
			
			
			
			fclose ($handle);
			mysql_close($link);	
			$stat['count']=$count;
			$stat['startDate']=$startDate;
			$stat['endDate']=$endDate;
			$stat['ignoredCount']=$ignoredCount;		
			return $stat;
		} //End HTML Case
	} //End Function
	
	
	//Функция преобразования даты в формат, который съест MySQL
	private function ToMySQLDate($date)
	{
		$date = explode(".",strip_tags($date));
		$trueDate = str_replace("\r\n","",($date[2]."-".$date[1]."-".$date[0]));
		return $trueDate;
	}
	
	
	private function AddRecord($INSERT, $link)
	{
		$INSERT = substr($INSERT, 0, strlen($INSERT)-1); //Отрезали лишнюю запятую в конце
		$query='INSERT INTO `teiriko_synergy`.`electricity_measurement` 
				(`id`, `hash`, `emelement_id`, `date`, `time`, `value`) 
				VALUES '.$INSERT;
		mysql_query($query,$link);
	}
	
}
