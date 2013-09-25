<?php
//Возвращает строку с адресом здания с id=$building_id
function GetAddressByBid($building_id) {
	$query = '	SELECT street.name, building.no 
				FROM building, street 
				WHERE building.id='.$building_id.' AND building.street_id=street.id';
	$tmp = evaluate_Query($query);
	$row = mysql_fetch_array($tmp);
	$address = 'ул. '.$row['name'].', д. '.$row['no'];
	return $address;								
}
?>