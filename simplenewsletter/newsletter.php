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
 * Affichage du contenu d'une newsletter
 */
require 'header.php';
$xoopsOption['template_main'] = 'simplenewsletter_news.html';
require_once XOOPS_ROOT_PATH.'/header.php';

if(isset($_GET['news_id'])) {
	$news_id = intval($_GET['news_id']);
} else {
	simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR1, 'index.php', 4);
}
$newsletter = null;
$newsletter = $simplenewsletter_handler->h_simplenewsletter_news->get($news_id);
if(!is_object($newsletter)) {
	simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR2, 'index.php', 4);
}
if(simplenewsletter_utils::getModuleOption('use_tags') && simplenewsletter_utils::tagModuleExists()) {
    require_once XOOPS_ROOT_PATH.'/modules/tag/include/tagbar.php';
    $xoopsTpl->assign('tagbar', tagBar($news_id, 0));
}
$xoopsTpl->assign('newsletter', $newsletter->toArray());
$title = $newsletter->news_title.' - '.simplenewsletter_utils::getModuleName();
$metakeywords = simplenewsletter_utils::createMetaKeywords($newsletter->news_title.' '.$newsletter->news_body);
simplenewsletter_utils::setMetas($title, $title, $metakeywords);
simplenewsletter_utils::addRssMetaLink();
require_once(XOOPS_ROOT_PATH.'/footer.php');
?>