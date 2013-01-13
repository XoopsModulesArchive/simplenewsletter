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

function simplenewsletter_search($queryarray, $andor, $limit, $offset, $userid)
{
	global $xoopsDB;
	include XOOPS_ROOT_PATH.'/modules/simplenewsletter/include/common.php';
	include_once XOOPS_ROOT_PATH.'/modules/simplenewsletter/class/simplenewsletter_news.php';

	$sql = 'SELECT news_id, news_title, news_date, news_uid FROM '.$xoopsDB->prefix('simplenewsletter_news').' WHERE news_sent = '._SIMPLENEWSLETTER_NEWSLETTER_SENT;
	if ( $userid != 0 ) {
		$sql .= '  AND news_uid = '.$userid;
	}

	$tmpObject = new simplenewsletter_news();
	$datas = $tmpObject->getVars();
	$fields = array();
	$cnt = 0;
	foreach($datas as $key => $value) {
		if($value['data_type'] == XOBJ_DTYPE_TXTBOX || $value['data_type'] == XOBJ_DTYPE_TXTAREA) {
			if($cnt == 0) {
				$fields[] = $key;
			} else {
				$fields[] = ' OR '.$key;
			}
			$cnt++;
		}
	}

	$count = count($queryarray);
	$more = '';
	if( is_array($queryarray) && $count > 0 ) {
		$cnt = 0;
		$sql .= ' AND (';
		$more = ')';
		foreach($queryarray as $oneQuery) {
			$sql .= '(';
			$cond = " LIKE '%".$oneQuery."%' ";
			$sql .= implode($cond, $fields).$cond.')';
			$cnt++;
			if($cnt != $count) {
				$sql .= ' '.$andor.' ';
			}
		}
	}
	$sql .= $more.' ORDER BY news_date DESC';
	$i = 0;
	$ret = array();
	$myts =& MyTextSanitizer::getInstance();
	$result = $xoopsDB->query($sql,$limit,$offset);
 	while ($myrow = $xoopsDB->fetchArray($result)) {
 		$tmpObject->setVar('news_id', $myrow['news_id']);
 		$tmpObject->setVar('news_title', $myrow['news_title']);

		$ret[$i]['image'] = 'images/mail_new.png';
		$ret[$i]['link'] = $tmpObject->getUrl();
		$ret[$i]['title'] = $myts->htmlSpecialChars($myrow['news_title']);
		$ret[$i]['time'] = $myrow['news_date'];
		$ret[$i]['uid'] = $myrow['news_uid'];
		$i++;
	}
	return $ret;
}
?>