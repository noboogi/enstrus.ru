<?php


class Controller_Emeasurements extends Controller
{

	function __construct()
	{
		$this->model = new Model_Emeasurements();
		$this->view = new View();	
	}
	
	function action_index()
	{	
		$data = $this->model->get_data();
		$this->view->generate('emeasurementsTable_view.php', 'template_view.php', $data);
	}
	
	//Запрос данных в виде таблицы
	function action_table()
	{	
		$data = $this->model->get_data();
		$this->view->generate('emeasurementsTable_view.php', 'template_view.php', $data);
	}
	
	//Запрос данных в виде графика
	function action_graph()
	{	
		$data = $this->model->get_data();
		$this->view->generate('emeasurementsGraph_view.php', 'template_view.php', $data);
	}



}
