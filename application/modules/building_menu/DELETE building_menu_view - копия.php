<!------------------Меню навигации по личному кабинету------------------>
<div class='vertmenu'>
	<ul>
		<?php
			$menu = $data; //Да хоть горшком обзови
			foreach ($menu as $item => $value) {
				echo '<li class="'.$menu[$item]['Icon'].'">';
				echo '<a href="'.$menu[$item]['Url'].'">'.$menu[$item]['Name'].'</a>';
				echo '</li>';
			}
		?>
	</ul>
</div>

<div id="menu234">
<ul>
<li><a href="#">Home</a></li>
<li><a href="#">Contact</a></li>
<li><a href="#">Support</a></li>
<li><a href="#">Categories</a></li>
<li><a href="#">All Downloads</a></li>
<li><a href="#">Latest Updates</a></li>
<li><a href="#">Report an Error</a></li>
</ul>
</div>
<!---------------------------------------------------->
