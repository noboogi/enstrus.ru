<?php
class Controller_buildings extends Controller {

	function __construct() {
		parent::__construct();
		if (!($this->CheckSession(MGCOMPANY))) {header('Location:/login');}
		$this->model = new Model_Buildings();
	}
	
	function action_index() {	
		$template = 'a_buildings_view.php'; 
		$filter = array();
		if (isset($_GET['streetId'])) {$filter['streetId'] = $_GET['streetId'];}
		if (isset($_GET['areaId'])) {$filter['areaId'] = $_GET['areaId'];}
		if ($this->user->GetStatus() == MGCOMPANY) {$template = 'buildings_view.php'; $filter['mgcompanyId'] = $this->user->GetMgcompanyId();}
		
		$data = $this->model->get_data($filter);
		$this->ShowView('template_view.php', $template, $data, array('main_menu'));		
	}
}
