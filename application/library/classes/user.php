<?php
class User {
	private $id;
	private $login;
	private $password;
	private $regdate;
	private $fullName;
	private $status;
	private $mgcompany_id;
	private $flat_id;
	private $address;
	public	$exist;
	
	function __construct($login) {
		$user = BDSM::getRow('SELECT * FROM user WHERE login=?s',$login);
		if (!count($user)==1) {
			$this->exist = FALSE;
		}
		else {
			$this->exist = TRUE;
			$this->id=$user['id'];
			$this->login=$user['login'];
			$this->password=$user['pass'];
			$this->regdate=$user['regDate'];
			$this->fullName=$user['fullName'];
			$this->status=$user['status'];
			$this->mgcompany_id=$user['mgcompany_id'];
			$this->flat_id=$user['flat_id'];
			$this->address=$this->ObtainAddress();
		}
	}
		
	private function ObtainAddress() {
		$query = '	SELECT street.id AS streetId, street.name AS streetName, building.id AS buildingId, building.no AS buildingNo,  
					porch.id AS porchId, porch.no AS porchNo, floor.id AS floorId, floor.no AS floorNo, flat.id AS flatId, flat.no AS flatNo
					FROM street, building, porch, floor, flat
					WHERE flat.id = ?i AND floor.id = flat.floor_id AND floor.porch_id = porch.id 
					AND building.id = porch.building_id AND building.street_id = street.id';
		$address = BDSM::getRow($query,$this->flat_id);
		return $address;	
	}
	
	public function Auth($password) {
		$hash = hash('sha256',($this->login).($password).($this->regdate));
		return ($hash == $this->password) ? TRUE : FALSE;	
	}
	
	public function GetStatus() {
		return $this->status;
	}
	
	public function GetId() {
		return $this->id;
	}
	
	public function GetLogin() {
		return $this->login;
	}

	public function GetRegdate() {
		return $this->regdate;
	}

	public function GetFullName() {
		return $this->fullName;
	}	

	public function GetMgcompanyId() {
		return $this->mgcompany_id;
	}		

	public function GetFlatId() {
		return $this->flat_id;
	}
	
	public function GetAddress() {
		return $this->address;
	}
	
	public function GetBuildingId() {
		$tmp = $this->address;
		return $tmp['buildingId'];
	}	
	
	
}
?>