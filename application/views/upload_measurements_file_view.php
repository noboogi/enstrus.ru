

<form action="/upload_measurements_file/upload" method="post" enctype="multipart/form-data" name="upload" id="upload">
	<input type="hidden" name="eid" id="eid" value="<?php echo $data['eid'];?>"/>
	<table border="0">
		<tr>
			<td colspan="2">
				<b><div style="float:left">Загрузка данных, счётчик №: <?php echo $data['sn'];?> </div></b>				
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
<a href="/building/emelements?bid=5">dsdd</a>