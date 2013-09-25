<?php


class Model_mail_report extends Model {	
	
	public function get_data() {
		if (!$this->check_session()) {header('Location:/login');} 
		$building_id = $this->CallCommonFunc('CheckBidAccessLevel',$_GET['bid']);
		$data['address'] = $this->CallCommonFunc("GetAddressByBid",$building_id);
		$data['bid'] = $building_id;
		$action = $_GET['action'];
	
		$startDate = new DateTime(date('o-m-1',strToTime('first day -1 month')));
		$startDate 	= 	isset($_POST['startDate']) 	? $this->SafeSQL($_POST['startDate'])	: $startDate->format('o-m-d');
		$data['startDate'] = $startDate;
		$endDate = new DateTime(date('o-m-d',strToTime('last day of -1 month')));
		$endDate 	= 	isset($_POST['endDate']) 	? $this->SafeSQL($_POST['endDate'])		: $endDate->format('o-m-d');;				
		$data['endDate'] = $endDate;
				
		$emelements = array();
		while (list($var,$value) = each($_POST)) {
			if (stristr($var,"emelement")) {$emelements[] = $value;}
		}
					
		$emelementList = $this->GetEmelementReportList($emelements);							
		if (is_string($emelementList)) {
			$data['operationStatus']['label'] = $emelementList; //GetEmelementReportList вернула ошибку, а не список элементов
			$data['operationStatus']['code'] = 0;
		}
		else {$data['emelementList'] = $emelementList;}
					
		if ($action=="preview") {
			$data['previewDocument'] = $this->GetMailReport($emelementList, $startDate, $endDate, 0);
		}	
		$test = new Emelement();	
		return $data;
	}
	
	//Формирование отчёта для отправки в "Энергокомфорт"
	private function GetMailReport($emelementList, $startDate, $endDate, $action) {
		$userId = $_SESSION['user_id'];
		//Перебор всех дней в заданом диапазоне
		$startDate = new DateTime($startDate);
		$endDate = new DateTime($endDate);
		
		//Запрос номера документа для пользователя вставить
		$docNumber = 32;
		$falseSum = 0; $trueSum = 0;
		while ($startDate <= $endDate) {
			$documentTimestamp = new DateTime(date('YmdHis'));;
			$dayTimestamp = $startDate->format('Ymd');
			//Для корректной нумерации из под админа надо добавить в базу владельца здания (или счётчика?)
			//$docNumber = GetDocNumber($userId);
			
			//Запрос на наш INN? Непонятно
			$INN = 6749387456;
			$fileName = '8020_1001263646_'.$dayTimestamp.'_'.$docNumber.'.xml';
			$fp = fopen("files/reports/".$fileName, "w");
			fwrite($fp, iconv("UTF-8", "WINDOWS-1251",$this->GetDocHeader($docNumber,$documentTimestamp->format('YmdHis'), $dayTimestamp, $_POST['INN'],$_POST['companyName'])));
			
			$remains = 0;
			foreach ($emelementList as $e) {
				$query = 'SELECT * FROM electricity_measurement WHERE electricity_measurement.emelement_id ='.$e['id'].
				' AND electricity_measurement.date = "'.$startDate->format('Y-m-d').'"';	
				$query = evaluate_Query($query);
				//Проверить, есть ли 48 измерений. Если нет - ошибка
				$row = mysql_fetch_array($query);
				$row['value'] = $row['value']*500; $trueSum = $row['value']  + $trueSum;//echo "было ".$row['value']."<br>"; 
				$row['value'] = $row['value']+$remains; //echo "с предыдущим остатком".$row['value']."<br>"; 
				$roundedValue = round($row['value']); $falseSum = $roundedValue  + $falseSum; //echo "округлили до ".$roundedValue."<br>";
				$remains = $row['value']-$roundedValue; //echo "остаток ".$remains."<br>";
				$row['value'] = $roundedValue; //echo "записываем ".$row['value']."<br>";
				if ($row['value']<0) {$row['value']=0; $remains=0;};
				
				$testSum=$testSum+$row['value'];
				
				fwrite($fp, iconv("UTF-8", "WINDOWS-1251", '<measuringpoint code="'.$e['pointCode'].'" name="'.$e['descr'].'">'."\r\n"));
				fwrite($fp, iconv("UTF-8", "WINDOWS-1251",'<measuringchannel code="01" desc="Активная +">'."\r\n"));
				
				fwrite($fp, iconv("UTF-8", "WINDOWS-1251",$this->GetXMLPeriodValue($row)));
				while ($row = mysql_fetch_array($query)) {
					$row['value'] = $row['value']*500; $trueSum = $trueSum + $row['value']; //echo "было ".$row['value']."<br>";
					$row['value'] = $row['value']+$remains; //echo "с предыдущим остатком".$row['value']."<br>"; 
					$roundedValue = round($row['value']); $falseSum = $roundedValue + $falseSum;//echo "округлили до ".$roundedValue."<br>";
					$remains = $row['value']-$roundedValue; //echo "остаток ".$remains."<br>";					
					$row['value'] = $roundedValue; //echo "записываем ".$row['value']."<br>";
					/////////////////////////////////////////////////////
					if ($row['value']<0) {$row['value']=0; $remains=0;};
					
					fwrite($fp, iconv("UTF-8", "WINDOWS-1251",$this->GetXMLPeriodValue($row)));	
					$testSum=$testSum+$row['value'];		
				}
				fwrite($fp, iconv("UTF-8", "WINDOWS-1251", '</measuringchannel>'."\r\n".'</measuringpoint>'));
				
			}
			
			fwrite($fp, iconv("UTF-8", "WINDOWS-1251", "</area>\r\n</message>"));
			fclose($fp);
			$startDate->modify('+1 day');
			$docNumber++;	
		}	
		echo "Без округления за месяц: ".$trueSum." С округлением за месяц:".$falseSum;			
		return $docHeader;

	
	}

