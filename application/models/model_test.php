<?php

class Model_test extends Model
{

	public function get_data($type="all")
	{
		$link = mysql_connect("127.0.0.1","teiriko_synergy","159753");
		mysql_query('SET NAMES utf8');
		mysql_select_db("teiriko_synergy", $link);
		if (!$link) 
			{
				echo "Хуй";
			}

		if (isset($_REQUEST['add'])) {
		$startDate = new DateTime($_POST['startDate']." 00:00"); 
		$endDate = new DateTime($_POST['endDate']." 00:00"); 
		$eid = $_POST['eid'];		
			while ($startDate <= $endDate) {
				$d = $startDate->format('Y-m-d'); 
				$t = $startDate->format('H:i');
				$query='INSERT INTO `electricity_measurement` (`id`, `hash`, `emelement_id`, `date`, `time`, `value`) VALUES (NULL, MD5(CONCAT("'.$eid.'","'.$d.'","'.$t.'")), '."$eid,".'"'.$d.'"'.",".'"'.$t.'"'.",123".')';
				$res = mysql_query($query,$link);
				$startDate->modify('+30 minutes'); 
			}
		}
		
//		
//		$s = $this->microtime_float();
//		$sstartDate = $_POST['sstartDate']; 
//		$sendDate = $_POST['sendDate']; 
//		$eid = $_POST['seid'];
//		if (isset($_REQUEST['search'])) {
//			$query='SELECT * FROM electricity_measurement WHERE `emelement_id`='.$eid.' AND `date` BETWEEN "'.$sstartDate.'" AND "'.$sendDate.'"';
//			$res = mysql_query($query,$link);
//		}
//		$data = ($this->microtime_float() - $s);
		
		
		mysql_close($link);	
		return $data;
		
	}
	
	function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

}
