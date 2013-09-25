<?php
class Model_building_emelements extends Model_building_common {	

	public function __construct($requestedBid) {
		parent::__construct($requestedBid);
	}
	
	public function ObtainEmelementsList() {
		$this->data['generalEmelementsList'] =  $this->ObtainGeneralEmelementsList();
		$this->data['porchEmelementsList'] =   $this->ObtainPorchEmelementsList();
		$this->data['flatEmelementsList'] =   $this->ObtainFlatEmelementsList();
		return $this->data;
	}
	
	//Отбор счетчиков, привязанных на уровне здания (ВРУ)
	private function ObtainGeneralEmelementsList() {	
		$query = '	SELECT emelement.id AS eid, emelement.sn AS sn, emelement.type AS type, c_emelementtype.label AS label, emelement.cRatio AS cRatio,
					emelement.descr AS descr, emelement.stop_date 
					FROM emelement, c_emelementtype
					WHERE emelement.type = c_emelementtype.id AND emelement.building_id=?i 
					AND emelement.porch_id IS NULL
					AND emelement.stop_date IS NULL';
		return BDSM::getAll($query,$this->building->GetId());	
	}

	//Отбор счетчиков, привязанных на уровне подъезда
	private function ObtainPorchEmelementsList() {	
		$query = '	SELECT emelement.id AS eid, emelement.sn AS sn, emelement.type AS type, c_emelementtype.label AS label, emelement.cRatio AS cRatio,
					emelement.descr AS descr, emelement.stop_date 
					FROM emelement, c_emelementtype
					WHERE emelement.type = c_emelementtype.id AND emelement.building_id=?i 
					AND emelement.porch_id IS NOT NULL AND emelement.floor_id IS NULL AND emelement.flat_id IS NULL
					AND emelement.stop_date IS NULL';
		return BDSM::getAll($query,$this->building->GetId());
	}
	
