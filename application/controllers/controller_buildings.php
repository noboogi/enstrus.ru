<?php


class Controller_buildings extends Controller
{

	function __construct()
	{
		$this->model = new Model_Buildings();
		$this->view = new View();	
	}
	
	function action_index()
	{	
		$data = $this->model->get_data();
		if ($data['access'] == 7)
		{
			//Если админ - используем view с дополнительными элментами управления
			$this->view->generate('a_buildings_view.php', 'template_view.php', $data);
		}
		else
		{
			//Стандартная view
			$this->view->generate('buildings_view.php', 'template_view.php', $data);
		}
	}



}
