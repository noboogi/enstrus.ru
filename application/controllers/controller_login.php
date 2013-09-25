<?php
class Controller_Login extends Controller {

	function __construct() {
		parent::__construct();
		$this->model = new Model_login();
	}
	
	function action_index() {	
		if ($this->checkSession()) {
			/*Выход уже выполнен, перенаправляем в ЛК*/	
			header('Location:/office');		
		}
		else {
			/*Вход ещё не был выполнен*/
			$data = NULL;
			if(isset($_POST['login']) && isset($_POST['password'])) {
				$data = $this->model->get_data($_POST['login'],$_POST['password']);
				if ($data instanceof User) {$_SESSION['user'] = $data; header('Location:/office');}	
			}
			$this->ShowView('empty_template_view.php', 'login_view.php', $data);		
		}
	}

	function action_logout()
	{
		session_destroy();
		header('Location:/main');
	}
	
}
