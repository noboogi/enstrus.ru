<?php
session_start();
class Model_building_menu extends Model
{

	public function get_data()
	{		
		/*Проверка авторизации*/
		if ($this->check_session()) {			
			/*Пункты меню*/
			if (isset($_GET['bid'])) {$bid='?bid='.$_GET['bid'];};
			$data = array	(
				array ('Name' => 'Объявления', 'Url' => '/building_passport'.$bid,'Icon' => 'news'),
				array ('Name' => 'Паспорт', 'Url' => '/building_passport/passport'.$bid,'Icon' => 'passport'),
				array ('Name' => 'Заявки', 'Url' => '/building_passport/laments'.$bid,'Icon' => 'lament'),		
				array ('Name' => 'Услуги', 'Url' => '/building_passport/services'.$bid,'Icon' => 'service'),
				array ('Name' => 'Приборы учёта', 'Url' => '/building_passport/emelements'.$bid,'Icon' => 'graph')
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