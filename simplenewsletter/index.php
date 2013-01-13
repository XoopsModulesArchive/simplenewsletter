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
 * Liste les dernières newsletter
 */
require 'header.php';
$xoopsOption['template_main'] = 'simplenewsletter_index.html';
require_once XOOPS_ROOT_PATH.'/header.php';
require_once XOOPS_ROOT_PATH.'/class/pagenav.php';
require_once SIMPLENEWSLETTER_PATH.'class/registryfile.php';

$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$limit = simplenewsletter_utils::getModuleOption('perpage');	// Nombre maximum d'éléments à afficher
$baseurl = SIMPLENEWSLETTER_URL.basename(__FILE__);	// URL de ce script
$registry = new simplenewsletter_registryfile();
$xoopsTpl->assign('headerMessage', nl2br($registry->getfile(SIMPLENEWSLETTER_MESSAGE3_PATH)));

$itemsCount = $simplenewsletter_handler->h_simplenewsletter_news->getNewsletterSentCount();
$items = $simplenewsletter_handler->h_simplenewsletter_news->getLastNews($start, $limit);
if($itemsCount > $limit) {
	$pagenav = new XoopsPageNav($itemsCount, $limit, $start, 'start');
	$xoopsTpl->assign('pagenav', $pagenav->renderNav());
}
if(count($items) > 0) {
	foreach($items as $item) {
		$xoopsTpl->append('newsletters', $item->toArray());
	}
}
simplenewsletter_utils::addRssMetaLink();
$title = simplenewsletter_utils::getModuleName();
simplenewsletter_utils::setMetas($title, $title);
require_once(XOOPS_ROOT_PATH.'/footer.php');
?>