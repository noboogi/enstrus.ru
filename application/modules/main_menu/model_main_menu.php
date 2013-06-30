<?php
class Model_main_menu extends Model
{

	public function get_data()
	{		
		/*Позже тут будет SQL запрос, возвращающий пункты меню из базы...*/
		$user_access=0;
		$data='';
		if (isset($_SESSION['user_status'])) {$user_access=$_SESSION['user_status'];}
		
		/*Основные пункты*/
		$menu = array	(
			array ('id' => 1, 'Name' => 'Главная', 'Url' => '/main','Icon' => '','Access' => 0),
			array ('id' => 2, 'Name' => 'База данных', 'Url' => '#','Icon' => 'database','Access' => 5),
			array ('id' => 3, 'Name' => 'Документы', 'Url' => '#','Icon' => 'docs','Access' => 5),		
			array ('id' => 4, 'Name' => 'Отчёты', 'Url' => '#','Icon' => 'reports','Access' => 5),
			array ('id' => 5, 'Name' => 'Пользователи', 'Url' => '#','Icon' => 'users','Access' => 6),
			array ('id' => 6, 'Name' => 'Личный кабинет', 'Url' => '/office','Icon' => 'login','Access' => 3)	
		);
		if (!$this->check_session()) {
			$menu[] = array ('id' => 7, 'Name' => 'Вход', 'Url' => '/login','Icon' => 'login','Access' => 0);
		}
		
		/*Пункты второго уровня*/
		$submenu = array	(
			array ('id' => 1, 'pid' => 2, 'Name' => 'Жилые дома', 'Url' => '/buildings','Icon' => '','Access' => 5),
			array ('id' => 2, 'pid' => 2, 'Name' => 'Потребители', 'Url' => '#','Icon' => '','Access' => 6),
			array ('id' => 3, 'pid' => 2, 'Name' => 'Приборы учёта', 'Url' => '#','Icon' => '','Access' => 6),		
			array ('id' => 4, 'pid' => 2, 'Name' => 'Заявки', 'Url' => '#','Icon' => '','Access' => 5),
			array ('id' => 5, 'pid' => 4, 'Name' => 'Графики энергопотребления', 'Url' => '#','Icon' => '','Access' => 5),
			array ('id' => 6, 'pid' => 4, 'Name' => 'Заявки от потребителей', 'Url' => '#','Icon' => '','Access' => 6),
			array ('id' => 7, 'pid' => 6, 'Name' => 'Мой профиль', 'Url' => '#','Icon' => '','Access' => 3),
			array ('id' => 8, 'pid' => 6, 'Name' => 'Выход', 'Url' => '/login/logout','Icon' => '','Access' => 3)		
		);

		/*Пункты третьего уровня*/
		$submenu2 = array	(
			array ('pid' => 3, 'Name' => 'Счётчики электрические', 'Url' => '/emelements','Icon' => '','Access' => 5),		
			array ('pid' => 3, 'Name' => 'Счётчики воды', 'Url' => '#','Icon' => '','Access' => 5)		
		);

		/*В цикле удалим все пункты, уровень доступа которых не соответствует уровню доступа пользователя*/
		foreach ($menu as $item => $value) {
			if ($menu[$item]['Access'] > $user_access) {unset($menu[$item]);};
		}
		foreach ($submenu as $item => $value) {
			if ($submenu[$item]['Access'] > $user_access) {unset($submenu[$item]);};
		}
		foreach ($submenu2 as $item => $value) {
			if ($submenu2[$item]['Access'] > $user_access) {unset($submenu2[$item]);};
		}
		
		$data = array("menu" => $menu, "submenu" => $submenu, "submenu2" => $submenu2);
		return $data;
	}

}
?>