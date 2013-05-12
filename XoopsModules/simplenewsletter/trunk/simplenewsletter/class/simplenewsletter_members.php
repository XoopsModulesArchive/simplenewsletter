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

include_once XOOPS_ROOT_PATH.'/kernel/object.php';
if (!class_exists('Simplenewsletter_XoopsPersistableObjectHandler')) {
	include_once XOOPS_ROOT_PATH.'/modules/simplenewsletter/class/PersistableObjectHandler.php';
}

define("_SIMPLENEWSLETTER_STATE_1", 0);
define("_SIMPLENEWSLETTER_STATE_2", 1);
define("_SIMPLENEWSLETTER_STATE_3", 2);
define("_SIMPLENEWSLETTER_STATE_4", 3);

define("_SIMPLENEWSLETTER_MEMBER_SENT", 1);
define("_SIMPLENEWSLETTER_MEMBER_NOTSENT", 0);

define("_SIMPLENEWSLETTER_MEMBER_VERIFIED", 1);
define("_SIMPLENEWSLETTER_MEMBER_NOTVERIFIED", 0);

define("_SIMPLENEWSLETTER_SESSION_NAME", 'simplenewsletter_session');

class simplenewsletter_members extends Simplenewsletter_Object {

	function __construct()
	{
		$this->initVar('uid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('sub_date', XOBJ_DTYPE_INT, null, false);
		$this->initVar('member_sent', XOBJ_DTYPE_INT, null, false);
		$this->initVar('member_uid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('member_firstname',XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar('member_lastname',XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar('member_password',XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar('member_verified', XOBJ_DTYPE_INT, null, false);
		$this->initVar('member_email',XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar('member_temporary', XOBJ_DTYPE_INT, null, false);
		$this->initVar('member_user_password', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('member_title', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('member_street', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('member_city', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('member_state', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('member_zip', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('member_telephone', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('member_fax', XOBJ_DTYPE_TXTBOX, null, false);
	}

    /**
     * Retourne la liste des variables utilisables dans les templates
     *
     * @return array
     */
	function getUsableVariables()
	{
		$ret = array();
		$excluded = array('member_title', 'member_street', 'member_city', 'member_state', 'member_zip', 'member_telephone', 'member_fax');
		foreach ($this->vars as $k => $v) {
		    $add = true;
		    if(simplenewsletter_utils::getModuleOption('additional_fields') == 0) {
		        if(in_array($k, $excluded)) {
		            $add = false;
		        }
		    }
            if($add) {
			    $ret[] = '{'.strtoupper($k).'}';
            }
		}
		return $ret;
	}
}


class SimplenewsletterSimplenewsletter_membersHandler extends Simplenewsletter_XoopsPersistableObjectHandler
{
	function __construct($db)
	{	//								Table					Class	   				 Id		Descr.
		parent::__construct($db, 'simplenewsletter_members', 'simplenewsletter_members', 'uid', '');
	}

    /**
     * Retourne les variables de chaque membre utilisables dans les templates de mail
     *
     * @param simplenewsletter_members $member
     * @return array
     * @since 2.0.2009.03.07
     */
	function getMemberVariablesForTemplate(simplenewsletter_members $member)
	{
	    $originalMember = $member->toArray('n');
	    $memberForTemplate = array();
		foreach($originalMember as $key => $value) {
			$memberForTemplate[strtoupper($key)] = $value;
		}
		return $memberForTemplate;
	}


	/**
	 * Indique si l'utilisateur courant est inscrit à la newsletter ou pas
	 *
	 * @param integer $uid	L'id utilisateur
	 * @return boolean
	 */
	function isUserSubscribed($uid = 0)
	{
		if($uid == 0) {
			$uid = simplenewsletter_utils::getCurrentUserID();
		}
		$criteria = new Criteria('member_uid', $uid, '=');
		$count = $this->getCount($criteria);
		if($count > 0) {
			return true;
		} else {
			return false;
		}
	}

    /**
     * Indique si une adresse email n'est pas déjà inscrite
     *
     * @param string $member_email
     * @param integer $member_uid	Si spécifié on recherche une adresse mail mais pas pour cet utilisateur (sinon son adresse mail existe déjà !)
     * @param string $member_user_password	Idem que ci-dessus (sur le principe) mais pour un anonyme
     * @return boolean
     * @since 2.0.2009.03.04
     */
	function isEmailAlreadySubscribed($member_email, $member_uid = 0, $member_user_password = '')
	{
        $criteria = new CriteriaCompo();
		$criteria->add(new Criteria('member_email', $member_email, '='));
		if($member_uid != 0) {
            $criteria->add(new Criteria('member_uid', $member_uid, '<>'));
		}
		if($member_user_password != '') {
            $criteria->add(new Criteria('member_user_password', $member_user_password, '<>'));
		}
		return (bool) $this->getCount($criteria);
	}

    /**
     * Envoi du mail de confirmation d'inscription (et d'adresse mail) à un anonyme
     *
     * @param simplenewsletter_members $member
     * @param string $password
     * @return boolean	Le résultat de l'envoi
     * @since 2.0.2009.03.04
     */
	function sendConfirmationMessage(simplenewsletter_members $member, $password)
	{
	    $message = array();
	    $message = $this->getMemberVariablesForTemplate($member);
	    $message['VERIFY_URL'] = SIMPLENEWSLETTER_URL.'verify.php?code='.$password;
	    simplenewsletter_utils::sendEmailFromTpl('simplenewsletter_verify.tpl', $member->getVar('member_email'), _SIMPLENEWSLETTER_PLEASE_CONFIRM_SUBSCRIPTION, $message);
	    if(xoops_trim(simplenewsletter_utils::getModuleOption('validatemail_bcc')) != '') {
            simplenewsletter_utils::sendEmailFromTpl('simplenewsletter_verify.tpl', simplenewsletter_utils::getModuleOption('validatemail_bcc'), _SIMPLENEWSLETTER_PLEASE_CONFIRM_SUBSCRIPTION, $message);
	    }
	}

    /**
     * Indique si un code de validation existe avec le mot de passe donné en paramètre
     *
     * @param string $member_password
     * @return boolean
     * @since 2.0.2009.03.04
     */
	function isVerificationCodeExist($member_password)
	{
	    $criteria = new CriteriaCompo();
		$criteria->add(new Criteria('member_password', $member_password, '='));
		$criteria->add(new Criteria('member_verified', _SIMPLENEWSLETTER_MEMBER_NOTVERIFIED, '='));
        return (bool) $this->getCount($criteria);
	}

    /**
     * Valide l'inscription d'un anonyme à partir de son code de validation
     *
     * @param string $member_password
     * @return boolean	Le résultat de la validation
     * @since 2.0.2009.03.04
     */
	function validateSubscriptionFromPassword($member_password)
	{
	    $criteria = new Criteria('member_password', $member_password, '=');
        return $this->updateAll('member_verified', _SIMPLENEWSLETTER_MEMBER_VERIFIED, $criteria, true);
	}

    /**
     * Adresse un message de bienvenue à un nouvel inscrit
     *
     * @param simplenewsletter_members $member
     */
	function sayWelcome(simplenewsletter_members $member)
	{
	    $variables = array();
        $member_email = $member->getVar('member_email');
        $variables = $this->getMemberVariablesForTemplate($member);
		simplenewsletter_utils::sendEmailFromTpl(SIMPLENEWSLETTER_MESSAGE1_PATH, $member_email, _SIMPLENEWSLETTER_WELCOME_TITLE, $variables, XOOPS_UPLOAD_PATH, true);
	}

    /**
     * Inscription d'un membre à la liste
     *
     * @param integer $member_uid
     * @return boolean	Le résultat de l'inscription
     * @since 2.0.2009.03.04
     */
	private function subscribeMember($member_uid)
	{
		$member = $this->create(true);
		$member->setVar('sub_date', time());
		$member->setVar('member_sent', 0);
		$member->setVar('member_uid', $member_uid);
		$member->setVar('member_firstname', $_POST['member_firstname']);
		$member->setVar('member_lastname', $_POST['member_lastname']);
        if(simplenewsletter_utils::getModuleOption('additional_fields')) {
            $member->setVar('member_title', isset($_POST['member_title']) ? $_POST['member_title'] : '');
            $member->setVar('member_street', isset($_POST['member_street']) ? $_POST['member_street'] : '');
            $member->setVar('member_city', isset($_POST['member_city']) ? $_POST['member_city'] : '');
            $member->setVar('member_state', isset($_POST['member_state']) ? $_POST['member_state'] : '');
            $member->setVar('member_zip', isset($_POST['member_zip']) ? $_POST['member_zip'] : '');
            $member->setVar('member_telephone', isset($_POST['member_telephone']) ? $_POST['member_telephone'] : '');
            $member->setVar('member_fax', isset($_POST['member_fax']) ? $_POST['member_fax'] : '');
        }
		if($member_uid > 0) {
		    $password = '';
		    $member->setVar('member_password', '');
		    $member->setVar('member_verified', _SIMPLENEWSLETTER_MEMBER_VERIFIED);
		} else {
		    $password = md5(xoops_makepass());
            $member->setVar('member_password', $password);
            if(simplenewsletter_utils::getModuleOption('auto_approve') == 1) {
                $member->setVar('member_verified', _SIMPLENEWSLETTER_MEMBER_VERIFIED);
            } else {
                $member->setVar('member_verified', _SIMPLENEWSLETTER_MEMBER_NOTVERIFIED);
            }
		}
        $member->setVar('member_email', $_POST['member_email']);
		$member->setVar('member_temporary', 0);
		if(isset($_POST['member_user_password']) && $member_uid == 0) {
		    $member->setVar('member_user_password', $_POST['member_user_password']);
		} else {
		    $member->setVar('member_user_password', '');
		}
		$res = $this->insert($member, true);
		if($res) {
		    if($member_uid == 0 && simplenewsletter_utils::getModuleOption('auto_approve') == 0) {    // Il faut envoyer l'email de confirmation
		        $this->sendConfirmationMessage($member, $password);
		    } elseif(simplenewsletter_utils::getModuleOption('welcome_email')) {	                 // On souhaite la bienvenue aux nouveaux
		        $this->sayWelcome($member);
		    }
		}
		return $res;
	}

	/**
	 * Inscription d'un utilisateur à la newsletter
	 *
	 * @param integer $uid	L'id utilisateur
	 * @return boolean
	 */
	function subscribeUser($uid = 0)
	{
		if($uid == 0) {
			$uid = simplenewsletter_utils::getCurrentUserID();
		}
		return $this->subscribeMember($uid);
	}

    /**
     * Inscription d'un anonyme à la newsletter
     *
     * @return boolean
     * @since 2.0.2009.03.04
     */
	function subscribeAnonymous()
	{
        return $this->subscribeMember(0);
	}

	/**
	 * Retourne la liste des x derniers utilisateurs inscrits
	 *
	 * @param integer $start	Position de départ
	 * @param integer $limit	Nombre d'enregistrements à renvoyer
	 * @param string $sort	Zone de tri
	 * @param string $order	Sens du tri
	 * @param boolean $withXoopsUsers	Indique s'il faut renvoyer l'utilisateur Xoops correspondant
	 * @return array
	 */
	function getLastSubscribedUsers($start = 0, $limit = 0, $sort = 'sub_date', $order= 'DESC', $withXoopsUsers = true)
	{
		$ret = array();
        $criteria = new CriteriaCompo();
		$criteria->add(new Criteria('member_temporary', 0, '='));
		$criteria->add(new Criteria('member_verified', _SIMPLENEWSLETTER_MEMBER_VERIFIED, '='));

		$criteria->setSort($sort);
		$criteria->setLimit($limit);
		$criteria->setStart($start);
		$criteria->setOrder($order);
		$users = $this->getObjects($criteria, true);
		if($withXoopsUsers && count($users) > 0) {
			$xoopsUsers = simplenewsletter_utils::getUsersFromIds(array_keys($users));
		}

		foreach($users as $user) {
			$data = $user->toArray();
			if($withXoopsUsers && $user->getVar('member_uid') > 0 && isset($xoopsUsers[$user->getVar('member_uid')])) {
				$data['xoopsUser']['name'] = $xoopsUsers[$user->getVar('member_uid')]->getVar('name');
				$data['xoopsUser']['uname'] = $xoopsUsers[$user->getVar('member_uid')]->getVar('uname');
			}
			$ret[] = $data;
		}
		return $ret;
	}

    /**
     * Envoi un email d'au revoir à un membre inscrit
     *
     * @param string $member_email	L'adresse email de la personne a qui dire au revoir
     * @return void
     */
	function sayGoodbye($member_email)
	{
	    $memberForTemplate = array();
	    $member = null;
	    $member = $this->getMemberFromEmail($member_email);
	    if(is_object($member)) {
    		$memberForTemplate = $this->getMemberVariablesForTemplate($member);
	    }
        simplenewsletter_utils::sendEmailFromTpl(SIMPLENEWSLETTER_MESSAGE2_PATH, $member_email, _SIMPLENEWSLETTER_BYEBYE_TITLE, $memberForTemplate, XOOPS_UPLOAD_PATH, true);
	}

    /**
     * Désinscription d'un membre de la newsletter
     *
     * @param simplenewsletter_members $member
     * @return boolean	Le résultat de la suppression
     * @since 2.0.2009.03.04
     */
	private function unSubscribeMember(simplenewsletter_members $member)
	{
		if(is_object($member)) {
		    $member_email = $member->getVar('member_email');
		}
		if(simplenewsletter_utils::getModuleOption('byebye_email')) {	// On dit au revoir
			$this->sayGoodbye($member_email);
		}
		$res = $this->delete($member, true);
		return $res;
	}

	/**
	 * Désinscription d'un membre à la newsletter
	 *
	 * @param integer $uid	L'id utilisateur
	 * @return boolean
	 */
	function unSubscribeUser($uid = 0)
	{
		if($uid == 0) {
			$uid = simplenewsletter_utils::getCurrentUserID();
		}
		$member = $this->getMemberSubscription($uid);
		return $this->unSubscribeMember($member);
	}

    /**
     * Désinscription d'un anonyme à la newsletter
     *
     * @param string $member_email
     * @param string $member_user_password
     * @return boolean
     * @since 2.0.2009.03.04
     */
	function unSubscribeAnonymousUser(simplenewsletter_members $member)
	{
	    $res = $this->unSubscribeMember($member);
	    if($res) {
	        if(isset($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME])) {
	            $_SESSION[_SIMPLENEWSLETTER_SESSION_NAME] = null;
	            unset($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME]);
	        }
	    }
	    return $res;
	}

    /**
     * Désinscription d'un membre depuis son objet
     *
     * @param simplenewsletter_members $member
     * @return boolean
     */
	function unSubscribeFromObject(simplenewsletter_members $member)
	{
        return $this->unSubscribeMember($member);
	}

	/**
	 * Indique l'état d'inscription
	 *
	 * @return integer	Un entier qui indique l'état d'inscription
	 */
	function getSubscriptionState()
	{
		$uid = simplenewsletter_utils::getCurrentUserID();
		if($uid == 0) {
		    if(isset($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME])) {
			    return _SIMPLENEWSLETTER_STATE_4;
		    } else {
                return _SIMPLENEWSLETTER_STATE_1;
		    }
		} else {
			if($this->isUserSubscribed()) {
				return _SIMPLENEWSLETTER_STATE_2;
			} else {
				return _SIMPLENEWSLETTER_STATE_3;
			}
		}
	}


    /**
     * Arrête l'envoi de la newsletter courante
     *
     * @return boolean	Le résultat de la mise à jour
     */
	function stopSending(simplenewsletter_news $newsletter)
	{
	    $news_id = $newsletter->getVar('news_id');
        $sql = 'INSERT IGNORE INTO '.$this->db->prefix('simplenewsletter_sent')." (sent_id, sent_news_id, sent_uid) SELECT 0, $news_id, uid FROM ".$this->table;
        $result = $this->db->queryF($sql);
        if($result) {
            $this->forceCacheClean();
        }
        return $result;
	}

	/**
	 * Reprend l'envoi de la newsletter
	 *
	 * @return boolean	Le résultat de la mise à jour
	 */
	function startSending(simplenewsletter_news $newsletter)
	{
	    $news_id = $newsletter->getVar('news_id');
        $sql = 'DELETE FROM '.$this->db->prefix('simplenewsletter_sent').' WHERE sent_news_id = '.$news_id;
        $result = $this->db->queryF($sql);
        if($result) {
            $this->forceCacheClean();
        }
        return $result;
	}

    /**
     * Supprime les envois d'une newsletter
     *
     * @param simplenewsletter_news $newsletter
     * @return boolean
     */
	function deleteSent(simplenewsletter_news $newsletter)
	{
	    return $this->startSending($newsletter);
	}

    /**
     * Création des enregistrements permettant d'indiquer qu'une newsletter a été envoyée à une série d'inscrits
     *
     * @param array $usersIds
     * @param integer	$news_id
     * @return boolean
     */
	function setSentUsers($usersIds, $news_id)
	{
	    $news_id = intval($news_id);
        $sql = 'INSERT IGNORE INTO '.$this->db->prefix('simplenewsletter_sent')." (sent_id, sent_news_id, sent_uid) (SELECT 0, $news_id, uid FROM ".$this->table." WHERE ".$this->db->prefix('simplenewsletter_members').".uid IN (".implode(',', $usersIds).'))';
	    $result = $this->db->queryF($sql);
        if($result) {
            $this->forceCacheClean();
        }
        return $result;
	}


    /**
     * Retourne la liste des utilisateurs qui n'ont pas reçu la newsletter
     *
     * @param object $newsletter
     * @param integer $start
     * @param integer $limit
     * @return array Objets de type simplenewsletter_members
     */
	function getNotSentUsers(simplenewsletter_news $newsletter, $start, $limit)
	{
	    global $xoopsDB;
	    $ret = array();
        //$sql = "SELECT m.uid FROM ".$this->table." m LEFT OUTER JOIN ".$xoopsDB->prefix('simplenewsletter_sent')." s ON (m.uid = s.sent_uid) WHERE (s.sent_news_id = ".$newsletter->news_id.") OR ISNULL(s.sent_uid)";
        $sql = 'SELECT * FROM '.$this->table." WHERE member_verified = 1 AND uid NOT IN (SELECT sent_uid FROM ".$xoopsDB->prefix('simplenewsletter_sent')." WHERE sent_news_id=".$newsletter->news_id.')';
        $result = $this->db->query($sql, $limit, $start);
        if(!$result) {
            return $ret;
        }
        while ($row = $this->db->fetchArray($result)) {
            $obj =& $this->create(false);
            $obj->assignVars($row);
            $ret[] =& $obj;
            unset($obj);
        }
        return $ret;
	}


    /**
     * Envoi, par paquets, les newsletters aux inscrits
     *
     * @param simplenewsletter_news $newsletter
     * @return mixed
     */
	function sendThemNewsletter(simplenewsletter_news $newsletter)
	{
        $start = 0;
        $limit = $newsletter->getVar('news_paquets');
        $members = array();
	    $members = $this->getNotSentUsers($newsletter, $start, $limit);
		if(count($members) > 0) {
			$emails = $usersIds = array();
    		if(function_exists('xoops_getMailer')) {
	    		$xoopsMailer =& xoops_getMailer();
    		} else {
		    	$xoopsMailer =& getMailer();
    		}
    		require_once XOOPS_ROOT_PATH.'/class/template.php';

    		foreach($members as $member) {
    		    $xoopsMailer->reset();
			    $xoopsMailer->useMail();
			    $xoopsMailer->setFromEmail(simplenewsletter_utils::getModuleOption('sender_email'));
			    $xoopsMailer->setFromName(simplenewsletter_utils::getModuleOption('sender_name'));
   			    $xoopsMailer->setSubject($newsletter->getVar('news_title', 'n'));
   			    $body = $newsletter->getVar('news_body');
   			    if($newsletter->getVar('news_html') == 1) {
   			        $Tpl = new XoopsTpl();
   			        $Tpl->assign('title', $newsletter->getVar('news_title'));
   			        $Tpl->assign('body', $body);
   			        $body = $Tpl->fetch('db:simplenewsletter_html_model.html');
   			    }
			    $xoopsMailer->setBody($body);
			    if(xoops_trim($newsletter->getVar('news_attachment')) != '') {
			        $xoopsMailer->multimailer->ClearAttachments();
			        $attachedFile = simplenewsletter_utils::getModuleOption('attach_path').DIRECTORY_SEPARATOR.xoops_trim($newsletter->getVar('news_attachment'));
			        $xoopsMailer->multimailer->AddAttachment($attachedFile, _SIMPLENEWSLETTER_FILE, 'base64', $newsletter->getVar('news_mime'));
			    }
			    $variables = array();
			    $variables = $this->getMemberVariablesForTemplate($member);
		        if(is_array($variables) && count($variables) > 0) {
			        foreach($variables as $key => $value) {
				        $xoopsMailer->assign($key, $value);
			        }
		        }
			    if($newsletter->getVar('news_html')) {
    				$xoopsMailer->multimailer->isHTML(true);
			    }
			    $xoopsMailer->setToEmails($member->getVar('member_email'));
			    if($xoopsMailer->send()) {
    			    $usersIds[] = $member->getVar('uid');
			    }
			}
			$usersSentCount = count($usersIds);    // Nombre de personnes à qui on a envoyé la newsletter
			if ( $usersSentCount > 0) {
			    $this->setSentUsers($usersIds, $newsletter->news_id);    // Marquage des utilisateurs à qui on a envoyé la newsletter
			    return $usersSentCount;
			}
			return false;
		}
		$this->forceCacheClean();
		return 0;
	}

    /**
     * Inscription de tous les membres du site à la newsletter
     *
     * @return boolean	Le résultat de l'insertion
     */
	function subscribeAll()
	{
	    $sql = 'DELETE FROM '.$this->table.' WHERE member_uid <> 0';
	    $result = $this->db->queryF($sql);

	    $sql = 'INSERT IGNORE INTO '.$this->table." (uid, sub_date, member_sent, member_uid, member_firstname, member_lastname, member_password, member_verified, member_email, member_temporary, member_user_password) SELECT 0, UNIX_TIMESTAMP(), 0, uid, uname, '', '', 1, email, 0, ''  FROM ".$this->db->prefix('users');
	    $result = $this->db->queryF($sql);
        if($result) {
            $this->forceCacheClean();
        }
        return $result;
	}

    /**
     * Désinscription de tous les membres
     *
     * @return boolean	Le résultat de la mise à jour
     */
	function unsubscribeAll()
	{
        $sql = 'TRUNCATE TABLE '.$this->table;
        $result = $this->db->queryF($sql);
        if($result) {
            $this->forceCacheClean();
        }
        return $result;
	}

    /**
     * Retourne les informations d'un membre à partir de son numéro d'utilisateur Xoops
     *
     * @param integer $uid	L'identifiant de l'utilisateur
     * @return mixed	Soit un objet de type simplenewsletter_members si l'utilisateur a été trouvé sinon null
     */
	function getMemberSubscription($uid = 0)
	{
        $member = null;
        $ret = array();
		if($uid == 0) {
			$uid = simplenewsletter_utils::getCurrentUserID();
		}
		$criteria = new Criteria('member_uid', $uid, '=');
		$ret = $this->getObjects($criteria);
		if(count($ret) > 0) {
		    $member = $ret[0];
		}
		return $member;
	}

    /**
     * Retourne l'enregistrement correspondant à un anonyme
     *
     * @param string $member_email
     * @param string $member_user_password
     * @return mixed	Soit l'object simplenewsletter_members soit null en cas d'échec
     * @since 2.0.2009.03.04
     */
	function getAnonymousSubscription($member_email, $member_user_password)
	{
	    if($this->loginAnonymousUser($member_email, $member_user_password)) {
	        return unserialize($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME]);
	    } else {
	        return null;
	    }
	}

    /**
     * Indique si un mot de passe associé à une adresse email est valide
     *
     * @param string $member_email
     * @param string $member_user_password
     * @return boolean
     * @since 2.0.2009.03.02
     */
	function isValidLogin($member_email, $member_user_password)
	{
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('member_email', $member_email, '='));
		$criteria->add(new Criteria('member_user_password', $member_user_password, '='));
		return (bool) $this->getCount($criteria);
	}

	/**
	 * Connecte un utilisateur anonyme et place en session les informations le concernant (l'objet simplenewsletter_members)
	 *
	 * @param string $member_email
	 * @param string $member_user_password
	 * @return boolean	True si l'utilisateur a été trouvé
	 * @since 2.0.2009.03.03
	 */
	function loginAnonymousUser($member_email, $member_user_password)
	{
	    $members = array();
	    $ret = false;
	    $criteria = new CriteriaCompo();
		$criteria->add(new Criteria('member_email', $member_email, '='));
		$criteria->add(new Criteria('member_user_password', $member_user_password, '='));
		$members = $this->getObjects($criteria);
		if(count($members) > 0) {
            $member = $members[0];
            $_SESSION[_SIMPLENEWSLETTER_SESSION_NAME] = serialize($member);
            $ret = true;
		}
		return $ret;
	}

    /**
     * Recharge les informations d'un anonyme en session
     *
     * @param simplenewsletter_members $member
     * @return void
     */
	function reloadAnonymousInformation(simplenewsletter_members $member)
	{
	    if(isset($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME])) {
	        $_SESSION[_SIMPLENEWSLETTER_SESSION_NAME] = null;
	        unset($_SESSION[_SIMPLENEWSLETTER_SESSION_NAME]);
            $_SESSION[_SIMPLENEWSLETTER_SESSION_NAME] = serialize($member);
	    }
	}

    /**
     * Retourne un membre à partir de son adresse email
     *
     * @param string $member_email
     * @return mixed	Soit un objet de type simplenewsletter_members soit null si le membre n'est pas trouvé
     */
	function getMemberFromEmail($member_email)
	{
	    $ret = array();
	    $criteria = new Criteria('member_email', $member_email, '=');
	    $ret = $this->getObjects($criteria);
	    if(count($ret) > 0) {
	        return $ret[0];
	    }
	    return null;
	}

    /**
     * Retourne un membre depuis son mot de passe de validation
     *
     * @param string $member_password
     * @return objet de type simplenewsletter_members
     * @since 2.0.2009.03.06
     */
	function getMemberFromValidationCode($member_password)
	{
	    $ret = array();
	    $criteria = new Criteria('member_password', $member_password, '=');
	    $ret = $this->getObjects($criteria);
	    if(count($ret) > 0) {
	        return $ret[0];
	    }
	    return null;
	}

    /**
     * Retourne le nombre total de personnes inscrites au site (que l'inscription soit validée ou pas et que ce soit un anonyme ou pas)
     *
     * @return integer
     * @since 2.0.2009.03.06
     */
	function getTotalMembersCount()
	{
        return $this->getCount();
	}

    /**
     * Retourne le nombre total de personnes dont l'inscription est validée
     *
     * @return integer
     * @since 2.0.2009.03.06
     */
	function getTotalMembersVerified()
	{
        $criteria = new Criteria('member_verified', _SIMPLENEWSLETTER_MEMBER_VERIFIED, '=');
        return $this->getCount($criteria);
	}

    /**
     * Retourne le nombre total de personnes dont l'inscription n'est pas validée
     *
     * @return integer
     * @since 2.0.2009.03.06
     */
	function getTotalMembersNotVerified()
	{
        $criteria = new Criteria('member_verified', _SIMPLENEWSLETTER_MEMBER_NOTVERIFIED, '=');
        return $this->getCount($criteria);
	}

	/**
	 * Retourne le nombre de personnes, membres du site, qui sont inscrites
	 *
	 * @return integer
	 * @since 2.0.2009.03.06
	 */
	function getTotalMemberSiteCount()
	{
        $criteria = new Criteria('member_uid', 0, '<>');
        return $this->getCount($criteria);
	}

	/**
	 * Retourne le nombre de personnes, NON membre du site, qui sont inscrites
	 *
	 * @return integer
	 * @since 2.0.2009.03.06
	 */
	function getTotalNotMemberSiteCount()
	{
        $criteria = new Criteria('member_uid', 0, '=');
        return $this->getCount($criteria);
	}
}
?>