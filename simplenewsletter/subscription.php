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
 * Affiche l'état de souscription de l'utilisateur
 */
require 'header.php';
require_once SIMPLENEWSLETTER_PATH.'class/Numeral.php';
$xoopsOption['template_main'] = 'simplenewsletter_subscription.html';
require_once XOOPS_ROOT_PATH.'/header.php';
// Appel de jQuery et validate
$xoTheme->addScript(SIMPLENEWSLETTER_JS_URL.'jquery/jquery.js');
$xoTheme->addScript(SIMPLENEWSLETTER_JS_URL.'validate/jquery.validate.min.js');
$xoTheme->addStylesheet(SIMPLENEWSLETTER_CSS_URL.'subscription.css');

$uid = simplenewsletter_utils::getCurrentUserID();
$baseurl = SIMPLENEWSLETTER_URL.basename(__FILE__);	// URL de ce script
$member = null;
$xoopsTpl->assign('additional_fields', simplenewsletter_utils::getModuleOption('additional_fields'));

if(isset($_GET['op'])) {
    switch ($_GET['op']) {
    	case 'unsubscribe':    // Demande de désinscription
            $xoopsTpl->assign('removeSubscription', true);
            $xoopsTpl->assign('securityToken', $GLOBALS['xoopsSecurity']->getTokenHTML());
        	break;

    	case 'logout':    // Déconnexion (d'un anonyme)
            if(isset($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME])) {
                $_SESSION[_SIMPLENEWSLETTER_SESSION_NAME] = null;
                unset($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME]);
                simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_YOU_ARE_DECONNECTED, $baseurl, 2);
            }
    	    break;
    }
}

if(isset($_POST['op'])) {
    switch ($_POST['op']) {
    	case 'confirmRemove':    // Confirmation de la désinscription à la newsletter
    	    if(!$GLOBALS['xoopsSecurity']->check()) {
    	        simplenewsletter_utils::redirect($GLOBALS['xoopsSecurity']->getErrors(), $baseurl, 4);
    	    } else {
    	        $res = false;
    	        if($uid != 0) {
                    $res = $simplenewsletter_handler->h_simplenewsletter_members->unSubscribeUser($uid);
    	        } else {
    	            if(isset($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME])) {
    	                $member = unserialize($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME]);
                        $res = $simplenewsletter_handler->h_simplenewsletter_members->unSubscribeAnonymousUser($member);
    	            }
    	        }
    	        if($res) {
    	            simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_YOU_ARE_UNSUBCRIBED, $baseurl, 1);
    	        } else {
    	            simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR9, $baseurl, 4);
    	        }
    	    }
        	break;

    	case 'login':    // Tentative de connexion d'un anonyme
    	    $mandatoryFields = array('loginEmail', 'loginPassword');
    	    if(!simplenewsletter_utils::mandatoryFields($mandatoryFields)) {
    	        simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR4, $baseurl, 4);
    	    }
    	    if($simplenewsletter_handler->h_simplenewsletter_members->isValidLogin($_POST['loginEmail'], $_POST['loginPassword'])) {
                $simplenewsletter_handler->h_simplenewsletter_members->loginAnonymousUser($_POST['loginEmail'], $_POST['loginPassword']);
                simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_SUCCESSFULLY_LOGGED, $baseurl, 1);
    	    } else {
    	        simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR14, $baseurl, 4);
    	    }
        	break;
    }
}

