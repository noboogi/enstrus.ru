<?php
	function evaluate_Query($q)
	{
		global $db_loc,$db_name,$db_user_adm,$db_pass_adm;
		$link = mysql_connect($db_loc,$db_user_adm,$db_pass_adm);
		mysql_query('SET NAMES utf8');
		mysql_select_db($db_name, $link);
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
	
	function getDBLink()
	{
		global $db_loc,$db_name,$db_user_adm,$db_pass_adm;
		$link = mysql_connect($db_loc,$db_user_adm,$db_pass_adm);
		mysql_query('SET NAMES utf8');
		mysql_select_db($db_name, $link);
		if (!$link) 
			{
				return 0;
			}
			else
			{
				return $link;
			}	
	}
	
	//Дополнительная проверка на SQL-инъекцию
	function CheckKeyWords($q) 
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
	function SafeSQL($data, $length=NULL)
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
	
?>