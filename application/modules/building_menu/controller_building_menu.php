<?php

class Controller_building_menu extends Controller
{
	function __construct() {
		parent::__construct();
		$this->model = new Model_building_menu();
	}

	function action_index() {
		$bid = isset($_GET['bid']) ? '?bid='.$_GET['bid'] : '';	
		$data = $this->model->get_data($bid);		
		return $this->view->GenerateBlock('building_menu', $data);
	}
}
