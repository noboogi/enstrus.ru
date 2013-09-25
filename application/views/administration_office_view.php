<div class="box left wide">
	<div class="title">
		Новости проекта
	</div>

	<div class="content">
		<?php
			foreach ($data['news'] as $n) {
				echo '<div class="news_header">'.$n['header'].'<div class="meta">'.$n['date'].' добавил: '.$n['fullName'].'</div></div>';
				echo '<div class="news_body">'.$n['content'].'</div>';	
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
			foreach ($data['contacts'] as $c) {
				echo '<div class="name">'.$c['fullName'].'</div>';
				echo '<div class="contact phone">'.$c['mobilePhone'].'</div>';
				echo '<div class="contact email">'.$c['email'].'</div>';
				echo '<div class="contact icq">'.$c['icq'].'</div>';
				echo '<div class="contact skype">'.$c['skype'].'</div>';			
			}
		?>
	</div>
</div>


