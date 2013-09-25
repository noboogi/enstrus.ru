<?php
class View {
	
	public $instance;

	function Init($commonTemplate, $commonContent, $data = NULL) {		
		$template = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/application/views/common/'.$commonTemplate);
		ob_start();
			include($_SERVER['DOCUMENT_ROOT'].'/application/views/'.$commonContent);
			$content = ob_get_contents();
		ob_clean();		
		$this->instance = str_replace('{CONTENT}', $content, $template);
	}
	
	function AddModule($moduleName, $placeholder = FALSE) {
		$blockView = $this->GetBlock($moduleName);
		$placeholder = $placeholder ? strtoupper($placeholder) : strtoupper($moduleName);
		$this->instance = str_replace('{'.$placeholder.'}', $blockView, $this->instance);
	}
	
	private function GetBlock($blockName, $actionName='index') {
		$modelFilePath = $_SERVER['DOCUMENT_ROOT']."/application/modules/".strtolower($blockName).'/model_'.strtolower($blockName).'.php';
		if(file_exists($modelFilePath)) {
			include $modelFilePath;
		}
	
		$controllerFilePath = $_SERVER['DOCUMENT_ROOT'].'/application/modules/'.$blockName.'/controller_'.strtolower($blockName).'.php';;
		if(file_exists($controllerFilePath)) {
			include $controllerFilePath;
		}
		else {
			die('Modules controller including error: '.$blockName);
		}
	
		$controllerName = 'Controller_'.$blockName;
		$controller = new $controllerName;
		$action = 'action_'.$actionName;
			
		if(method_exists($controller, $action)) {
			/*¬озвращаем в виде строки содержимое модул€*/
			return $controller->$action();
		}
		else {
			die('Modules method existing error: '.$blockName.'/'.$actionName);
		}
	}	
	
	/*»спользуетс€ в контроллере блока*/
	function GenerateBlock($blockView, $data = null) {
		$viewFilePath = $_SERVER['DOCUMENT_ROOT'].'/application/modules/'.$blockView.'/'.$blockView.'_view.php';
		if(file_exists($viewFilePath)) {
			ob_start();
			include($viewFilePath);
			$blockView = ob_get_contents();
			ob_clean(); 	
			return  $blockView;
		}
		else {die('Modules view including error: '.$viewFilePath);}
	}
	
	function Show($data = NULL) {
		echo $this->instance;
	}	
}
