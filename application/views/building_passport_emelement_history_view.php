
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
		<div style="float: left">Счётчик № <?php echo $data['esn']; ?></div>
		<div style="float: right"><?php echo $data['address']; ?>

		</div>
	</div>

	<div class="content height450">
		<div class='horizontal_button_menu_background'>
		<?php GetAsBlock("emelement_menu"); ?>	
		</div>
		Тут история счётчика (замены, обслуживание и .т.д)
		

		           
	</div>

		



