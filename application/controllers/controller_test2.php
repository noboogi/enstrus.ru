<?php

class Controller_test2 extends Controller
{


	function __construct()
	{
		$this->model = new Model_test2();
		$this->view = new View();
	}
	
	function action_index()
	{			
		$data = $this->model->get_data();
		$this->view->generate('test2_view.php', 'template_view.php', $data);
	}
}