<?php
class Controller_emelement extends Controller {
	
	private $requestedEid;
	
	function __construct() {
		parent::__construct();
		if (!$this->CheckSession()) {header('Location:/login');}
		if (isset($_GET['eid'])) {$requestedEid = $_GET['eid'];}
		$this->model = new Model_emelement($this->$requestedEid);
	}
	
	function action_index() {			
		$data = $this->model->get_data();
		$this->ShowView('template_view.php', 'emelement_view.php', $data);
	}
}