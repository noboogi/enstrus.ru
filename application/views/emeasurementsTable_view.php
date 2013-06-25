<div class="box left narrow">
	<div class="title">
		Фильтр	
	</div>
  	<div class="content">
			<form id="emeasurementsFilter" name="emeasurementsFilter" method="post" action="">
			<input type="hidden" name="emelementsId" />
			  <table width="100%" border="0" cellpadding="3">
                <tr>
                  <td colspan="3"><b>Дата (год-месяц-день)</b></td>              
                </tr>
                <tr>
                  <td><div align="right">от: </div></td>
                  <td colspan="2"><input type="text" name="startDate" class="width95" value="<?php echo $data['startDate']?>"/></td>
                </tr>
                <tr>
                  <td><div align="right">до: </div></td>
                  <td colspan="2"><input name="endDate" type="text" class="width95" value="<?php echo $data['endDate']?>"/></td>
                </tr>
                <tr>
                  <td colspan="3"><b>Время (час:минуты)</b></td>
                </tr>
                <tr>
                  <td><div align="right">от: </div></td>
                  <td colspan="2"><input type="text" name="startTime" class="width95" value="<?php echo $data['startTime']?>"/></td>
                </tr>
                <tr>
                  <td><div align="right">до: </div></td>
                  <td colspan="2"><input type="text" name="endTime" class="width95" value="<?php echo $data['endTime']?>"/></td>
                </tr>
                <tr>
                  <td>
				  	  <br />
					  <div align="right">
						<input type="checkbox" <?php echo $data["onlyMax"]?> name="onlyMax" value="true" />
					  </div>				 
				  </td>
                  <td><br />только максимумы </td>
                  <td>
					  <div align="right">
					  	<br />
						<input name="submit" type="submit" value="Отбор" />
					  </div>
				  </td>
                </tr>

              </table>
	  </form>
  </div>

</div>

<div class="box right wide">
	<div class="title">
		Показания счётчиков электрической энергии	
	</div>

	<div class="content">
	<!--Сортируемая таблица-->
    <table class="information sortable">
		<!--Шапка таблицы-->
		<thead>
			<tr>
				<th>
					Дата
				</th>
				<th>
					Время
				</th>
				<th>
					A+
				</th>				
			</tr>
		</thead>
		<!--Тело таблицы-->
		<tbody>		
		<?php						
			$emeasurements = $data['emeasurement'];	
			while($row = mysql_fetch_array($emeasurements))
			{
				echo '<tr>';
					echo '<td>'.$row['date'].'</td>';
					echo '<td>'.$row['time'].'</td>';
					echo '<td>'.$row['value'].'</td>';
				echo '</tr>';
			}
		?>
        </tbody>                
	</table>
    <!----------------------->		

	</div>
</div>