// Inscription à la newsletter
if(isset($_POST['btnGo']) && xoops_trim($_POST['btnGo']) != '' ) {
    if($uid > 0) {    // Utilisateur enregistré, on vérifie qu'il n'est pas déjà inscrit
   	    $mandatoryFields = array('member_firstname', 'member_email');
   	    if(!simplenewsletter_utils::mandatoryFields($mandatoryFields)) {
   	        simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR4, $baseurl, 4);
   	    }

        if($simplenewsletter_handler->h_simplenewsletter_members->isUserSubscribed($uid)) {    // Il est inscrit, on modifie ses informations
    	    $member = null;
            $member = $simplenewsletter_handler->h_simplenewsletter_members->getMemberSubscription($uid);
            if($simplenewsletter_handler->h_simplenewsletter_members->isEmailAlreadySubscribed($_POST['member_email'], $uid)) {
                simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR6, $baseurl, 4);
            }
            if(is_object($member)) {
                $member->setVar('member_firstname', $_POST['member_firstname']);
                $member->setVar('member_lastname', $_POST['member_lastname']);
                $member->setVar('member_email', $_POST['member_email']);
                if(simplenewsletter_utils::getModuleOption('additional_fields')) {
                    $member->setVar('member_title', isset($_POST['member_title']) ? $_POST['member_title'] : '');
                    $member->setVar('member_street', isset($_POST['member_street']) ? $_POST['member_street'] : '');
                    $member->setVar('member_city', isset($_POST['member_city']) ? $_POST['member_city'] : '');
                    $member->setVar('member_state', isset($_POST['member_state']) ? $_POST['member_state'] : '');
                    $member->setVar('member_zip', isset($_POST['member_zip']) ? $_POST['member_zip'] : '');
                    $member->setVar('member_telephone', isset($_POST['member_telephone']) ? $_POST['member_telephone'] : '');
                    $member->setVar('member_fax', isset($_POST['member_fax']) ? $_POST['member_fax'] : '');
                }
                $res = $simplenewsletter_handler->h_simplenewsletter_members->insert($member);
                if($res) {
                    simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_SUBSCRIPTION_MODIFY_OK, $baseurl, 2);
                } else {
                    simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR13, $baseurl, 4);
                }
            } else {    // Cas qui ne devrait pas arriver ...
                simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR5, $baseurl, 4);
            }
        } else {    // Utilisateur enregistré mais pas inscrit
    	    $member = null;
    	    if($simplenewsletter_handler->h_simplenewsletter_members->isEmailAlreadySubscribed($_POST['member_email'])) {
    	        simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR6, $baseurl, 4);
    	    }
            $res = $simplenewsletter_handler->h_simplenewsletter_members->subscribeUser($uid);
            if($res) {
                simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_SUBSCRIPTION_OK, $baseurl, 2);
            } else {
                simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR12, $baseurl, 4);
            }
        }
    } else {    // Utilisateur anonyme
        // Vérification des champs obligatoires
        $mandatoryFields = array('member_firstname', 'member_email');
        if(simplenewsletter_utils::getModuleOption('use_captcha') && !isset($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME])) {
            $mandatoryFields[] = 'captcha';
        }
        if(!isset($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME])) {    // L'anonyme n'est pas connecté donc on lui demande son mot de passe
            $mandatoryFields[] = 'member_user_password';
            $mandatoryFields[] = 'member_user_password_confirm';
        }
   	    if(!simplenewsletter_utils::mandatoryFields($mandatoryFields)) {
   	        simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR4, $baseurl, 4);
   	    }
        if(!isset($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME])) {    // Inscription
            // Vérification que mot de passe 1 = mot de passe 2 (si l'anonyme n'est pas connecté)
            if(xoops_trim($_POST['member_user_password']) != xoops_trim($_POST['member_user_password_confirm'])) {
                simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR7, $baseurl, 4);
            }
            // Vérification du CAPTCHA
            if(simplenewsletter_utils::getModuleOption('use_captcha')) {
                if ($_POST['captcha'] != $_SESSION['simplenewsletter_answer']) {
                    simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR8, $baseurl, 4);
                }
            }
            if($simplenewsletter_handler->h_simplenewsletter_members->isEmailAlreadySubscribed($_POST['member_email'])) {
                simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR6, $baseurl, 4);
            }
            // Arrivé là tout est bon
            $member = null;
            $res = $simplenewsletter_handler->h_simplenewsletter_members->subscribeAnonymous();
            if($res) {
                $additional = '';
                if(simplenewsletter_utils::getModuleOption('auto_approve') == 0) {
                    $additional = '<br />'._SIMPLENEWSLETTER_SUBSCRIPTION_MUST_VALIDATE;
                }
                simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_SUBSCRIPTION_OK.$additional, $baseurl, 2);
            } else {
                simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR12, $baseurl, 4);
            }
        } else {    // Modification des informations de l'anonyme
            $member = unserialize($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME]);
            if($simplenewsletter_handler->h_simplenewsletter_members->isEmailAlreadySubscribed($_POST['member_email'], 0, $member->getVar('member_user_password'))) {
                simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR6, $baseurl, 4);
            }
            $member->setVar('member_firstname', $_POST['member_firstname']);
            $member->setVar('member_lastname', $_POST['member_lastname']);
            $member->setVar('member_email', $_POST['member_email']);
            if(simplenewsletter_utils::getModuleOption('additional_fields')) {
                $member->setVar('member_title', isset($_POST['member_title']) ? $_POST['member_title'] : '');
                $member->setVar('member_street', isset($_POST['member_street']) ? $_POST['member_street'] : '');
                $member->setVar('member_city', isset($_POST['member_city']) ? $_POST['member_city'] : '');
                $member->setVar('member_state', isset($_POST['member_state']) ? $_POST['member_state'] : '');
                $member->setVar('member_zip', isset($_POST['member_zip']) ? $_POST['member_zip'] : '');
                $member->setVar('member_telephone', isset($_POST['member_telephone']) ? $_POST['member_telephone'] : '');
                $member->setVar('member_fax', isset($_POST['member_fax']) ? $_POST['member_fax'] : '');
            }
            if(isset($_POST['member_user_password']) && xoops_trim($_POST['member_user_password']) != '') {
                $member->setVar('member_user_password', $_POST['member_user_password']);
            }
            $res = $simplenewsletter_handler->h_simplenewsletter_members->insert($member);
            if($res) {
                $simplenewsletter_handler->h_simplenewsletter_members->reloadAnonymousInformation($member);
                simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_SUBSCRIPTION_MODIFY_OK, $baseurl, 2);
            } else {
                simplenewsletter_utils::redirect(_SIMPLENEWSLETTER_ERROR13, $baseurl, 4);
            }
        }
    }
}

