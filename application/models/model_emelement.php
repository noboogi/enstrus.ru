<?php
class Model_emelement extends Model_emelement_common {
	
	public $emelement;
	
	public function __construct($eid) {
		parent::__construct();
	}
	
	public function GetChars() {
		$this->data['id'] = $this->emelement->GetId();
		$this->data['sn'] = $this->emelement->GetSn();
		$this->data['type'] = $this->emelement->GetType();
		
	}

}