	//Отбор счетчиков, привязанных на уровне квартир
	private function ObtainFlatEmelementsList() {	
		$query = '	SELECT emelement.id AS eid, emelement.sn AS sn, emelement.type AS type, c_emelementtype.label AS label, emelement.cRatio AS cRatio,
					emelement.descr AS descr, emelement.stop_date  
					FROM emelement, c_emelementtype
					WHERE emelement.type = c_emelementtype.id
					AND emelement.building_id=?i 
					AND emelement.porch_id IS NOT NULL AND emelement.floor_id IS NOT NULL
					AND emelement.stop_date IS NULL';
		return  BDSM::getAll($query,$this->building->GetId());
	}		
	//public function get_data($section=null) {
//
//			
//		if ($section!=null)
//		{
//			switch ($section) 
//			{
//				case "passport" 	: break;
//				case "laments" 		: break;
//				case "services" 	: break;
//				case "emelements" 	: 
//					$data['emelementsList'] = $this->GetEmelementsList($building_id);
//				break;	
//				case "emelementMeasures":				
//					$eid = CheckEidAccessLevel($_GET['eid']);
//					if (isset($_POST['newDate'])) {
//						$newDate		=	$this->SafeSQL($_POST['newDate'],10);
//						$newTotalValue	=	$this->SafeSQL($_POST['newTotalValue'],15);
//						$newNightValue	=	$this->SafeSQL($_POST['newNightValue'],15);
//						$newDayValue	=	$this->SafeSQL($_POST['newDayValue'],15);
//						$data['operationStatus'] =	$this->AddMeasurementValue($eid, $newDate,$newTotalValue,$newNightValue,$newDayValue);
//					};					
//					$emelementType = $this->GetEmelementData($eid);
//					$emelementType = mysql_fetch_array($emelementType['eChars']);
//					$emelementType = $emelementType['type'];
//					$date = new DateTime(date("o-m-d"));
//					$date->modify('-365 day');
//					$startDate 	= 	isset($_POST['startDate']) 	? $this->SafeSQL($_POST['startDate'])	: $date->format('o-m-d'); //Дата начала отбора
//					$endDate 	= 	isset($_POST['endDate']) 	? $this->SafeSQL($_POST['endDate'])		: date("o-m-d");		  //Дата конца отбора
//					$data['measurements'] = $this->GetMeasurements($eid, $emelementType, $startDate, $endDate);					
//					$data['esnum'] = $this->GetEmelementSnum($eid);
//					$data['startDate'] = $startDate;
//					$data['endDate'] = $endDate;
//					$data['currentDate'] = date("o-m-d");
//					$data['eid'] = $eid;	
//				break;
//				case "emelementChars":
//					$eid = CheckEidAccessLevel($_GET['eid']);
//					$tmp = $this->GetEmelementData($eid);
//					$data['eChars'] = $tmp['eChars'];
//					$data['eTypesList'] = $tmp['eTypesList'];
//					$data['esnum'] = $this->GetEmelementSnum($eid);		
//					$data['eid'] = $this->GetEmelementSnum($eid); //??? WTF	
//				break;
//				case "emelementMailReport":
//					//Начало предыдущего месяца
//					$firstDay = new DateTime(date('o-m-d',strToTime('first day of -1 month')));
//					//Конец предыдущего месяца
//					$lastDay = new DateTime(date('o-m-d',strToTime('last day of -1 month')));
//					echo $firstDay->format('Y-m-d')." - ".$lastDay->format('Y-m-d');
//					//$date->modify('-1 month');
//					//Конец предыдущего месяца
//					
//					
//					
//					$startDate 	= 	isset($_POST['startDate']) 	? $this->SafeSQL($_POST['startDate'])	: $firstDay->format('o-m-d'); 
//					$endDate 	= 	isset($_POST['endDate']) 	? $this->SafeSQL($_POST['endDate'])		: $lastDay->format('o-m-d');;
//				
//					$action = $_GET['action'];
//					
//					$emelements = array();
//					for ($i = 0; $i <= 20; $i++) 
//					{
//						if ($_POST['emelement'.$i]) {$emelements[] = $_POST['emelement'.$i];}
//					}
//					
//					if (count($emelements)==0) {
//						$data['operationStatus']['label'] = "Не найдены измерительные элементы";
//						$data['operationStatus']['code'] = 0;						
//					}
//					else
//					{
//						$emelementList = GetEmelementReportList($emelements);	
//					}
//					
//					
//					
//					if (is_string($emelementList)) {
//						$data['operationStatus']['label'] = $emelementList;
//						$data['operationStatus']['code'] = 0;
//					}
//					else {
//						$data['emelementList'] = $emelementList;
//					}
//					
//					if ($action=="preview") {
//						$data['previewDocument'] = GetMailReport($emelementList, $startDate, $endDate, 0);
//					}	
//				break;
//			} //end of Switch			
//		} //end of If	
//
//		return $data;
//	}
	
//
//	private function AddMeasurementValue($eid, $newDate,$newTotalValue,$newNightValue,$newDayValue) {
//		$label = ""; $code = 1;
//		//Ищем предыдущее измерение ПЕРЕДЕЛАТЬ, ДАТУ СОРТИРУЕТ НЕПРАВИЛЬНО!!!!!!111111адынадын ХЗ
//		$query = "	SELECT DATE_FORMAT(`date`,'%d.%m.%Y') AS orderFormatDate, `date`, `total`, `day`, `night` 
//					FROM `electricity_measurement_month` 
//					WHERE `emelement_id`= $eid 
//					ORDER BY orderFormatDate ASC LIMIT 1";
//		$last = evaluate_Query($query);
//		$last = mysql_fetch_array($last);
//		$lastDate = $last['date'];
//		$lastTotalValue = $last['total'];
//		$lastDayValue = $last['day'];
//		$lastNightValue = $last['night'];
//		//Если предыдущее измерение не найдено - берём начальные значения счётчика
//		if ($lastDate == '') {
//			$last = evaluate_Query("SELECT start_date, initial_totalValue, initial_dayValue, initial_nightValue
//									FROM emelement
//									WHERE emelement.id = $eid");
//			$last = mysql_fetch_array($last);	
//			$lastDate = $last['start_date'];
//			$lastTotalValue = $last['initial_totalValue'];
//			$lastDayValue = $last['initial_dayValue'];
//			$lastNightValue = $last['initial_nightValue'];													
//		};
//		
//		//Переделать проверку даты, сечас можно ввести 9999-99-99, или 31 день в месяце с 29 днями
//		if (!is_numeric($newTotalValue) or !is_numeric($newNightValue) or !is_numeric($newDayValue) or !preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $newDate)) {
//				$code=0;
//				$label="Некорректный ввод данных. Значение не было добавлено.";
//		}
//		elseif ((($newTotalValue-$lastTotalValue)<0) or (($newDayValue-$lastDayValue)<0) or (($newNightValue - $lastNightValue)<0)) {
//				$code=0;
//				$label="Введённые значения меньше показаний за предыдущий месяц".$lastNightValue." ".$newNightValue;						
//		}
//		elseif (($newTotalValue != $newNightValue + $newDayValue) and ($newDayValue!=0) and ($newNightValue!=0)) {
//				$code=0;
//				$label="Сумма показаний за день и ночь не равна общему значению";		
//		}
//		elseif (((strtotime($newDate." 00:00:00")-strtotime($lastDate." 00:00:00"))<=0)and ($_SESSION['user_status']<8)) {
//			$code=0;
//			$label="Измерения на введённую или более позднюю дату  уже записаны.";
//		}
//		else {
//			$query = 'INSERT INTO `teiriko_synergy`.`electricity_measurement_month` 
//			(`id`, `emelement_id`, `date`, `time`, `total`, `day`, `night`) 
//			VALUES (NULL, "'.$eid.'", "'.$newDate.'", "12:00:00", "'.$newTotalValue.'", "'.$newDayValue.'", "'.$newNightValue.'")';
//			evaluate_Query($query);
//			$code=1;
//			$label="Значение успешно добавлено.";			
//		};
//		return array('label' => $label, 'code' => $code);		
//	}
//
//	private function GetMeasurements($eid, $emelementType, $startDate, $endDate) {
//		$measurementsTable = array();
//		
//		//Выбор таблицы в зависимости от типа счётика (1 - почасовые, 2,3,4,5 - прочие)
//		if ($emelementType > 1) {
//			//Измерения по месяцам
//			$query = '	SELECT DATE_FORMAT(`date`,"%Y") AS `year`, DATE_FORMAT(`date`,"%m") AS `month`, DATE_FORMAT(`date`,"%d") AS `day`,
//						`total`*emelement.cRatio AS `totalValue`, `day`*emelement.cRatio AS `dayValue`, `night`*emelement.cRatio AS `nightValue` 
//						FROM `emelement`, `electricity_measurement_month`
//						WHERE emelement.id = electricity_measurement_month.emelement_id
//						AND `date` BETWEEN "'.$startDate.'" AND "'.$endDate.'"
//						AND emelement.id = '.$eid.
//						' ORDER BY `date`';
//
//			$res = evaluate_Query($query);
//			if ($res) {
//				while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) 
//				{		
//					$rusMonths = array("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");		
//					$year = $row['year']; $month = $row['month']; $day = $row['day'];
//					$totalValue = $row['totalValue']; $dayValue = $row['dayValue']; $nightValue = $row['nightValue'];
//					$prevValues = $this->GetPrevMonthValue($year, $month, $eid);
//					$prevTotal = $prevValues['totalValue'];
//					$prevDay = $prevValues['dayValue'];
//					$prevNight = $prevValues['nightValue'];
//					$row['month'] = $rusMonths[$row['month']-1];
//					$row['deltaTotal'] = $totalValue - $prevTotal;
//					$row['deltaDay'] = $dayValue - $prevDay;
//					$row['deltaNight'] = $nightValue - $prevNight;
//					$measurementsTable[] = $row;		
//				}
//			}
//		}
//		else {
//			//Измерения по часам преобразуем в измерени по месяцам
//			$query = '	SELECT  DATE_FORMAT(`date`,"%Y") AS `year`, DATE_FORMAT(`date`,"%m") AS `month`, SUM(`value`*emelement.cRatio) AS value
//						FROM `emelement`, `electricity_measurement`
//						WHERE emelement.id=electricity_measurement.emelement_id
//						AND `date` BETWEEN "'.$startDate.'" AND "'.$endDate.'"
//						AND emelement.id = '.$eid.'
//						GROUP BY `month`
//						ORDER BY `date`';
//		}
//		return $measurementsTable;
//	}
//	
//	private function GetPrevMonthValue($year, $month, $eid) {
//		//смещаем заданную дату на месяц назад
//		$year = ($month==1) ? $year-1 : $year;
//		$month = ($month==1) ? 12 : $month;
//		$startDate = $year."-".$month."-00";
//		$endDate = $year."-".$month."-31";
//		
//		$query = '	SELECT `total`*emelement.cRatio AS `totalValue`, `day`*emelement.cRatio AS `dayValue`, `night`*emelement.cRatio AS `nightValue` 
//					FROM `emelement`, `electricity_measurement_month`
//					WHERE emelement.id = electricity_measurement_month.emelement_id
//					AND emelement.id = '.$eid.
//					'AND `date` BETWEEN "'.$startDate.'" AND "'.$endDate.'"
//					ORDER BY `date` DESC 
//					LIMIT 1';
//		$res = '';
//		$tmp = evaluate_Query($query);
//		
//		if ($tmp) {
//			$tmp = mysql_fetch_array($tmp, MYSQL_ASSOC);
//			$res['totalValue']	= 	$tmp['totalValue'];
//			$res['dayValue']	=	$tmp['dayValue'];
//			$res['nightValue']	= 	$tmp['nightValue'];
//		}
//
//		else {
//			$query = "	SELECT initial_totalValue, initial_dayValue, initial_nightValue
//						FROM emelement
//						WHERE id = ".$eid;
//			$initialValues = mysql_fetch_array(evaluate_Query($query), MYSQL_ASSOC);
//			$res['totalValue']	= 	$initialValues['initial_totalValue'];
//			$res['dayValue']	=	$initialValues['initial_dayValue'];
//			$res['nightValue']	= 	$initialValues['initial_nightValue'];						
//		}
//		return $res;	
//	}
//
//
//	private function GetEmelementSnum($eid) {
//		$res = evaluate_Query("SELECT sn FROM emelement WHERE id=$eid");
//		return mysql_result($res,0,0);
//	}
//	
//	

//
//

//		
//	
//	private	function GetEmelementData($eid)
//	{
//		$result['eChars'] = evaluate_Query('SELECT * FROM emelement WHERE emelement.id='.$eid);
//		$result['eTypesList'] = evaluate_Query('SELECT * FROM c_emelementtype');	
//		return $result;				
//	}
//	
//	
//	//История счётчика (замена, обслуживание и т.д.)
//	public function get_emelement_history()
//	{
//		return 0;
//	}
//	
//	//Отображение измерений на графике
//	public function get_emelement_graph()
//	{
//		return 0;
//	}

}
