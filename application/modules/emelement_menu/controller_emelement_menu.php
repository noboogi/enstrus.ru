<?php

class Controller_emelement_menu extends Controller
{

	function __construct()
	{
		$this->model = new Model_emelement_menu();
		$this->view = new View();
	}


	
	function action_index()
	{
		$data = $this->model->get_data();		
		$this->view->generateBlock('emelement_menu', $data);
	}
	


}
