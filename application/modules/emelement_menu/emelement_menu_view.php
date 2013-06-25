<!------------------Меню навигации для электрического счётчика------------------>
<div class='grad_color last'>

	<ul class='horizontal_submenu'>
		<?php
			$menu = $data; 
			foreach ($menu as $item => $value) {
				echo '<li class="grad_color">';
				echo '<a href="'.$menu[$item]['Url'].'">'.$menu[$item]['Name'].'</a>';
				echo '</li>';
			}
		?>
	</ul>
	&nbsp;
</div>
<!---------------------------------------------------->
