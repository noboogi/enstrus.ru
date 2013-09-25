

<form id="test" name="test" method="post" action="">
	<input id="eid" name="eid" /><label>EID</label>
	<input name="startDate" type="text" value="<?php echo "2012-01-01"?>" class="tcal"/><label>От</label>
	<input name="endDate" type="text" value="<?php echo "2013-01-01"?>" class="tcal"/><label>До</label>
	<input name="add" type="submit" id="add" value="Добавить" text="Добавить записи">
</form>
<br />

<form id="search" name="search" method="post" action="">
	<input id="seid" name="seid" /><label>EID</label>
	<input name="sstartDate" type="text" value="<?php echo "2013-09-01"?>" class="tcal"/><label>От</label>
	<input name="sendDate" type="text" value="<?php echo "2013-09-31"?>" class="tcal"/><label>До</label>
	<input name="search" type="submit" id="search" value="Выбрать" text="Выбрать">
</form>
<?php echo "Время поиска: ".$data; 
print_r(PDO::getAvailableDrivers());
?>