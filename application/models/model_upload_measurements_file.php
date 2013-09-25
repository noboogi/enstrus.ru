<?php
class Model_UploadMeasurementsFile extends Model {

	public function ParseMeasurementsFile($filePath, $eid) {
		$handle = fopen($filePath, "r");
		$count = 0; 		//Количество считанных записей
		$ignoredCount = 0; 	//Количество тех, что не перезаписали
		$startDate = 0;		//Первая дата в файле
		$endDate = 0;		//Последняя дата в файле
		
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
				$buffer = fgets($handle, 4096); $buffer = fgets($handle, 4096); 							//Пропуск строки
				$buffer = fgets($handle, 4096); $buffer = fgets($handle, 4096);								//Пропуск строки
				$timestamp = str_replace("\r\n","",strip_tags($buffer));									//timestamp
				$date = $this->TimestampToMySQLDateTime($timestamp, 'date');
				$time = $this->TimestampToMySQLDateTime($timestamp, 'time');	
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
	} //End Function
	
	private function TimestampToMySQLDateTime($timestamp, $dt) {
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
