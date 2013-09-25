<?php
class Controller_building extends Controller {

	private $requestedBid;
	
	function __construct() {
		parent::__construct();
		if (!$this->CheckSession()) {header('Location:/login');}
		$this->requestedBid = isset($_GET['bid']) ? $_GET['bid'] : $this->user->GetBuildingId();			
	}
	
	function action_index() {
		$this->action_news();	
	}
	
	function action_news() {
		$this->model = new Model_building_news($this->requestedBid);
		if (!$this->model->building->AccessGranted($this->user)) {header('Location:/login');}
		$data = $this->model->getNews();
		$this->ShowView('template_view.php', 'building_news_view.php', $data, array('main_menu', 'building_menu'));			
	}
	
	function action_emelements() {
		$this->model = new Model_building_emelements($this->requestedBid);
		if (!$this->model->building->AccessGranted($this->user)) {header('Location:/login');}
		
		if ($this->user->GetStatus() == MEMBER) {	
			/*Надо получить только счётчик, принадлежащий юзеру*/
			/*А ещё лучше - сразу перенаправить на страницу счётчика*/				
		} 
		elseif ($this->user->GetStatus() >= HOA) {
			$data = $this->model->ObtainEmelementsList();
			$this->ShowView('template_view.php', 'building_emelements_view.php', $data, array('main_menu', 'building_menu'));
		}		
	}
}
