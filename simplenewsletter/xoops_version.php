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

$modversion['name'] = _MI_SIMPLENEWSLETTER_NAME;
$modversion['version'] = 2.32;
$modversion['description'] = _MI_SIMPLENEWSLETTER_DESC;
$modversion['author'] = "Hervé Thouzard (http://www.herve-thouzard.com)";
$modversion['credits'] = "Klaus Lamort, Charles Benninghoff";
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0 or later';
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html/";
$modversion['official'] = 0;
$modversion['image'] = 'images/news_subscribe.png';
$modversion['dirname'] = 'simplenewsletter';


$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16']        = '../../Frameworks/moduleclasses/icons/16';
$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';
//about
$modversion['release_date']        = '2013/01/12';
$modversion["module_website_url"]  = "www.xoops.org";
$modversion["module_website_name"] = "XOOPS";
$modversion["module_status"]       = "Beta 1";
$modversion['min_php']             = '5.2';
$modversion['min_xoops']           = "2.5.5";
$modversion['min_admin']           = '1.1';
$modversion['min_db']              = array(
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7'
);

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][0] = 'simplenewsletter_members';
$modversion['tables'][1] = 'simplenewsletter_news';
$modversion['tables'][2] = 'simplenewsletter_sent';

$modversion['hasAdmin'] = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

// ********************************************************************************************************************
// Blocks *************************************************************************************************************
// ********************************************************************************************************************
$cptb = 0;

/**
 * Liste des dernières newsletter
 */
$cptb++;
$modversion['blocks'][$cptb]['file'] = 'block_simplenewsletter_lastnews.php';
$modversion['blocks'][$cptb]['name'] = _MI_SIMPLENEWSLETTER_BNAME1;
$modversion['blocks'][$cptb]['description'] = '';
$modversion['blocks'][$cptb]['show_func'] = 'b_sn_lastnews_show';
$modversion['blocks'][$cptb]['edit_func'] = 'b_sn_lastnews_edit';
$modversion['blocks'][$cptb]['options'] = '5';	// Nombre d'éléments visibles simultanément
$modversion['blocks'][$cptb]['template'] = 'simplenewsletter_block_lastnews.html';

/**
 * Liste les derniers inscrits
 */
$cptb++;
$modversion['blocks'][$cptb]['file'] = 'block_simplenewsletter_members.php';
$modversion['blocks'][$cptb]['name'] = _MI_SIMPLENEWSLETTER_BNAME2;
$modversion['blocks'][$cptb]['description'] = '';
$modversion['blocks'][$cptb]['show_func'] = 'b_sn_lastmembers_show';
$modversion['blocks'][$cptb]['edit_func'] = 'b_sn_lastmembers_edit';
$modversion['blocks'][$cptb]['options'] = '5';	// Nombre d'éléments visibles simultanément
$modversion['blocks'][$cptb]['template'] = 'simplenewsletter_block_lastmembers.html';

/**
 * Bloc permettant de s'inscrire ou de se désinscrire
 */
$cptb++;
$modversion['blocks'][$cptb]['file'] = 'block_simplenewsletter_subscribe.php';
$modversion['blocks'][$cptb]['name'] = _MI_SIMPLENEWSLETTER_BNAME3;
$modversion['blocks'][$cptb]['description'] = '';
$modversion['blocks'][$cptb]['show_func'] = 'b_sn_subscribe_show';
$modversion['blocks'][$cptb]['edit_func'] = '';
$modversion['blocks'][$cptb]['options'] = '';
$modversion['blocks'][$cptb]['template'] = 'simplenewsletter_block_subscribe.html';

/**
 * CRON
 */
$cptb++;
$modversion['blocks'][$cptb]['file'] = 'block_simplenewsletter_cron.php';
$modversion['blocks'][$cptb]['name'] = _MI_SIMPLENEWSLETTER_BNAME4;
$modversion['blocks'][$cptb]['description'] = '';
$modversion['blocks'][$cptb]['show_func'] = 'b_sn_cron_show';
$modversion['blocks'][$cptb]['edit_func'] = '';
$modversion['blocks'][$cptb]['options'] = '';
$modversion['blocks'][$cptb]['template'] = 'simplenewsletter_block_cron.html';

/*
 * $options:
 *					$options[0] - number of tags to display
 *					$options[1] - time duration, in days, 0 for all the time
 *					$options[2] - max font size (px or %)
 *					$options[3] - min font size (px or %)
 */
