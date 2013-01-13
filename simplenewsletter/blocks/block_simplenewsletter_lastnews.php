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

/**
 * Affiche les dernières newsletter
 */

/**
 * Création du contenu du bloc
 *
 * @param array $options	[0] = nombre d'éléments à afficher
 */
function b_sn_lastnews_show($options)
{
	require XOOPS_ROOT_PATH.'/modules/simplenewsletter/include/common.php';
	$block = array();
	$start = 0;
	$limit = intval($options[0]);

	$news = $h_simplenewsletter_news->getLastNews($start, $limit);
	if( count($news) > 0) {
		foreach($news as $new) {
			$block['block_last_news'][] = $new->toArray();
		}
	}
	return $block;
}

/**
 * Edition des paramètres du bloc
 *
 * @param array $options	[0] = nombre d'éléments à afficher
 */
function b_sn_lastnews_edit($options)
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
function b_sn_lastnews_duplicatable($options)
{
	$options = explode('|',$options);
	$block = b_sn_lastnews_show($options);

	$tpl = new XoopsTpl();
	$tpl->assign('block', $block);
	$tpl->display('db:simplenewsletter_block_lastnews.html');
}
?>