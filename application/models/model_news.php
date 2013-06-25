<?php

class Model_news extends Model
{

	public function get_data($type="all")
	{
		$Query='';
		
		/*� ����������� �� $type ���������� ������*/
		switch ($type) 
		{
			//������� ��� �������������
			case "all":
				$Query="SELECT development_news.id, development_news.access, development_news.Date, development_news.Header, users.fullName, development_news.Content
						FROM `development_news`,`users`
						WHERE users.id=development_news.Author
						ORDER BY Date DESC
						LIMIT 0 , 15
						";
				break;

			//������� ��� �������������
			case "adm":
				$Query="SELECT development_news.id, development_news.access, development_news.Date, development_news.Header, users.fullName, development_news.Content
						FROM `development_news`,`users`
						WHERE users.id=development_news.Author AND development_news.access<7
						ORDER BY Date DESC
						LIMIT 0 , 15
						";
				break;

			//����c�� ��� �������������, ������� ������� ��� �������������
			case "dev":
				$Query="SELECT development_news.id, development_news.access, development_news.Date, development_news.Header, development_news.Content, 
						users.fullName, users.Status
						FROM `development_news`,`users`
						WHERE users.id=development_news.Author
						ORDER BY Date DESC
						LIMIT 0 , 15
						";
				break;
		}

		return $this->evaluate_Query($Query);
	}

}