$modversion['blocks'][]	= array(
	'file'			=> 'simplenewsletter_block_tag.php',
	'name'			=> 'Module Tag Cloud',
	'description'	=> 'Show tag cloud',
	'show_func'		=> 'simplenewsletter_tag_block_cloud_show',
	'edit_func'		=> 'simplenewsletter_tag_block_cloud_edit',
	'options'		=> '100|0|150|80',
	'template'		=> 'simplenewsletter_tag_block_cloud.html',
	);
/*
 * $options:
 *					$options[0] - number of tags to display
 *					$options[1] - time duration, in days, 0 for all the time
 *					$options[2] - sort: a - alphabet; c - count; t - time
 */
$modversion['blocks'][]	= array(
	'file'			=> 'simplenewsletter_block_tag.php',
	'name'			=> 'Module Top Tags',
	'description'	=> 'Show top tags',
	'show_func'		=> 'simplenewsletter_tag_block_top_show',
	'edit_func'		=> 'simplenewsletter_tag_block_top_edit',
	'options'		=> '50|30|c',
	'template'		=> 'simplenewsletter_tag_block_top.html',
	);


// ********************************************************************************************************************
// Menu ***************************************************************************************************************
// ********************************************************************************************************************
$modversion['hasMain'] = 1;
$cptm = 0;

$cptm++;
$modversion['sub'][$cptm]['name'] = _MI_SIMPLENEWSLETTER_SMNAME2;
$modversion['sub'][$cptm]['url'] = 'subscription.php';

$cptm++;
$modversion['sub'][$cptm]['name'] = _MI_SIMPLENEWSLETTER_SMNAME3;
$modversion['sub'][$cptm]['url'] = 'rss.php';

$cptm++;
$modversion['sub'][$cptm]['name'] = _MI_SIMPLENEWSLETTER_SMNAME4;
$modversion['sub'][$cptm]['url'] = 'forgotten.php';

// ********************************************************************************************************************
// Recherche **********************************************************************************************************
// ********************************************************************************************************************
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'simplenewsletter_search';

// ********************************************************************************************************************
// Commentaires *******************************************************************************************************
// ********************************************************************************************************************
$modversion['hasComments'] = 0;


// ********************************************************************************************************************
// Templates **********************************************************************************************************
// ********************************************************************************************************************
$cptt = 0;

$cptt++;
$modversion['templates'][$cptt]['file'] = 'simplenewsletter_html_model.html';
$modversion['templates'][$cptt]['description'] = "Pour les newsletters au format html";

$cptt++;
$modversion['templates'][$cptt]['file'] = 'simplenewsletter_index.html';
$modversion['templates'][$cptt]['description'] = "Page d'index du module";

$cptt++;
$modversion['templates'][$cptt]['file'] = 'simplenewsletter_news.html';
$modversion['templates'][$cptt]['description'] = "Page d'une newsletter";

$cptt++;
$modversion['templates'][$cptt]['file'] = 'simplenewsletter_rss.html';
$modversion['templates'][$cptt]['description'] = "Flux RSS";

$cptt++;
$modversion['templates'][$cptt]['file'] = 'simplenewsletter_subscription.html';
$modversion['templates'][$cptt]['description'] = "Etat de la souscription";

$cptt++;
$modversion['templates'][$cptt]['file'] = 'simplenewsletter_forgotten.html';
$modversion['templates'][$cptt]['description'] = "Mot de page oublié";

// ********************************************************************************************************************
// Préférences ********************************************************************************************************
// ********************************************************************************************************************
$cpto = 0;

