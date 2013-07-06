<?php


class Controller_building_passport extends Controller
{

	function __construct()
	{
		$this->model = new Model_building_passport();
		$this->view = new View();
	}
	
	//���� ��������� �������� �� ���������
	function action_index()
	{
		$data = $this->model->get_data();		
		$this->view->generate('building_passport_news_view.php', 'template_view.php', $data);	
	}
	
	//������� ������: ����������� �������
	function action_passport()
	{
		$data = $this->model->get_data("passport");			
		$this->view->generate('building_passport_techdata_view.php', 'template_view.php', $data);	
	}
	//������� ������: ������-������ ������������
	function action_laments()
	{
		$data = $this->model->get_data("laments");			
		$this->view->generate('customer_office_laments_view.php', 'template_view.php', $data);	
	}
	
	//������� ������: ������� ������
	function action_services()
	{
		$data = $this->model->get_data("services");			
		$this->view->generate('customer_office_services_view.php', 'template_view.php', $data);	
	}
	
	//�������: ��������� ����������� ������������� �������
	function action_emelements()
	{
		$data = $this->model->get_data("emelements");
		$this->view->generate('building_passport_emelements_view.php', 'template_view.php', $data);	
	}
	
	
	//����������� �������������� ��������
	function action_echars()
	{
		$data = $this->model->get_data("emelementChars");	
		$this->view->generate('building_passport_emelement_chars_view.php', 'template_view.php', $data);	
	}
	
	//������ ��������� ��������
	function action_emeasures()
	{
		$data = $this->model->get_data("emelementMeasures");
		$this->view->generate('building_passport_emelement_measures_view.php', 'template_view.php', $data);	
	}
	
	//������� ������������ (������, ������ � �.�.)
	function action_ehistory()
	{
		$data = $this->model->get_data("emelementHistory");
		$this->view->generate('building_passport_emelement_history_view.php', 'template_view.php', $data);	
	}
	
	//����������� ������ ��������� �� �������
	function action_egraph()
	{
		$data = $this->model->get_data("emelementGraph");
		$this->view->generate('building_passport_emelement_graph_view.php', 'template_view.php', $data);	
	}




}
