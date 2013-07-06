<?php

class Model_building_passport extends Model
{
	
	public function getAddress($building_id) {
		//Сформируем заголовок личного кабинета для пользователя
		$query = '	SELECT street.name, building.no 
					FROM building, street 
					WHERE building.id='.$building_id.' AND building.street_id=street.id';
		$tmp = $this->evaluate_Query($query);
		$row = mysql_fetch_array($tmp);
		$address = 'ул. '.$row['name'].', д. '.$row['no'];
		return $address;								
	}
	
	public function get_data($section=null)
	{
		if (!$this->check_session()) {header('Location:/login');} 
		
		$building_id = $this->CheckBidAccessLevel($_GET['bid']);
		$data['bid'] = $building_id;
		$data['address'] = $this->GetAddress($building_id);
			
		//В зависимости от запрошенного раздела ЛК сформируем данные
		if ($section!=null)
		{
			switch ($section) 
			{
				case "passport" 	: break;
				case "laments" 		: break;
				case "services" 	: break;
				case "emelements" 	: 
					$data['emelementsList'] = $this->GetEmelementsList($building_id);
				break;	
				case "emelementMeasures":
					$eid = $this->CheckEidAccessLevel($_GET['eid']);
					$measurementsFrequency = $this->GetEmelementData($eid);
					$measurementsFrequency = mysql_fetch_array($measurementsFrequency['eChars']);
					$measurementsFrequency = $measurementsFrequency['frequency'];
					
					$date = new DateTime(date("o-m-d"));
					$date->modify('-365 day');
					$startDate 	= 	isset($_POST['startDate']) 	? $this->SafeSQL($_POST['startDate'])	: $date->format('o-m-d'); //Дата начала отбора
					$endDate 	= 	isset($_POST['endDate']) 	? $this->SafeSQL($_POST['endDate'])		: date("o-m-d");		  //Дата конца отбора
					$data['measurements'] = $this->GetMeasurements($eid, $measurementsFrequency, $startDate, $endDate);					
					$data['esnum'] = $this->GetEmelementSnum($eid);
					$data['startDate'] = $startDate;
					$data['endDate'] = $endDate;
				break;
				case "emelementChars":
					$eid = $this->CheckEidAccessLevel($_GET['eid']);
					$tmp = $this->GetEmelementData($eid);
					$data['eChars'] = $tmp['eChars'];
					$data['eTypesList'] = $tmp['eTypesList'];
					$data['esnum'] = $this->GetEmelementSnum($eid);		
					$data['eid'] = $eid;	
				break;
			} //end of Switch			
		} //end of If	

		return $data;
	}

