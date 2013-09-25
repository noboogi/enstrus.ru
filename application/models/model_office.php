<?php
class Model_office extends Model {

	public function GetAdministrationOfficeData() {
		//Новости для администрации проекта
		$newsQuery = "SELECT 
					development_news.user_id, development_news.date, development_news.header, 
					development_news.content, user.id, user.fullName
					FROM `development_news`,`user`
					WHERE user.id = development_news.user_id
					ORDER BY Date DESC
					LIMIT 0 , 30";
		$this->data['news'] = BDSM::GetAll($newsQuery);
		
		//Контакты разработчиков
		$contactsQuery = "SELECT 
						user.fullName,user_contacts.mobilePhone, user_contacts.phone, 
						user_contacts.email, user_contacts.icq, user_contacts.skype
						FROM `user_contacts` , `user`
						WHERE user.status = ?i
						AND user.id = user_contacts.user_id";
		$this->data['contacts'] = BDSM::GetAll($contactsQuery, ADMINISTRATOR);
		return $this->data;
	}

	public function GetMgCompanyOfficeData() {
		$this->data = "Тут офис управляющей компании";
		return $this->data;
	}
}
