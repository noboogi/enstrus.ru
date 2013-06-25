<?php


class Controller_Emelements extends Controller
{

	function __construct()
	{
		$this->model = new Model_Emelements();
		$this->view = new View();	
	}
	
	function action_index()
	{	
		$data['data'] = $this->model->get_data();
		$this->view->generate('emelements_view.php', 'template_view.php', $data);
	}
	
	function action_upload()
	{	
		$data['uploadStatus'] = $this->model->upload();
		$data['data'] = $this->model->get_data();
		$this->view->generate('emelements_view.php', 'template_view.php', $data);
	}



}
