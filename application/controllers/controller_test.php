<?php

class Controller_test extends Controller
{


	function __construct()
	{
		$this->model = new Model_test();
		$this->view = new View();
	}
	
	function action_index()
	{			
		$data = $this->model->get_data();
		$this->view->generate('test_view.php', 'template_view.php', $data);
	}
}