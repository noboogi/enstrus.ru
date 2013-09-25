<?php
class Model_emelement_common extends Model {
	
	public $emelement;
	
	public function __construct($eid) {
		$this->emelement = new Emelement($eid);
		if (!$this->emelement->exist) {die("Запрошенный счётчик не существует");}
		$this->data['eid'] = $this->emelement->GetId();
		$this->data['address'] = $this->emelement->GetAddress();			
	}

}
