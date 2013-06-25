<?php

class Controller_Login extends Controller
{

	function __construct()
	{
		$this->model = new Model_login();
		$this->view = new View();
	}
	
	function action_index()
	{
			
		if ($this->model->check_session()) {
				/*����� ��� ��������*/
				//�������������� � ��	
				header('Location:/office');		
		}
		else {
				/*���� �� ��� ��������*/
				if(isset($_POST['login']) && isset($_POST['password']))
				{
					$login=$_POST['login'];
					$password=$_POST['password'];
					$data = $this->model->get_data($login,$password);	
				}
				$this->view->generate('login_view.php', 'empty_template_view.php', $data);		
		}
	}

	function action_logout()
	{
		session_start(); //����� ��������� - ���� ������� ���������...
		session_destroy();
		//�������������� �� �������
		header('Location:/main');
		
	}
	
}
