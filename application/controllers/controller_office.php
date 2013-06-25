<?php


class Controller_office extends Controller
{

	function __construct()
	{
		$this->model = new Model_office();
		$this->view = new View();
	}
	
	//Если запрошена главная страница личного кабинета
	function action_index()
	{
		$data = $this->model->get_data();
		if ($data['status'] > 5) 
		{	
			//Офис для администрации портала	
			$this->view->generate('administration_office_view.php', 'template_view.php', $data);
		}
		elseif ($data['status'] > 4)
		{
			//Офис управляющей компании			
			$this->view->generate('mgcompany_office_view.php', 'template_view.php', $data);				
		}
		else
		{
			//Офис потребителя-клиента			
			$this->view->generate('building_passport_news_view.php', 'template_view.php', $data);	
		}
	}
	
	//Офис: Технический паспорт
	function action_passport()
	{
		$data = $this->model->get_data("passport");			
		$this->view->generate('customer_office_passport_view.php', 'template_view.php', $data);	
	}
	//Офис: Жалобы-заявки пользователя
	function action_laments()
	{
		$data = $this->model->get_data("laments");			
		$this->view->generate('customer_office_laments_view.php', 'template_view.php', $data);	
	}
	
	//Платные услуги
	function action_services()
	{
		$data = $this->model->get_data("services");			
		$this->view->generate('customer_office_services_view.php', 'template_view.php', $data);	
	}
	
	//Измерения потребления энергии, счётчики (электричество, вода, прочее)
	function action_measurements()
	{
		$data = $this->model->get_data("measurements");			
		$this->view->generate('customer_office_measurements_view.php', 'template_view.php', $data);	
	}



}
