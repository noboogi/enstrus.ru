<?php
class Model_Login extends Model
{

	public function get_data($login, $password)
	{
		$user = new User($login);
		if (!$user->exist) {
			$this->data['error']="Пользователь с таким именем не зарегистрирован";
			return $this->data;
		}
		  
		$userStatus = $user->GetStatus(); 
		if ($userStatus == NOACTIVATED) {
			$this->data['error']="Пользователь ещё не активирован";
			return $this->data;
		}
		
		if ($userStatus == BLOCKED) {
			$this->data['error']="Пользователь заблокирован. Обратитесь к администрации портала";
			return $this->data;
		}
	
		if (!$user->Auth($password)) {
			$this->data['error']="Неправильный пароль";
			return $this->data;
		}
		
	 	return $user;
	} 

}


