<div class="center">
				<?php 
				/*Сообщим об успехе операции загрузкии файла*/
				if ($data['uploadStatus']['label'] != "")
				{
					if ($data['uploadStatus']['code'] == 0) 
					{
						echo '<div id="message" class="message error">';	
					}
					else
					{
						echo '<div id="message" class="message success">';
					} 
					echo $data['uploadStatus']['label']."<br> Всего записей загружено ".$data['uploadStatus']['parsingResult']['count'];
					echo " за период: c ".$data['uploadStatus']['parsingResult']['startDate']." по ".$data['uploadStatus']['parsingResult']['endDate'];
					if ($data['uploadStatus']['parsingResult']['ignoredCount']>0) 
					{
						echo "<br> Из них ".$data['uploadStatus']['parsingResult']['ignoredCount']." не записано, так как измерения на эту дату уже содержатся в БД";
					}
					echo '</div>';
				}
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
		<div class="icon_button" 
		onclick="document.emeasurementsElements.action='/emeasurements/graph'; document.emeasurementsElements.submit()" style="cursor:pointer;">
			<img src="../images/icons/graph32-2.png" width="26" height="25" title="Статистика по выбраным: график" />		
		</div>
		<div class="icon_button" 
		onclick="document.emeasurementsElements.action='/emeasurements/table'; document.emeasurementsElements.submit()" style="cursor:pointer;">
			<img src="../images/icons/sum.png" width="26" height="25" title="Статистика по выбраным: таблица" />		
		</div>
		<div class="icon_button"><img src="../images/icons/plus24.png" title="Создать новый"/></div>	
	</div>
	

  	<div class="content">				
		<!--Сортируемая таблица-->
		<table class="information sortable">
			<!--Шапка таблицы-->
			<thead>
				<tr>
					<th>
						<!--Для чекбоксов-->
					</th>
					<th>
						Серийный номер
					</th>
					<th>
						Тип
					</th>
					<th>
						Комментарий
					</th>
					<th>
						КТТ
					</th>
					<th>
						КТН
					</th>
					<th>
						Улица
					</th>	
					<th>
						Подъезд
					</th>		
					<th>
						Квартира
					</th>
					<th>
						<!--Колонка с иконками-->
					</th>													
				</tr>
			</thead>
			<!--Тело таблицы-->
			<tbody>	
			<form id="emeasurementsElements" name="emeasurementsElements" method="post" action="/emeasurements">	
			<?php	
				$i = 0; //Счетчик чекбоксов					
				$emelements = $data['data'];
			
				while($row = mysql_fetch_array($emelements))
				{
					$i++;
					echo '<tr>';
						echo '<td><input type="checkbox" name="emelement'.$i.'" value="'.$row['sn'].'" /></td>';
						echo '<td><a href="/emeasurements?sn='.$row['sn'].'">'.$row['sn'].'</a></td>';
						echo '<td>'.$row['label'].'</td>';
						echo '<td>'.$row['descr'].'</td>';
						echo '<td>'.$row['cRatio'].'</td>';
						echo '<td>'.$row['vRatio'].'</td>';
						echo '<td>'.$row['streetName'].'</td>';
						echo '<td>'.$row['porchNo'].'</td>';
						echo '<td>'.$row['flatNo'].'</td>';					
						echo '	<td>
									<div class="icon_button"><img title="Удалить" src="../images/icons/delete16.png" id="'.$row['id'].'"/></div>
									<div class="icon_button">
										<a href="#openModal" title="Загрузка файлов с измерениями" 
										onClick="document.getElementById(\'emelementId\').value = '.$row['id'].';
										document.getElementById(\'snLabel\').innerHTML = '.$row['sn'].'">
											<img style="border: 0" src="../images/icons/upload16.png"/>
										</a>
									</div>	
									<div class="icon_button">
										<a href="/emeasurements/graph?sn='.$row['sn'].'">
										<img title="Вывести график" src="../images/icons/graph16.png" />
										</a>
									</div>							
								</td>';
					echo '</tr>';
				}
			?>
			</form>
			</tbody>              
		</table>
		<!----------------------->		


	
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






