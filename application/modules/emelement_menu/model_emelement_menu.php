<?php
session_start();
class Model_emelement_menu extends Model
{

	public function get_data()
	{		
		/*Проверка авторизации*/
		if ($this->check_session()) {			
			/*Пункты меню*/
			if (isset($_GET['eid'])) {$eid='?eid='.$_GET['eid'];};
			if (isset($_GET['bid'])) {$bid='&bid='.$_GET['bid'];};
			$data = array	(
				array ('Name' => 'Показания', 'Url' => '/building_passport/emeasures'.$eid.$bid),
				array ('Name' => 'График', 'Url' => '/building_passport/egraph'.$eid.$bid),
				array ('Name' => 'Характеристики', 'Url' => '/building_passport/echars'.$eid.$bid),		
				array ('Name' => 'История', 'Url' => '/building_passport/ehistory'.$eid.$bid)
			);
			return $data;		
		}		
		/*Пользователь не авторизован*/
		else
		{
			header('Location:/login');
		}

	}

}
?>