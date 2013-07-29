
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
	    <form id="mailReport" name="mailReport" method="post" action="/building_passport/ereportmail" >       
	    <table width="98%" border="0" style=" margin: 5px;">
          <tr>
            <td colspan="4"><strong>Период:</strong></td>
          </tr>
          <tr>
            <td colspan="4">
              <table width="100%" border="0">
                <tr>
                  <td><div align="center">c</div></td>
                  <td><input type="text" name="startDate" class="tcal width100" value="<?php echo $data['startDate']?>"/></td>
                  <td><div align="center">по</div></td>
                  <td><input type="text" name="startDate" class="tcal width100" value="<?php echo $data['endDate']?>"/></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td colspan="4"><hr /></td>
          </tr>
          <tr>
            <td width="250">ИНН:</td>
            <td width="250" colspan="3"><input type="text" name="INN" class="width100" value="1001263646" /></td>
          </tr>
          <tr>
            <td>Номер документа: </td>
            <td colspan="3"><input type="text" name="documentNum" class="width100" value="0"/></td>
          </tr>
          <tr>
            <td>Номер АИИС: </td>
            <td colspan="3"><input type="text" name="AIISNum" class="width100" value="0" /></td>
          </tr>
          <tr>
            <td>Наименование организации: </td>
            <td colspan="3"><input type="text" name="companyName" class="width100" value="ТЦ Тетрис" /></td>
          </tr>
          <tr>
            <td>Точка измерения: </td>
            <td colspan="3"><input type="text" name="pointName" class="width100" value="<?php echo $data['pointName'];?>"/></td>
          </tr>
          <tr>
            <td>Адрес назначения: </td>
            <td colspan="3"><input type="text" name="email" class="width100" value="companymail@mail.ru"/></td>
          </tr>		  
          <tr>
            <td colspan="4"><hr /></td>
          </tr>
          <tr>
            <td colspan="4">
				<div style="float: right;">
					<button class="button small" 
					onclick="document.mailReport.action='/building_passport/ereportmail?action=preview<?php echo '&eid='.$data['eid'].'&bid='.$data['bid']; ?>'; 
					document.mailReport.submit()">
						Предварительный просмотр					</button>
					<button class="button small"
					onclick="document.mailReport.action='/building_passport/ereportmail?action=send<?php echo '&eid='.$data['eid'].'&bid='.$data['bid']; ?>'; 
					document.mailReport.submit()">
						Отправить					</button>			
				</div>			</td>
          </tr>
		  <?php
		  if (strlen($data['previewDocument'])>0) {
				echo 
				'<tr>
				<td colspan="4">Предварительный просмотр: </td>
				</tr>
								
				<tr>
				<td colspan="4">
					<textarea style="width: 100%">';
					echo $data['previewDocument'];
				echo '</textarea>			
				</td>
			  </tr>';
		  }
		  ?>
        </table>
		</form>

</div>

		



