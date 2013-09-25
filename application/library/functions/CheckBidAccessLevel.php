<?php	
//Проверка прав доступа к зданию с указанным id.
function CheckBidAccessLevel($bid)
{
	$user_building_id =  isset($_SESSION['user_building_id']) ? $_SESSION['user_building_id'] : '';
	$user_status = $_SESSION['user_status'];
	
	//Определим, соответствуют ли права пользователя id запрошенного здания. 
	if ($bid=='') 
	{
		//Если id здания не определено - присвоим id здания, где проживает пользователь
		if ($user_building_id != '') {$building_id = $user_building_id;}
		else {header('Location:/login');}
	}
	else
	{	
		$bid = SafeSQL($bid,9);
		if ($user_status > 5) {
			$building_id = $bid;
		}
		elseif ($user_status == 5) { 
			//Если запрос от управляющей компании, то проверим её права доступа к данном зданию
			$query = 'SELECT mgcompany_id FROM building WHERE building.id='.$bid;
			$building_mgcompany_id = mysql_result(evaluate_Query($query), 0, 0);
			if ($building_mgcompany_id == $_SESSION['user_mgcompany']) {
				$building_id = $bid;
			}
			else {
				//Запрос не соответсвует правам доступа. 
				//Можно занести в журнал событий
				header('Location:/login');
			}
		}
		elseif ($user_status > 2) {
			//Запрос от обычного жильца или члена ТСЖ, без проверки присваиваем его личный дом
			$building_id = $user_building_id;	
		}		
		else {
			//Запрос не соответсвует правам доступа. 
			//Можно занести в журнал событий
			header('Location:/login');
		}
	}		
return $building_id;
}
?>	