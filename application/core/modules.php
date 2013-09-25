<?php
/*function GetBlock($blockName, $actionName='index') {
	
	$modelFilePath = $_SERVER['DOCUMENT_ROOT']."/application/modules/".strtolower($blockName).'/model_'.strtolower($blockName).'.php';;
	if(file_exists($modelFilePath)) {
		include $modelFilePath;
	}

	$controllerFilePath = $_SERVER['DOCUMENT_ROOT'].'/application/modules/'.$blockName.'/controller_'.strtolower($blockName).'.php';;
	if(file_exists($controllerFilePath)) {
		include $controllerFilePath;
	}
	else {
		Route::ErrorPage404();
	}

	$controllerName = 'Controller_'.$blockName;
	$controller = new $controllerName;
	$action = 'action_'.$actionName;
		
	if(method_exists($controller, $action)) {
		//Возвращаем в виде строки содержимое модуля
		return $controller->$action();
	}
	else {
		Route::ErrorPage404();
	}
}*/

?>