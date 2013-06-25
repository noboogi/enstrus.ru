<?php

class Model_Buildings extends Model
{

	public function get_data()
	{
		if ($this->check_session()) 
		{
			$user_status = $_SESSION['user_status'];
			if ($user_status>=5)
			{
				$disclosed = -1; //Здесь сохраним areaId, чтобы раскрыть соответсвующий пункт меню, если пользователь выбирал определённую улицу
				 
				//Сформируем данные дервовидного меню для выбора улицы
				$street_tree = $this->evaluate_Query("	SELECT area.id AS areaId, area.name AS areaName, street.id AS streetId, street.name AS streetName
														FROM `street`, `area`
														WHERE area.id = street.area_id
														ORDER BY areaId, streetId");
					
				
				//Сформируем строку WHERE для SQL-запроса в зависимости от GET-параметров
				//По умолчанию, если не передано GET параметров:
				$where = "street.id = building.street_id";	
				//Фильтр по улице
				if (isset($_GET['streetId'])) 
				{$where = $where." AND street.id=".$this->SafeSQL($_GET['streetId'],4); unset($_GET['streetId']); $disclosed=$_GET['areaId'];};
				
				//Фильтр по району
				if (isset($_GET['areaId'])) {$where = $where." AND street.area_id=".$this->SafeSQL($_GET['areaId'], 4); unset($_GET['areaId']);};
				
				//Если пользователь - член управляющей компании, наложим ограничение на список зданий
				if ($user_status == 5 ) {$where = $where." AND building.mgcompany_id=".$_SESSION['user_mgcompany'];};
				
				$query = "	SELECT street.name AS streetName, building.id AS buildingId, building.no AS no, building.building_date AS buildingDate,
							building.total_area AS totalArea, building.living_area AS livingArea
							FROM `building`, `street`
							WHERE $where
							";

				//Если фильтр для отбора не задан, то выведем 30 последних созданных элементов							
				if ($where=="street.id = building.street_id") 
				{
					$query=$query."ORDER BY building.id DESC LIMIT 0,30";
				}
				else
				{
					$query=$query."ORDER BY street.name, building.no";
				};
																				
				$buildings = $this->evaluate_Query($query);
							
				$data['street_tree'] = $street_tree;
				$data['buildings'] = $buildings;
				$data['disclosed'] = $disclosed;
				$data['access'] = $user_status;
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
}
