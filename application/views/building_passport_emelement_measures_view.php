<?php 
				/*Сообщим об успехе операции*/
				if ($data['operationStatus']['label'] != "")
				{
					if ($data['uploadStatus']['code'] == 0) 
					{
						echo '<div id="message" class="message error">	
						<a href="#" title="Закрыть" class="closeMessage error" onclick="document.getElementById(\'message\').style.display = \'none\'">X</a>';
					}
					else
					{
						echo '<div id="message" class="message success">
						<a href="#" title="Закрыть" class="closeMessage success">X</a>';						
					} 

					echo "sd";
					echo '</div>';
				}
			?>		
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
			<a href="#openModalAdd" title="Добавить"><img src="../images/icons/24/add.png" border=0/></a>
			<a href="#openModalFilter" title="Отбор по дате"><img src="../images/icons/24/calendar.png"  border=0/></a>		
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
		<a href="#" title="Закрыть" class="close">X</a>
		<form id="addValue" name="addValue" method="post" 
		action="/building_passport/emeasures?bid=<?php echo $data['bid'].'&eid='.$data['eid'];?>">
		<table border="0">
		  <tr>
		  	<td colspan="4"><br />Введите текущие показания счётчика: </td>
		  </tr>
		  <tr>
			<td width="70"><div align="right">Дата</div></td>
			<td width="170"><input name="newDate" type="text" value="<?php echo $data['currentDate']?>" class="tcal width100"/></td>
			<td width="70"><div align="right">Значение</div></td>
			<td width="150"><input name="newValue" type="text" value="<?php echo "0"?>" class="width100"></td>
		  </tr>
		  <tr>
			<td width="70"><div align="right">День</div></td>
			<td width="150"><input name="newDayValue" type="text" value="<?php echo "0"?>" class="width100"></td>
			<td width="70"><div align="right">Ночь</div></td>
			<td width="150"><input name="newNightValue" type="text" value="<?php echo "0"?>" class="width100"></td>
		  </tr>
		  <tr>
			<td colspan="4"><div align="center" style="margin-top: 15px;">
				<input type="submit" class="button big primary" value="Сохранить"></div></td>
		  </tr>
		</table>
		</form>
		</div>
	</div>
	
		<div name="openModalFilter" id="openModalFilter" class="modalDialog">
		<div style="width: 150px; margin-left:auto; margin-right:auto;">
			<a href="#" title="Закрыть" class="close">X</a>
			<form id="dateFilter" name="dateFilter" method="post" 
			action="/building_passport/emeasures?bid=<?php echo $data['bid'].'&eid='.$data['eid'];?>">
			<table border="0">
			  <tr>
				<td colspan="4"><div align="left">Период отбора:</div></td>
			  </tr>
			  <tr>
				<td colspan="2"><div align="right"> с:</div></td>
				<td colspan="2"><input name="startDate" type="text" value="<?php echo $data['startDate']?>" class="tcal width100"/></td>
			  </tr>
			  <tr>
				<td colspan="2"><div align="right"> по:</div></td>
				<td colspan="2"><input name="endDate" type="text" value="<?php echo $data['endDate']?>" class="tcal width100"/></td>
			  </tr>
			  <tr>
			  	<td colspan="4">
					<div align="center" style="margin-top: 15px;">
						<input type="submit" class="button big primary" value="Отбор">	
					</div>
				</td>
			  </tr>
			</table>
			</form>
			
		</div>
	</div>

		




