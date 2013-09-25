<?php

class Controller_Upload_Measurements_File extends Controller {
	
	private $data;
	
	function __construct() {
		parent::__construct();
		if (!($this->CheckSession(MODERATOR))) {header('Location:/login');}
		$this->model = new Model_UploadMeasurementsFile();
	}
	
	function action_index() {
		$data = NULL;
		if (isset($_GET['eid'])) {
			$this->data['eid'] = $_GET['eid'];
		}
		if (isset($_GET['sn'])) {
			$this->data['sn'] = $_GET['sn'];
		}		
		$this->view->generate('upload_measurements_file_view.php', 'empty_template_view.php', $this->data);
	}
	
	function action_upload() {
		$this->data['error'] = FALSE;
		$emelement = NULL;
		$filePath = '';
		
		/*Проверка входных параметров*/
		if (isset($_POST['eid'])) {
			$emelement = new Emelement($_POST['eid']);
			if (!($emelement->exist)) {
				$this->data['error'] = 'Некорректно задан идентификатор счётчика';
			}
			elseif (!($emelement->GetType() == 1)) {
				$this->data['error'] = 'Данный счётчик не поддерживает получасовые измерения';	
			}
		}
		else {
			$this->data['error'] = 'Не задан идентификатор счётчика';
		};
		
		/*Обработка загруженного файла*/	
		if(!$this->data['error'] && $_FILES["filename"]["size"] > 1024*1*1024) {$data['error'] = "Размер файла превышает 1 Мб";}
		if(!$this->data['error'] && is_uploaded_file($_FILES["filename"]["tmp_name"])) {			
			$filePath = "files/measures/".iconv("UTF-8",  "CP1251", $_FILES["filename"]["name"]);
			if (!move_uploaded_file($_FILES["filename"]["tmp_name"], $filePath)) {	
				$data['error'] = 'Ошибка при сохранении файла на сервере';	
			}
		} 
		else {
			$data['error'] = 'Ошибка загрузки файла';
		}
		
		if (!$this->data['error']) {
			$this->data['uploadResult'] = $this->model->ParseMeasurementsFile($filePath, $emelement->GetId());
		}
		
		$this->data['eid'] = $emelement->GetId();
		$this->data['sn'] = $emelement->GetSn();			
		$this->view->generate('upload_measurements_file_view.php', 'empty_template_view.php', $this->data);
	}						
	
}