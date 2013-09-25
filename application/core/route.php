<?php
/*Класс-маршрутизатор для определения запрашиваемой страницы.
> цепляет классы контроллеров и моделей;
> создает экземпляры контролеров страниц и вызывает действия этих контроллеров*/
class Route {

	static function start() {
		/*Контроллер и действие по умолчанию*/
		$controller_name = 'Main';
		$action_name = 'index';
		
		/*Отсекаем часть с GET запросом*/
		$request = explode('?', $_SERVER['REQUEST_URI']);		
		$routes = explode('/', $request[0]);

		/*Получаем имя контроллера */
		if ( !empty($routes[1]) ) {	
			$controller_name = $routes[1];
		}
		
		/*Получаем имя экшена*/
		if ( !empty($routes[2]) ) {
			$action_name = $routes[2];
		}

		/*Добавляем префиксы*/
		//$model_name = 'Model_'.$controller_name;
		$controller_name = 'Controller_'.$controller_name;
		$action_name = 'action_'.$action_name;

/*Подцепляем файл с классом модели (файла модели может и не быть)*/
//ВСЁ, ЧТО СВЯЗАНО С МОДЕЛЬЮ, МОЖНО ЗДЕСЬ УДАЛИТЬ, ТАК КАК КОНТРОЛЛЕР ВИДИТ ИХ ЧЕРЕЗ АВТОЛАДЕР
//Т.К. МОДЕЛЕЙ МОЖЕТ ИСПОЛЬЗОВАТЬСЯ МНОГО РАЗНЫХ В ОДНОМ КОНТРОЛЛЕРЕ, ТО ЭТО ЛОГИЧНО. 
//ВОЗМОЖНО, ПРИДЁТСЯ ВЕРНУТЬ КАК БЫЛО ПРИ ОПТИМИЗАЦИИ, НО МАЛОООООВЕРОЯТНО
//		$model_file = strtolower($model_name).'.php';
//		$model_path = "application/models/".$model_file;
//		if(file_exists($model_path)) {
//			include "application/models/".$model_file;
//		}

		/*Подцепляем файл с классом контроллера*/
		$controllerFilePath = $_SERVER['DOCUMENT_ROOT'].'/application/controllers/'.strtolower($controller_name).'.php';
		if(file_exists($controllerFilePath)) {
			include $controllerFilePath;
		}
		else {
			Route::ErrorPage404();
		}
		
		/*Создаем контроллер*/
		$controller = new $controller_name;
		$action = $action_name;
		
		if(method_exists($controller, $action)) {
			$controller->$action();
		}
		else {
			Route::ErrorPage404();
		}
	
	}

	function ErrorPage404() {
		$host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:'.$host.'404');
    }
    
}
