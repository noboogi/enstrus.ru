<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);
//Подключаем файлы ядра
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once 'core/modules.php';
require_once 'core/route.php';


//Запускаем маршрутизатор
Route::start(); 
