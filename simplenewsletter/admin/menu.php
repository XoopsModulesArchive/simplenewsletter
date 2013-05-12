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

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(dirname(__FILE__)));
$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;


if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu = array();
$i=0;
$adminmenu[$i]["title"] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/home.png';

//-------------------------
$i++;
$adminmenu[$i]['title'] = _MI_SIMPLENEWSLETTER_ADMENU0;
$adminmenu[$i]['link'] = "admin/main.php?op=default";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/add.png';
$i++;
$adminmenu[$i]['title'] = _MI_SIMPLENEWSLETTER_ADMENU1;
$adminmenu[$i]['link'] = "admin/main.php?op=old";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/view_detailed.png';
$i++;
$adminmenu[$i]['title'] = _MI_SIMPLENEWSLETTER_ADMENU2;
$adminmenu[$i]['link'] = "admin/main.php?op=members";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/identity.png';
$i++;
$adminmenu[$i]['title'] = _MI_SIMPLENEWSLETTER_ADMENU3;
$adminmenu[$i]['link'] = "admin/main.php?op=messages";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/mail_foward.png';
$i++;
$adminmenu[$i]['title'] = _MI_SIMPLENEWSLETTER_ADMENU4;
$adminmenu[$i]['link'] = "admin/main.php?op=texts";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/content.png';
$i++;
$adminmenu[$i]['title'] = _MI_SIMPLENEWSLETTER_ADMENU5;
$adminmenu[$i]['link'] = "admin/main.php?op=csvimport";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/compfile.png';
$i++;
$adminmenu[$i]['title'] = _MI_SIMPLENEWSLETTER_ADMENU7;
$adminmenu[$i]['link'] = "admin/main.php?op=maintain";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/update.png';
$i++;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/about.png';