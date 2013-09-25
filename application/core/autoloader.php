<?php
spl_autoload_register ('AutoloadLibrary');
spl_autoload_register ('AutoloadModel');

function AutoloadLibrary ($className) {
	$fileName = ($_SERVER['DOCUMENT_ROOT'].'/application/library/classes/'.$className. '.php');
	if (file_exists($fileName)) {require_once $fileName;}
}

function AutoloadModel ($className) {
	$fileName = ($_SERVER['DOCUMENT_ROOT'].'/application/models/'.$className. '.php');
	if (file_exists($fileName)) {require_once $fileName;}
}
?>