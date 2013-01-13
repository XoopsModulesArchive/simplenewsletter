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
 * Bloc permettant de s'inscrire ou se désinscrire, selon l'état de l'utilisateur
 */
function b_sn_subscribe_show()
{
	global $xoopsConfig;
	require XOOPS_ROOT_PATH.'/modules/simplenewsletter/include/common.php';
	$block = array();
	$subscriptionState = simplenewsletter_handler::getInstance()->h_simplenewsletter_members->getSubscriptionState();
	$block['block_subscriptionState'] = $subscriptionState;
    $block['open_subscriptions'] = simplenewsletter_utils::getModuleOption('open_subscriptions');

    if($subscriptionState == _SIMPLENEWSLETTER_STATE_2) {    // Utilisateur enregistré inscrit
        $member = simplenewsletter_handler::getInstance()->h_simplenewsletter_members->getMemberSubscription();
        if($member !== null) {
            $block['member'] = $member->toArray('e');
        }
    } elseif(isset($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME])) {
        $member = unserialize($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME]);
        $block['member'] = $member->toArray('e');
    } else {
        $block['member'] =  '';
    }
    $block['uid'] =  simplenewsletter_utils::getCurrentUserID();
	return $block;
}
?>