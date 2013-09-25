<?php
class Controller {
	
	public $model;
	public $view;
	public $user;
	private $data;
	
	function __construct() {
		$this->view = new View();		
		if (isset($_SESSION['user'])) {$this->user = $_SESSION['user'];};
	}

	/* @param string 			$commonTemplate - основной шаблон, /application/views/common/page_common_view */
	/* @param string			$contentTemplate - шаблон конкретной страницы, /application/views/page_view */
	/* @param array of mixed	$modules - список модулей, которые нужно подключить, array('main_menu', 'building_menu', 'etc')*/
	/* 							> элементы массива должны соответствовать именам плэйсхолдеров в шаблонах, {MAIN_MENU}, {ETC}*/
	/*							> регистр не важен*/
	/* @param mixed				$data - данные для подстановки в шаблон, непосредственно контент страницы*/
	public function ShowView($commonTemplate, $contentTemplate, $data = NULL, $modules = array()) {
		$this->view->Init($commonTemplate, $contentTemplate, $data);
		/*Добавить проверку, является ли параметр массивом или просто строкой*/
		foreach ($modules as $module) {
			$this->view->AddModule($module);	
		}
		$this->view->Show();	
	}
		
	/*Проверка авторизации*/
	public function CheckSession($MinimalUserStatus = NULL) {
		if (isset($_SESSION['user'])) {
			if (!($MinimalUserStatus === NULL)) {
				if ($this->user->GetStatus() < $MinimalUserStatus) {return FALSE;}
			}
			return TRUE;
		}
		else {
			return FALSE;
		}
	}	
}
