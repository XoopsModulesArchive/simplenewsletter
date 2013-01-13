<?php
/**
 * ****************************************************************************
 * simplenewsletter - MODULE FOR XOOPS
 * Copyright (c) Herv� Thouzard of http://www.herve-thouzard.com
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Herv� Thouzard of http://www.herve-thouzard.com
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         simplenewsletter
 * @author 			Herv� Thouzard of http://www.herve-thouzard.com
 *
 * Version : $Id:
 * ****************************************************************************
 */
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

function simplenewsletter_adminMenu($currentoption = 0, $breadcrumb = '')
{
	global $xoopsConfig, $xoopsModule;
	if(file_exists(XOOPS_ROOT_PATH.'/modules/simplenewsletter/language/'.$xoopsConfig['language'].'/modinfo.php')) {
		require_once XOOPS_ROOT_PATH.'/modules/simplenewsletter/language/'.$xoopsConfig['language'].'/modinfo.php';
	} else {
		require_once XOOPS_ROOT_PATH.'/modules/simplenewsletter/language/english/modinfo.php';
	}
	require XOOPS_ROOT_PATH.'/modules/simplenewsletter/admin/menu.php';

	echo "<style type=\"text/css\">\n";
	echo "#buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }\n";
	echo "#buttonbar { float:left; width:100%; background: #e7e7e7 url('../images/modadminbg.gif') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }\n";
	echo "#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }\n";
	echo "#buttonbar li { display:inline; margin:0; padding:0; }";
	echo "#buttonbar a { float:left; background:url('../images/left_both.gif') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }\n";
	echo "#buttonbar a span { float:left; display:block; background:url('../images/right_both.gif') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }\n";
	echo "/* Commented Backslash Hack hides rule from IE5-Mac \*/\n";
	echo "#buttonbar a span {float:none;}\n";
	echo "/* End IE5-Mac hack */\n";
	echo "#buttonbar a:hover span { color:#333; }\n";
	echo "#buttonbar .current a { background-position:0 -150px; border-width:0; }\n";
	echo "#buttonbar .current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }\n";
	echo "#buttonbar a:hover { background-position:0% -150px; }\n";
	echo "#buttonbar a:hover span { background-position:100% -150px; }\n";
	echo "</style>\n";

	echo "<div id=\"buttontop\">\n";
	echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\">\n";
	echo "<tr>\n";
	echo "<td style=\"width: 70%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\">\n";
	echo "<a href=\"../index.php\">"._AM_SIMPLENEWSLETTER_GO_TO_MODULE."</a> | <a href=\"".XOOPS_URL."/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=".$xoopsModule->getVar('mid')."\">"._AM_SIMPLENEWSLETTER_PREFERENCES."</a> | <a href='index.php?op=maintain'>"._AM_SIMPLENEWSLETTER_MAINTAIN."</a>\n";
	echo "</td>\n";
	echo "<td style=\"width: 30%; font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;\">\n";
	echo "<b>".$xoopsModule->getVar('name').' - '._AM_SIMPLENEWSLETTER_ADMINISTRATION."</b>&nbsp;".$breadcrumb."\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
	echo "<div id=\"buttonbar\">\n";
	echo "<ul>\n";
	foreach($adminmenu as $key=>$link) {
		if($key == $currentoption) {
			echo "<li class=\"current\">\n";
		} else {
			echo "<li>\n";
		}
		echo "<a href=\"".XOOPS_URL."/modules/simplenewsletter/".$link['link']."\"><span>".$link['title']."</span></a>\n";
		echo "</li>\n";
	}
	echo "</ul>\n";
	echo "</div>\n";
	echo "<br style=\"clear:both;\" />\n";
}

/**
 * Verify that a mysql table exists
 *
 * @package News
 * @author Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
*/
function simplenewsletter_tableExists($tablename)
{
	global $xoopsDB;
	$result=$xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");
	return($xoopsDB->getRowsNum($result) > 0);
}

/**
 * Verify that a field exists inside a mysql table
 *
 * @package News
 * @author Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
*/
function simplenewsletter_fieldExists($fieldname,$table)
{
	global $xoopsDB;
	$result=$xoopsDB->queryF("SHOW COLUMNS FROM	$table LIKE '$fieldname'");
	return($xoopsDB->getRowsNum($result) > 0);
}

/**
 * Add a field to a mysql table
 *
 * @package News
 * @author Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function simplenewsletter_addField($field, $table)
{
	global $xoopsDB;
	$result=$xoopsDB->queryF('ALTER TABLE ' . $table . " ADD $field;");
	return $result;
}
?>