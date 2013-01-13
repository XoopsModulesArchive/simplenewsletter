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
 * Gestion des mots de passe oubliés
 *
 * @since 2.0.2009.03.05
 */
require 'header.php';
require_once SIMPLENEWSLETTER_PATH.'class/Numeral.php';
$xoopsOption['template_main'] = 'simplenewsletter_forgotten.html';
require_once XOOPS_ROOT_PATH.'/header.php';
// Appel de jQuery et validate
$xoTheme->addScript(SIMPLENEWSLETTER_JS_URL.'jquery/jquery.js');
$xoTheme->addScript(SIMPLENEWSLETTER_JS_URL.'validate/jquery.validate.min.js');
$xoTheme->addStylesheet(SIMPLENEWSLETTER_CSS_URL.'subscription.css');
$baseurl = SIMPLENEWSLETTER_URL.basename(__FILE__);	// URL de ce script

if(isset($_POST['op']) && $_POST['op'] == 'send') {
    $email = isset($_POST['loginEmail']) ? $_POST['loginEmail'] : '';
    if($email == '') {
        simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR15, $baseurl, 4);
    }
    $member = null;
    $member = $simplenewsletter_handler->h_simplenewsletter_members->getMemberFromEmail($_POST['loginEmail']);
    if($member == null) {
        simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR16, $baseurl, 4);
    } else {
        $message = array();
        $message['MEMBER_USER_PASSWORD'] = $member->getVar('member_user_password');
        simplenewsletter_utils::sendEmailFromTpl('simplenewsletter_password_lost.tpl', $member->getVar('member_email'), _SIMPLENEWSLETTER_PASSWORD_LOST, $message);
        simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_PASSWORD_SENT, $baseurl, 4);
    }
}
simplenewsletter_utils::addRssMetaLink();
$title = _SIMPLENEWSLETTER_LOSTPASSWORD.' - '.simplenewsletter_utils::getModuleName();
simplenewsletter_utils::setMetas($title, $title);
require_once(XOOPS_ROOT_PATH.'/footer.php');
?>