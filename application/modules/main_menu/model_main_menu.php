<?php
class Model_main_menu extends Model {

	public function get_data($userStatus) {		
		/*Основные пункты*/
		$menu = array	(
			array ('id' => 1, 'Name' => 'Главная', 'Url' => '/main','Icon' => '','Access' => NOACTIVATED),
			array ('id' => 2, 'Name' => 'База данных', 'Url' => '#','Icon' => 'database','Access' => MGCOMPANY),
			array ('id' => 3, 'Name' => 'Документы', 'Url' => '#','Icon' => 'docs','Access' => MGCOMPANY),		
			array ('id' => 4, 'Name' => 'Отчёты', 'Url' => '#','Icon' => 'reports','Access' => MGCOMPANY),
			array ('id' => 5, 'Name' => 'Пользователи', 'Url' => '#','Icon' => 'users','Access' => MODERATOR),
			array ('id' => 6, 'Name' => 'Личный кабинет', 'Url' => '/office','Icon' => 'login','Access' => MEMBER)	
		);
		if (!$userStatus) {
			$menu[] = array ('id' => 7, 'Name' => 'Вход', 'Url' => '/login','Icon' => 'login','Access' => NOACTIVATED);
		}
		
		/*Пункты второго уровня*/
		$submenu = array	(
			array ('id' => 1, 'pid' => 2, 'Name' => 'Жилые дома', 'Url' => '/buildings','Icon' => '','Access' => MGCOMPANY),
			array ('id' => 2, 'pid' => 2, 'Name' => 'Потребители', 'Url' => '#','Icon' => '','Access' => MODERATOR),
			array ('id' => 3, 'pid' => 2, 'Name' => 'Приборы учёта', 'Url' => '#','Icon' => '','Access' => MODERATOR),		
			array ('id' => 4, 'pid' => 2, 'Name' => 'Заявки', 'Url' => '#','Icon' => '','Access' => MGCOMPANY),
			array ('id' => 5, 'pid' => 4, 'Name' => 'Графики энергопотребления', 'Url' => '#','Icon' => '','Access' => MGCOMPANY),
			array ('id' => 6, 'pid' => 4, 'Name' => 'Заявки от потребителей', 'Url' => '#','Icon' => '','Access' => MODERATOR),
			array ('id' => 7, 'pid' => 6, 'Name' => 'Мой профиль', 'Url' => '#','Icon' => '','Access' => MEMBER),
			array ('id' => 8, 'pid' => 6, 'Name' => 'Выход', 'Url' => '/login/logout','Icon' => '','Access' => MEMBER)		
		);

		/*Пункты третьего уровня*/
		$submenu2 = array	(
			array ('pid' => 3, 'Name' => 'Счётчики электрические', 'Url' => '/emelements','Icon' => '','Access' => MGCOMPANY),		
			array ('pid' => 3, 'Name' => 'Счётчики воды', 'Url' => '#','Icon' => '','Access' => MGCOMPANY)		
		);

		/*В цикле удалим все пункты, уровень доступа которых не соответствует уровню доступа пользователя*/
		foreach ($menu as $item => $value) {
			if ($menu[$item]['Access'] > $userStatus) {unset($menu[$item]);};
		}
		foreach ($submenu as $item => $value) {
			if ($submenu[$item]['Access'] > $userStatus) {unset($submenu[$item]);};
		}
		foreach ($submenu2 as $item => $value) {
			if ($submenu2[$item]['Access'] > $userStatus) {unset($submenu2[$item]);};
		}
		
		$data = array("menu" => $menu, "submenu" => $submenu, "submenu2" => $submenu2);
		return $data;
	}

}
?>