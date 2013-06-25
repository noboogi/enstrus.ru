<?php

class Controller_building_menu extends Controller
{

	function __construct()
	{
		$this->model = new Model_building_menu();
		$this->view = new View();
	}


	
	function action_index()
	{
		$data = $this->model->get_data();		
		$this->view->generateBlock('building_menu', $data);
	}
	


}
