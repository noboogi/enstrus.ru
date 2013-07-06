<!------------------Меню навигации для электрического счётчика------------------>
<div class="button-group">
		<?php
			$menu = $data; 
			foreach ($menu as $item => $value) {
				echo '<a href="'.$menu[$item]['Url'].'" class="button big square">'.$menu[$item]['Name'].'</a>';
			}
		?>

</div>
<!---------------------------------------------------->

	