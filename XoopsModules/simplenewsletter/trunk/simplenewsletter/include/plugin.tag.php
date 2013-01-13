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
function simplenewsletter_tag_iteminfo(&$items)
{
	$items_id = array();
	foreach(array_keys($items) as $cat_id){
		foreach(array_keys($items[$cat_id]) as $item_id){
			$items_id[] = intval($item_id);
		}
	}
	require XOOPS_ROOT_PATH.'/modules/simplenewsletter/include/common.php';
	$items_obj = $h_simplenewsletter_news->getItemsFromIds($items_id);

	foreach(array_keys($items) as $cat_id){
		foreach(array_keys($items[$cat_id]) as $item_id){
			$item_obj =& $items_obj[$item_id];
			$items[$cat_id][$item_id] = array(
				'title'		=> $item_obj->getVar('news_title'),
				'uid'		=> $item_obj->getVar('news_uid'),
				'link'		=> $item_obj->getUrl(true),
				'time'		=> $item_obj->getVar('news_date'),
				'tags'		=> '',
				'content'	=> '',
				);
		}
	}
	unset($items_obj);
}

/** Remove orphan tag-item links **/
function simplenewsletter_tag_synchronization($mid)
{
	global $xoopsDB;
	$item_handler_keyName = 'news_id';
	$item_handler_table = $xoopsDB->prefix('simplenewsletter_news');
	$link_handler =& xoops_getmodulehandler('link', 'tag'');
	$where = "($item_handler_table.news_sent = 1)";
	$where1 = "($item_handler_table.news_sent = 0)";

	/* clear tag-item links */
	if($link_handler->mysql_major_version() >= 4):
    $sql =	"	DELETE FROM {$link_handler->table}".
    		"	WHERE ".
    		"		tag_modid = {$mid}".
    		"		AND ".
    		"		( tag_itemid NOT IN ".
    		"			( SELECT DISTINCT {$item_handler_keyName} ".
    		"				FROM {$item_handler_table} ".
    		"				WHERE $where".
    		"			) ".
    		"		)";
    else:
    $sql = 	"	DELETE {$link_handler->table} FROM {$link_handler->table}".
    		"	LEFT JOIN {$item_handler_table} AS aa ON {$link_handler->table}.tag_itemid = aa.{$item_handler_keyName} ".
    		"	WHERE ".
    		"		tag_modid = {$mid}".
    		"		AND ".
    		"		( aa.{$item_handler_keyName} IS NULL".
    		"			OR $where1".
    		"		)";
	endif;
    if (!$link_handler->db->queryF($sql)) {
        trigger_error($link_handler->db->error());
  	}
}
?>