	private function GetXMLPeriodValue($row) {
		$s = new DateTime($row['time']);
		$s=$s->format("Hi");
		
		$e = new DateTime($row['time']);
		$e = $e->modify("+30 min");
		$e=$e->format('Hi');
		
		$t = '<period start="'.$s.'" end="'.$e.'">'."\r\n";
		$t = $t."<value>\r\n";
		$t = $t.$row['value']; //Домножить на коэффициент счётчика, а не на константу
		$t = $t."</value>\r\n</period>\r\n";
		return $t;
	}
	
	private function GetDocHeader($docNumber, $documentTimestamp, $dayTimestamp, $INN, $companyName) {
		$docHeader = '<?xml version="1.0" encoding="windows-1251"?>'."\r\n";
		$docHeader = $docHeader.'<message class="80020" version="2" number="'.$docNumber.'">'."\r\n";
		$docHeader = $docHeader.'<datetime>'."\r\n".'<timestamp>'.$documentTimestamp.
								'</timestamp>'."\r\n".'<daylightsavingtime>1</daylightsavingtime>'."\r\n";
		$docHeader = $docHeader."<day>$dayTimestamp</day>\r\n</datetime>\r\n";	
		$docHeader = $docHeader."<sender>\r\n<inn>1001263646</inn>\r\n<name>$companyName</name>\r\n</sender>\r\n";
		$docHeader = $docHeader.'<area timezone="1">'."\r\n";	
		$docHeader = $docHeader."<inn>$INN</inn>\r\n";
		$docHeader = $docHeader."<name>$companyName</name>\r\n";			
		return $docHeader;
	}
	
	private function GetDocNumber($userId) {
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
	
	private function GetEmelementReportList($emelementList) {
		$list = array();
		if (count($emelementList) == 0) {
			return 'Не указаны измерительные элементы!';
		}
		else
		{
			foreach ($emelementList as $e) {
				$query = '	SELECT emelement.id AS id, emelement.sn AS sn, emelement.descr AS descr, emelement.pointCode AS pointCode
							FROM emelement
							WHERE emelement.sn = '.SafeSQL($e, 10).' AND (emelement.pointCode IS NOT NULL)';
				$res = evaluate_Query($query);
				if (!mysql_num_rows($res)) { return 'Не найдены зарегистрированные в АИСС измерительные элементы';}
	
				while ($row = mysql_fetch_array($res)) {
					$id			= 	$row['id'];
					$sn 		= 	$row['sn'];
					$descr 		= 	$row['descr'];
					$pointCode	= 	$row['pointCode'];			
					$query = "	SELECT electricity_reports.reportDate AS reportDate, electricity_reports.docNumber AS docNumber
								FROM electricity_reports
								WHERE electricity_reports.emelement_id= ".$row['id']."
								ORDER BY reportDate DESC
								LIMIT 0,1";				
					$list[] = array('id' => $id, 'sn' => $sn, 'descr' => $descr, 'reportDate' => $reportDate, 'pointCode' => $pointCode);		
				}
			}
			return $list;
		}
	}	
		
	


	

	
}
