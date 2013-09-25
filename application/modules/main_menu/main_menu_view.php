<?php	
		extract($data);

		echo '<nav id="menu-wrap">';
		echo '<ul id="menu">';
		/*Вывод меню на экран*/
		foreach ($menu as $item => $value) { //1
			/*Вывод основных пунктов*/
			//Если для пункта определена иконка, то припишем соответствующий класс
			$class = (($menu[$item]['Icon'] != '') ? 'class="icon '.$menu[$item]['Icon'].'"' : '');
			echo "<li $class>";	//1-1		
			echo '	<a href="'.$menu[$item]['Url'].'">'.$menu[$item]['Name'].'</a>';

			/*Вывод подпунктов*/
			echo '<ul>';
			foreach ($submenu as $subitem => $value) { //2
				//Соответствие подпункта пункту 
				if ($submenu[$subitem]['pid'] == $menu[$item]['id']) {
						$class = (($submenu[$subitem]['Icon'] != '') ? 'class="icon '.$menu[$item]['Icon'].'"' : '');
						echo "<li $class>";	 //1-2		
						echo '<a href="'.$submenu[$subitem]['Url'].'">'.$submenu[$subitem]['Name'].'</a>';
	
						/*Вывод под-подпунктов :)*/
						echo '<ul>';
						foreach ($submenu2 as $subitem2 => $value) { //3
							//Соответствие подпункта пункту
							if ($submenu2[$subitem2]['pid'] == $submenu[$subitem]['id']) {	
								echo "<li $class>";			
								echo '<a href="'.$submenu2[$subitem2]['Url'].'">'.$submenu2[$subitem2]['Name'].'</a>';
								echo "</li>";	
							}
						} //3
						echo '</ul>';
						echo "</li>"; //1-2												
				}
			} //2
			echo '</ul>';
			echo "</li>"; //1-1

		} //1
		echo '</nav>';
		echo '</ul>';
?>