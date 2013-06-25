
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
		<div style="float: left">Счётчик №</div>
		<div style="float: right"><?php echo $data['address']; ?>

		</div>
	</div>

	<div class="content height450">
		
		<div class="tabHeader gradColor activeTab" onclick="javascript:activateTab('page1')">Измерения</div>
		<div class="tabHeader gradColor activeTab" onclick="javascript:activateTab('page2')">График</div>
		<div class="tabHeader gradColor activeTab" onclick="javascript:activateTab('page3')">Характеристики</div>
		<div class="tabHeader lastHeader gradColor">&nbsp;</div>
		<div id="tabBody">
			<div id="page1" style="display: block;">
				<div style="padding: 10px;">
				 <table width="100%" class="simple">
				  <thead>
					<tr>
					  <td rowspan="2">Дата</td>
					  <td rowspan="2">Время</td>
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
					<tr>
					  <td>25.01.2013 </td>
					  <td>12.00</td>
					  <td>29771</td>
					  <td>13000</td>
					  <td>16774</td>
					  <td>5000</td>
					  <td>2000</td>
					  <td>3000</td>
					</tr>
					<tr>
					  <td>25.02.2013 </td>
					  <td>15.00</td>
					  <td>31056</td>
					  <td>14788</td>
					  <td>16999</td>
					  <td>4600</td>
					  <td>1300</td>
					  <td>3300</td>
					</tr>
					<tr>
					  <td>25.03.2013 </td>
					  <td>15.00</td>
					  <td>29771</td>
					  <td>13000</td>
					  <td>16774</td>
					  <td>5000</td>
					  <td>2000</td>
					  <td>3000</td>
					</tr>
					<tr>
					  <td>25.04.2013 </td>
					  <td>15.00</td>
					  <td>31056</td>
					  <td>14788</td>
					  <td>16999</td>
					  <td>4600</td>
					  <td>1300</td>
					  <td>3300</td>
					</tr>
					<tr>
					  <td>25.05.2013</td>
					  <td>15.00</td>
					  <td>29771</td>
					  <td>13000</td>
					  <td>16774</td>
					  <td>5000</td>
					  <td>2000</td>
					  <td>3000</td>
					</tr>
					<tr>
					  <td>25.06.2013</td>
					  <td>15.00</td>
					  <td>31056</td>
					  <td>14788</td>
					  <td>16999</td>
					  <td>4600</td>
					  <td>1300</td>
					  <td>3300</td>
					</tr>
					<tr>
					  <td>25.07.2013</td>
					  <td>15.00</td>
					  <td>29771</td>
					  <td>13000</td>
					  <td>16774</td>
					  <td>5000</td>
					  <td>2000</td>
					  <td>3000</td>
					</tr>
					<tr>
					  <td>25.08.2013</td>
					  <td>15.00</td>
					  <td>31056</td>
					  <td>14788</td>
					  <td>16999</td>
					  <td>4600</td>
					  <td>1300</td>
					  <td>3300</td>
					</tr>
					<tr>
					  <td>25.09.2013</td>
					  <td>15.00</td>
					  <td>29771</td>
					  <td>13000</td>
					  <td>16774</td>
					  <td>5000</td>
					  <td>2000</td>
					  <td>3000</td>
					</tr>
					<tr>
					  <td>25.10.2013</td>
					  <td>15.00</td>
					  <td>31056</td>
					  <td>14788</td>
					  <td>16999</td>
					  <td>4600</td>
					  <td>1300</td>
					  <td>3300</td>
					</tr>
					<tr>
					  <td>25.11.2013</td>
					  <td>15.00</td>
					  <td>29771</td>
					  <td>13000</td>
					  <td>16774</td>
					  <td>5000</td>
					  <td>2000</td>
					  <td>3000</td>
					</tr>
					<tr>
					  <td>25.12.2013</td>
					  <td>15.00</td>
					  <td>31056</td>
					  <td>14788</td>
					  <td>16999</td>
					  <td>4600</td>
					  <td>1300</td>
					  <td>3300</td>
					</tr>
				  </table>
				</div>
			</div>
			<div id="page2" style="display: none;">График с измерениями (для получасовок)</div>
			<div id="page3" style="display: none;">
				<form id="characteristics" name="characteristics" method="post" action="">
				<table width="100%" style="padding-right: 10px;">
				
					<tr>
						<td width="150">Серийный номер</td>
						<td>
						<input name="cRatio" type="text" value="1" class="width100">
						</td>
					</tr>
								
					<tr>
						<td width="150">Тип</td>
						<td>
						<select name="type" class="width100">
							<option>Меркурий 230 ART 03 PQCSIDN</option>
							<option>Другой</option>
						</select>
						</td>
					</tr>
					
					<tr>
						<td width="150">Комментарий</td>
						<td>	
						<input name="descr" type="text" value="Квартира №2" class="width100">
						</td>
					</tr>
					
					<tr>
						<td width="150">Ввод в эксплуатацию</td>
						<td>
						<input name="start_date" type="text" value="23-04-2013" class="tcal width100" />
						</td>
					</tr>
					
					<tr>
						<td width="150">Вывод из эксплуатации </td>
						<td>
						<input name="stop_date" type="text" value="-" class="tcal width100"/>
						</td>
					</tr>
					
					<tr>
						<td width="150">Начальное значение </td>
						<td>
						<input name="initial_value" type="text" value="0" class="width100">
						</td>
					</tr>
					
					<tr>
						<td width="150">КТТ</td>
						<td>
						<input name="cRatio" type="text" value="1" class="width100">
						</td>
					</tr>
					
					<tr>
						<td width="150">КТН</td>
						<td>
						<input name="vRatio" type="text" value="1"  class="width100">
						</td>
					</tr>			
	
			 	 </table>
				</form>

			</div>
		</div>	
		           
	</div>

		



