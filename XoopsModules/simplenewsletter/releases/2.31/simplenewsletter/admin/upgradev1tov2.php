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
 * Migration de la structure de la version 1 version la structure de la version 2
 */

if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

/**
 * Procédure chargée de migrer les données de la v1 vers la v2
 * @since 2.0.2009.02.26
 * @return void
 */
function migrateFromV1ToV2()
{
    global $xoopsDB;
    $simplenewsletter_members = $xoopsDB->prefix('simplenewsletter_members');
    $xoops_users = $xoopsDB->prefix('users');

    // Passage de uid vers member_uid
    echo '<br />'._AM_SIMPLENEWSLETTER_PROCESS.'1/4';
    $res = $xoopsDB->queryF("UPDATE $simplenewsletter_members SET member_uid = uid");
    if(!$res) {
        echo "<br />"._AM_SIMPLENEWSLETTER_PROCESS_ERROR;
    }

    // Marquage des inscriptions comme validées
    echo '<br />'._AM_SIMPLENEWSLETTER_PROCESS.'2/4';
    $xoopsDB->queryF("UPDATE $simplenewsletter_members SET member_verified = 1");
    if(!$res) {
        echo "<br />"._AM_SIMPLENEWSLETTER_PROCESS_ERROR;
    }

    // Récupération de l'adresse email depuis la table xoops_users
    echo '<br />'._AM_SIMPLENEWSLETTER_PROCESS.'3/4';
    $xoopsDB->queryF("UPDATE $simplenewsletter_members SET member_email = (SELECT email FROM $xoops_users WHERE $simplenewsletter_members.member_uid = $xoops_users.uid)");
    if(!$res) {
        echo "<br />"._AM_SIMPLENEWSLETTER_PROCESS_ERROR;
    }

    // Récupération de member_firstname à partir de uname (depuis la table xoops_users)
    echo '<br />'._AM_SIMPLENEWSLETTER_PROCESS.'4/4';
    $xoopsDB->queryF("UPDATE $simplenewsletter_members SET member_firstname = (SELECT uname FROM $xoops_users WHERE $simplenewsletter_members.member_uid = $xoops_users.uid)");
    if(!$res) {
        echo "<br />"._AM_SIMPLENEWSLETTER_PROCESS_ERROR;
    }

    // Fin du processus
    echo '<br />'._AM_SIMPLENEWSLETTER_PROCESS_END;
}
?>