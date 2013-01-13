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
 * @link 			http://xoops.instant-zero.com/
 *
 * Version : $Id:
 * ****************************************************************************
 */

include_once("admin_header.php");
require_once '../../../include/cp_header.php';
require_once '../include/common.php';

require_once SIMPLENEWSLETTER_PATH.'admin/functions.php';
require_once XOOPS_ROOT_PATH.'/class/tree.php';
require_once XOOPS_ROOT_PATH.'/class/pagenav.php';
require_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

$op = 'default';
if (isset($_POST['op'])) {
	$op = $_POST['op'];
} else {
	if ( isset($_GET['op'])) {
    	$op = $_GET['op'];
	}
}

$baseurl = SIMPLENEWSLETTER_URL.'admin/'.basename(__FILE__);	// URL de ce script
$limit = simplenewsletter_utils::getModuleOption('perpage');	// Nombre maximum d'éléments à afficher
$paquets = simplenewsletter_utils::getModuleOption('paquet_size');	// Envoyer par paquets de ...
simplenewsletter_utils::prepareFolder(simplenewsletter_utils::getModuleOption('attach_path').DIRECTORY_SEPARATOR);
simplenewsletter_utils::prepareFolder(SIMPLENEWSLETTER_CACHE_PATH);
$mainAdmin = new ModuleAdmin();

/**
 * Affichage du pied de page de l'administration
 *
 * PLEASE, KEEP THIS COPYRIGHT *INTACT* !
 */
function show_footer()
{
	echo "<br /><br /><div align='center'><a href='http://www.instant-zero.com' target='_blank' title='Instant Zero'><img src='../images/instantzero.gif' alt='Instant Zero' /></a></div>";
}

global $xoopsConfig;
simplenewsletter_utils::loadLanguageFile('modinfo.php');

/**
 * Vérification de l'existance des nouveaux champs et nouvelles tables
 */
$simplenewsletter_sent = $xoopsDB->prefix('simplenewsletter_sent');
if(!simplenewsletter_tableExists($simplenewsletter_sent)) {
    $sql = "CREATE TABLE `$simplenewsletter_sent` (
              `sent_id` int(10) unsigned NOT NULL auto_increment,
              `sent_news_id` int(10) unsigned NOT NULL,
              `sent_uid` int(8) unsigned NOT NULL,
              PRIMARY KEY  (`sent_id`),
              KEY `new_uid` (`sent_news_id`,`sent_uid`),
              KEY `sent_uid` (`sent_uid`),
              KEY `sent_news_id` (`sent_news_id`)
        ) ENGINE=InnoDB;";
    $xoopsDB->queryF($sql);
}

// Modification apportées à la table des newsletters
$simplenewsletter_news = $xoopsDB->prefix('simplenewsletter_news');
if(!simplenewsletter_fieldExists('news_members_sent', $simplenewsletter_news)) {
    simplenewsletter_addField('`news_members_sent` INT UNSIGNED NOT NULL', $simplenewsletter_news);
}
if(!simplenewsletter_fieldExists('news_attachment', $simplenewsletter_news)) {
    simplenewsletter_addField('`news_attachment` VARCHAR( 255 ) NOT NULL', $simplenewsletter_news);
}
if(!simplenewsletter_fieldExists('news_mime', $simplenewsletter_news)) {
    simplenewsletter_addField('`news_mime` VARCHAR( 255 ) NOT NULL', $simplenewsletter_news);
}

// Modifications apportées sur la table des inscrits (version 2.0)
$simplenewsletter_members = $xoopsDB->prefix('simplenewsletter_members');
if(!simplenewsletter_fieldExists('member_uid', $simplenewsletter_members)) {
    simplenewsletter_addField('`member_uid` MEDIUMINT( 8 ) UNSIGNED NOT NULL', $simplenewsletter_members);
    simplenewsletter_addField('`member_firstname` VARCHAR( 255 ) NOT NULL', $simplenewsletter_members);
    simplenewsletter_addField('`member_lastname` VARCHAR( 255 ) NOT NULL', $simplenewsletter_members);
    simplenewsletter_addField('`member_password` VARCHAR( 32 ) NOT NULL', $simplenewsletter_members);
    simplenewsletter_addField('`member_verified` TINYINT( 1 ) UNSIGNED NOT NULL', $simplenewsletter_members);
    simplenewsletter_addField('`member_email` VARCHAR( 255 ) NOT NULL', $simplenewsletter_members);
    simplenewsletter_addField('`member_temporary` TINYINT( 1 ) UNSIGNED NOT NULL', $simplenewsletter_members);
    simplenewsletter_addField('`member_user_password` VARCHAR( 32 ) NOT NULL', $simplenewsletter_members);
    // Ajout de l'index
    $xoopsDB->queryF("ALTER TABLE $simplenewsletter_members ADD INDEX ( `member_verified` )");
    // Passage de uid en auto incrément
    $xoopsDB->queryF("ALTER TABLE `$simplenewsletter_members` CHANGE `uid` `uid` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT");
    // Migration des données
    require 'upgradev1tov2.php';
    migrateFromV1ToV2();
    $op = 'maintain';    // On force la mise à jour du cache de requêtes
}

if(!simplenewsletter_fieldExists('member_title', $simplenewsletter_members)) {
    simplenewsletter_addField('`member_title` VARCHAR( 50 ) NOT NULL', $simplenewsletter_members);
    simplenewsletter_addField('`member_street` VARCHAR( 255 ) NOT NULL', $simplenewsletter_members);
    simplenewsletter_addField('`member_city` VARCHAR( 255 ) NOT NULL', $simplenewsletter_members);
    simplenewsletter_addField('`member_state` VARCHAR( 100 ) NOT NULL', $simplenewsletter_members);
    simplenewsletter_addField('`member_zip` VARCHAR( 50 ) NOT NULL', $simplenewsletter_members);
    simplenewsletter_addField('`member_telephone` VARCHAR( 50 ) NOT NULL', $simplenewsletter_members);
    simplenewsletter_addField('`member_fax` VARCHAR( 50 ) NOT NULL', $simplenewsletter_members);
    $op = 'maintain';    // On force la mise à jour du cache de requêtes
}
// **********************************************************************************************************

$tempMember = new simplenewsletter_members();
$variables = $tempMember->getUsableVariables();
$additional = '<br /><br />'._AM_SIMPLENEWSLETTER_YOU_CAN_USE."<br /><div style='height: 150px;  overflow: auto;'>".implode('<br />', $variables).'</div>';
unset($tempMember);

/**
 * Ajout ou édition d'un membre à la newsletter
 *
 * @param integer $uid
 * @return void
 */
function addEditMember($uid)
{
    global $baseurl;
    $uid = intval($uid);
    $item = null;
    if($uid == 0) {    // Création
        $label_submit = _AM_SIMPLENEWSLETTER_ADD;
        $item = simplenewsletter_handler::getInstance()->h_simplenewsletter_members->create(true);
        $edit = false;
    } else {    // Edtion
        $label_submit = _AM_SIMPLENEWSLETTER_MODIFY;
		$item = simplenewsletter_handler::getInstance()->h_simplenewsletter_members->get($uid);
		$edit = true;
		if(!is_object($item)) {
		    simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_NOT_FOUND, 'index.php', 5);
		}
    }
	$sform = new XoopsThemeForm(_SIMPLENEWSLETTER_SUBSCRIBE_MEMBER, 'subscribemember', $baseurl);
	$sform->addElement(new XoopsFormHidden('op', 'subscribemember'));
	$sform->addElement(new XoopsFormHidden('uid', $uid));
	$include_anon = false;
	if(simplenewsletter_utils::getModuleOption('open_subscriptions') == 1) {
	    $include_anon = true;
	}
	$sform->addElement(new XoopsFormSelectUser(_USERNAME, 'member_uid', $include_anon, $item->getVar('member_uid', 'e')), true);
	$sform->addElement(new XoopsFormText(_SIMPLENEWSLETTER_FIRST_NAME, 'member_firstname', 50, 255, $item->getVar('member_firstname', 'e')), true);
	$sform->addElement(new XoopsFormText(_SIMPLENEWSLETTER_LAST_NAME, 'member_lastname', 50, 255, $item->getVar('member_lastname', 'e')), false);
	$sform->addElement(new XoopsFormText(_SIMPLENEWSLETTER_EMAIL, 'member_email', 50, 255, $item->getVar('member_email', 'e')), true);
	// Champs supplémentaires
	if(simplenewsletter_utils::getModuleOption('additional_fields')) {
        $sform->addElement(new XoopsFormText(_SIMPLENEWSLETTER_MEMBER_TITLE, 'member_title', 50, 50, $item->getVar('member_title', 'e')), false);
        $sform->addElement(new XoopsFormText(_SIMPLENEWSLETTER_MEMBER_STREET, 'member_street', 50, 255, $item->getVar('member_street', 'e')), false);
        $sform->addElement(new XoopsFormText(_SIMPLENEWSLETTER_MEMBER_ZIP, 'member_zip', 50, 50, $item->getVar('member_zip', 'e')), false);
        $sform->addElement(new XoopsFormText(_SIMPLENEWSLETTER_MEMBER_CITY, 'member_city', 50, 255, $item->getVar('member_city', 'e')), false);
        $sform->addElement(new XoopsFormText(_SIMPLENEWSLETTER_MEMBER_STATE, 'member_state', 50, 100, $item->getVar('member_state', 'e')), false);
        $sform->addElement(new XoopsFormText(_SIMPLENEWSLETTER_MEMBER_TELEPHONE, 'member_telephone', 50, 50, $item->getVar('member_telephone', 'e')), false);
        $sform->addElement(new XoopsFormText(_SIMPLENEWSLETTER_MEMBER_FAX, 'member_fax', 50, 50, $item->getVar('member_fax', 'e')), false);
	}
	if($include_anon) {
	    $sform->addElement(new XoopsFormText(_SIMPLENEWSLETTER_PASSWORD, 'member_user_password', 50, 255, $item->getVar('member_user_password', 'e')), false);
	    if(simplenewsletter_utils::getModuleOption('auto_approve') == 0) {
	        $sform->addElement(new XoopsFormRadioYN(_AM_SIMPLENEWSLETTER_VERIFIED, 'member_verified', $item->getVar('member_verified', 'e')), true);
	    }
	}
	$button_tray = new XoopsFormElementTray('' ,'');
	$submit_btn = new XoopsFormButton('', 'post', $label_submit, 'submit');
	$button_tray->addElement($submit_btn);
	$sform->addElement($button_tray, false);
	$sform = simplenewsletter_utils::formMarkRequiredFields($sform);
	$sform->display();
}

