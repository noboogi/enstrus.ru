<?php
class Controller_main_menu extends Controller {

	function __construct() {
		parent::__construct();
		$this->model = new Model_main_menu();
	}

	function action_index() {
		$userStatus = 0;
		if ($this->user <> NULL) {$userStatus = $this->user->GetStatus();}
		$data = $this->model->get_data($userStatus);		
		return $this->view->GenerateBlock('main_menu', $data);
	}
}
?>
