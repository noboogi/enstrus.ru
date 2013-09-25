<div class="box left narrow">
	<div class="title">
		Навигация
	</div>

	<div class="content">
		{BUILDING_MENU}
	</div>
</div>

<div class="box right wide">
	<div class="title">
		<div style="float: left">Приборы учёта: счётчики электрические</div>
			<div class="icon_button bordered" 
				onclick="document.emeasurementsElements.action='/mail_report?bid=<?php echo $data['bid']?>'; 
				document.emeasurementsElements.submit()" style="cursor:pointer;">
				<img src="../images/icons/24/mail.png" width="20" height="20" title="Отправка отчёта в «Энергокомфорт»" />		
			</div>		
			<div class="icon_button bordered" 
				onclick="document.emeasurementsElements.action='/emeasurements/graph'; 
				document.emeasurementsElements.submit()" style="cursor:pointer;">
				<img src="../images/icons/24/bar_chart.png" width="20" height="20" title="Статистика по выбраным: график" />		
			</div>
			<div class="icon_button bordered" 
				onclick="document.emeasurementsElements.action='/emeasurements/table'; 
				document.emeasurementsElements.submit()" style="cursor:pointer; margin-left: 15px;">
				<img src="../images/icons/24/sum.png" width="20" height="20" title="Статистика по выбраным: таблица" />		
			</div>			
			<div style="float: right"><?php echo $data['address']; ?>
		</div>	
	</div>

	<div class="content height450">
	<form id="emeasurementsElements" name="emeasurementsElements" method="post" action="/emeasurements">
		<?php if (count($data['generalEmelementsList'])) : ?>
			<div style="margin: 10px;">
			<div class="article">
			<div class="text-header">Уровень 1: Входное распределительное устройство</div>
				<table class="simple">
					<thead>
						<th>&nbsp;</th>
						<th>Серийный номер</th>
						<th>Тип</th>
						<th>Коэффициент трансформации</th>
						<th>Комментарий</th>
					</thead>		
					<?php foreach ($data['generalEmelementsList'] as $e):?>
						<tr>
							<td><input type="checkbox" name="emelement<?php echo $e['eid']?>" value="<?php echo $e['sn']?>" /></td> 
							<td><a href=/building_passport/emeasures?eid=<?php echo $e['eid'].'&bid='.$data['bid']?>><?php echo $e['sn']?></a></td>
							<td><?php echo $e['label']?></td>
							<td><?php echo $e['cRatio']?></td>	
							<td><?php echo $e['descr']?></td>								
						</tr>					
					<?php endforeach;?>
				</table>
			</div>	
			</div>			
		<?php endif;?>
		
		<?php if (count($data['porchEmelementsList'])) : ?>
			<div style="margin: 10px;">
			<div class="article">
			<div class="text-header">Уровень 2: Подъезды</div>
				<table class="simple">
					<thead>
						<th>&nbsp;</th>
						<th>Серийный номер</th>
						<th>Тип</th>
						<th>Коэффициент трансформации</th>
						<th>Комментарий</th>
					</thead>		
					<?php foreach ($data['porchEmelementsList'] as $e):?>
						<tr>
							<td><input type="checkbox" name="emelement<?php echo $e['eid']?>" value="<?php echo $e['sn']?>" /></td> 
							<td><a href=/building_passport/emeasures?eid=<?php echo $e['eid'].'&bid='.$data['bid']?>><?php echo $e['sn']?></a></td>
							<td><?php echo $e['label']?></td>
							<td><?php echo $e['cRatio']?></td>	
							<td><?php echo $e['descr']?></td>								
						</tr>					
					<?php endforeach;?>
				</table>
			</div>	
			</div>			
		<?php endif;?>
		
		<?php if (count($data['flatEmelementsList'])) : ?>
			<div style="margin: 10px;">
			<div class="article">
			<div class="text-header">Уровень 3: Квартиры</div>
				<table class="simple">
					<thead>
						<th>&nbsp;</th>
						<th>Серийный номер</th>
						<th>Тип</th>
						<th>Коэффициент трансформации</th>
						<th>Комментарий</th>
					</thead>		
					<?php foreach ($data['flatEmelementsList'] as $e):?>
						<tr>
							<td><input type="checkbox" name="emelement<?php echo $e['eid']?>" value="<?php echo $e['sn']?>" /></td> 
							<td><a href=/building_passport/emeasures?eid=<?php echo $e['eid'].'&bid='.$data['bid']?>><?php echo $e['sn']?></a></td>
							<td><?php echo $e['label']?></td>
							<td><?php echo $e['cRatio']?></td>	
							<td><?php echo $e['descr']?></td>								
						</tr>					
					<?php endforeach;?>
				</table>
			</div>	
			</div>			
		<?php endif;?>					
	</form>
	</div>
</div>
</div>





