<?php
/**
 * ****************************************************************************
 * simplenewsletter - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         simplenewsletter
 * @author 			Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 *
 * Version : $Id:
 * ****************************************************************************
 */


/**
 * Affiche les derniers inscrits
 */

/**
 * Création du contenu du bloc
 *
 * @param array $options	[0] = nombre d'éléments à afficher
 */
function b_sn_lastmembers_show($options)
{
	global $xoopsConfig;
	require XOOPS_ROOT_PATH.'/modules/simplenewsletter/include/common.php';
	$block = array();
	$start = 0;
	$limit = intval($options[0]);

	$members = $h_simplenewsletter_members->getLastSubscribedUsers($start, $limit);
	if( count($members) > 0) {
		$block['block_last_members'] = $members;
	}
	return $block;
}

/**
 * Edition des paramètres du bloc
 *
 * @param array $options	[0] = nombre d'éléments à afficher
 */
function b_sn_lastmembers_edit($options)
{
	global $xoopsConfig;
	include XOOPS_ROOT_PATH.'/modules/simplenewsletter/include/common.php';
	$form = '';
	$form .= "<table border='0'>";
	$form .= '<tr><td>'._MB_SIMPLENEWSLETTER_ITEMS_CNT. "</td><td><input type='text' name='options[]' id='options' value='".$options[0]."' /></td></tr>";
	$form .= '</table>';
	return $form;
}

/**
 * Bloc à la volée
 */
function b_sn_lastmembers_duplicatable($options)
{
	$options = explode('|',$options);
	$block = b_sn_lastmembers_show($options);

	$tpl = new XoopsTpl();
	$tpl->assign('block', $block);
	$tpl->display('db:simplenewsletter_block_lastmembers.html');
}
?>