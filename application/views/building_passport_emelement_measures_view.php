
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
		<div style="float: left">Счётчик № <?php echo $data['esnum']; echo ', период с '.$data['startDate'].' по '.$data['endDate']?></div>
		<div style="float: right"><?php echo $data['address']; ?>

		</div>
	</div>

	<div class="content height450">
		<!--Меню офиса-->
		<?php GetAsBlock("emelement_menu"); ?>	
		<!--/Меню офиса-->
		<div style="padding: 5px">
		<?php ?>
		<table width="100%" class="simple">
			<thead>
				<tr>
				  <td rowspan="2">Год</td>
				  <td rowspan="2">Месяц</td>
				  <td colspan="3">Количество электроэнергии </td>
				  <td colspan="3">Разность показаний </td>
				</tr>
				<tr>
				  <td>ТА</td>
				  <td>Т1</td>
				  <td>Т2</td>
				  <td>ТА</td>
				  <td>Т1</td>
				  <td>Т2</td>
				</tr>
			</thead>
            <?php 
			foreach ($data['measurements'] as $tableRow) {
				echo '<tr>';
					echo '<td>'.$tableRow['year'].'</td>';
					echo '<td>'.$tableRow['month'].'</td>';
					echo '<td>'.$tableRow['totalValue'].'</td>';
					echo '<td>'.$tableRow['dayValue'].'</td>';
					echo '<td>'.$tableRow['nightValue'].'</td>';
					echo '<td>'.$tableRow['deltaTotal'].'</td>';
					echo '<td>'.$tableRow['deltaDay'].'</td>';
					echo '<td>'.$tableRow['deltaNight'].'</td>';
				echo '</tr>';
			}
			?>
          </table>
		  </div>
		  <div style="padding: 5px">
 			<div class="icon_button"><a href="#openModal" title="Внести измерения"><img src="/images/icons/date24.png" /></a></div>
		  	<div class="icon_button"><a href="#openModal"><img src="/images/icons/add24.png" /></a></div>

		  </div>	           
	</div>
	
	<div id="openModal" class="modalDialog">
		<div>
			<a href="#close" title="Закрыть" class="close">X</a>
		</div>
	</div>

		



