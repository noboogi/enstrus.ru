<?php	
//�������� ���� ������� � ������ � ��������� id.
function CheckBidAccessLevel($bid)
{
	$user_building_id =  isset($_SESSION['user_building_id']) ? $_SESSION['user_building_id'] : '';
	$user_status = $_SESSION['user_status'];
	
	//���������, ������������� �� ����� ������������ id ������������ ������. 
	if ($bid=='') 
	{
		//���� id ������ �� ���������� - �������� id ������, ��� ��������� ������������
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
			//���� ������ �� ����������� ��������, �� �������� � ����� ������� � ������ ������
			$query = 'SELECT mgcompany_id FROM building WHERE building.id='.$bid;
			$building_mgcompany_id = mysql_result(evaluate_Query($query), 0, 0);
			if ($building_mgcompany_id == $_SESSION['user_mgcompany']) {
				$building_id = $bid;
			}
			else {
				//������ �� ������������ ������ �������. 
				//����� ������� � ������ �������
				header('Location:/login');
			}
		}
		elseif ($user_status > 2) {
			//������ �� �������� ������ ��� ����� ���, ��� �������� ����������� ��� ������ ���
			$building_id = $user_building_id;	
		}		
		else {
			//������ �� ������������ ������ �������. 
			//����� ������� � ������ �������
			header('Location:/login');
		}
	}		
return $building_id;
}
?>	