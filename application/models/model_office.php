<?php


class Model_office extends Model
{

	
	public function get_data($section=null)
	{
		$data = '';
		/*Если пользователь авторизован, то перенаправим его в...*/
		if ($this->check_session())
		{
			$user_status = $_SESSION['user_status'];
			//Если пользователь - администратор (модератор, инженер, сотрудник)
			if ($user_status > 4) 
			{
					$data['status'] = $user_status;
					//Получим новости для администрации проекта
					$news = $this->evaluate_Query("	SELECT 
													development_news.user_id, development_news.date, development_news.header, 
													development_news.content, user.id, user.fullName
													FROM `development_news`,`user`
													WHERE development_news.access <= $user_status AND user.id = development_news.user_id
													ORDER BY Date DESC
													LIMIT 0 , 30");
					//Контакты разработчиков
					$contacts = $this->evaluate_Query("	SELECT 
														user.fullName,user_contacts.mobilePhone, user_contacts.phone, 
														user_contacts.email, user_contacts.icq, user_contacts.skype
														FROM `user_contacts` , `user`
														WHERE user.status =7
														AND user.id = user_contacts.user_id");
					$data['news'] = $news;
					$data['contacts'] = $contacts;
			}
			//Если пользователь - клиент
			else 
			{
				//Запрос для клиента
				$data['status'] = $user_status;
				//Сформируем заголовок личного кабинета для пользователя
				$address = "ул. ".$_SESSION['user_street_name'].", д. ".$_SESSION['user_building_no'];
				//Если для пользователя определена квартира
				if ($_SESSION['user_flat_no']!=NULL && $_SESSION['user_flat_no']!=0) {$address = $address.", кв. ".$_SESSION['user_flat_no'];}								
				$data['address'] = $address;								

				
		
				//В зависимости от запрошенного раздела ЛК сформируем данные
				if ($section!=null)
				{

					switch ($section) 
					{
						case "passport" : break;
						case "laments" : break;
						case "services" : break;
						case "measurements" : 
							
							//Отбор счетчиков, привязанных на уровне здания (ВРУ)
							$query = "	SELECT emelement.sn AS sn, emelement.type AS type, c_emelementtype.label AS label, emelement.cRatio AS cRatio,
										emelement.descr AS descr  
										FROM emelement, c_emelementtype
										WHERE emelement.type = c_emelementtype.id AND emelement.building_id=".$_SESSION['user_building_id']." 
										AND emelement.porch_id IS NULL";
							$test = $query;
							$buildingEmelements = $this->evaluate_Query($query);
							
							//Отбор счетчиков, привязанных на уровне подъезда

							if ($user_status>=4) 
							{
								//Если председатель ТСЖ (вывод счётчика по подъезду жильца)
								$query = "	SELECT emelement.sn AS sn, emelement.type AS type, c_emelementtype.label AS label, emelement.cRatio AS cRatio,
											emelement.descr AS descr  
											FROM emelement, c_emelementtype
											WHERE emelement.type = c_emelementtype.id AND emelement.building_id=".$_SESSION['user_building_id']." 
											AND emelement.porch_id IS NOT NULL AND emelement.floor_id IS NULL AND emelement.flat_id IS NULL";
							}
							else {$query='SELECT * FROM emelement WHERE emelement.sn<-1' ;}; //Костыль - запрос всегда возвращает ноль строк							
							$porchEmelements = $this->evaluate_Query($query);
							
							
							//Отбор счетчиков, привязанных на уровне квартиры (то есть пользователя)
							$query = "	SELECT emelement.sn AS sn, emelement.type AS type, c_emelementtype.label AS label, emelement.cRatio AS cRatio,
										emelement.descr AS descr  
										FROM emelement, c_emelementtype
										WHERE emelement.type = c_emelementtype.id
										AND emelement.building_id=".$_SESSION['user_building_id']." 
										AND emelement.porch_id IS NOT NULL AND emelement.floor_id IS NOT NULL";
							//Если председатель ТСЖ - то имеет право видеть счётики всех квартир			
							if ($user_status<4) {$query = $query." AND emelement.flat_id = ".$_SESSION['user_flat_id'];};
							
							$flatEmelements = $this->evaluate_Query($query);	
							/*:crazylol:*/														
							$data['buildingEmelements'] = mysql_num_rows($buildingEmelements) ? $buildingEmelements : FALSE;
							$data['porchEmelements'] = mysql_num_rows($porchEmelements) ? $porchEmelements : FALSE;
							$data['flatEmelements'] = mysql_num_rows($flatEmelements) ? $flatEmelements : FALSE;
						break;
					
					} //end of Switch			
				} //end of If
			}


		}
		/*Попытка зайти в личный кабинет без авторизации*/
		else 
		{
			header('Location:/login');
		}

		return $data;
	}

}
