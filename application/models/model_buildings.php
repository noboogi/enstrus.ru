<?php

class Model_Buildings extends Model {
	
	public function get_data($filter) {
		/*id улицы, которая будет раскрыта в меню, во view*/
		$this->data['disclosed'] = -1;
				 
		//Сформируем данные дервовидного меню для выбора улицы
		$this->data['streetTreeMenu'] = BDSM::GetAll("SELECT area.id AS areaId, area.name AS areaName, street.id AS streetId, street.name AS streetName
										FROM `street`, `area`
										WHERE area.id = street.area_id
										ORDER BY areaId, streetId");
													
		$where = " street.id = building.street_id";	
		if (isset($filter['areaId'])) {
			$where = BDSM::parse($where." AND street.area_id=?i",$filter['areaId']);
			$this->data['disclosed'] = intval($filter['areaId']);
		}
		if (isset($filter['streetId'])) {
			$where = BDSM::parse($where." AND street.id=?i",$filter['streetId']);
		}
		if (isset($filter['mgcompanyId'])) {
			$where = BDSM::parse($where." AND building.mgcompany_id=?i",$filter['mgcompanyId']);
		}
		//Тут по идее parseQuery массивом фильтра, щапридумаем
		
				
		$query = "	SELECT street.name AS streetName, building.id AS buildingId, building.no AS no, building.building_date AS buildingDate,
					building.total_area AS totalArea, building.living_area AS livingArea
					FROM `building`, `street`
					WHERE ?p 
					ORDER BY street.name, building.no DESC";
																	
		$this->data['buildingsList'] = BDSM::GetAll($query, $where);					
		return $this->data;	
	}
}
