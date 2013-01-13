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
if(!defined("SIMPLENEWSLETTER_CACHE_PATH")) {
	// Le chemin du cache (il est conseillé de le mettre en dehors de la portée du web)
	define("SIMPLENEWSLETTER_CACHE_PATH", XOOPS_UPLOAD_PATH.DIRECTORY_SEPARATOR.SIMPLENEWSLETTER_DIRNAME.DIRECTORY_SEPARATOR);

	define("SIMPLENEWSLETTER_META_KEYWORDS_AUTO_GENERATE", true);		// Do you want that the module automatically generates meta keywords ?
	define("SIMPLENEWSLETTER_META_KEYWORDS_COUNT", 50);					// How many meta keywords to generate ?
	define("SIMPLENEWSLETTER_META_KEYWORDS_ORDER", 0);					// Meta keywords order

	define("SIMPLENEWSLETTER_MESSAGE1_PATH", 'simplenewsletter_welcome.txt');
	define("SIMPLENEWSLETTER_MESSAGE2_PATH", 'simplenewsletter_bye.txt');
	define("SIMPLENEWSLETTER_MESSAGE3_PATH", 'simplenewsletter_index.txt');
	define("SIMPLENEWSLETTER_MESSAGE4_PATH", 'simplenewsletter_confirm.txt');

	// RSS Feed cache duration (in minutes)
	define("SIMPLENEWSLETTER_RSS_CACHE", 3600);

	// Max size of uploaded CSV files
	define("SIMPLENEWSLETTER_CSV_MAX_SIZE", 10000000);
}
?>