<?php
class Building {
	public $exist = TRUE;
	private $id;
	private $type;			/*Тип здания - жилое, магазин и т.д*/
	private $lat;			/*Широта*/
	private $lng;			/*Долгота*/
	private $streetId;		/*id улицы*/
	private $no;			/*Номер*/
	private $totalArea;		/*Общая площадь*/
	private $livingArea;	/*Жилая площадь*/
	private $flatCount;		/*Количество квартир*/
	public	$floorCount;	/*Количество этажей*/
	private $porchCount;	/*Количество подъездов*/
	private $mgcompanyId;	/*id управляющей компании*/
	private $address;		/*Адрес*/
		
	function __construct($bid) {
		$building = BDSM::getRow('SELECT * FROM building WHERE id=?i',$bid);
		if (count($building)!=0) {
			$this->id=$building['id'];
			$this->type=$building['type'];
			$this->lat=$building['lat'];
			$this->lng=$building['lng'];	
			$this->streetId=$building['street_id'];
			$this->no=$building['no'];
			$this->totalArea=$building['total_area'];
			$this->livingArea=$building['living_area'];	
			$this->flatCount=$building['flat_count'];
			$this->floorCount=$building['floor_count'];
			$this->porchCount=$building['porch_count'];
			$this->mgcompanyId=$building['mgcompany_id'];
			$this->address=$this->ObtainAddress();
		}	
		else {
			$this->exist = FALSE;
		}			
	}
	
	public function AccessGranted($user) {
		$userStatus = $user->GetStatus();
		if ($userStatus >= MODERATOR) {return TRUE;}
		elseif ($user->GetMgcompanyId() == $this->mgcompanyId) {return TRUE;}
		else {
			$userAddress = $user->GetAddress();
			if ($userAddress['buildingId'] == $this->id) {return TRUE;}
		}		
		return FALSE;			
	}

	private function ObtainAddress() {
		$query = '	SELECT street.name AS streetName, building.no AS buildingNo 
					FROM building, street 
					WHERE building.id=?i AND building.street_id=street.id';
		$building = BDSM::getRow($query,$this->id);
		$address = 'ул. '.$building['streetName'].', д. '.$building['buildingNo'];
		return $address;								
	}
					
		
	public function GetAddress() {
		return $this->address;
	}
	
	public function GetId() {
		return $this->id;
	}
}
?>