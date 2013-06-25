<?php

class Model_Login extends Model
{

	public function get_data($login, $password)
	{
		if ($login<>"" && $password<>"") //1
		{
			/*Валидация данных и хэширование пароля*/
			$login = $this->SafeSQL($login, 15);
			$password = $this->SafeSQL($password, 30);

			$user = $this->evaluate_Query("SELECT * FROM user WHERE login='$login'");
	
			/*Определяем, каков результат запроса*/
			 if (mysql_num_rows($user) != 1) 
			  {
				$data['message']="Пользователь с таким именем не зарегистрирован!"; //Или таких пользователей несколько 0_о
				$data['error']="error";
			  }
			 else 
			 {  
			 	$user = mysql_fetch_array($user);
				// Какой статус у пользователя?
				 if ($user['status'] == 0) 
				 {
					 $data['message']="Логин ещё не активирован";
  					 $data['error']="error"; 
				 }
				 elseif ($user['status'] == 1)
				 {
					 $data['message']="Пользователь заблокирован. Обратитесь к администратору сайта";
					 $data['error']="error";
				 }
				 else
				 {		
				 	
					//Проверка пароля
					//Вычисляем хэш от пароля с солью :)
					$password = hash('sha256',$user['login'].$password.$user['regdate']);
					if ($password != $user['pass'])
					{
						$data['message']="Неправильный пароль".$password;
					 	$data['error']="error";
					}
					else
					{		 	
						//session_start();
						$_SESSION['user_id'] 		= $user['id'];								
						$_SESSION['user_login'] 	= $user['login']; 	
						$_SESSION['user_name'] 		= $user['fullName']; 		
						$_SESSION['user_flat'] 		= $user['flat_id'];		
						$_SESSION['user_status'] 	= $user['status'];	
						$_SESSION['user_mgcompany'] = $user['mgcompany_id'];					
						
						//Установим адрес пользователя
						$query = '	SELECT street.id AS streetId, street.name AS streetName, building.id AS buildingId, building.no AS buildingNo,  
									porch.id AS porchId, porch.no AS porchNo, floor.id AS floorId, floor.no AS floorNo, flat.id AS flatId, flat.no AS flatNo
									FROM street, building, porch, floor, flat
									WHERE flat.id = '.$_SESSION['user_flat'].' AND floor.id = flat.floor_id AND floor.porch_id = porch.id 
									AND building.id = porch.building_id AND building.street_id = street.id';
						$res = $this->evaluate_Query($query);
						//Сформируем читаемую строку адреса
						$_SESSION['user_street_id'] = 	mysql_result($res, 0, 0);
						$_SESSION['user_street_name'] = mysql_result($res, 0, 1);
						$_SESSION['user_building_id'] =	mysql_result($res, 0, 2);
						$_SESSION['user_building_no'] =	mysql_result($res, 0, 3);
						$_SESSION['user_porch_id'] =	mysql_result($res, 0, 4);
						$_SESSION['user_porch_no'] =	mysql_result($res, 0, 5);
						$_SESSION['user_floor_id'] =	mysql_result($res, 0, 6);
						$_SESSION['user_floor_no'] =	mysql_result($res, 0, 7);
						$_SESSION['user_flat_id'] =		mysql_result($res, 0, 8);
						$_SESSION['user_flat_no'] =		mysql_result($res, 0, 9);
						
					
						$data['message']="Вход в истему выполнен";
						$data['error']="success";
						header('Location:/office');
					}
				 }
			  }
		} 
		else
		{
			$data['message'] = "Введите логин и пароль";
			$data['error'] = "error";		
		} //1

		return $data;
	}

}
