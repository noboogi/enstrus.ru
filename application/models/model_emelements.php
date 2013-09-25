<?php

class Model_Emelements extends Model {

	public function get_data() {
		// 1) Модифицируем запрос  при заданых параметрах фильтра
		// 2) Когда без фильтра - выводим последние 30
								
		//Запросим счетчики по очереди: 1) Частные 2) По подъезду  3) По дому
		//Весьма странный запрос. Сделать нормальный.
		$emelements = BDSM::GetAll('(SELECT emelement.id, emelement.sn, c_emelementtype.label, emelement.descr, emelement.cRatio, 
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
																												
		$this->data['emelementsList'] = $emelements;
		return $this->data;	
	}

	public function UploadMeasurement() {
		$filePath="";
		if($_FILES["filename"]["size"] > 1024*1*1024) {
		$uploadStatus['label']="Размер файла превышает 1 Мб";
		}
		
		// Проверяем загружен ли файл
		if(is_uploaded_file($_FILES["filename"]["tmp_name"])) {
			// Если файл загружен успешно, перемещаем его
			// из временной директории в конечную
			if (move_uploaded_file($_FILES["filename"]["tmp_name"], "files/measures/".iconv("UTF-8",  "CP1251", $_FILES["filename"]["name"]))) {
				$filePath = "files/measures/".iconv("UTF-8",  "CP1251", $_FILES["filename"]["name"]);
				$uploadStatus['label'] = "Файл успешно сохранён на сервере";
				$uploadStatus['code'] = 1;
				//Передаём файл функции-парсеру, которая запишет данные в БД
				$uploadStatus['parsingResult'] = $this->readMeasureFile($filePath, $_POST['emelementId'], $_POST['fileType'],$_POST['rewrite']);
			}
			else {
				$uploadStatus['label'] = "Ошибка при сохранении файла на сервере";
			}
		} 
		else {
			$uploadStatus['label']="Ошибка загрузки файла";
		}
		return $uploadStatus;
	}	

	private function readMeasureFile($filePath, $emelementId, $fileType = NULL, $rewrite = FALSE) {
		$handle = fopen ($filePath, "r");
		$count = 0; 		//Количество считанных записей
		$ignoredCount = 0; 	//Количество тех, что не перезаписали
		$startDate = 0;		//Первая дата в файле
		$endDate = 0;		//Последняя дата в файле
		
		//На вход могут поступать разные типы файлов, с различной структурой
		if  ($fileType == "HTML") {	
			$IC = 0; //Здесь количество строк, добавляемых в таблицу
			$INSERT = ""; //Здесь итоговая строка с VALUES для SQL запроса INSERT
			
			while (!feof ($handle)) {
				//Считываемые данные
				$Ap=0; $Am=0; $Rp=0; $Rm=0; $time=0; $date=0;		
					
				$buffer = fgets($handle, 4096);
				if (substr($buffer, 0, 3) == "<TD")	{
					//Нашли начало строки, разнесём содержимое ячеек по переменным
					$buffer = fgets($handle, 4096);	$Ap = str_replace("\r\n","",strip_tags($buffer));			//А+
					$buffer = fgets($handle, 4096);	$Am = str_replace("\r\n","",strip_tags($buffer));			//А-	
					$buffer = fgets($handle, 4096);	$Rp = str_replace("\r\n","",strip_tags($buffer));			//R+	
					$buffer = fgets($handle, 4096);	$Rm = str_replace("\r\n","",strip_tags($buffer));			//R-
					$buffer = fgets($handle, 4096); $time = str_replace("\r\n","",strip_tags($buffer).":00");	//Time
					$buffer = fgets($handle, 4096); $buffer = fgets($handle, 4096); 							
					$buffer = fgets($handle, 4096); $buffer = fgets($handle, 4096);								//timestamp
					$timestamp = str_replace("\r\n","",strip_tags($buffer));
					$date =  $this->ToMySQLDate($timestamp, 'date');
					$time = $this->ToMySQLDate($timestamp, 'time');	
					//Пропускаем оставшиеся строчки	
					$buffer = fgets($handle, 4096); 
					$count++;
					if ($startDate == 0) {$startDate=$date." ".$time;};
					$endDate = $date." ".$time;
					$IC++;
					$INSERT = $INSERT.'(NULL, MD5(CONCAT("'.$emelementId.'","'.$date.'","'.$time.'")),"'.$emelementId.'","'.$date.'","'.$time.'","'.$Ap.'"),';
					//Если собрали 50 значений, то отдадим функции, производящей запись в БД и обнулим всё...
					if ($IC>50) {	
						$this->AddRecord($INSERT, $link);
						$IC=0;
						$INSERT="";
					}				
				}	
			} //End While
			$this->AddRecord($INSERT, $link); //После цикла While остался обрезок INSERT-строки, запишем его
				
			fclose ($handle);
			$stat['count']=$count;
			$stat['startDate']=$startDate;
			$stat['endDate']=$endDate;
			$stat['ignoredCount']=$ignoredCount;		
			return $stat;
		} //End HTML Case
	} //End Function
	
	
	//Функция преобразования даты в формат, который съест MySQL
	private function ToMySQLDate($timestamp, $dt) {
		//Обрезаем миллисекунды от timestamp
		$timestamp = substr($timestamp,0,strlen($timestamp)-3);

		date_default_timezone_set('GMT');
		if ($dt=='date') {
			return date("Y-m-d",$timestamp); 
		}
		else {
			return date("H:i",$timestamp);	
		}
	}
	
	
	private function AddRecord($INSERT, $link) {
		$INSERT = substr($INSERT, 0, strlen($INSERT)-1); //Отрезали лишнюю запятую в конце
		$query='INSERT INTO `teiriko_synergy`.`electricity_measurement` 
				(`id`, `hash`, `emelement_id`, `date`, `time`, `value`) 
				VALUES '.$INSERT;
		BDSM::query($query);
	}
	
}
