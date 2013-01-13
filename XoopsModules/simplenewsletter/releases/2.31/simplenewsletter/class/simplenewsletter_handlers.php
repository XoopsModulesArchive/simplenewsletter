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
 * Chargement des handlers utilisés par le module
 */
class simplenewsletter_handler
{
	public $h_simplenewsletter_members = null;
	public $h_simplenewsletter_news = null;
	public $h_simplenewsletter_sent = null;
	private static $instance = false;

	private function __construct()
	{
		$handlersNames = array('simplenewsletter_members','simplenewsletter_news', 'simplenewsletter_sent');
		foreach($handlersNames as $handlerName) {
			$internalName = 'h_'.$handlerName;
			$this->$internalName = xoops_getmodulehandler($handlerName, SIMPLENEWSLETTER_DIRNAME);
		}
	}

	public static function getInstance()
	{
		if (!self::$instance instanceof self) {
      		self::$instance = new self;
		}
		return self::$instance;
	}
}
?>