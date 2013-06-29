<?php

class View
{
		
	
	//$content_file - виды отображающие контент страниц;
	//$template_file - общий для всех страниц шаблон;
	//$data - массив, содержащий элементы контента страницы. Обычно заполняется в модели.
	
	function generate($content_view, $template_view, $data = null)
	{		
		
		//динамически подключаем общий шаблон (вид),
		//внутри которого будет встраиваться вид
		//для отображения контента конкретной страницы.		
		include 'application/views/'.$template_view;
	}
	
	function generateBlock($content_view, $data = null)
	{		
		include 'application/modules/'.$content_view.'/'.$content_view.'_view.php';
	}
}
