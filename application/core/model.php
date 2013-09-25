<?php		
class Model {	 
	private $data;
	
	public function CallCommonFunc($functionName, $arg) {
		$filePath = $_SERVER['DOCUMENT_ROOT'].'/application/library/functions/'.$functionName.'.php';
		if (!file_exists($filePath)) {die("Ошибка вызова функции: файл $path не существует");}
		
		include_once($_SERVER['DOCUMENT_ROOT'].'/application/library/functions/'.$functionName.'.php');
		if (is_array($arg)) {
	   		return call_user_func_array($functionName, $arg);} 
		else {
			return call_user_func($functionName, $arg);
		}
	}
}

