<?php

class Model_Emeasurements extends Model
{

	public function get_data()
	{
		if ($this->check_session()) 
		{
			$user_status = $_SESSION['user_status'];
			if ($user_status>2)
			{
				//Запрос может придти как на один счётчик, так и на несколько (для суммирования)
				//Объединим все запрошенные счётчики в один массив
				$emelements = array();
				if (isset($_GET['sn'])) {$emelements[] = $this->SafeSQL($_GET['sn']);};
				
				//Если был запрос на несколько счётчиков, то занесём серийные номера всех в массив
				//Для этого переберём 20 вариантов имён чекбоксов (вряд ли будет выбрано больше)
				//В любом случае, этот кусок говнокода исправить придётся позже
				for ($i = 0; $i <= 20; $i++) 
				{
					if ($_POST['emelement'.$i])
					{
						$emelements[] = $this->SafeSQL($_POST['emelement'.$i]);
					}
				}
				
				//Проверим каждый счётчик в массиве на соответствие правам доступа пользователя
				$accsessEnabled = TRUE;
				foreach($emelements as $e)
				{
					//Если пользователь - администратор, то уровень доступа не ограничен
					if ($user_status > 5){break;}
					//Если пользователь - управляющая компания...
					if ($user_status == 5) 
					{
						$query='SELECT building.mgcompany_id 
								FROM emelement, building
								WHERE emelement.sn="'.$e.'" AND emelement.building_id=building.id 
								AND building.mgcompany_id ='.$_SESSION['user_mgcompany'];
						$tmp = $this->evaluate_Query($query);
						if (mysql_num_rows($tmp) < 1) {$accsessEnabled = FALSE; break;}
					}
					//Если ТСЖ...
					elseif ($user_status == 4)
					{
						$query='SELECT building_id FROM emelement WHERE sn="'.$e.'"';
						$tmp = $this->evaluate_Query($query);
						$row = mysql_fetch_array($tmp);
						if ($row['building_id'] != $_SESSION['user_building_id']) {$accsessEnabled = FALSE; break;};
					}
					elseif ($user_status == 3)
					{
						$query = 'SELECT flat_id FROM emelement WHERE sn="'.$e.'"';
						$tmp = $this->evaluate_Query($query);
						$row = mysql_fetch_array($tmp);	
						if ($row['flat_id'] != $_SESSION['user_flat_id']) {$accsessEnabled = FALSE; break;};					
					}					
				}
				//Если был запрошен счётчик, к которому нет прав доступа - выкидываем
				if (!$accsessEnabled) {header('Location:/login');};
				
				//Параметры отбора
				//Дата, период по умолчанию: текущий месяц
				$date = new DateTime(date("o-m-d"));
				$date->modify('-30 day');
				$startDate 	= 	isset($_POST['startDate']) 	? $this->SafeSQL($_POST['startDate'])	: $date->format('o-m-d'); //Дата начала отбора
				$endDate 	= 	isset($_POST['endDate']) 	? $this->SafeSQL($_POST['endDate'])		: date("o-m-d");		  //Дата конца отбора
				//Время, период по умолчанию: 00:00 до 23.30
				$startTime 	= 	isset($_POST['startTime']) 	? $this->SafeSQL($_POST['startTime']) 	: "00:00";
				$endTime 	=	isset($_POST['endTime']) 	? $this->SafeSQL($_POST['endTime']) 	: "23:30";
				
				//Выводить все измерения или только максимумы за сутки
				$onlyMax = isset($_POST['onlyMax']) ? "checked" : "";
		
				$data['emelements'] = $emelements;
				$data['startDate'] 	= $startDate; $data['endDate'] = $endDate;
				$data['startTime'] 	= $startTime; $data['endTime'] = $endTime;
				$data['onlyMax'] 	= $onlyMax;
				
				//Теперь выберем необходимые записи из БД
				//В зависимости от количества счётчиков сформируем условие выборки
				$where = 'WHERE (emelement.sn="'.$emelements[0].'" ';
				for ($i = count($emelements)-1; $i>=1; $i--)
				{
					$where=$where.' OR emelement.sn="'.$emelements[$i].'"';
				}
				$where=$where.') ';
				
				$query = '	SELECT  `date`, `time`, emelement.sn, SUM(`value`*emelement.cRatio) AS value
							FROM `emelement`, `electricity_measurement` '.
							$where
							.'AND emelement.id=electricity_measurement.emelement_id
							AND `date` BETWEEN "'.$startDate.'" AND "'.$endDate.'"
							AND `time` BETWEEN "'.$startTime.'" AND "'.$endTime.'"
							GROUP BY `date`, `time`';
				$data['emeasurement'] = $this->evaluate_Query($query);
				
				return $data;
			}
			else
			{
				//Не хватило прав доступа
				header('Location:/login');
			}
		} 
		else
		{
			//Не выполнен вход в систему
			header('Location:/login');
		}
	
	} //End of getData

}
