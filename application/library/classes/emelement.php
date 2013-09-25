<?php
/*Счётчик электрический*/
class Emelement {
	
	private $id;
	private $sn;
	private $type;
	public 	$exist = TRUE;
	
	function __construct($eid) {
		$emelement = BDSM::getRow('SELECT * FROM emelement WHERE id=?i',$eid);
		if (count($emelement)!=0) {
			$this->id=$emelement['id'];
			$this->sn=$emelement['sn'];
			$this->type=$emelement['type'];
		}	
		else {
			$this->exist = FALSE;
		}
	}

	public function GetId() {
		return $this->id;
	}
	
	public function GetSn() {
		return $this->sn;
	}	
	
	public function GetType() {
		return $this->type;
	}	

}
?>