<?php

class Model
{
	var $domain='localhost';
	var $db_name = "teiriko_synergy";
	var $db_user_adm = "teiriko_synergy";
	var $db_pass_adm = "";
	var $db_loc = "localhost";
	

	public function evaluate_Query($q)
	{
		$link = mysql_connect($this->db_loc,$this->db_user_adm,$this->db_pass_adm);
		mysql_query('SET NAMES utf8');
		mysql_select_db($this->db_name, $link);
		if (!$link) 
			{
				return 0;
			}
			else
			{
					$res = mysql_query($q,$link);
					mysql_close($link);	
					return $res;
			}
	}

	// метод выборки данных
	public function get_data()
	{

	}
	
	
	//Дополнительная проверка на SQL-инъекцию
	public function CheckKeyWords($q) 
	{
	  $q = strtolower($q); // Приравниваем текст параметра к нижнему регистру
	  if (
		!strpos($q, "select") && //
		!strpos($q, "union") && //
		!strpos($q, " or ") && //
		!strpos($q, "create") && //
		!strpos($q, "order") && // Ищем вхождение слов в параметре
		!strpos($q, "where") && //
		!strpos($q, "char") && 	//
		!strpos($q, "from") 	//
	  ) {
		return true; // Вхождений нету - возвращаем true
	  } else {
		return false; // Вхождения есть - возвращаем false
	  }
	}

	//Валидация данных
	public function SafeSQL($data, $length=NULL)
	{
		//Обрезаем строку, если требуется
		if ($length != NULL)
		{
			$data = substr($data,0,$length);
		}
		//Удаляем спецсимволы и возвращаем
		$data = strip_tags(mysql_escape_string($data));
		If ($this->CheckKeyWords($data))
		{
			return $data;	
		}		
		else
		{
			//Подозрение на SQL-инъекцию, можно записать в журнал...
			return NULL;
		}
	}

	//Функция проверки, авторизован ли пользователь
	public function check_session() 
	{
		session_start();
		if (isset($_SESSION['user_login']) && isset($_SESSION['user_id']) && isset($_SESSION['user_status']) && isset($_SESSION['user_name']))
		{
			return TRUE;
		}
		else {
			return FALSE;
		}
	}


}

