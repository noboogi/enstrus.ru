<?php
class Controller_Emelements extends Controller {

	function __construct() {
		parent::__construct();
		if (!($this->CheckSession(MODERATOR))) {header('Location:/login');}
		$this->model = new Model_Emelements();	
	}
	
	function action_index() {	
		$data = $this->model->get_data();
		$this->ShowView('template_view.php', 'emelements_view.php', $data, array('main_menu'));
	}
	
	function action_UploadMeasurement() {	
		$data['uploadStatus'] = $this->model->UploadMeasurement();
		$data['data'] = $this->model->get_data();
		$this->ShowView('template_view.php', 'emelements_view.php', $data, array('main_menu'));
	}
}
