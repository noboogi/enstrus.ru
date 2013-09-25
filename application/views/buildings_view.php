<div class="box left narrow">
	<div class="title">
		Отбор
	</div>

	<div class="content">
		<ol class="tree">
		<?php
			$disclosed = $data['disclosed'];
			$prev_area='';
			
			foreach ($data['streetTreeMenu'] as $row)
			{
				if ($prev_area!=$row['areaName']) 
				{
					if ($prev_area!='') {echo '</ol></li>';}; //Закрываем предыдущий пункт и подпункт, если это не первый
					echo '	<li>
							<label for="'.$row['areaName'].'">
							<a href="/buildings?areaId='.$row['areaId'].'">'.$row['areaName'].'</a></label> 
							<input type="checkbox"';
							if ($data['disclosed'] == $row['areaId']) {echo "checked ";};
					echo '	/>';
					echo '		<ol>'; //Открываем подпункт
					echo '			<li class="sub"><a href="/buildings?areaId='.$row['areaId'].'&streetId='.$row['streetId'].'">'.$row['streetName'].'</a></li>';
				}
				else //То есть если $prev_area==$row['areaName']
				{
					echo '			<li class="sub"><a href="/buildings?areaId='.$row['areaId'].'&streetId='.$row['streetId'].'">'.$row['streetName'].'</a></li>';	
				}
				$prev_area=$row['areaName'];
			}; 
		?>
		</ol>            
	</div>
</div>

<div class="box right wide">
	<div class="title">
		Список	
	</div>

	<div class="content">
    <table class="information sortable">
		<thead>
			<tr>
				<th>
					Улица
				</th>
				<th>
					Номер дома
				</th>
				<th>
					Дата постройки
				</th>
				<th>
					Общая площадь
				</th>
				<th>
					<!--Столбец для иконок-->
				</th>				
			</tr>
		</thead>
		<tbody>		
		<?php						
			foreach ($data['buildingsList'] as $row)
			{
				echo '<tr>';
					echo '<td>'.$row['streetName'].'</td>';
					echo '<td>'.$row['no'].'</td>';
					echo '<td>'.$row['buildingDate'].'</td>';
					echo '<td>'.$row['totalArea'].'</td>';
					echo '	<td>
								<div class="icon_button"><img src="images/icons/map16.png"  border=0 /></div>
								<div class="icon_button"><a href="/building_passport?bid='.$row['buildingId'].'"><img src="images/icons/home16.png"  border=0 /></a></div>								
							</td>';
				echo '</tr>';
			}
		?>
        </tbody>                
	</table>
	</div>
</div>





