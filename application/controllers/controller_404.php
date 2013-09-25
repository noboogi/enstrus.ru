<?php
class Controller_404 extends Controller {	
	function action_index() {
		$this->ShowView('empty_template_view.php', '404_view.php', NULL);
	}
}
