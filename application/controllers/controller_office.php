<?php
class Controller_office extends Controller {

	function __construct() {
		parent::__construct();	
		$this->model = new Model_office();
	}
	
	function action_index() {
		$userStatus = $this->user->GetStatus();
		if ($userStatus >= MODERATOR) {
			$data = $this->model->GetAdministrationOfficeData();
			$this->ShowView('template_view.php', 'administration_office_view.php', $data, array('main_menu'));		
		}
		elseif ($userStatus == MGCOMPANY) {
			$data = $this->model->GetMgCompanyOfficeData();
			$this->ShowView('template_view.php', 'mgcompany_office_view.php', $data, array('main_menu'));		
		}
		else {
			//Офис потребителя-клиента или ТСЖ - перенаправляем сразу на страницу дома
			header('Location:/building/news');							
		}
	}
}
