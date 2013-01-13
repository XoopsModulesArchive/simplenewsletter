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
 * Vérification de l'inscription d'un anonyme
 *
 * @param string $code	Le code de validation
 * @since 2.0.2009.03.04
 */
require 'header.php';
// template
require_once XOOPS_ROOT_PATH.'/header.php';
$code = isset($_GET['code']) ? $_GET['code'] : '';

if($code == '') {
    simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR10, 'index.php', 4);
}

// Vérification du code
if($simplenewsletter_handler->h_simplenewsletter_members->isVerificationCodeExist($code)) {
    $simplenewsletter_handler->h_simplenewsletter_members->validateSubscriptionFromPassword($code);
    if(simplenewsletter_utils::getModuleOption('welcome_email')) {
        $member = null;
        $member = $simplenewsletter_handler->h_simplenewsletter_members->getMemberFromValidationCode($code);
        if(is_object($member)) {
            $simplenewsletter_handler->h_simplenewsletter_members->sayWelcome($member);
        }
    }
    simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_SUCCESSFULLY_SUBSCIBED, 'index.php', 2);
} else {
    simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR10, 'index.php', 4);
}
require_once(XOOPS_ROOT_PATH.'/footer.php');
?>