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

if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

include_once XOOPS_ROOT_PATH.'/class/xoopsobject.php';
if (!class_exists('Simplenewsletter_XoopsPersistableObjectHandler')) {
	include_once XOOPS_ROOT_PATH.'/modules/simplenewsletter/class/PersistableObjectHandler.php';
}

define("_SIMPLENEWSLETTER_NEWSLETTER_SENT", 1);
define("_SIMPLENEWSLETTER_NEWSLETTER_NOTSENT", 0);

class simplenewsletter_news extends Simplenewsletter_Object {

	function __construct()
	{
		$this->initVar('news_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('news_title', XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar('news_body', XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar('news_date', XOBJ_DTYPE_INT, null, false);
		$this->initVar('news_html', XOBJ_DTYPE_INT, null, false);
        $this->initVar('news_uid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('news_sent', XOBJ_DTYPE_INT, null, false);
        $this->initVar('news_paquets', XOBJ_DTYPE_INT, null, false);
        $this->initVar('news_members_sent', XOBJ_DTYPE_INT, null, false);
        $this->initVar('news_attachment', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('news_mime', XOBJ_DTYPE_TXTBOX, null, false);
		// Pour autoriser le html
		$this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
	}

	/**
	 * Retourne la date de publication de la newsletter formatée
	 *
	 * @return string
	 */
	function getFormatedDate()
	{
		return formatTimestamp($this->news_date, 's');
	}

	/**
	 * Retourne l'URL d'une newsletter (en tenant compte des préférence d'url rewriting)
	 *
	 * @param boolean $shortFormat	Indique s'il faut renvoyer l'url au format long (avec le chemin complet) ou au format court
	 * @return string	L'URL
	 */
	function getUrl($shortFormat = false)
	{
		$url = '';
		if(simplenewsletter_utils::getModuleOption('urlrewriting') == 1) {
		    if(!$shortFormat) {
			    $url = SIMPLENEWSLETTER_URL.'newsletter-'.$this->getVar('news_id').simplenewsletter_utils::makeSeoUrl($this->getVar('news_title')).'.html';
		    } else {
                $url = 'newsletter-'.$this->getVar('news_id').simplenewsletter_utils::makeSeoUrl($this->getVar('news_title')).'.html';
		    }
		} else {
		    if(!$shortFormat) {
			    $url = SIMPLENEWSLETTER_URL.'newsletter.php?news_id='.$this->getVar('news_id');
		    } else {
                $url = 'newsletter.php?news_id='.$this->getVar('news_id');
		    }
		}
		return $url;
	}

	/**
	 * Retourne la chaine de caractères qui peut être utilisée dans l'attribut href d'une balise html A.
	 *
	 * @return string
	 */
	function getHrefTitle()
	{
		return simplenewsletter_utils::makeHrefTitle($this->getVar('news_title'));
	}

	/**
	 * Retourne le contenu abrégé de la newsletter
	 *
	 * @return string
	 */
	function getShortContent()
	{
		return simplenewsletter_utils::truncate_tagsafe($this->getVar('news_body'), simplenewsletter_utils::getModuleOption('char_cut'));
	}

	/**
	 * Retourne les éléments formatés pour affichage
	 *
	 * @param string $format	Format à utiliser
	 * @return array
	 */
	function toArray($format = 's')
	{
		$ret = array();
		$ret = parent::toArray($format);
		$ret['news_attachment_full_url'] = simplenewsletter_utils::getModuleOption('attach_url').'/'.$this->getVar('news_attachment');
		$ret['news_href_title'] = $this->getHrefTitle();
		$ret['news_url_rewrited'] = $this->getURL();
		$ret['news_formated_date'] = $this->getFormatedDate();
		$ret['news_shorted_newsletter'] = $this->getShortContent();
		$this->getFormatedDate();
		return $ret;
	}
}



class SimplenewsletterSimplenewsletter_newsHandler extends Simplenewsletter_XoopsPersistableObjectHandler
{
	function __construct($db)
	{	//								Table					Class				Id			Descr.
		parent::__construct($db, 'simplenewsletter_news', 'simplenewsletter_news', 'news_id', 'news_title');
	}

	/**
	 * Retourne les dernières newsletter
	 *
	 * @param integer $start	Position de départ
	 * @param integer $limit	Nombre d'enregsitrements
	 * @param string $sort	Zone de tri
	 * @param string $order	Sens du tri
	 * @param boolean $onlySent	Indique s'il ne faut renvoyer que les newsletter envoyées
	 * @return array	Objets de type simplenewsletter_news
	 */
	function getLastNews($start = 0, $limit = 0, $sort = 'news_date', $order = 'DESC', $onlySent = true)
	{
	    if($onlySent) {
		    $criteria = new Criteria('news_sent', _SIMPLENEWSLETTER_NEWSLETTER_SENT, '=');
	    } else {
            $criteria = new Criteria('news_id', 0, '<>');
	    }
		$criteria->setStart($start);
		$criteria->setLimit($limit);
		$criteria->setSort($sort);
		$criteria->setOrder($order);
		return $this->getObjects($criteria);
	}

    /**
     * Arrête l'envoi d'une newsletter
     *
     * @param simplenewsletter_news $newsletter
     * @return boolean	Le résultat de la mise à jour
     */
	function stopSendingMe(simplenewsletter_news $newsletter)
	{
        $newsletter->setVar('news_sent', _SIMPLENEWSLETTER_NEWSLETTER_SENT);
        return $this->insert($newsletter, true);
	}

    /**
     * Reprend l'envoi d'une newsletter
     *
     * @param simplenewsletter_news $newsletter
     * @return boolean Le résultat de la mise à jour
     */
	function startSendingMe(simplenewsletter_news $newsletter)
	{
        $newsletter->setVar('news_sent', _SIMPLENEWSLETTER_NEWSLETTER_NOTSENT);
        return $this->insert($newsletter, true);
	}

    /**
     * Retourne le nombre de newsletters publiées
     *
     * @return integer
     */
	function getNewsletterSentCount()
	{
	    return $this->getCount(new Criteria('news_sent', _SIMPLENEWSLETTER_NEWSLETTER_SENT, '='));
	}

	/**
	 * Retourne le nombre de newsletters qui ne sont pas parties
	 *
	 * @return unknown
	 */
	function getNewslettersNotSentCount()
	{
	    return $this->getCount(new Criteria('news_sent', _SIMPLENEWSLETTER_NEWSLETTER_NOTSENT, '='));
	}

    /**
     * Retourne sous forme d'objets, les newsletter en attente d'envoi
     *
     * @return array	Objets de type simplenewsletter_news
     */
	function getWaitingNewsletters()
	{
	    return $this->getObjects(new Criteria('news_sent', _SIMPLENEWSLETTER_NEWSLETTER_NOTSENT, '='));
	}

    /**
     * Mise à jour du nombre d'envois faits pour une newsletter
     *
     * @param simplenewsletter_news $newsletter
     * @param integer $count
     * @return boolean	Le résultat de la mise à jour
     */
	function updateNewsletterMembersSentCount(simplenewsletter_news $newsletter, $count)
	{
	    $count = intval($count);
	    $sql = 'UPDATE '.$this->table.' SET news_members_sent = news_members_sent + '.$count.' WHERE news_id = '.$newsletter->getVar('news_id');
	    $res = $this->db->queryF($sql);
	    if($res) {
	        $this->forceCacheClean();
	    }
	    return $res;
	}


    /**
     * Fonction chargée d'envoyer les newsletters en attente
     *
     *  @return boolean
     */
	function sendWaitingNewsletters()
	{
	    $this->forceCacheClean();
        if($this->getNewslettersNotSentCount() == 0) {    // S'il n'y a plus de newsletter en attente
            return true;
        }
        global $h_simplenewsletter_members, $h_simplenewsletter_sent;
        if(!is_object($h_simplenewsletter_members) || !is_object($h_simplenewsletter_sent)) {
            $simplenewsletter_handler = simplenewsletter_handler::getInstance();
            $h_simplenewsletter_members = $simplenewsletter_handler->h_simplenewsletter_members;
            $h_simplenewsletter_sent = $simplenewsletter_handler->h_simplenewsletter_sent;
        }
        $newsletters = array();
        $newsletters = $this->getWaitingNewsletters();
        if(count($newsletters) > 0) {
            foreach($newsletters as $newsletter) {
                $ret = $h_simplenewsletter_members->sendThemNewsletter($newsletter);
                if($ret === 0) {    // Il n'y a plus personne à qui envoyer la newsletter
                    $newsletter->setVar('news_sent', _SIMPLENEWSLETTER_NEWSLETTER_SENT);
                    $h_simplenewsletter_sent->deleteSentForNewsletter($newsletter->getVar('news_id'));
                    $this->insert($newsletter, true);
                } else {    // Mise à jour du nombre d'envois fait
                    if($ret > 0) {
                        $this->updateNewsletterMembersSentCount($newsletter, $ret);
                    }
                }
            }
            $this->forceCacheClean();
            simplenewsletter_utils::updateCache();
        }
        return true;
	}
}
?>