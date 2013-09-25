<script type="text/javascript" language="JavaScript">
function OpenUploadWindow(eid,sn) {
	var windowProperty='width=350, height=350, toolbar=0, fullscreen=0, location=0, directories=0, status=0, menubar=0, scrollbars=0, resizable=0';
    var windowName='Зарузка файлов с измерениями';
	var windowPath='/upload_measurements_file?eid='+eid+'&sn='+sn;
    window.open(windowPath, windowName, windowProperty);
};
</script>

<div class="center">
<?php 
				/*Сообщим об успехе операции загрузкии файла*/
//				if ($data['uploadStatus']['label'] != "")
//				{
//					if ($data['uploadStatus']['code'] == 0) 
//					{
//						echo '<div id="message" class="message error">';	
//					}
//					else
//					{
//						echo '<div id="message" class="message success">';
//					} 
//					echo $data['uploadStatus']['label']."<br> Всего записей загружено ".$data['uploadStatus']['parsingResult']['count'];
//					echo " за период: c ".$data['uploadStatus']['parsingResult']['startDate']." по ".$data['uploadStatus']['parsingResult']['endDate'];
//					if ($data['uploadStatus']['parsingResult']['ignoredCount']>0) 
//					{
//						echo "<br> Из них ".$data['uploadStatus']['parsingResult']['ignoredCount']." не записано, так как измерения на эту дату уже содержатся в БД";
//					}
//					echo '</div>';
//				}
?>
</div>

<div class="box left narrow">
	<div class="title">
		Отбор
	</div>
	<div class="content">
		Форма фильтра будет позже
	</div>
</div>

<div class="box right wide">
	<div class="title">
		Список
		<div class="icon_button bordered" 
		onclick="document.emeasurementsElements.action='/emeasurements/graph'; document.emeasurementsElements.submit()" style="cursor:pointer;">
			<img src="../images/icons/24/bar_chart.png" title="Статистика по выбраным: график" width="20" height="20" />		
		</div>
		<div class="icon_button bordered" 
		onclick="document.emeasurementsElements.action='/emeasurements/table'; document.emeasurementsElements.submit()" style="cursor:pointer;">
			<img src="../images/icons/24/sum.png" title="Статистика по выбраным: таблица" width="20" height="20" />		
		</div>
		<div class="icon_button bordered"><img src="../images/icons/24/add.png" title="Создать новый" width="20" height="20" /></div>	
	</div>
	
  	<div class="content">				
		<table class="information sortable">
			<thead>
				<tr>
					<th><!--checkboxes--></th>
					<th>Серийный номер</th>
					<th>Тип</th>
					<th>Комментарий</th>
					<th>КТТ</th>
					<th>КТН</th>
					<th>Улица</th>	
					<th>Подъезд</th>		
					<th>Квартира</th>
					<th><!--icons--></th>													
				</tr>
			</thead>
			<tbody>	
			<form id="emeasurementsElements" name="emeasurementsElements" method="post" action="/emeasurements">
			<?php foreach ($data['emelementsList'] as $row):?>
				<tr>
				<td><input type="checkbox" name="emelement<?php echo $i?>" value="<?php echo $row['sn']?>" /></td>
				<td><a href="/emeasurements?sn=<?php echo $row['sn']?>"><?php echo $row['sn']?></a></td>
				<td><?php echo $row['label']?></td>
				<td><?php echo $row['descr']?></td>
				<td><?php echo $row['cRatio']?></td>
				<td><?php echo $row['vRatio']?></td>
				<td><?php echo $row['streetName']?></td>
				<td><?php echo $row['porchNo']?></td>
				<td><?php echo $row['flatNo']?></td>				
				<td>
				<div class="icon_button"><img title="Удалить" src="../images/icons/delete16.png"  border=0 id="<?php echo $row['id']?>"/></div>
				<div class="icon_button">
					<img  border=0 style="border: 0" src="../images/icons/upload16.png" onClick="OpenUploadWindow(<?php echo $row['id'].",".$row['sn']?>);">
				</div>	
				<div class="icon_button">
					<a href="/emeasurements/graph?sn=<?php echo $row['sn']?>">
					<img  border=0 title="Вывести график" src="../images/icons/graph16.png" />
					</a>
				</div>							
				</td>
				</tr>		
			<?php endforeach;?>			
			</form>
			</tbody>              
		</table>
			
	<!--Модальное окно-->
	<div id="openModal" class="modalDialog">
	    <div>
	        <a href="#close" title="Закрыть" class="close">X</a>
			<form action="/emelements/upload" method="post" enctype="multipart/form-data" name="upload" id="upload">
			<input type="hidden" name="emelementId" id="emelementId" />
			<table width="100%" border="0">
              <tr>
                <td colspan="2">
					<b>
						<div style="float:left">Загрузка данных: </div>
						<div id="snLabel" style="float:right; text-align:right">№0000000000</div>
					</b>				
				</td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td>Тип файла: </td>
                <td>
					<div align="left">
					  <select name="fileType" style="width: 220px;">
							<option value="HTML">HTML</option>
							<option value="TXT">TXT</option>
							<option value="XML">XML</option>
					  </select>
					</div>
				</td>
              </tr>
              <tr>
                <td>Выберите файл:</td>
                <td>
					<div align="left">
						<input type="file" name="filename" />
					</div>				
				</td>
              </tr>
              <tr>
			  		<td></td>
					<td>	
						<div align="left">
							<input type="checkbox" name="rewrite" value="checkbox" />
							Перезаписывать существующие					
						</div>
					</td>
			  </tr>
				<tr>
					<td colspan="2">
						<br />
						<div align="center">
							<input type="submit" value="Загрузить">
						</div>				
					</td>
            	</tr>
            </table>
          </form>	  
	    </div>
	</div>

	
	</div>
</div>