switch($op)
{
    // ****************************************************************************************************************
    case 'default':            // Création d'une newsletter
    case 'editnewsletter':     // Edition d'une newsletter
    // ****************************************************************************************************************
        xoops_cp_header();
    echo $mainAdmin->addNavigation('main.php?op=default');
		if($op == 'editnewsletter') {
		    //simplenewsletter_adminMenu(1);
			$title = _AM_SIMPLENEWSLETTER_NEWS_EDIT;
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			if(empty($id)) {
			    simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_ERROR_1, $baseurl, 5);
			}
			// Item exits ?
			$item = null;
			$item = $h_simplenewsletter_news->get($id);
			if(!is_object($item)) {
				references_utils::redirect(_AM_SIMPLENEWSLETTER_NOT_FOUND, $baseurl, 5);
			}
			$edit = true;
			$label_submit = _AM_SIMPLENEWSLETTER_MODIFY;
		} else {
            //simplenewsletter_adminMenu(0);
			$title = _AM_SIMPLENEWSLETTER_NEWS_CREATE;
			$item = $h_simplenewsletter_news->create(true);
			$label_submit = _AM_SIMPLENEWSLETTER_ADD;
			$edit = false;
			$item->setVar('news_paquets', $paquets);
		}
        $sform = new XoopsThemeForm($title, 'frmnews', $baseurl);
        $sform->setExtra('enctype="multipart/form-data"');
        $sform->addElement(new XoopsFormHidden('op', 'send'));
        $sform->addElement(new XoopsFormHidden('news_id', $item->getVar('news_id', 'e')));
        $sform->addElement(new XoopsFormText(_SIMPLENEWSLETTER_NEWS_TITLE, 'news_title',50,255, $item->getVar('news_title', 'e')), true);
		$editor = simplenewsletter_utils::getWysiwygForm(_AM_SIMPLENEWSLETTER_NEWS_BODY.$additional, 'news_body', $item->getVar('news_body', 'e'), 15, 60, 'news_body_hidden');
		if($editor) {
			$sform->addElement($editor, false);
		}
		if(simplenewsletter_utils::getModuleOption('use_tags') && simplenewsletter_utils::tagModuleExists()) {
            require_once XOOPS_ROOT_PATH.'/modules/tag/include/formtag.php';
            $sform->addElement(new XoopsFormTag('item_tag', 60, 255, $item->getVar('news_id'), 0));
		}
		$sform->addElement(new XoopsFormRadioYN(_AM_SIMPLENEWSLETTER_SEND_HTML, 'news_html', $item->getVar('news_html', 'e')), true);
		$sform->addElement(new XoopsFormRadioYN(_AM_SIMPLENEWSLETTER_SEND_TEST, 'send_test', 0), true);
		$sform->addElement(new XoopsFormText(_AM_SIMPLENEWSLETTER_SEND_PAQUET, 'news_paquets', 5, 5, $item->getVar('news_paquets', 'e')), true);
		$sform->addElement(new XoopsFormFile(_SIMPLENEWSLETTER_ATTACHED_FILE, 'attachedfile1', simplenewsletter_utils::getModuleOption('maxuploadsize')), false);

		$button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', _AM_SIMPLENEWSLETTER_SEND, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray, false);

		$sform = simplenewsletter_utils::formMarkRequiredFields($sform);
		$sform->display();
        //show_footer();
    include_once("admin_footer.php");
        break;

	// ****************************************************************************************************************
    case 'send':	// Prévisualisation de la newsletter avant de l'envoyer
    // ****************************************************************************************************************
        xoops_cp_header();
        //simplenewsletter_adminMenu(0);

        $myts = &MyTextSanitizer::getInstance();
        if(simplenewsletter_utils::getModuleOption('use_tags') && simplenewsletter_utils::tagModuleExists()) {
            $news_tags = '';
            if(isset($_POST['item_tag'])) {
                $news_tags = $myts->stripSlashesGPC($_POST['item_tag']);
            }
        }

		$item = $h_simplenewsletter_news->create(true);
		$item->setVar('news_title', $myts->stripSlashesGPC($_POST['news_title']));
        $item->setVar('news_body', $myts->stripSlashesGPC($_POST['news_body']));
        $item->setVar('news_html', intval($_POST['news_html']));
        $item->setVar('news_paquets', intval($_POST['news_paquets']));
        $item->setVar('news_id', intval($_POST['news_id']));

        $sform = new XoopsThemeForm(_AM_SIMPLENEWSLETTER_NEWS_PREVIEW, 'frmnewspreview', $baseurl);

        $destname = $uploadedFile = $uploaderMimetype = $uploaderRealType = '';
		$res1 = simplenewsletter_utils::uploadFile(0, simplenewsletter_utils::getModuleOption('attach_path'));
		if($res1 === true) {
			$uploadedFile = basename($destname);
		    $item->setVar('news_attachment', $uploadedFile);
		    $mimetype = xoops_trim(simplenewsletter_utils::getMimeType(simplenewsletter_utils::getModuleOption('attach_path').DIRECTORY_SEPARATOR.basename($destname)));
		    if(xoops_trim($mimetype) == '') {
		        $mimetype = $uploaderRealType;
		        if(xoops_trim($mimetype) == '') {
                    $mimetype = $uploaderMimetype;
		        }
		    }
		    $item->setVar('news_mime', $mimetype);
		    $sform->addElement(new XoopsFormHidden('news_mime', $mimetype));
   		}

   		if(isset($_POST['send_test']) && intval($_POST['send_test']) == 1) {    // Envoi pour test
            $emailsList = simplenewsletter_utils::getEmailsFromGroup(XOOPS_GROUP_ADMIN);
    		if(function_exists('xoops_getMailer')) {
	    		$xoopsMailer =& xoops_getMailer();
    		} else {
		    	$xoopsMailer =& getMailer();
    		}
    		require_once XOOPS_ROOT_PATH.'/class/template.php';
		    $xoopsMailer->reset();
		    $xoopsMailer->useMail();
		    $xoopsMailer->setFromEmail(simplenewsletter_utils::getModuleOption('sender_email'));
		    $xoopsMailer->setFromName(simplenewsletter_utils::getModuleOption('sender_name'));
		    $xoopsMailer->setSubject($item->getVar('news_title', 'n'));
		    $body = $item->getVar('news_body');
		    if($item->getVar('news_html') == 1) {
		        $Tpl = new XoopsTpl();
		        $Tpl->assign('title', $item->getVar('news_title'));
		        $Tpl->assign('body', $body);
		        $body = $Tpl->fetch('db:simplenewsletter_html_model.html');
		    }
		    $xoopsMailer->setBody($body);
		    if(xoops_trim($item->getVar('news_attachment')) != '') {
		        $xoopsMailer->multimailer->ClearAttachments();
		        $attachedFile = simplenewsletter_utils::getModuleOption('attach_path').DIRECTORY_SEPARATOR.xoops_trim($item->getVar('news_attachment'));
		        $xoopsMailer->multimailer->AddAttachment($attachedFile, _SIMPLENEWSLETTER_FILE, 'base64', $item->getVar('news_mime'));
		    }
		    $handlers = simplenewsletter_handler::getInstance();
//		    $variables = array();
//		    $variables = $handlers->h_simplenewsletter_members->getMemberVariablesForTemplate($member);
//	        if(is_array($variables) && count($variables) > 0) {
//		        foreach($variables as $key => $value) {
//			        $xoopsMailer->assign($key, $value);
//		        }
//	        }
		    if($item->getVar('news_html')) {
				$xoopsMailer->multimailer->isHTML(true);
		    }
		    $xoopsMailer->setToEmails($emailsList);
		    $xoopsMailer->send();
   		}

        $sform->addElement(new XoopsFormHidden('op', 'sendpaquets'));
        $sform->addElement(new XoopsFormHidden('news_id', $item->getVar('news_id')));
        if(xoops_trim($uploadedFile) != '') {
            $sform->addElement(new XoopsFormHidden('news_attachment', $uploadedFile));
            $sform->addElement(new XoopsFormLabel(_SIMPLENEWSLETTER_FILE, $uploadedFile), false);
        }

        $sform->addElement(new XoopsFormLabel(_SIMPLENEWSLETTER_NEWS_TITLE, $item->getVar('news_title')), false);
        $sform->addElement(new XoopsFormLabel(_AM_SIMPLENEWSLETTER_NEWS_BODY, $item->getVar('news_body')), false);

        $sform->addElement(new XoopsFormText(_SIMPLENEWSLETTER_NEWS_TITLE, 'news_title',50,255, $item->getVar('news_title', 'e')), true);
		$editor = simplenewsletter_utils::getWysiwygForm(_AM_SIMPLENEWSLETTER_NEWS_BODY, 'news_body', $item->getVar('news_body', 'e'), 15, 60, 'news_body_hidden');
		if(simplenewsletter_utils::getModuleOption('use_tags') && simplenewsletter_utils::tagModuleExists()) {
		    require_once XOOPS_ROOT_PATH.'/modules/tag/include/formtag.php';
		    $sform->addElement(new XoopsFormTag('item_tag', 60, 255, $news_tags, 0));
		}
		if($editor) {
			$sform->addElement($editor, false);
		}
		$sform->addElement(new XoopsFormRadioYN(_AM_SIMPLENEWSLETTER_SEND_HTML, 'news_html', $item->getVar('news_html', 'e')), true);
		$sform->addElement(new XoopsFormText(_AM_SIMPLENEWSLETTER_SEND_PAQUET, 'news_paquets', 5, 5, $item->getVar('news_paquets', 'e')), true);


		$button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', _AM_SIMPLENEWSLETTER_SEND, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray, false);
		$sform = simplenewsletter_utils::formMarkRequiredFields($sform);
		$sform->display();
        //show_footer();
        include_once("admin_footer.php");
        break;

	// ****************************************************************************************************************
    case 'sendpaquets':	// Sauvegarde de la newsletter ...
    // ****************************************************************************************************************
        xoops_cp_header();
        //simplenewsletter_adminMenu(0);
		$id = isset($_POST['news_id']) ? intval($_POST['news_id']) : 0;
		$opRedirect = 'default';
		if(!empty($id)) {
			$edit = true;
			$item = $h_simplenewsletter_news->get($id);
			if(!is_object($item)) {
				simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_NOT_FOUND, $baseurl, 5);
			}
			$item->unsetNew();
		} else {
			$edit = false;
			$item= $h_simplenewsletter_news->create(true);
		}
		$item->setVars($_POST);
		$item->setVar('news_members_sent', 0);
		if(!$edit) {
            $item->setVar('news_uid', simplenewsletter_utils::getCurrentUserID());
            $item->setVar('news_sent', _SIMPLENEWSLETTER_NEWSLETTER_NOTSENT);
            $item->setVar('news_date', time());
		}

		$res = $h_simplenewsletter_news->insert($item);
		if($res) {
		    if(simplenewsletter_utils::getModuleOption('use_tags') && simplenewsletter_utils::tagModuleExists()) {
                $tag_handler = xoops_getmodulehandler('tag', 'tag');
                $tag_handler->updateByItem($_POST['item_tag'], $item->getVar('news_id'), $xoopsModule->getVar('dirname'), 0);
		    }
			simplenewsletter_utils::updateCache();
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_OK, $baseurl.'?op='.$opRedirect, 2);
		} else {
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_PB, $baseurl.'?op='.$opRedirect,5);
		}
    	break;

    // ****************************************************************************************************************
    case 'texts':    // Gestion des textes
    // ****************************************************************************************************************
    	xoops_cp_header();
        //$mainAdmin = new ModuleAdmin();
        echo $mainAdmin->addNavigation('main.php?op=texts');
    	//simplenewsletter_adminMenu(4);
    	require_once SIMPLENEWSLETTER_PATH.'class/registryfile.php';
		$registry = new simplenewsletter_registryfile();
		$sform = new XoopsThemeForm(_AM_SIMPLENEWSLETTER_MESSAGE3, 'frmatxt', $baseurl);
		$sform->addElement(new XoopsFormHidden('op', 'savetexts2'));
		// Message de bienvenue
		$editor1 = simplenewsletter_utils::getWysiwygForm(_AM_SIMPLENEWSLETTER_MESSAGE3, 'text1', $registry->getfile(SIMPLENEWSLETTER_MESSAGE3_PATH), 5, 60, 'hometext1_hidden');
		if($editor1) {
			$sform->addElement($editor1, false);
		}

		$button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', _AM_SIMPLENEWSLETTER_SAVE, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray, false);
		$sform = simplenewsletter_utils::formMarkRequiredFields($sform);
		$sform->display();
		//show_footer();
        include_once("admin_footer.php");
		break;

    // ****************************************************************************************************************
    case 'savetexts2':    // Enregistrement texte à afficher sur la page d'accueil
    // ****************************************************************************************************************
		xoops_cp_header();
    	require_once SIMPLENEWSLETTER_PATH.'class/registryfile.php';
    	$myts = &MyTextSanitizer::getInstance();
		$registry = new simplenewsletter_registryfile();
		$registry->savefile($myts->stripSlashesGPC($_POST['text1']), SIMPLENEWSLETTER_MESSAGE3_PATH);
		simplenewsletter_utils::updateCache();
		simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_OK, $baseurl.'?op=texts', 2);
		break;


    // ****************************************************************************************************************
    case 'old':    // Anciennes newsletter
    // ****************************************************************************************************************
    	xoops_cp_header();
        echo $mainAdmin->addNavigation('main.php?op=old');
    	//simplenewsletter_adminMenu(1);
    	$class = '';
    	$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
		$itemsCount = $simplenewsletter_handler->h_simplenewsletter_news->getCount();
		$items = $simplenewsletter_handler->h_simplenewsletter_news->getLastNews($start, $limit, 'news_date', 'DESC', false);
		if($itemsCount > $limit) {
			$pagenav = new XoopsPageNav($itemsCount, $limit, $start, 'start', 'op=old');
		}
		if(isset($pagenav) && is_object($pagenav)) {
			echo "<div align='center'>".$pagenav->renderNav()."</div>";
		}
		echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
		echo "<tr>";
		echo "<th align='center'>"._SIMPLENEWSLETTER_ID."</th><th align='center'>"._SIMPLENEWSLETTER_DATE."</th><th align='center'>"._SIMPLENEWSLETTER_NEWS_TITLE."</th><th align='center'>"._AM_SIMPLENEWSLETTER_STATUS."</th><th align='center'>"._AM_SIMPLENEWSLETTER_SENDED."</th><th align='center'>"._AM_SIMPLENEWSLETTER_ACTION."</th>\n";
		echo "</tr>";
		$confDeleteNewsletter = simplenewsletter_utils::javascriptLinkConfirm(_AM_SIMPLENEWSLETTER_DELETE_NEWSLETTER);
		foreach($items as $item) {
			$class = ($class == 'even') ? 'odd' : 'even';
			$id = $item->news_id;
			if($item->getVar('news_sent') == _SIMPLENEWSLETTER_NEWSLETTER_SENT) {
			    $status = _AM_SIMPLENEWSLETTER_STATUS_SENT;
			    $action = "<a href='".$baseurl."?op=launch&id=".$id."'>"._AM_SIMPLENEWSLETTER_RELAUNCH."</a>";
			} else {
			    $status = _AM_SIMPLENEWSLETTER_STATUS_NOTSENT;
                $action = "<a href='".$baseurl."?op=stop&id=".$id."'>"._AM_SIMPLENEWSLETTER_STOP."</a>";
			}
			$actionDelete = "<a href='".$baseurl."?op=delete&id=".$id."' $confDeleteNewsletter>"._DELETE."</a>";
			$actionEdit = "<a href='".$baseurl."?op=editnewsletter&id=".$id."'>"._EDIT."</a>";

			echo "<tr class='".$class."'>\n";
			echo "<td align='center'>".$id."</td>";
			echo "<td align='center'>".$item->getFormatedDate()."</td>";
			echo "<td><a href='".$item->getUrl()."'>".$item->news_title."</a></td>";
			echo "<td align='center'>".$status."</td>";
			echo "<td align='right'>".$item->getVar('news_members_sent')."</td>";
			echo "<td align='center'>".$action.' - '.$actionDelete.' - '.$actionEdit."</td>";
			echo "</tr>\n";
		}
		echo "</table>\n";
		if(isset($pagenav) && is_object($pagenav)) {
			echo "<div align='center'>".$pagenav->renderNav()."</div>";
		}
		//show_footer();
        include_once("admin_footer.php");
		break;

	// ****************************************************************************************************************
    case 'stop':      // Arrêter l'envoi d'une newsletter
    case 'launch':    // Renvoyer une newsletter
    case 'delete':    // Suppression d'une newsletter
    // ****************************************************************************************************************
    	xoops_cp_header();
    	//simplenewsletter_adminMenu(1);
    	$opRedirect = 'old';
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(empty($id)) {
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_ERROR_1, $baseurl, 5);
		}
		$newsletter = null;
        $newsletter = $h_simplenewsletter_news->get($id);
        if(!is_object($newsletter)) {
            simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_NOT_FOUND, $baseurl.'?op='.$opRedirect,5);
        }
        if($op == 'stop') {
            $h_simplenewsletter_members->stopSending($newsletter);
            $h_simplenewsletter_news->stopSendingMe($newsletter);
        } elseif($op == 'launch') {
            $h_simplenewsletter_news->startSendingMe($newsletter);
            $h_simplenewsletter_members->startSending($newsletter);
        } elseif($op == 'delete') {
            $h_simplenewsletter_news->delete($newsletter, true);
            $h_simplenewsletter_members->deleteSent($newsletter);
        }
		simplenewsletter_utils::updateCache();
		simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_OK, $baseurl.'?op='.$opRedirect, 2);
        break;

    // ****************************************************************************************************************
    case 'deletemember':    // Suppression de l'inscription d'un membre
    // ****************************************************************************************************************
    	xoops_cp_header();
    	//simplenewsletter_adminMenu(2);
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(empty($id)) {
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_ERROR_1, $baseurl, 5);
		}
		$opRedirect = 'members';
		$item = null;
		$item = $simplenewsletter_handler->h_simplenewsletter_members->get($id);
		if(is_object($item)) {
		    $res = $simplenewsletter_handler->h_simplenewsletter_members->unSubscribeFromObject($item);
			if($res) {
				simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_OK, $baseurl.'?op='.$opRedirect,2);
			} else {
				simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_PB, $baseurl.'?op='.$opRedirect,5);
			}
		} else {
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_NOT_FOUND, $baseurl.'?op='.$opRedirect,5);
		}
    	break;

    // ****************************************************************************************************************
    case 'subscribeall':    // Inscrire tous les membres du site
    // ****************************************************************************************************************
    	xoops_cp_header();
    	//simplenewsletter_adminMenu(2);
    	$opRedirect = 'members';
        @set_time_limit(0);
        $res = $h_simplenewsletter_members->subscribeAll();
		if($res) {
		    $h_simplenewsletter_members->forceCacheClean();
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_OK, $baseurl.'?op='.$opRedirect, 2);
		} else {
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_PB, $baseurl.'?op='.$opRedirect, 5);
		}
        break;

    // ****************************************************************************************************************
    case 'unsubscribeall':    // Désinscription de tous les membres
    // ****************************************************************************************************************
    	xoops_cp_header();
    	//simplenewsletter_adminMenu(2);
    	$opRedirect = 'members';
        $res = $h_simplenewsletter_members->unsubscribeAll();
		if($res) {
		    $h_simplenewsletter_members->forceCacheClean();
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_OK, $baseurl.'?op='.$opRedirect, 2);
		} else {
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_PB, $baseurl.'?op='.$opRedirect, 5);
		}
        break;

    // ****************************************************************************************************************
    case 'exportmembers':    // Exporter la liste des membres
    // ****************************************************************************************************************
    	xoops_cp_header();
    	//simplenewsletter_adminMenu(2);
		$csvFilename = 'simplenewsletter_members.csv';
		$fp = fopen(XOOPS_UPLOAD_PATH.'/'.$csvFilename,'w');
		if($fp) {
			// Création de l'entête du fichier
			$header = array();
			$s = simplenewsletter_utils::getModuleOption('csv_sep');
			$memberHeader = new simplenewsletter_members();
			foreach($memberHeader->getVars() as $fieldName => $properties) {
				$header[] = $fieldName;
			}
			fwrite($fp, implode($s, $header)."\n");

			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('uid', 0, '<>'));
			$criteria->setSort('sub_date');
			$criteria->setOrder('ASC');
			$members = simplenewsletter_handler::getInstance()->h_simplenewsletter_members->getObjects($criteria);
			foreach($members as $member) {
			    $line = array();
				foreach($header as $memberField) {
					$line[] = $member->getVar($memberField);
				}
				fwrite($fp, implode($s, $line)."\n");
			}
			fclose($fp);
			echo "<a target='_blank' href='".XOOPS_UPLOAD_URL.'/'.$csvFilename."'>"._AM_SIMPLENEWSLETTER_CSV_READY.'</a>';
		} else {
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_ERROR_3);
		}
    	break;

    // ****************************************************************************************************************
    case 'editmember':    // Edition d'un membre
    // ****************************************************************************************************************
    	xoops_cp_header();
    	//simplenewsletter_adminMenu(2);
    	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    	if($id == 0) {
            simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_NOT_FOUND, 'index.php?op=members', 5);
    	}
    	addEditMember($id);
        break;


    // ****************************************************************************************************************
    case 'validatemember':    // Validation d'un membre
    // ****************************************************************************************************************
    	xoops_cp_header();
    	//simplenewsletter_adminMenu(2);
    	$opRedirect = 'members';
    	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    	if($id == 0) {
            simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_NOT_FOUND, 'index.php?op='.$opRedirect, 5);
    	}
    	$member = null;
    	$member = $simplenewsletter_handler->h_simplenewsletter_members->get($id);
    	if(!is_object($member)) {
    	    simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_NOT_FOUND, 'index.php?op='.$opRedirect, 5);
    	}
    	$member->setVar('member_verified', _SIMPLENEWSLETTER_MEMBER_VERIFIED);
    	$res = $simplenewsletter_handler->h_simplenewsletter_members->insert($member, true);
    	if($res) {
			simplenewsletter_utils::updateCache();
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_OK, $baseurl.'?op='.$opRedirect, 2);
		} else {
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_PB, $baseurl.'?op='.$opRedirect,5);
		}
    	addEditMember($id);
        break;


    // ****************************************************************************************************************
    case 'members':    // Membres
    // ****************************************************************************************************************
    	xoops_cp_header();
        echo $mainAdmin->addNavigation('main.php?op=members');
    	//simplenewsletter_adminMenu(2);

    	$class = '';
    	$filter_member_lastname = $filter_member_firstname = $filter_member_email = '';
    	$filter_member_uid = $filter_member_verified = 0;
        $newFilter = false;

		$confSubscribeAll = simplenewsletter_utils::javascriptLinkConfirm(_AM_SIMPLENEWSLETTER_CONF_SUBSCRIBE_ALL);
		$confUnsubscribeAll = simplenewsletter_utils::javascriptLinkConfirm(_AM_SIMPLENEWSLETTER_CONF_UNSUBSCRIBE_ALL);
		$conf_msg = simplenewsletter_utils::javascriptLinkConfirm(_AM_SIMPLENEWSLETTER_DELETE_MEMBER);
		$conf_validate = simplenewsletter_utils::javascriptLinkConfirm(_AM_SIMPLENEWSLETTER_VALIDATE_MEMBER);

    	$start = isset($_GET['start']) ? intval($_GET['start']) : 0;

        $criteria = new CriteriaCompo();
		$criteria->add(new Criteria('uid', 0, '<>'));

		if(isset($_POST['filter_member_lastname']) && xoops_trim($_POST['filter_member_lastname']) != '') {
			$criteria->add(new Criteria('member_lastname', '%'.$_POST['filter_member_lastname'].'%', 'LIKE'));
			$filter_member_lastname = $_POST['filter_member_lastname'];
			$newFilter = true;
		}

		if(isset($_POST['filter_member_firstname']) && xoops_trim($_POST['filter_member_firstname']) != '') {
			$criteria->add(new Criteria('member_firstname', '%'.$_POST['filter_member_firstname'].'%', 'LIKE'));
			$filter_member_firstname = $_POST['filter_member_firstname'];
			$newFilter = true;
		}

		if(isset($_POST['filter_member_email']) && xoops_trim($_POST['filter_member_email']) != '') {
			$criteria->add(new Criteria('member_email', '%'.$_POST['filter_member_email'].'%', 'LIKE'));
			$filter_member_email = $_POST['filter_member_email'];
			$newFilter = true;
		}

		if(isset($_POST['filter_member_uid']) && intval($_POST['filter_member_uid']) != 0) {
		    if(intval($_POST['filter_member_uid'] == 1 )) {    // Juste les membres
			    $criteria->add(new Criteria('member_uid', 0, '<>'));
		    } else {
                $criteria->add(new Criteria('member_uid', 0, '='));
		    }
			$filter_member_uid = intval($_POST['filter_member_uid']);
			$newFilter = true;
		}

		if(isset($_POST['filter_member_verified']) && intval($_POST['filter_member_verified']) != 0) {
		    if(intval($_POST['filter_member_verified']) == 1) {
			    $criteria->add(new Criteria('member_verified', _SIMPLENEWSLETTER_MEMBER_VERIFIED, '='));
		    } else {
		        $criteria->add(new Criteria('member_verified', _SIMPLENEWSLETTER_MEMBER_NOTVERIFIED, '='));
		    }
			$filter_member_verified = intval($_POST['filter_member_verified']);
			$newFilter = true;
		}

        if(	$filter_member_lastname == '' && $filter_member_firstname == '' && $filter_member_email == '' && $filter_member_uid == 0 && $filter_member_verified == 0) {
            $newFilter = true;
        }

		if(!$newFilter && isset($_SESSION['simplenewsletter_filter'])) {
			$criteria = unserialize($_SESSION['simplenewsletter_filter']);
			$filter_member_lastname = $_SESSION['filter_member_lastname'];
			$filter_member_firstname = $_SESSION['filter_member_firstname'];
			$filter_member_email = $_SESSION['filter_member_email'];
			$filter_member_uid = $_SESSION['filter_member_uid'];
			$filter_member_verified = $_SESSION['filter_member_verified'];
		}

		$_SESSION['simplenewsletter_filter'] = serialize($criteria);
		$_SESSION['filter_member_lastname'] = $filter_member_lastname;
		$_SESSION['filter_member_firstname'] = $filter_member_firstname;
		$_SESSION['filter_member_email'] = $filter_member_email;
		$_SESSION['filter_member_uid'] = $filter_member_uid;
		$_SESSION['filter_member_verified'] = $filter_member_verified;

		$itemsCount = $simplenewsletter_handler->h_simplenewsletter_members->getCount($criteria);
		if($itemsCount > $limit) {
			$pagenav = new XoopsPageNav($itemsCount, $limit, $start, 'start', 'op=members');
		}
    	// Liste des inscits
    	simplenewsletter_utils::htitle(_AM_SIMPLENEWSLETTER_MEMBERS_LIST.' ('.$itemsCount.')', 3);

		$criteria->setLimit($limit);
		$criteria->setStart($start);
		$criteria->setSort('sub_date DESC, member_email');
		$criteria->setOrder('ASC');

		$items = $simplenewsletter_handler->h_simplenewsletter_members->getObjects($criteria);

		if(isset($pagenav) && is_object($pagenav)) {
			echo "<div align='center'>".$pagenav->renderNav()."</div>";
		}

		$memberSelect = simplenewsletter_utils::htmlSelect('filter_member_uid', array( 1 => _YES, 2 => _NO), $filter_member_uid);
		$verifiedSelect = simplenewsletter_utils::htmlSelect('filter_member_verified', array( 1 => _YES, 2 => _NO), $filter_member_verified);

		echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
		// Entête de colonnes
		echo "<tr>";
		echo "<th align='center'>"._SIMPLENEWSLETTER_LAST_NAME."</th><th align='center'>"._SIMPLENEWSLETTER_FIRST_NAME."</th><th align='center'>"._SIMPLENEWSLETTER_EMAIL."</th><th align='center'>"._AM_SIMPLENEWSLETTER_SUBSCRIPTION_DATE."</th><th align='center'>"._AM_SIMPLENEWSLETTER_SITE_MEMBER."</th><th align='center'>"._AM_SIMPLENEWSLETTER_VERIFIED."</th><th align='center'>"._SIMPLENEWSLETTER_ACTION."</th>";
		echo "</tr>";

		// Filtres
		echo "<tr><form method='post' action='$baseurl'>";
		echo "<th align='center'><input type='text size='15' name='filter_member_lastname' id='filter_member_lastname' value='$filter_member_lastname' /></th>";
		echo "<th align='center'><input type='text size='15' name='filter_member_firstname' id='filter_member_firstname' value='$filter_member_firstname' /></th>";
		echo "<th align='center'><input type='text size='15' name='filter_member_email' id='filter_member_email' value='$filter_member_email' /></th>";
		echo "<th align='center'>&nbsp;</th>";
        echo "<th align='center'>".$memberSelect."</th>";
        echo "<th align='center'>".$verifiedSelect."</th>";
		echo "<th align='center'><input type='hidden' name='op' id='op' value='members' /><input type='submit' name='btngo' id='btngo' value='"._GO."' /></th></form>";
		echo "</tr>\n";

		foreach($items as $item) {
			$class = ($class == 'even') ? 'odd' : 'even';
			$id = $item->uid;
			$email = $item->getvar('member_email');
			$isMemberOfTheSite = $item->getvar('member_uid') != 0 ? _YES : _NO;
			$verified = $item->getvar('member_verified') == _SIMPLENEWSLETTER_MEMBER_VERIFIED ? _YES : _NO;
			$actions = array();
			$actions[] = "<a href='$baseurl?op=editmember&id=".$id."' title='"._EDIT."'>".$icones['edit'].'</a>';
			$actions[] = "<a href='$baseurl?op=deletemember&id=".$id."' title='"._DELETE."'".$conf_msg.">".$icones['delete'].'</a>';
			if($item->getvar('member_verified') != _SIMPLENEWSLETTER_MEMBER_VERIFIED) {
			    $actions[] = "<a href='$baseurl?op=validatemember&id=".$id."' title='"._SIMPLENEWSLETTER_VALIDATE."'".$conf_validate.">".$icones['validate'].'</a>';
			}

			echo "<tr class='".$class."'>\n";
			echo "<td align='left'>".$item->getvar('member_lastname')."</td>";
			echo "<td align='left'>".$item->getvar('member_firstname')."</td>";
			echo "<td align='left'>".$item->getvar('member_email')."</td>";
			echo "<td align='center'>".formatTimestamp($item->getvar('sub_date'), 's')."</td>";
			echo "<td align='center'>".$isMemberOfTheSite."</td>";
			echo "<td align='center'>".$verified."</td>";
			echo "<td align='center'>".implode(' ', $actions)."</td>";
			echo "</tr>\n";
		}
		echo "</table>\n";
		if(isset($pagenav) && is_object($pagenav)) {
			echo "<div align='center'>".$pagenav->renderNav()."</div>";
		}
		echo "<br /><a $confSubscribeAll href='".$baseurl."?op=subscribeall'>"._AM_SIMPLENEWSLETTER_SUBSCRIBE_ALL.'</a>';
		echo " - <a $confUnsubscribeAll href='".$baseurl."?op=unsubscribeall'>"._AM_SIMPLENEWSLETTER_UNSUBSCRIBE_ALL.'</a>';
		if($itemsCount > 0) {
		    echo " - <a href='".$baseurl."?op=exportmembers'>"._AM_SIMPLENEWSLETTER_EXPORT_MEMBERS.'</a>';
		}
		echo '<br /><br />';

    	// Formulaire d'inscription d'un membre
        addEditMember(0);

		// Statistiques
		echo "<br /><br /><fieldset>\n";
		echo "<legend>"._AM_SIMPLENEWSLETTER_STATISTICS."</legend>";
		echo "<table cellspacing='1' cellpadding='3' border='0' class='outer' width='50%'>";
		$class = ($class == 'even') ? 'odd' : 'even';
        echo "<td class='".$class."'>".sprintf(_AM_SIMPLENEWSLETTER_STATISTICS1, $simplenewsletter_handler->h_simplenewsletter_members->getTotalMembersVerified())."<br />";
        echo sprintf(_AM_SIMPLENEWSLETTER_STATISTICS2, $simplenewsletter_handler->h_simplenewsletter_members->getTotalMembersNotVerified())."<br />";
        echo sprintf(_AM_SIMPLENEWSLETTER_STATISTICS3, $simplenewsletter_handler->h_simplenewsletter_members->getTotalMembersCount())."</td>";
		$class = ($class == 'even') ? 'odd' : 'even';
        echo "<td class='".$class."'>".sprintf(_AM_SIMPLENEWSLETTER_STATISTICS4, $simplenewsletter_handler->h_simplenewsletter_members->getTotalMemberSiteCount())."<br />";
        echo sprintf(_AM_SIMPLENEWSLETTER_STATISTICS5, $simplenewsletter_handler->h_simplenewsletter_members->getTotalNotMemberSiteCount())."</td>";
		echo "</tr>";
		echo "</table>";
		echo "</fieldset>";
		//show_footer();
        include_once("admin_footer.php");
    	break;

    // ****************************************************************************************************************
    case 'instant-zero':    // Publicité
    // ****************************************************************************************************************
        xoops_cp_header();
        //simplenewsletter_adminMenu(7);
		echo "<iframe src='http://www.instant-zero.com/modules/liaise/?form_id=2' width='100%' height='600' frameborder='0'></iframe>";
		//show_footer();
        include_once("admin_footer.php");
		break;

    // ****************************************************************************************************************
    case 'blokcs':    // Blocs
    // ****************************************************************************************************************
        xoops_cp_header();
        //simplenewsletter_adminMenu(6);
        if(simplenewsletter_utils::isX23()) {
            $url = XOOPS_URL.'/modules/system/admin.php?fct=blocksadmin&selmod=-1&selvis=-1&selgrp=2&selgen='.$xoopsModule->getVar('mid');
        } else {
            $url = XOOPS_URL.'/modules/system/admin.php?fct=blocksadmin';
        }
        simplenewsletter_utils::redirect(_MI_SIMPLENEWSLETTER_ADMENU6, $url, 0);
		//show_footer();
        include_once("admin_footer.php");
		break;

    // ****************************************************************************************************************
    case 'subscribemember':	// Inscription d'un membre (ou édition)
   	// ****************************************************************************************************************
    	xoops_cp_header();
    	//simplenewsletter_adminMenu(2);
		$uid = isset($_POST['uid']) ? intval($_POST['uid']) : 0;

		$opRedirect = 'members';
		if(!empty($uid)) {
			$edit = true;
			$item = simplenewsletter_handler::getInstance()->h_simplenewsletter_members->get($uid);
			if(!is_object($item)) {
				simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_NOT_FOUND, $baseurl, 5);
			}
			$item->unsetNew();
		} else {
			$edit = false;
			$item = simplenewsletter_handler::getInstance()->h_simplenewsletter_members->create(true);
			$item->setVar('sub_date', time());
			$item->setVar('member_temporary', 0);
		}
		$item->setVars($_POST);
		$res = simplenewsletter_handler::getInstance()->h_simplenewsletter_members->insert($item);
		if($res) {
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_OK, $baseurl.'?op=members', 2);
		} else {
			simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_PB, $baseurl.'?op=members', 5);
		}
    	break;

    // ****************************************************************************************************************
    case 'maintain':    // Maintenance des tables et du cache
    // ****************************************************************************************************************
    	xoops_cp_header();
    	//simplenewsletter_adminMenu();
    	require '../xoops_version.php';
    	$tables = array();
		foreach ($modversion['tables'] as $table) {
			$tables[] = $xoopsDB->prefix($table);
		}
		if(count($tables) > 0) {
			$list = implode(',', $tables);
			$xoopsDB->queryF('CHECK TABLE '.$list);
			$xoopsDB->queryF('ANALYZE TABLE '.$list);
			$xoopsDB->queryF('OPTIMIZE TABLE '.$list);
		}
		simplenewsletter_utils::updateCache();
		$simplenewsletter_handler->h_simplenewsletter_news->forceCacheClean();
		simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_OK, $baseurl, 2);
    	break;

    // ****************************************************************************************************************
    case 'messages':    // Message de bienvenue, d'au revoir et de validation
    // ****************************************************************************************************************
    	xoops_cp_header();
        echo $mainAdmin->addNavigation('main.php?op=messages');
    	//simplenewsletter_adminMenu(3);
    	require_once SIMPLENEWSLETTER_PATH.'class/registryfile.php';
		$registry = new simplenewsletter_registryfile();
		$sform = new XoopsThemeForm(_AM_SIMPLENEWSLETTER_MESSAGES, 'frmatxt', $baseurl);
		$sform->addElement(new XoopsFormHidden('op', 'savetexts'));
		// Message de bienvenue
		$editor1 = simplenewsletter_utils::getWysiwygForm(_AM_SIMPLENEWSLETTER_MESSAGE1.$additional, 'text1', $registry->getfile(SIMPLENEWSLETTER_MESSAGE1_PATH), 5, 60, 'hometext1_hidden');
		if($editor1) {
			$sform->addElement($editor1, false);
		}
		// Messsage d'au revoir
		$editor2 = simplenewsletter_utils::getWysiwygForm(_AM_SIMPLENEWSLETTER_MESSAGE2.$additional, 'text2', $registry->getfile(SIMPLENEWSLETTER_MESSAGE2_PATH), 5, 60, 'hometext2_hidden');
		if($editor2) {
			$sform->addElement($editor2, false);
		}
		// Message de validation
		if(simplenewsletter_utils::getModuleOption('auto_approve') == 0) {
		    $confirmationMessageFilename = SIMPLENEWSLETTER_PATH.'language'.DIRECTORY_SEPARATOR.$xoopsConfig['language'].DIRECTORY_SEPARATOR.'mail_template'.DIRECTORY_SEPARATOR.'simplenewsletter_verify.tpl';
		    $messageContent = nl2br(file_get_contents($confirmationMessageFilename));
		    $confirmationMessageField = new XoopsFormLabel(_AM_SIMPLENEWSLETTER_VALIDATION_MESSAGE, $messageContent);
		    $confirmationMessageField->setDescription(_AM_SIMPLENEWSLETTER_VALIDATION_WHERE. ' : '.$confirmationMessageFilename);
            $sform->addElement($confirmationMessageField, false);
		}

		$button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', _AM_SIMPLENEWSLETTER_SAVE, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray, false);
		$sform = simplenewsletter_utils::formMarkRequiredFields($sform);
		$sform->display();
		//show_footer();
        include_once("admin_footer.php");
		break;

    // ****************************************************************************************************************
    case 'savetexts':    // Sauvegarde des messages
    // ****************************************************************************************************************
		xoops_cp_header();
    	require_once SIMPLENEWSLETTER_PATH.'class/registryfile.php';
    	$myts = &MyTextSanitizer::getInstance();
		$registry = new simplenewsletter_registryfile();
		$registry->savefile($myts->stripSlashesGPC($_POST['text1']), SIMPLENEWSLETTER_MESSAGE1_PATH);
		$registry->savefile($myts->stripSlashesGPC($_POST['text2']), SIMPLENEWSLETTER_MESSAGE2_PATH);
		simplenewsletter_utils::updateCache();
		simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_OK, $baseurl.'?op=messages', 2);
		break;

	// ****************************************************************************************************************
    case 'csvimport':    // Import CSV
    // ****************************************************************************************************************
    	xoops_cp_header();
        echo $mainAdmin->addNavigation('main.php?op=csvimport');
    	//simplenewsletter_adminMenu(5);
        require_once XOOPS_ROOT_PATH.'/class/xoopslists.php';

    	$sform = new XoopsThemeForm(_AM_SIMPLENEWSLETTER_CSV_PARAMETERS, 'frmCsvParam', $baseurl);
        $sform->setExtra('enctype="multipart/form-data"');
        $sform->addElement(new XoopsFormHidden('op', 'csvstep2'));
        $uploadField = new XoopsFormFile(_AM_SIMPLENEWSLETTER_CSV_UPLOAD_FILE, 'csvfile', SIMPLENEWSLETTER_CSV_MAX_SIZE);
        $uploadField->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP1);
        $sform->addElement($uploadField, false);
        $uploadFiles = XoopsLists::getFileListAsArray(XOOPS_UPLOAD_PATH);
        $fileSelect = new XoopsFormSelect(sprintf(_AM_SIMPLENEWSLETTER_CSV_SELECT_FILE, XOOPS_UPLOAD_PATH), 'existingFile', null, 10);
        $fileSelect->addOptionArray($uploadFiles);
        $sform->addElement($fileSelect, false);

        $sform->addElement(new XoopsFormRadioYN(_AM_SIMPLENEWSLETTER_CSV_SKIP_FIRST, 'skipFirst', 0), true);
        $sform->addElement(new XoopsFormRadioYN(_AM_SIMPLENEWSLETTER_CSV_EMPTY_CONTENT, 'emptyContent', 0), true);

        $sform->addElement(new XoopsFormText(_AM_SIMPLENEWSLETTER_CSV_FIELDS_SEP, 'fieldsSep', 1, 1, ';'), true);
        $sform->addElement(new XoopsFormText(_AM_SIMPLENEWSLETTER_CSV_STRINGS_SEP, 'stringsSep', 1, 1, '"'), true);

		$button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', _AM_SIMPLENEWSLETTER_CSV_NEXT_STEP, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray, false);

		$sform = simplenewsletter_utils::formMarkRequiredFields($sform);
		$sform->display();
        //show_footer();
        include_once("admin_footer.php");
        break;

    // ****************************************************************************************************************
    case 'csvstep2':    // Paramétrage du mappage des champs
    // ****************************************************************************************************************
    	xoops_cp_header();
    	//simplenewsletter_adminMenu(5);

    	$destname = $csvFile = '';
		$res = simplenewsletter_utils::uploadFile(0, XOOPS_UPLOAD_PATH, array('text/csv', 'text/plain'), SIMPLENEWSLETTER_CSV_MAX_SIZE);
		if($res === true) {
			$csvFile = basename($destname);
   		} else {
            if(isset($_POST['existingFile'])) {
                $csvFile = $_POST['existingFile'];
            }
   		}
   		if(xoops_trim($csvFile) == '') {
            simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_ERROR_4, 'index.php?op=csvimport', 5);
   		}
   		$skipFirst = intval($_POST['skipFirst']);
   		$emptyContent = intval($_POST['emptyContent']);
   		$fieldsSep = $_POST['fieldsSep'];
   		$stringsSep = $_POST['stringsSep'];
   		$csvFullName = XOOPS_UPLOAD_PATH.'/'.$csvFile;
   		$fp = fopen($csvFullName, 'r');
   		if($fp === false) {
   		    simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_ERROR_5, 'index.php?op=csvimport', 5);
   		}
   		if($skipFirst) {
            $firstLine = fgetcsv($fp, 0, $fieldsSep, $stringsSep);
   		}
   		$firstLine = fgetcsv($fp, 0, $fieldsSep, $stringsSep);
   		$csvFields = array_merge(array('---'), $firstLine);

   		fclose($fp);
        $sform = new XoopsThemeForm(_AM_SIMPLENEWSLETTER_FIELDS_MAPPING, 'frmCsvmapping', $baseurl);
        $sform->addElement(new XoopsFormHidden('csvFile', $csvFile));
        $sform->addElement(new XoopsFormHidden('skipFirst', $skipFirst));
        $sform->addElement(new XoopsFormHidden('emptyContent', $emptyContent));
        $sform->addElement(new XoopsFormHidden('fieldsSep', ord($fieldsSep)));
        $sform->addElement(new XoopsFormHidden('stringsSep', ord($stringsSep)));
        $sform->addElement(new XoopsFormHidden('op', 'csvstep3'));
        $sform->insertBreak("<div style='text-align: center; font-weight: bold;'>"._AM_SIMPLENEWSLETTER_CSV_HELP."</div>",'foot');
        // sub_date ***********************************************************
        $sub_date_tray = new XoopsFormElementTray('sub_date', '<br />');
        $sub_date_tray->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP2);
        // Sélecteur de champs
        $sub_date_select = new XoopsFormSelect('', 'sub_date_fields', 0);
        $sub_date_select->addOptionArray($csvFields);
        $sub_date_tray->addElement($sub_date_select);

        // Le créer automatiquement avec la date du jour
        $sub_date_checkbox = new XoopsFormCheckBox('', 'sub_date_checkbox', 0);
        $sub_date_checkbox->addOption(1, _AM_SIMPLENEWSLETTER_CSV_HELP3);
        $sub_date_tray->addElement($sub_date_checkbox);
        $sform->addElement($sub_date_tray);


        // member_uid *********************************************************
        $member_uid_tray = new XoopsFormElementTray('member_uid', '<br />');
        $member_uid_tray->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP4);
        // Sélecteur de champs
        $member_uid_select = new XoopsFormSelect('', 'member_uid_fields', 0);
        $member_uid_select->addOptionArray($csvFields);
        $member_uid_tray->addElement($member_uid_select);

        // Mettre une valeur fixe
        $member_uid_textbox = new XoopsFormText(_AM_SIMPLENEWSLETTER_CSV_HELP5, 'member_uid_textbox', 3, 4, 0);
        $member_uid_tray->addElement($member_uid_textbox);
        $sform->addElement($member_uid_tray);

        // member_firstname ***************************************************
        $member_firstname_select = new XoopsFormSelect('member_firstname', 'member_firstname', 0);
        $member_firstname_select->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP6);
        $member_firstname_select->addOptionArray($csvFields);
        $sform->addElement($member_firstname_select);

        // member_lastname ***************************************************
        $member_lastname_select = new XoopsFormSelect('member_lastname', 'member_lastname', 0);
        $member_lastname_select->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP7);
        $member_lastname_select->addOptionArray($csvFields);
        $sform->addElement($member_lastname_select);

        // member_verified *********************************************************
        $member_verified_tray = new XoopsFormElementTray('member_verified', '<br />');
        $member_verified_tray->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP8);
        // Sélecteur de champs
        $member_verified_select = new XoopsFormSelect('', 'member_verified_fields', 0);
        $member_verified_select->addOptionArray($csvFields);
        $member_verified_tray->addElement($member_verified_select);

        // Mettre une valeur fixe
        $member_verified_textbox = new XoopsFormText(_AM_SIMPLENEWSLETTER_CSV_HELP5, 'member_verified_textbox', 3, 4, 0);
        $member_verified_tray->addElement($member_verified_textbox);
        $sform->addElement($member_verified_tray);

        // member_email ***************************************************
        $member_email_select = new XoopsFormSelect('member_email', 'member_email', 0);
        $member_email_select->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP9);
        $member_email_select->addOptionArray($csvFields);
        $sform->addElement($member_email_select);

        // member_user_password *********************************************************
        $member_user_password_tray = new XoopsFormElementTray('member_user_password', '<br />');
        $member_user_password_tray->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP10);
        // Sélecteur de champs
        $member_user_password_select = new XoopsFormSelect('', 'member_user_password_fields', 0);
        $member_user_password_select->addOptionArray($csvFields);
        $member_user_password_tray->addElement($member_user_password_select);

        // Mettre une valeur fixe
        $member_user_password_textbox = new XoopsFormText(_AM_SIMPLENEWSLETTER_CSV_HELP5, 'member_user_password_textbox', 10, 50, '');
        $member_user_password_tray->addElement($member_user_password_textbox);
        $sform->addElement($member_user_password_tray);

        // member_title ***************************************************
        $member_title_select = new XoopsFormSelect('member_title', 'member_title', 0);
        $member_title_select->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP11);
        $member_title_select->addOptionArray($csvFields);
        $sform->addElement($member_title_select);

        // member_street ***************************************************
        $member_street_select = new XoopsFormSelect('member_street', 'member_street', 0);
        $member_street_select->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP12);
        $member_street_select->addOptionArray($csvFields);
        $sform->addElement($member_street_select);

        // member_city ***************************************************
        $member_city_select = new XoopsFormSelect('member_city', 'member_city', 0);
        $member_city_select->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP13);
        $member_city_select->addOptionArray($csvFields);
        $sform->addElement($member_city_select);

        // member_state ***************************************************
        $member_state_select = new XoopsFormSelect('member_state', 'member_state', 0);
        $member_state_select->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP14);
        $member_state_select->addOptionArray($csvFields);
        $sform->addElement($member_state_select);

        // member_zip ***************************************************
        $member_zip_select = new XoopsFormSelect('member_zip', 'member_zip', 0);
        $member_zip_select->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP15);
        $member_zip_select->addOptionArray($csvFields);
        $sform->addElement($member_zip_select);

        // member_telephone ***************************************************
        $member_telephone_select = new XoopsFormSelect('member_telephone', 'member_telephone', 0);
        $member_telephone_select->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP16);
        $member_telephone_select->addOptionArray($csvFields);
        $sform->addElement($member_telephone_select);

        // member_fax ***************************************************
        $member_fax_select = new XoopsFormSelect('member_fax', 'member_fax', 0);
        $member_fax_select->setDescription(_AM_SIMPLENEWSLETTER_CSV_HELP17);
        $member_fax_select->addOptionArray($csvFields);
        $sform->addElement($member_fax_select);

        // Validation du formulaire *******************************************
        $button_tray = new XoopsFormElementTray('' ,'');
		$submit_btn = new XoopsFormButton('', 'post', _AM_SIMPLENEWSLETTER_CSV_NEXT_STEP, 'submit');
		$button_tray->addElement($submit_btn);
		$sform->addElement($button_tray, false);

		$sform = simplenewsletter_utils::formMarkRequiredFields($sform);
		$sform->display();
        //show_footer();
        include_once("admin_footer.php");
        break;

    // ****************************************************************************************************************
    case 'csvstep3':    // Lancement de l'import CSV
    // ****************************************************************************************************************
    	xoops_cp_header();
    	//simplenewsletter_adminMenu(5);
        // Lecture de tous les paramètres
        $csvFile = isset($_POST['csvFile']) ? $_POST['csvFile'] : '';
        if(xoops_trim($csvFile) == '') {
            simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_ERROR_4, 'index.php?op=csvimport', 5);
   		}
   		$skipFirst = intval($_POST['skipFirst']);
   		$emptyContent = intval($_POST['emptyContent']);
   		$fieldsSep = chr(intval($_POST['fieldsSep']));
   		$stringsSep = chr(intval($_POST['stringsSep']));
   		// sub_date
   		$sub_date_fields = intval($_POST['sub_date_fields']);
   		$sub_date_checkbox = isset($_POST['sub_date_checkbox']) ? intval($_POST['sub_date_checkbox']) : 0;
        // member_uid
        $member_uid_fields = intval($_POST['member_uid_fields']);
        $member_uid_textbox = isset($_POST['member_uid_textbox']) ? intval($_POST['member_uid_textbox']) : 0;
        // member_firstname
        $member_firstname = intval($_POST['member_firstname']);
        // member_lastname
        $member_lastname = intval($_POST['member_lastname']);
        // member_verified
        $member_verified_fields = intval($_POST['member_verified_fields']);
        $member_verified_textbox = isset($_POST['member_verified_textbox']) ? intval($_POST['member_verified_textbox']) : 0;
        // member_email
        $member_email = intval($_POST['member_email']);
        // member_user_password
        $member_user_password_fields = intval($_POST['member_user_password_fields']);
        $member_user_password_textbox = isset($_POST['member_user_password_textbox']) ? $_POST['member_user_password_textbox'] : '';
        // member_title
        $member_title = intval($_POST['member_title']);
        // member_street
        $member_street = intval($_POST['member_street']);
        // member_city
        $member_city = intval($_POST['member_city']);
        // member_state
        $member_state = intval($_POST['member_state']);
        // member_zip
        $member_zip = intval($_POST['member_zip']);
        // member_telephone
        $member_telephone = intval($_POST['member_telephone']);
        // member_fax
        $member_fax = intval($_POST['member_fax']);

        @set_time_limit(0);

   		// Début des traitements
   		$csvFullName = XOOPS_UPLOAD_PATH.'/'.$csvFile;
   		$fp = fopen($csvFullName, 'r');
   		if($fp === false) {
   		    simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_ERROR_5, 'index.php?op=csvimport', 5);
   		}
   		$handlers = simplenewsletter_handler::getInstance();
   		$lineCount = $importedUsers = 0;

   		// Vidage préalable de la base
   		if($emptyContent) {
            $handlers->h_simplenewsletter_members->emptyTable();
   		}
   		if($skipFirst) {
   		    $lineCount++;
            $firstLine = fgetcsv($fp, 0, $fieldsSep, $stringsSep);
   		}
   		echo '<br />'._AM_SIMPLENEWSLETTER_CSV_STARTING;
   		while (($fields = fgetcsv($fp, 0, $fieldsSep, $stringsSep)) !== false) {
   		    $lineCount++;
            $member = $handlers->h_simplenewsletter_members->create(true);
            if($sub_date_fields == 0) {
                $member->setVar('sub_date', time());
            } else {
                $member->setVar('sub_date', $fields[$sub_date_fields - 1]);
            }

            if($member_uid_fields == 0) {
                $member->setVar('member_uid', $member_uid_textbox);
            } else {
                $member->setVar('member_uid', $fields[$member_uid_fields - 1]);
            }

            if($member_firstname == 0) {
                $member->setVar('member_firstname', '');
            } else {
                $member->setVar('member_firstname', $fields[$member_firstname - 1]);
            }

            if($member_lastname == 0) {
                $member->setVar('member_lastname', '');
            } else {
                $member->setVar('member_lastname', $fields[$member_lastname - 1]);
            }

            if($member_verified_fields == 0) {
                $member->setVar('member_verified', $member_verified_textbox);
            } else {
                $member->setVar('member_verified', $fields[$member_verified_fields - 1]);
            }

            if($member_email == 0) {
                $member->setVar('member_email', '');
            } else {
                $member->setVar('member_email', $fields[$member_email - 1]);
            }

            if($member_user_password_fields == 0) {
                $member->setVar('member_user_password', $member_user_password_textbox);
            } else {
                $member->setVar('member_user_password', $fields[$member_user_password_fields - 1]);
            }

            if($member_title == 0) {
                $member->setVar('member_title', '');
            } else {
                $member->setVar('member_title', $fields[$member_title - 1]);
            }

            if($member_street == 0) {
                $member->setVar('member_street', '');
            } else {
                $member->setVar('member_street', $fields[$member_street - 1]);
            }

            if($member_city == 0) {
                $member->setVar('member_city', '');
            } else {
                $member->setVar('member_city', $fields[$member_city - 1]);
            }

            if($member_state == 0) {
                $member->setVar('member_state', '');
            } else {
                $member->setVar('member_state', $fields[$member_state - 1]);
            }

            if($member_zip == 0) {
                $member->setVar('member_zip', '');
            } else {
                $member->setVar('member_zip', $fields[$member_zip - 1]);
            }

            if($member_telephone == 0) {
                $member->setVar('member_telephone', '');
            } else {
                $member->setVar('member_telephone', $fields[$member_telephone - 1]);
            }

            if($member_fax == 0) {
                $member->setVar('member_fax', '');
            } else {
                $member->setVar('member_fax', $fields[$member_fax - 1]);
            }

            $member->setVar('member_sent', _SIMPLENEWSLETTER_MEMBER_SENT);
            $member->setVar('member_password', '');    // Mot de passe utilisé en cas d'anonyme devant valider son inscription
            $member->setVar('member_temporary', 0);
            if(!$handlers->h_simplenewsletter_members->insert($member, true)) {
                echo '<br />'._AM_SIMPLENEWSLETTER_ERROR_6.' '.$lineCount;
            } else {
                $importedUsers++;
            }
   		}
        fclose($fp);
        $handlers->h_simplenewsletter_members->forceCacheClean();
        simplenewsletter_utils::updateCache();
        $removeLink = $baseurl.'?op=csvstep4&csvFile='.$csvFile;
        echo '<br />'.sprintf(_AM_SIMPLENEWSLETTER_CSV_END, $importedUsers, $csvFullName, $removeLink);
        //show_footer();
        include_once("admin_footer.php");
        break;

    // ****************************************************************************************************************
    case 'csvstep4':    // Suppression du fichier CSV importé
    // ****************************************************************************************************************
    	xoops_cp_header();
    	//simplenewsletter_adminMenu(5);
        // Lecture de tous les paramètres
        $csvFile = isset($_GET['csvFile']) ? $_GET['csvFile'] : '';
        if(xoops_trim($csvFile) == '') {
            simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_ERROR_4, 'index.php?op=csvimport', 5);
   		}
   		$csvFullName = XOOPS_UPLOAD_PATH.'/'.$csvFile;
   		if(unlink($csvFullName)) {
            simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_OK, $baseurl.'?op=members', 2);
   		} else {
   		    simplenewsletter_utils::redirect(_AM_SIMPLENEWSLETTER_SAVE_PB, $baseurl.'?op=members', 5);
   		}
        //show_footer();
        include_once("admin_footer.php");
        break;

}

include_once("admin_footer.php");
