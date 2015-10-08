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
 * Affichage RSS des newsletter
 */
require 'header.php';
include_once XOOPS_ROOT_PATH.'/class/template.php';

error_reporting(0);
@$xoopsLogger->activated = false;


if (function_exists('mb_http_output')) {
	mb_http_output('pass');
}
$charset = 'utf-8';
header ('Content-Type:text/xml; charset='.$charset);
$tpl = new XoopsTpl();

$tpl->xoops_setCaching(2);		// 1 = Cache global, 2 = Cache individuel (par template)
$tpl->xoops_setCacheTime(SIMPLENEWSLETTER_RSS_CACHE);	// Temps de cache en secondes

if (!$tpl->is_cached('db:simplenewsletter_rss.html', 'News')) {
    global $xoopsConfig;
	$categoryTitle = simplenewsletter_utils::getModuleName();
	$sitename = htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES);
	$email = checkEmail($xoopsConfig['adminmail'], true);
	$slogan = htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES);
	$limit = simplenewsletter_utils::getModuleOption('perpage');

	$tpl->assign('charset',$charset);
	$tpl->assign('channel_title', xoops_utf8_encode($sitename));
	$tpl->assign('channel_link', XOOPS_URL.'/');
	$tpl->assign('channel_desc', xoops_utf8_encode($slogan));
	$tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
	$tpl->assign('channel_webmaster', xoops_utf8_encode($email));
	$tpl->assign('channel_editor', xoops_utf8_encode($email));
	$tpl->assign('channel_category', xoops_utf8_encode($categoryTitle));
	$tpl->assign('channel_generator', xoops_utf8_encode(simplenewsletter_utils::getModuleName()));
	$tpl->assign('channel_language', _LANGCODE);
	$tpl->assign('image_url', XOOPS_URL.'/images/logo.gif');
	$dimention = getimagesize(XOOPS_ROOT_PATH.'/images/logo.gif');
	if (empty($dimention[0])) {
		$width = 88;
	} else {
		$width = ($dimention[0] > 144) ? 144 : $dimention[0];
	}
	if (empty($dimention[1])) {
		$height = 31;
	} else {
		$height = ($dimention[1] > 400) ? 400 : $dimention[1];
	}
	$tpl->assign('image_width', $width);
	$tpl->assign('image_height', $height);
	$start = 0;

	$articles = $simplenewsletter_handler->h_simplenewsletter_news->getLastNews($start, $limit);
	foreach($articles as $item) {
		$titre = htmlspecialchars($item->getVar('news_title'), ENT_QUOTES);
		$description = $item->getShortContent();
		$description = htmlspecialchars($description, ENT_QUOTES);
		$link = $item->getUrl();
    	      $tpl->append('items', array('title' => xoops_utf8_encode($titre),
          		'link' => $link,
          		'guid' => $link,
          		'pubdate' => formatTimestamp($item->getVar('news_date'), 'rss'),
          		'description' => xoops_utf8_encode($description)));
	}
}
$tpl->display('db:simplenewsletter_rss.html', 'News');
?>