$subscriptionState = simplenewsletter_handler::getInstance()->h_simplenewsletter_members->getSubscriptionState();
$xoopsTpl->assign('subscriptionState', $subscriptionState);

$xoopsTpl->assign('baseurl', $baseurl);
$xoopsTpl->assign('uid', simplenewsletter_utils::getCurrentUserID());
$xoopsTpl->assign('open_subscriptions', simplenewsletter_utils::getModuleOption('open_subscriptions'));
$xoopsTpl->assign('password_length', simplenewsletter_utils::getModuleOption('password_length'));

$lenghtMessage = sprintf(_SIMPLENEWSLETTER_REQUIRED_PLEASE3, intval(simplenewsletter_utils::getModuleOption('password_length')));
$xoopsTpl->assign('password_length_message', $lenghtMessage);

if($subscriptionState == _SIMPLENEWSLETTER_STATE_2) {    // Utilisateur enregistré inscrit
    $member = simplenewsletter_handler::getInstance()->h_simplenewsletter_members->getMemberSubscription();
    if($member !== null) {
        $xoopsTpl->assign('member', $member->toArray('e'));
    }
} elseif(isset($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME])) {
    $member = unserialize($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME]);
    $xoopsTpl->assign('member', $member->toArray('e'));
} else {
    $member = array();
    if($uid > 0) {
        $member['member_email'] = $xoopsUser->getVar('email');
    }
    $xoopsTpl->assign('member', $member);
}

if(simplenewsletter_utils::getModuleOption('use_captcha')) {
    $numcap = new simplenewsletter_Text_CAPTCHA_Numeral;
    $_SESSION['simplenewsletter_answer'] = $numcap->getAnswer();
    $xoopsTpl->assign('with_captcha', true);
    $xoopsTpl->assign('captcha_operation', $numcap->getOperation());
}

$title = _SIMPLENEWSLETTER_STATE.' - '.simplenewsletter_utils::getModuleName();
simplenewsletter_utils::setMetas($title, $title);
simplenewsletter_utils::addRssMetaLink();
require_once(XOOPS_ROOT_PATH.'/footer.php');
?>