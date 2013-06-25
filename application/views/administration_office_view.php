<div class="box left wide">
	<div class="title">
		Новости проекта
	</div>

	<div class="content">
		<?php 
			while($row = mysql_fetch_array($data['news']))
			{
				echo '<div class="news_header">'.$row['header'].'<div class="meta">'.$row['date'].' добавил: '.$row['fullName'].'</div></div>';
				echo '<div class="news_body">'.$row['content'].'</div>';
			} 
		?>		
	</div>
</div>

<div class="box right narrow">
	<div class="title">
		Контакты разработчиков
	</div>

	<div class="content height600">
		<?php 
			
			while($row = mysql_fetch_array($data['contacts']))
			{
				echo '<div class="name">'.$row['fullName'].'</div>';
				echo '<div class="contact phone">'.$row['mobilePhone'].'</div>';
				echo '<div class="contact email">'.$row['email'].'</div>';
				echo '<div class="contact icq">'.$row['icq'].'</div>';
				echo '<div class="contact skype">'.$row['skype'].'</div>';
			} 

		?>
	</div>
</div>