	private function GetMeasurements($eid, $measurementsFrequency, $startDate, $endDate) {
		$measurementsTable = array();
		
		//Выбор таблицы в зависимости от частоты измерения счётчика
		if ($measurementsFrequency == 0) {
			//Измерения по месяцам
			$query = '	SELECT DATE_FORMAT(`date`,"%Y") AS `year`, DATE_FORMAT(`date`,"%m") AS `month`, DATE_FORMAT(`date`,"%d") AS `day`,
						`total`*emelement.cRatio AS `totalValue`, `day`*emelement.cRatio AS `dayValue`, `night`*emelement.cRatio AS `nightValue` 
						FROM `emelement`, `electricity_measurement_month`
						WHERE emelement.id = electricity_measurement_month.emelement_id
						AND `date` BETWEEN "'.$startDate.'" AND "'.$endDate.'"
						AND emelement.id = '.$eid.
						' ORDER BY `date`';

			$res = $this->evaluate_Query($query);
			if ($res) {
				while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) 
				{		
					$rusMonths = array("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");		
					$year = $row['year']; $month = $row['month']; $day = $row['day'];
					$totalValue = $row['totalValue']; $dayValue = $row['dayValue']; $nightValue = $row['nightValue'];
					$prevValues = $this->GetPrevMonthValue($year, $month, $eid);
					$prevTotal = $prevValues['totalValue'];
					$prevDay = $prevValues['dayValue'];
					$prevNight = $prevValues['nightValue'];
					$row['month'] = $rusMonths[$row['month']-1];
					$row['deltaTotal'] = $totalValue - $prevTotal;
					$row['deltaDay'] = $dayValue - $prevDay;
					$row['deltaNight'] = $nightValue - $prevNight;
					$measurementsTable[] = $row;		
				}
			}
		}
		else {
			//Измерения по часам преобразуем в измерени по месяцам
			$query = '	SELECT  DATE_FORMAT(`date`,"%Y") AS `year`, DATE_FORMAT(`date`,"%m") AS `month`, SUM(`value`*emelement.cRatio) AS value
						FROM `emelement`, `electricity_measurement`
						WHERE emelement.id=electricity_measurement.emelement_id
						AND `date` BETWEEN "'.$startDate.'" AND "'.$endDate.'"
						AND emelement.id = '.$eid.'
						GROUP BY `month`
						ORDER BY `date`';
		}
		return $measurementsTable;
	}
	
	private function GetPrevMonthValue($year, $month, $eid) {
		//смещаем заданную дату на месяц назад
		$year = ($month==1) ? $year-1 : $year;
		$month = ($month==1) ? 12 : $month;
		$startDate = $year."-".$month."-00";
		$endDate = $year."-".$month."-31";
		
		$query = '	SELECT `total`*emelement.cRatio AS `totalValue`, `day`*emelement.cRatio AS `dayValue`, `night`*emelement.cRatio AS `nightValue` 
					FROM `emelement`, `electricity_measurement_month`
					WHERE emelement.id = electricity_measurement_month.emelement_id
					AND emelement.id = '.$eid.
					'AND `date` BETWEEN "'.$startDate.'" AND "'.$endDate.'"
					ORDER BY `date` DESC 
					LIMIT 1';
		$res = '';
		$tmp = $this->evaluate_Query($query);
		
		if ($tmp) {
			$tmp = mysql_fetch_array($tmp, MYSQL_ASSOC);
			$res['totalValue']	= 	$tmp['totalValue'];
			$res['dayValue']	=	$tmp['dayValue'];
			$res['nightValue']	= 	$tmp['nightValue'];
		}

		else {
			$query = "	SELECT initial_totalValue, initial_dayValue, initial_nightValue
						FROM emelement
						WHERE id = ".$eid;
			$initialValues = mysql_fetch_array($this->evaluate_Query($query), MYSQL_ASSOC);
			$res['totalValue']	= 	$initialValues['initial_totalValue'];
			$res['dayValue']	=	$initialValues['initial_dayValue'];
			$res['nightValue']	= 	$initialValues['initial_nightValue'];						
		}
		return $res;	
	}


	private function GetEmelementSnum($eid) {
		$res = $this->evaluate_Query("SELECT sn FROM emelement WHERE id=$eid");
		return mysql_result($res,0,0);
	}
	
	
	private function GetEmelementsList($building_id) {
		$user_status = $_SESSION['user_status'];
		if ($user_status < 4) {	
				header('Location:/building_passport/emeasures');					
		} 
		else
		{	
			//Отбор счетчиков, привязанных на уровне здания (ВРУ)
			$query = '	SELECT emelement.id AS eid, emelement.sn AS sn, emelement.type AS type, c_emelementtype.label AS label, emelement.cRatio AS cRatio,
						emelement.descr AS descr, emelement.stop_date 
						FROM emelement, c_emelementtype
						WHERE emelement.type = c_emelementtype.id AND emelement.building_id='.$building_id.' 
						AND emelement.porch_id IS NULL
						AND emelement.stop_date IS NULL';
			$buildingEmelements = $this->evaluate_Query($query);

			//Отбор счётчиков на уровне подъезда
			$query = '	SELECT emelement.id AS eid, emelement.sn AS sn, emelement.type AS type, c_emelementtype.label AS label, emelement.cRatio AS cRatio,
						emelement.descr AS descr, emelement.stop_date 
						FROM emelement, c_emelementtype
						WHERE emelement.type = c_emelementtype.id AND emelement.building_id='.$building_id.' 
						AND emelement.porch_id IS NOT NULL AND emelement.floor_id IS NULL AND emelement.flat_id IS NULL
						AND emelement.stop_date IS NULL';
			$porchEmelements = $this->evaluate_Query($query);
						
			//Отбор счетчиков, привязанных на уровне квартир
			$query = '	SELECT emelement.id AS eid, emelement.sn AS sn, emelement.type AS type, c_emelementtype.label AS label, emelement.cRatio AS cRatio,
						emelement.descr AS descr, emelement.stop_date  
						FROM emelement, c_emelementtype
						WHERE emelement.type = c_emelementtype.id
						AND emelement.building_id='.$building_id.' 
						AND emelement.porch_id IS NOT NULL AND emelement.floor_id IS NOT NULL
						AND emelement.stop_date IS NULL';
			$flatEmelements = $this->evaluate_Query($query);
		}						
											
			$list['buildingEmelements'] = $buildingEmelements;
			$list['porchEmelements'] = $porchEmelements;
			$list['flatEmelements'] = $flatEmelements;
			return $list;
	}


	//Проверка прав доступа к зданию с указанным id
	private function CheckBidAccessLevel($bid)
	{
		$user_building_id =  isset($_SESSION['user_building_id']) ? $_SESSION['user_building_id'] : '';
		$user_status = $_SESSION['user_status'];
		
		//Определим, соответствуют ли права пользователя id запрошенного здания. 
		if ($bid=='') 
		{
			//Если id здания не определено - присвоим id здания, где проживает пользователь
			if ($user_building_id != '')
			{
				$building_id = $user_building_id;
			}
			else
			{
				header('Location:/login');
			}
		}
		else
		{	
			$bid = $this->SafeSQL($bid,9);
			if ($user_status > 5) 
			{
				$building_id = $bid;
			}
			elseif ($user_status == 5)
			{
				//Если запрос от управляющей компании, то проверим её права доступа к данном зданию
				$query = 'SELECT mgcompany_id FROM building WHERE building.id='.$bid;
				$building_mgcompany_id = mysql_result($this->evaluate_Query($query), 0, 0);
				if ($building_mgcompany_id == $_SESSION['user_mgcompany']) 
				{
					$building_id = $bid;
				}
				else 
				{
					//Запрос не соответсвует правам доступа. 
					//Можно занести в журнал событий
					header('Location:/login');
				}
			}
			else
			{
				//Запрос от обычного жильца или члена ТСЖ, без проверки присваиваем его личный дом
				$building_id = $user_building_id;		
			}			
		}
		return $building_id;
	}
	
	
	//Проверка прав доступа к электрическому счтчику с указанным id
	private function CheckEidAccessLevel($eid)
	{
		$user_flat_id = isset($_SESSION['user_flat_id']) ? $_SESSION['user_flat_id'] : '';
		$user_status = $_SESSION['user_status'];
		//Определим, соответствуют ли права пользователя id запрошенного счётчика. 
		if ($eid == '') 
		{
			if ($user_flat_id == '')
			{
				//Невозможно установить требуемый счётчик для пользователя
				//Выбросим на страницу логина
				header('Location:/login');
			}
			else
			{
				$query = 'SELECT id FROM emelement WHERE emelement.stop_date IS NULL AND emelement.flat_id = '.$user_flat_id;
				$emelement_id = mysql_result($this->evaluate_Query($query), 0, 0);
			}
		}
		else
		{	
			$eid = $this->SafeSQL($eid,9);
			if ($user_status > 5) 
			{
				//Если администрация портала - то без вопросов пустим
				$emelement_id = $eid;
			}
			elseif ($user_status == 5)
			{
				//Если запрос от управляющей компании, то проверим её права доступа к данном зданию
				$query = 'SELECT mgcompany_id FROM building, emelement WHERE building.id = emelement.building_id AND emelement.id='.$eid;
				$emelement_mgcompany_id = mysql_result($this->evaluate_Query($query), 0, 0);
				if ($emelement_mgcompany_id == $_SESSION['user_mgcompany']) 
				{
					$emelement_id = $eid;
				}
				else 
				{
					//Запрос не соответсвует правам доступа. 
					//Можно занести в журнал событий
					header('Location:/login');
				}
			}
			elseif ($user_status == 4)
			{
				//Запрос от члена ТСЖ. Проверим, относится ли запрошенный счётчик к его дому
				$query = 'SELECT emelement.building_id FROM emelement WHERE emelement.id='.$eid;
				$emelement_building_id = mysql_result($this->evaluate_Query($query), 0, 0);
				if ($_SESSION['user_building_id'] == $emelement_building_id) 
				{
					$emelement_id = $eid;
				}
				else
				{
					//Запрос не соответсвует правам доступа. 
					//Можно занести в журнал событий
					header('Location:/login');					
				}
			}
			elseif ($user_status == 3)
			{
				//Обычный жилец, просто возвращаем id его личного счётчика
				$query = 'SELECT emelement.id FROM emelement WHERE emelement.stop_date IS NULL AND emelement.flat_id='.$user_flat_id;
				$emelement_id = mysql_result($this->evaluate_Query($query), 0, 0);
			}			
		}
		return $emelement_id;
	}
	
	
	private	function GetEmelementData($eid)
	{
		$query = 'SELECT * FROM emelement WHERE emelement.id='.$eid;
		$result['eChars'] = $this->evaluate_Query($query);
		$result['eTypesList'] = $this->evaluate_Query('SELECT * FROM c_emelementtype');	
		return $result;				
	}
	
	
	//История счётчика (замена, обслуживание и т.д.)
	public function get_emelement_history()
	{
		return 0;
	}
	
	//Отображение измерений на графике
	public function get_emelement_graph()
	{
		return 0;
	}

}
