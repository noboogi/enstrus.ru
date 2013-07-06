
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
		<div style="float: left">Счётчик № <?php echo $data['esnum']; ?></div>
		<div style="float: right"><?php echo $data['address']; ?>

		</div>
	</div>

	<div class="content height450">
		<div class='horizontal_button_menu_background'>
			<?php GetAsBlock("emelement_menu"); ?>	
		</div>

		<?php
			$eChars = mysql_fetch_array($data['eChars']);
		?>
		<form id="characteristics" name="characteristics" method="post" action="">
				<table width="100%" style="padding-right: 10px;">
					<tr>
						<td width="150">Серийный номер</td>
						<td>
						<input name="sn" type="text" value="<?php echo $eChars['sn']; ?>" class="width100">
						</td>
					</tr>
								
					<tr>
						<td width="150">Тип</td>
						<td>
						<select name="type" class="width100">
							<?php
								while($eType = mysql_fetch_array($data['eTypesList']))
								{
									if ($eType['id'] == $eChars['type']) {
										echo '<option selected value="'.$eType['id'].'">';
									}
									else
									{
										echo '<option value="'.$eType['id'].'">';	
									}
									echo $eType['label'];
									echo '</option>';
								}	
							?>
							<option>Другой</option>
						</select>
						</td>
					</tr>
					
					<tr>
						<td width="150">Комментарий</td>
						<td>	
						<input name="descr" type="text" value="<?php echo $eChars['descr']?>" class="width100">
						</td>
					</tr>
					
					<tr>
						<td width="150">Ввод в эксплуатацию</td>
						<td>
						<input name="start_date" type="text" value=<?php echo $eChars['start_date']?> class="tcal width100" />
						</td>
					</tr>
					
					<tr>
						<td width="150">Вывод из эксплуатации </td>
						<td>
						<input name="stop_date" type="text" value="<?php echo $eChars['stop_date']?>" class="tcal width100"/>
						</td>
					</tr>
					
					<tr>
						<td width="150">Начальное значение </td>
						<td>
						<input name="initial_value" type="text" value="<?php echo $eChars['intial_value']?>" class="width100">
						</td>
					</tr>
					
					<tr>
						<td width="150">КТТ</td>
						<td>
						<input name="cRatio" type="text" value="<?php echo $eChars['cRatio']?>" class="width100">
						</td>
					</tr>
					
					<tr>
						<td width="150">КТН</td>
						<td>
						<input name="vRatio" type="text" value="<?php echo $eChars['vRatio']?>"  class="width100">
						</td>
					</tr>			
	
			 	 </table>
		</form>
	</div>

		



