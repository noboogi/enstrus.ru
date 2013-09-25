<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/application/library/db.php');

//Формирование отчёта для отправки в "Энергокомфорт"
function GetMailReport($emelementList, $startDate, $endDate, $action) {
	$userId = $_SESSION['user_id'];
	//Перебор всех дней в заданом диапазоне
	$startDate = new DateTime($startDate);
	$endDate = new DateTime($endDate);
	
	//Запрос номера документа для пользователя вставить
	$docNumber = 1;

	while ($startDate != $endDate) {
		$documentTimestamp = new DateTime(date('YmdHis'));;
		$dayTimestamp = $startDate->format('Ymd');
		//Для корректной нумерации из под админа надо добавить в базу владельца здания (или счётчика?)
		//$docNumber = GetDocNumber($userId);
		
		//Запрос на наш INN? Непонятно
		$INN = 6749387456;
		$fileName = '8020_1001263646_'.$dayTimestamp.'_'.$docNumber.'.xml';
		$fp = fopen("files/reports/".$fileName, "w");
		fwrite($fp, iconv("UTF-8", "WINDOWS-1251",GetDocHeader($docNumber,$documentTimestamp->format('YmdHis'), $dayTimestamp, $_POST['INN'],$_POST['companyName'])));

		foreach ($emelementList as $e) {
			$query = 'SELECT * FROM electricity_measurement WHERE electricity_measurement.emelement_id ='.$e['id'].
			' AND electricity_measurement.date = "'.$startDate->format('Y-m-d').'"';
			
			$query = evaluate_Query($query);
			//Проверить, есть ли 48 измерений. Если нет - ошибка
			$row = mysql_fetch_array($query);
			fwrite($fp, iconv("UTF-8", "WINDOWS-1251", '<measuringpoint code="'.$e['sn'].'" name="'.$e['descr'].'">'."\r\n"));
			fwrite($fp, iconv("UTF-8", "WINDOWS-1251",'<measuringchannel code="01" desc="Активная +">'."\r\n"));
			$testSum = $testSum+$row['value']; //Домножить на коэффициент
			fwrite($fp, iconv("UTF-8", "WINDOWS-1251",GetXMLPeriodValue($row)));

			while ($row = mysql_fetch_array($query)) {
				$testSum = $testSum+$row['value']; //домножить на коэффициент
				fwrite($fp, iconv("UTF-8", "WINDOWS-1251",GetXMLPeriodValue($row)));				
			}
			fwrite($fp, iconv("UTF-8", "WINDOWS-1251", '</measuringchannel>'."\r\n".'</measuringpoint>'));
		}
		
		fwrite($fp, iconv("UTF-8", "WINDOWS-1251", "</area>\r\n</message>"));
		fclose($fp);
		$startDate->modify('+1 day');
		$docNumber++;
	}
	return $docHeader;
	
}

function GetXMLPeriodValue($row) {
	$s = new DateTime($row['time']);
	$s=$s->format("Hi");
	
	$e = new DateTime($row['time']);
	$e = $e->modify("+30 min");
	$e=$e->format('Hi');
	
	$t = '<period start="'.$s.'" end="'.$e.'">'."\r\n";
	$t = $t."<value>\r\n";
	$t = $t.$row['value']; //Домножить на коэффициент
	$t = $t."</value>\r\n</period>\r\n";
	return $t;
}

function GetDocHeader($docNumber, $documentTimestamp, $dayTimestamp, $INN, $companyName) {
	$docHeader = '<?xml version="1.0" encoding="windows-1251"?>'."\r\n";
	$docHeader = $docHeader.'<message class="80020" version="2" number="'.$docNumber.'">'."\r\n";
	$docHeader = $docHeader.'<datetime>'."\r\n".'<timestamp>'.$documentTimestamp.
							'</timestamp>'."\r\n".'<daylightsavingtime>1</daylightsavingtime>'."\r\n";
	$docHeader = $docHeader."<day>$dayTimestamp</day>\r\n</datetime>\r\n";	
	$docHeader = $docHeader."<sender>\r\n<inn>$INN</inn>\r\n<name>$companyName</name>\r\n</sender>\r\n";
	$docHeader = $docHeader.'<area timezone="1">'."\r\n";	
	$docHeader = $docHeader."<inn>$INN</inn>\r\n";
	$docHeader = $docHeader."<name>$companyName</name>\r\n";			
	return $docHeader;
}

function GetDocNumber($userId) {
	$query = "SELECT MAX(docNumber) AS lastDocNumber FROM electricity_reports WHERE electricity_reports.user_id=$userId";
	$query = evaluate_Query($query);
	if (mysql_num_rows($query)>0) {
		$res = mysql_fetch_array($query);
		return  $res['lastDocNumber']+1;
	}
	else { 
		return 1;
	}
}





//Проверка прав доступа к электрическому счётчику с указанным id
function CheckEidAccessLevel($eid)
	{
		$user_flat_id = isset($_SESSION['user_flat_id']) ? $_SESSION['user_flat_id'] : '';
		$user_status = $_SESSION['user_status'];
		//Определим, соответствуют ли права пользователя id запрошенного счётчика. 
		if ($eid == '') 
		{
			if ($user_flat_id == '')
			{
				//Невозможно установить требуемый счётчик для пользователя
				//Выбросим на страницу логина
				header('Location:/login');
			}
			else
			{
				$query = 'SELECT id FROM emelement WHERE emelement.stop_date IS NULL AND emelement.flat_id = '.$user_flat_id;
				$emelement_id = mysql_result(evaluate_Query($query), 0, 0);
			}
		}
		else
		{	
			$eid = SafeSQL($eid,9);
			if ($user_status > 5) 
			{
				//Если администрация портала - то без вопросов пустим
				$emelement_id = $eid;
			}
			elseif ($user_status == 5)
			{
				//Если запрос от управляющей компании, то проверим её права доступа к данном зданию
				$query = 'SELECT mgcompany_id FROM building, emelement WHERE building.id = emelement.building_id AND emelement.id='.$eid;
				$emelement_mgcompany_id = mysql_result(evaluate_Query($query), 0, 0);
				if ($emelement_mgcompany_id == $_SESSION['user_mgcompany']) 
				{
					$emelement_id = $eid;
				}
				else 
				{
					//Запрос не соответсвует правам доступа. 
					//Можно занести в журнал событий
					header('Location:/login');
				}
			}
			elseif ($user_status == 4)
			{
				//Запрос от члена ТСЖ. Проверим, относится ли запрошенный счётчик к его дому
				$query = 'SELECT emelement.building_id FROM emelement WHERE emelement.id='.$eid;
				$emelement_building_id = mysql_result(evaluate_Query($query), 0, 0);
				if ($_SESSION['user_building_id'] == $emelement_building_id) 
				{
					$emelement_id = $eid;
				}
				else
				{
					//Запрос не соответсвует правам доступа. 
					//Можно занести в журнал событий
					header('Location:/login');					
				}
			}
			elseif ($user_status == 3)
			{
				//Обычный жилец, просто возвращаем id его личного счётчика
				$query = 'SELECT emelement.id FROM emelement WHERE emelement.stop_date IS NULL AND emelement.flat_id='.$user_flat_id;
				$emelement_id = mysql_result(evaluate_Query($query), 0, 0);
			}			
		}
		return $emelement_id;
	}	
	
?>