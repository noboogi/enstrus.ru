<?php
require_once('/application/libs/db.php');

function GetMailReport($eid, $startDate, $endDate, $action) {
	$docHeader = '<?xml version="1.0" encoding="windows-1251"?>'."\r\n";
	$docHeader = $docHeader.'<message class="80020" version="2" number="'.$docNumber.'">';
	return $docHeader;
}
	
	
?>