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

if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH.'/class/xoopsobject.php';
if (!class_exists('Simplenewsletter_XoopsPersistableObjectHandler')) {
	include_once XOOPS_ROOT_PATH.'/modules/simplenewsletter/class/PersistableObjectHandler.php';
}

class simplenewsletter_sent extends Simplenewsletter_Object {

	function __construct()
	{
		$this->initVar('sent_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('sent_news_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('sent_uid', XOBJ_DTYPE_INT, null, false);
	}
}


class SimplenewsletterSimplenewsletter_sentHandler extends Simplenewsletter_XoopsPersistableObjectHandler
{
	function __construct($db)
	{	//								Table					Class				Id
		parent::__construct($db, 'simplenewsletter_sent', 'simplenewsletter_sent', 'sent_id', '');
	}

	/**
	 * Supprime les témoins d'envoi d'une newsletter
	 *
	 * @param integer $sent_news_id	L'identifiant de la newsletter
	 * @return boolean
	 */
	function deleteSentForNewsletter($sent_news_id)
	{
	    $sql = 'DELETE FROM '.$this->table.' WHERE sent_news_id = '.intval($sent_news_id);
	    $this->forceCacheClean();
	    return (bool) $this->db->queryF($sql);
	}
}
?>