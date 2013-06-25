<?php

	function GetAsBlock($block_name, $action_name="index")
	{

		// подцепляем файл с классом модели
		$model_file = $block_name.'/model_'.strtolower($block_name).'.php';
		$model_path = "application/modules/".$model_file;
		if(file_exists($model_path))
		{
			include "application/modules/".$model_file;
		}

		// подцепляем файл с классом контроллера
		$controller_file = $block_name.'/controller_'.strtolower($block_name).'.php';
		$controller_path = "application/modules/".$controller_file;
		if(file_exists($controller_path))
		{
			include "application/modules/".$controller_file;
		}
		else
		{
			/*
			правильно было бы кинуть здесь исключение,
			но для упрощения сразу сделаем редирект на страницу 404
			*/
			Route::ErrorPage404();
		}

		$controller_name = 'Controller_'.$block_name;
		$controller = new $controller_name;
		$action = 'action_'.$action_name;
		
		if(method_exists($controller, $action))
		{
			// вызываем действие контроллера
			$controller->$action();
		}
		else
		{
			// здесь также разумнее было бы кинуть исключение
			Route::ErrorPage404();
		}


	}



?>