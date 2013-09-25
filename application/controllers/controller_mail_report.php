<?php
class Controller_mail_report extends Controller
{

	function __construct()
	{
		$this->model = new Model_mail_report();
		$this->view = new View();
	}
	
	function action_index()
	{
		$data = $this->model->get_data();
		$this->view->generate('emelements_mailreport_view.php', 'template_view.php', $data);		
	}
}
?>
