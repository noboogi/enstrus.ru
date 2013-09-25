<?php
class Controller_Main extends Controller {
	
	function __construct() {
		parent::__construct();
	}

	function action_index() {	
		$this->ShowView('template_view.php', 'main_view.php', NULL, array('main_menu'));
	}
}
?>