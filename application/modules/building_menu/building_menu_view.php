<!------------------Меню навигации по личному кабинету------------------>
<div class='vertmenu'>
	<ul>
		<?php
			foreach ($data as $item => $value) {
				echo '<li>';
				echo '<a href="'.$data[$item]['Url'].'">'.$data[$item]['Name'].'</a>';
				echo '</li>';
			}
		?>
	</ul>
</div>

<!---------------------------------------------------->
