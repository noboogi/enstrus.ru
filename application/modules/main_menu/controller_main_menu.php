<?php

class Controller_main_menu extends Controller
{

	function __construct()
	{
		$this->model = new Model_main_menu();
		$this->view = new View();
	}


	
	function action_index()
	{
		$data = $this->model->get_data();		
		$this->view->generateBlock('main_menu', $data);
	}
	


}
