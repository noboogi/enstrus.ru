
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
	<div class='horizontal_button_menu_background'>
		<?php GetAsBlock("emelement_menu"); ?>	
		<div style="padding: 5px; float: right;">
			<a href="#openModalAdd" title="Добавить"><img src="../images/icons/addBlue.png" /></a>
			<a href="#openModalFilter" title="Отбор по дате"><img src="../images/icons/calendarBlue.png" /></a>		
		</div>				
	</div>

		<div style="padding: 5px">
		<table width="100%" class="simple">
			<thead>
				<tr>
				  <td rowspan="2">Год</td>
				  <td rowspan="2">Месяц</td>
				  <td colspan="3">Количество электроэнергии </td>
				  <td colspan="3">Разность показаний 
				  </td>
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
	</div>
	
	<div id="openModalAdd" class="modalDialog">
		<div style="width: 300px; margin-left:auto; margin-right:auto;">
		<a href="#close" title="Закрыть" class="close">X</a>
		<form id="addValue" name="addValue" method="post" action="">
		<table border="0">
		  <tr>
			<td width="70"><div align="right">Дата</div></td>
			<td width="120"><input name="date" type="text" value="<?php echo "50"?>" class="tcal width100"/></td>
			<td width="70"><div align="right">Значение</div></td>
			<td width="120"><input name="value" type="text" value="<?php echo "100"?>" class="width100"></td>
		  </tr>
		  <tr>
			<td width="70"><div align="right">День</div></td>
			<td width="120"><input name="value" type="text" value="<?php echo "100"?>" class="width100"></td>
			<td width="70"><div align="right">Ночь</div></td>
			<td width="120"><input name="value" type="text" value="<?php echo "100"?>" class="width100"></td>
		  </tr>
		  <tr>
			<td colspan="4"><div align="center" style="margin-top: 15px;">
				<a href="#" class="button big primary">Сохранить</a></div></td>
			</tr>
		</table>
		</form>
		</div>
	</div>
	
		<div id="openModalFilter" class="modalDialog">
		<div style="width: 150px; margin-left:auto; margin-right:auto;">
			<a href="#close" title="Закрыть" class="close">X</a>
			<form id="dateFilter" name="dateFilter" method="post" action="">
			<table border="0">
			  <tr>
				<td colspan="4"><div align="left">Период отбора:</div></td>
			  </tr>
			  <tr>
				<td colspan="2"><div align="right"> с:</div></td>
				<td colspan="2"><input name="startDate" type="text" value="<?php echo "01-10-2013"?>" class="tcal width100"/></td>
				</tr>
			  <tr>
				<td colspan="2"><div align="right"> по:</div></td>
				<td colspan="2"><input name="stopDate" type="text" value="<?php echo "31-10-2013"?>" class="tcal width100"/></td>
			  </tr>
			  <tr>
			  	<td colspan="4">
					<div align="center" style="margin-top: 15px;"><a href="#" class="button big primary">Выбрать</a></div>
				</td>
			  </tr>
			</table>
			</form>
		</div>
	</div>

		



