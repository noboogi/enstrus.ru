<?php

class Model_building_passport extends Model
{
	
	public function get_data($section=null)
	{
		if (!$this->check_session()) {header('Location:/login');} 

		$data = '';
		$data['status'] = $user_status;
		$user_status = $_SESSION['user_status'];
		$building_id = $this->CheckBidAccessLevel();
		
		//Сформируем заголовок личного кабинета для пользователя
		$query = '	SELECT street.name, building.no 
					FROM building, street 
					WHERE building.id='.$building_id.' AND building.street_id=street.id';
		$tmp = $this->evaluate_Query($query);
		$row = mysql_fetch_array($tmp);
		$address = 'ул. '.$row['name'].', д. '.$row['no'];
		//Если для пользователя определена квартира
		if ($_SESSION['user_flat_no']!=NULL && $_SESSION['user_flat_no']!=0) {$address = $address.", кв. ".$_SESSION['user_flat_no'];}								
		$data['address'] = $address;												
		
		//В зависимости от запрошенного раздела ЛК сформируем данные
		if ($section!=null)
		{

			switch ($section) 
			{
				case "passport" : break;
				case "laments" : break;
				case "services" : break;
				case "emelements" :
				//Запрос списка счётчиков по дому
					//Если простой жилец, то выберем только его личный счётчик и будем перенаправлять на страницу этого
					//конкретного счётчика (дадим сигнал контроллеру)
					if ($user_status < 4) {	
						header('Location:/building_passport/emelement');					
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
													
					$data['buildingEmelements'] = $buildingEmelements;
					$data['porchEmelements'] = $porchEmelements;
					$data['flatEmelements'] = $flatEmelements;
					$data['bid'] = $building_id;
					break;
					
				//Запрос конкретного счётчика	
				case "emelement":
					$emelement_id = $this->CheckEidAccessLevel();
					$tmp = $this->GetEmelementData($emelement_id);
					$data['emelement_id'] = $emelement_id;
					return $data;				
				break;
			} //end of Switch			
		} //end of If
		return $data;
	}
	

	
	//Проверка прав доступа к зданию с указанным id
	private function CheckBidAccessLevel()
	{
		$user_building_id =  isset($_SESSION['user_building_id']) ? $_SESSION['user_building_id'] : '';
		$user_status = $_SESSION['user_status'];
		//Определим, соответствуют ли права пользователя id запрошенного здания. 
		if (!isset($_GET['bid'])) 
		{
			//Если id здания не определено - присвоим id здания, где проживает пользователь
			if ($user_building_id != '')
			{
				$building_id = $user_building_id;
			}
			else
			{
				//Непонятно, кто что хочет. Здание для пользователя не определено, и конкретный id не запрошен
				//На всякий случай выбросим на страницу логина
				header('Location:/login');
			}
		}
		else
		{	
			$bid = $this->SafeSQL($_GET['bid'],9);
			if ($user_status > 5) 
			{
				//Если администрация портала - то без вопросов пустим
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
				//Вообще, надо бы проверить и, в случае попытки несанкционированного доступа, добавить запись в журнал событий
				$building_id = $user_building_id;		
			}			
		}
		return $building_id;
	}
	
	//***Проверка прав доступа к электрическому счтчику с указанным id***//
	private function CheckEidAccessLevel()
	{
		$user_flat_id = isset($_SESSION['user_flat_id']) ? $_SESSION['user_flat_id'] : '';
		$user_status = $_SESSION['user_status'];
		//Определим, соответствуют ли права пользователя id запрошенного счётчика. 
		if (!isset($_GET['eid'])) 
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
			$eid = $this->SafeSQL($_GET['eid'],9);
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
	
	//Получение технических характеристик счётчика
	public function get_emelement_chars()
	{
		$eid = $this->SafeSQL($_GET['eid'],9);
		$data['emelementChars'] = $this->GetEmelementData($eid);
		$data['emelementTypes'] = $this->GetEmelementTypes();
		$data['esnum'] = mysql_result($data['emelementChars'],0);
		return $data;
	}
	
	private	function GetEmelementData($eid)
	{
		$eTypesList = $this->GetEmelementTypes();		
		$query = 'SELECT * FROM emelement WHERE emelement.id='.$eid;
		$data['chars']=$this->evaluate_Query($query);	
	}
	
	private function GetEmelementTypes()
	{
		$query = 'SELECT * FROM c_emelementtype';
		$res = $this->evaluate_Query($query);	
	}
	
	//Данные измерений счётчика
	public function get_emelement_measures()
	{
		return 0;
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
