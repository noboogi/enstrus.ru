<div class='vertmenu'>
	<ul>
	<?php foreach ($data as $item):?>
		<li><a href="<?php echo $item['Url']?>"><?php echo $item['Name'] ?></a></li>
	<?php endforeach;?>
	</ul>
</div>
