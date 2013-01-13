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
 * CRON chargé de l'envoi de la newsletter
 */
require 'header.php';
$cronpassword = '';
$cronpassword = simplenewsletter_utils::getModuleOption('cron_password');

if(xoops_trim($cronpassword) == '') {
	exit("Password not defined");
}
if(isset($_GET['password'])) {
	if($cronpassword != $_GET['password']) {
		exit("Bad password");
	}
} else {
	exit("No password");
}

$h_simplenewsletter_news->sendWaitingNewsletters();
echo "<br />OK";
?>