/**
 * Editor to use
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'form_options';
$modversion['config'][$cpto]['title'] = "_MI_SIMPLENEWSLETTER_FORM_OPTIONS";
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'select';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['options'] = array(
											_MI_SIMPLENEWSLETTER_FORM_DHTML=>'dhtmltextarea',
											_MI_SIMPLENEWSLETTER_FORM_COMPACT=>'textarea',
											_MI_SIMPLENEWSLETTER_FORM_HTMLAREA=>'htmlarea',
											_MI_SIMPLENEWSLETTER_FORM_KOIVI=>'koivi',
											_MI_SIMPLENEWSLETTER_FORM_FCK=>'fckeditor',
											_MI_SIMPLENEWSLETTER_FORM_TINYEDITOR=>'tinyeditor',
											'tinymce' => 'tinymce'
											);
$modversion['config'][$cpto]['default'] = 'dhtmltextarea';


/**
 * Nombre d'éléments par page
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'perpage';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_PERPAGE';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 15;

/**
 * Adresse email de l'expéditeur
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'sender_email';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_SENDER_EMAIL';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = '';

/**
 * Nom de l'expéditeur
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'sender_name';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_SENDER_NAME';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = '';

/**
 * Voulez-vous utiliser l'url rewriting ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'urlrewriting';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_URL_REWRITE';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;


/**
 * Nombre de caractères à afficher, pour chaque newsletter, dans la page d'index
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'char_cut';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_CHAR_CUT';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 500;

/**
 * Envoyer un email de bienvenue ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'welcome_email';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_WELCOME_EMAIL';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 1;

/**
 * Envoyer un email d'au revoir ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'byebye_email';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_BYEBYE_EMAIL';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 1;


/**
 * Envoyer par paquets de X
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'paquet_size';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_PACKET_SIZE';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 500;

/**
 * Password for CRON
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'cron_password';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_CRON_PASSWORD';
$modversion['config'][$cpto]['description'] = '_MI_SIMPLENEWSLETTER_CRON_PASSWORD_DSC'.' '.XOOPS_URL.'/modules/simplenewsletter/cron.php?password=aqwxszedc123';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = xoops_makepass();


/**
 * Voulez-vous utiliser le système de TAGS ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'use_tags';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_USE_TAGS';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;

/**
 * Ouvrir les inscriptions aux personnes qui ne sont pas membres du site ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'open_subscriptions';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_OPEN_SUBSCRIPTION';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;

/**
 * Approuver automatiquement les inscriptions des non membres ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'auto_approve';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_AUTO_APPROVE';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;

/**
 * Voulez-vous utiliser un captcha sur le formulaire d'inscription ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'use_captcha';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_USE_CAPTCHA';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;

/**
 * Envoyer un double des messages de validation ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'validatemail_bcc';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_VALIDATE_BCC';
$modversion['config'][$cpto]['description'] = '_MI_SIMPLENEWSLETTER_VALIDATE_BCC_DSC';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = '';

/**
 * Mime Types
 * Default values : Web pictures (png, gif, jpeg), zip, pdf, gtar, tar, pdf
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'mimetypes';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_MIME_TYPE';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textarea';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = "image/gif\nimage/jpeg\nimage/pjpeg\nimage/x-png\nimage/png\napplication/x-zip-compressed\napplication/zip\napplication/pdf\napplication/x-gtar\napplication/x-tar\napplication/msexcel\napplication/vnd.ms-excel\napplication/msword";

/**
 * MAX Filesize Upload in kilo bytes
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'maxuploadsize';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_MAX_UPLOAD';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 1048576;

/**
 * Path where to save attached files
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'attach_path';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_PATH_ATTACHMENTS';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = XOOPS_UPLOAD_PATH;

/**
 * URL where to save attached files
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'attach_url';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_URL_ATTACHMENTS';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = XOOPS_UPLOAD_URL;

/**
 * Séparateur CSV
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'csv_sep';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_CSV_SEP';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'text';
$modversion['config'][$cpto]['default'] = '|';

/**
 * Voulez-vous utiliser les champs supplémentaires ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'additional_fields';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_NEW_FIELDS';
$modversion['config'][$cpto]['description'] = '_MI_SIMPLENEWSLETTER_NEWS_FIELDS_DSC';
$modversion['config'][$cpto]['formtype'] = 'yesno';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 0;

/**
 * Voulez-vous utiliser les champs supplémentaires ?
 */
$cpto++;
$modversion['config'][$cpto]['name'] = 'password_length';
$modversion['config'][$cpto]['title'] = '_MI_SIMPLENEWSLETTER_PASSWORD_MIN_LENGTH';
$modversion['config'][$cpto]['description'] = '';
$modversion['config'][$cpto]['formtype'] = 'textbox';
$modversion['config'][$cpto]['valuetype'] = 'int';
$modversion['config'][$cpto]['default'] = 5;


// ********************************************************************************************************************
// Notifications ******************************************************************************************************
// ********************************************************************************************************************
$modversion['hasNotification'] = 0;
?>