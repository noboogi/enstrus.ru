
<div class="box left narrow">
	<div class="title">
		Личный кабинет
	</div>
	<div class="content">
		<!--Меню офиса-->
		<?php GetAsBlock("building_menu"); ?>
	</div>
</div>

<div class="box right wide">
	<div class="title">
		<div style="float: left">Приборы учёта</div>
		<div style="float: right"><?php echo $data['address']; ?>
			<div class="icon_button" 
			onclick="document.emeasurementsElements.action='/emeasurements/graph'; document.emeasurementsElements.submit()" style="cursor:pointer;">
				<img src="../images/icons/graph32-2.png" width="26" height="25" title="Статистика по выбраным: таблица" />		
			</div>
			<div class="icon_button" 
			onclick="document.emeasurementsElements.action='/emeasurements/table'; document.emeasurementsElements.submit()" style="cursor:pointer; margin-left: 15px;">
				<img src="../images/icons/sum.png" width="26" height="25" title="Статистика по выбраным: таблица" />		
			</div>
		</div>
	</div>

	<div class="content height450">
	<form id="emeasurementsElements" name="emeasurementsElements" method="post" action="/emeasurements">
		<?php 
			//Если работает администратор - надо явно указывать id здания во всех ссылках
			$bid = ($_SESSION['user_status']>4) ? '&bid='.$data['bid'] : '';
			
			$emelementsList = $data['emelementsList'];
			$i=0;
			//Вывод счётчиков, привязанных к зданию
			if ($emelementsList['buildingEmelements']!= NULL && mysql_num_rows($emelementsList['buildingEmelements']))
			{
				echo '	<div class="article">';
				echo '	<div class="text-header">Уровень 1: Распределительное устройство</div>
							<table class="simple">
								<thead>
									<th>&nbsp;</th>
									<th>Серийный номер</th>
									<th>Тип</th>
									<th>Коэффициент <br />трансформации</th>
									<th>Комментарий</th>
								</thead>';
				
				$melements = $emelementsList['buildingEmelements'];
				while ($row = mysql_fetch_array($melements, MYSQL_ASSOC)) 
				{
					echo "<tr>";
					$i++;
					echo 	'<td><input type="checkbox" name="emelement'.$i.'" value="'.$row['sn'].'" /></td>'; 
					echo 	'<td><a href=/building_passport/emeasures?eid='.$row['eid'].$bid.'>'.$row['sn'].'</td>';
					//echo 	'<td><a href=/emeasurements/graph?sn='.$row['sn'].'>'.$row['sn'].'</td>';
					echo 	"<td>".$row['label']."</td>";
					echo 	"<td>".$row['cRatio']."</td>";	
					echo 	"<td>".$row['descr']."</td>";								
					echo "</tr>";
				}			
							
				echo '		</table>
						</div>';
			};
			
			//Вывод счётчиков, привязанных к подъезду
			if ($emelementsList['porchEmelements'] != NULL && mysql_num_rows($emelementsList['porchEmelements']))
			{
				echo '	<div class="article">';
				echo '	<div class="text-header">Уровень 2: Подъезд</div>
							<table class="simple">
								<thead>
									<th>&nbsp;</th>
									<th>Серийный номер</th>
									<th>Тип</th>
									<th>Коэффициент <br />трансформации</th>
									<th>Комментарий</th>
								</thead>';
				
				$melements = $emelementsList['porchEmelements'];
				while ($row = mysql_fetch_array($melements, MYSQL_ASSOC)) 
				{
					echo "<tr>";
					$i++;
					echo 	'<td><input type="checkbox" name="emelement'.$i.'" value="'.$row['sn'].'" /></td>';
					echo 	'<td><a href=/building_passport/emeasures?eid='.$row['eid'].$bid.'>'.$row['sn'].'</td>';
					//echo 	'<td><a href=/emeasurements/graph?sn='.$row['sn'].'>'.$row['sn'].'</td>';
					echo 	"<td>".$row['label']."</td>";
					echo 	"<td>".$row['cRatio']."</td>";	
					echo 	"<td>".$row['descr']."</td>";								
					echo "</tr>";
				}			
							
				echo '		</table>
						</div>';
			};
			
			//Вывод счётчиков, привязанных к квартире (потребителю)
			if ($emelementsList['flatEmelements'] != NULL && mysql_num_rows($emelementsList['flatEmelements']))
			{
				echo '	<div class="article">';
				echo '	<div class="text-header">Уровень 3: Квартира</div>
							<table class="simple">
								<thead>
									<th>&nbsp;</th>
									<th>Серийный номер</th>
									<th>Тип</th>
									<th>Коэффициент <br />трансформации</th>
									<th>Комментарий</th>
								</thead>';
				
				$melements = $emelementsList['flatEmelements'];
				while ($row = mysql_fetch_array($melements, MYSQL_ASSOC)) 
				{
					echo "<tr>";
					
					$i++;
					echo 	'<td><input type="checkbox" name="emelement'.$i.'" value="'.$row['sn'].'" /></td>';
					echo 	'<td><a href=/building_passport/emeasures?eid='.$row['eid'].$bid.'>'.$row['sn'].'</td>';
					//echo 	'<td><a href=/emeasurements/graph?sn='.$row['sn'].'>'.$row['sn'].'</td>';
					echo 	"<td>".$row['label']."</td>";
					echo 	"<td>".$row['cRatio']."</td>";	
					echo 	"<td>".$row['descr']."</td>";								
					echo "</tr>";
				}			
							
				echo '		</table>
						</div>';
			};						
		?>
		</form>

		


