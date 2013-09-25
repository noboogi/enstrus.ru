<?php
/*Статусы пользователей*/
const NOACTIVATED = 0;		/*Ещё не активирован*/
const BLOCKED = 1;			/*Заблокирован*/
const GUEST = 2;			/*Гость, не авторизован*/
const MEMBER = 3;			/*Обычный жилец*/
const HOA = 4; 				/*Homeowners Association - ТСЖ, член правления или председатель*/
const MGCOMPANY = 5;		/*Член управляющей компании*/
const MODERATOR = 6;		/*Модератор*/
const ADMINISTRATOR = 7;	/*Администратор*/

/*Конфигурация БД*/
const DOMAIN = '127.0.0.1';
const DB_NAME = 'teiriko_synergy';
const DB_USR_ADM = 'teiriko_synergy';
const DB_PASS_ADM = '159753';
const DB_LOC = '127.0.0.1';
	
/*Подключаем файлы ядра*/
require_once ($_SERVER['DOCUMENT_ROOT'].'/application/core/route.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/application/core/model.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/application/core/view.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/application/core/controller.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/application/core/modules.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/application/core/autoloader.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/application/core/bdsm.php'); /*basic database singleton method*/

/*Сессию начинаем после загрузки autoloader чтобы корректно 
 *работала (раз)сериализация для объектов в  $_SESSION */
session_start();

/*Запускаем маршрутизатор*/
Route::start(); 
?>