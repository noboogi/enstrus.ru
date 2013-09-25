<?php				
	if ($data['operationStatus']['label'] != "")
	{
		if ($data['operationStatus']['code'] == 0) 
		{
			echo '<div id="message" class="message error">	
			<a href="#" title="Закрыть" class="closeMessage error" onclick="document.getElementById(\'message\').style.display = \'none\'">X</a>';
		}
		else
		{
			echo '<div id="message" class="message success">
			<a href="#" title="Закрыть" class="closeMessage success" onclick="document.getElementById(\'message\').style.display = \'none\'">X</a>';					
		} 
		echo $data['operationStatus']['label'];
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
		<div style="float: left">Отправка отчётов в «Энергокомфорт»</div>
		<div style="float: right"><?php echo $data['address']; ?></div>
	</div>

<div class="content height450">
<div style="padding: 5px;">
<form id="mailReport" name="mailReport" method="post" action="/mail_report" >
  <table width="100%" border="0">
    <tr>
      <td valign="top">
	  <table width="100%" class="simple">
	  	<thead>
			<tr>
				<td colspan="5"><strong>Список точек измерения:</strong></td>
			</tr>
			<tr>
			  <td></td>
			  <td>№ </td>
			  <td>Наименование</td>
			  <td>Код точки </td>
			  <td>Предыдущий отчёт </td>
			</tr>
		</thead>
        <tr>
			<?php
			if (count($data['emelementList'])) {
				$i = 1;
				foreach ($data['emelementList'] as $e) {
					echo '<tr>';
					echo '<td><input type="checkbox" name="emelement'.$i.'" value="'.$e['sn'].'" checked="checked"/></td>';
					echo '<td>'.$e['sn'].'</td>';
					echo '<td>'.$e['descr'].'</td>';
					echo '<td>'.$e['pointCode'].'</td>';
					echo '<td>'.$e['reportDate'].'</td>';
					echo '<tr>';
					$i++;
				}
			}
			?>
        </tr>
      </table>
	  </td>
	  <td>
	  <table width="100%" border="0">
        <tr>
          <td><strong>Период:</strong></td>
          <td>с</td>
          <td><input type="text" name="startDate" class="tcal width95" value="<?php echo $data['startDate']?>"/></td>
          <td>по</td>
          <td><input type="text" name="endDate" class="tcal width95" value="<?php echo $data['endDate']?>"/></td>
        </tr>
        <tr>
          <td colspan="5"><hr /></td>
        </tr>

        <tr>
          <td colspan="5"><table width="100%" border="0">
            <tr>
              <td width="180px">ИНН: </td>
              <td><input type="text" name="INN" class="width100" value="100-1-41-08307-01" /></td>
            </tr>
            <tr>
              <td>Наименование организации: </td>
              <td><input type="text" name="companyName" class="width100" value="ООО «Мерецкова, 11»" /></td>
            </tr>
            <tr>
              <td>Адрес назначения: </td>
              <td><input type="text" name="email" class="width100" value="eso.askue@rks.karelia.ru"/></td>
            </tr>
          </table></td>
        </tr>
        
      </table>
	  </td>
    </tr>
	<tr>
	<td colspan="2">
		<div>
					<br />
		  			<button class="button small" 
					onclick="document.mailReport.action='/mail_report?action=preview<?php echo '&bid='.$data['bid']; ?>'; 
					document.mailReport.submit()">
						Предварительный просмотр				
					</button>
					<button class="button small active"
					onclick="document.mailReport.action='/mail_report?action=send<?php echo '&bid='.$data['bid']; ?>'; 
					document.mailReport.submit()">
						Отправить					
					</button>			
		</div>			
	</td>
	</tr>
  </table>
 </form>
		  <?php
		  if (strlen($data['previewDocument'])>0) {
				echo 
				'<tr>
				<br/>
				<td colspan="4">Предварительный просмотр: </td>
				</tr>
								
				<tr>
				<td colspan="4">
					<textarea style="width: 99%; height: 200px;">';
					echo $data['previewDocument'];
				echo '</textarea>			
				</td>
			  </tr>';
		  }
		  ?>
		  
        </table>
  </form>
</div>
</div>
		



