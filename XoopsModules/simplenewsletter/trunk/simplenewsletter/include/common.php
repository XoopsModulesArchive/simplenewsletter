<?php
/**
 * ****************************************************************************
 * simplenewsletter - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of http://www.herve-thouzard.com
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Hervé Thouzard of http://www.herve-thouzard.com
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         simplenewsletter
 * @author 			Hervé Thouzard of http://www.herve-thouzard.com
 *
 * Version : $Id:
 * ****************************************************************************
 */
if (!defined("XOOPS_ROOT_PATH")) {
 	die("XOOPS root path not defined");
}

if( !defined("SIMPLENEWSLETTER_DIRNAME") ) {
	define("SIMPLENEWSLETTER_DIRNAME", 'simplenewsletter');
	define("SIMPLENEWSLETTER_URL", XOOPS_URL.'/modules/'.SIMPLENEWSLETTER_DIRNAME.'/');
	define("SIMPLENEWSLETTER_PATH", XOOPS_ROOT_PATH.'/modules/'.SIMPLENEWSLETTER_DIRNAME.'/');
	define("SIMPLENEWSLETTER_IMAGES_URL", SIMPLENEWSLETTER_URL.'images/');		// Les images du module (l'url)
	define("SIMPLENEWSLETTER_IMAGES_PATH", SIMPLENEWSLETTER_PATH.'images/');	// Les images du module (le chemin)
	define("SIMPLENEWSLETTER_JS_URL", SIMPLENEWSLETTER_URL.'js/');
	define("SIMPLENEWSLETTER_CSS_URL", SIMPLENEWSLETTER_URL.'css/');
}

// Chargement des handler et des autres classes
require SIMPLENEWSLETTER_PATH.'config.php';
require_once SIMPLENEWSLETTER_PATH.'class/simplenewsletter_utils.php';
require_once SIMPLENEWSLETTER_PATH.'class/simplenewsletter_handlers.php';
//require_once SIMPLENEWSLETTER_PATH.'class/PEAR.php';

$simplenewsletter_handler = simplenewsletter_handler::getInstance();

$h_simplenewsletter_members = $simplenewsletter_handler->h_simplenewsletter_members;
$h_simplenewsletter_news =  $simplenewsletter_handler->h_simplenewsletter_news;
$h_simplenewsletter_sent = $simplenewsletter_handler->h_simplenewsletter_sent;

// Définition des images
if( !defined("_SIMPLENEWSLETTER_EDIT")) {
	simplenewsletter_utils::loadLanguageFile('main.php');
}

global $pathIcon16;
	$icones = array(
		'edit' => "<img src='". $pathIcon16 . "/edit.png' alt='"._SIMPLENEWSLETTER_EDIT."' align='middle' />",
		'delete' => "<img src='". $pathIcon16 ."/delete.png' alt='"._SIMPLENEWSLETTER_DELETE."' align='middle' />",
	    'validate' => "<img src='". $pathIcon16 ."/on.png' alt='"._SIMPLENEWSLETTER_VALIDATE."' align='middle' />"
	);
?>