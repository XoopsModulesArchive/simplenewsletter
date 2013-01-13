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
define("_MI_SIMPLENEWSLETTER_NAME", "SimpleNewsletter");
define("_MI_SIMPLENEWSLETTER_DESC", "A simple newsletter module");

define("_MI_SIMPLENEWSLETTER_BNAME1", "Last newsletters");
define("_MI_SIMPLENEWSLETTER_BNAME2", "Last subscribers");
define("_MI_SIMPLENEWSLETTER_BNAME3", "Subscribe/Unsubcribe");
define("_MI_SIMPLENEWSLETTER_BNAME4", "CRON");

define("_MI_SIMPLENEWSLETTER_SMNAME1", "Index");
define("_MI_SIMPLENEWSLETTER_SMNAME2", "Subscription");
define("_MI_SIMPLENEWSLETTER_SMNAME3", "RSS");
define("_MI_SIMPLENEWSLETTER_SMNAME4", "Password lost");

define("_MI_SIMPLENEWSLETTER_FORM_OPTIONS","Text editor to use");
define("_MI_SIMPLENEWSLETTER_FORM_COMPACT","Compact");
define("_MI_SIMPLENEWSLETTER_FORM_DHTML","DHTML");
define("_MI_SIMPLENEWSLETTER_FORM_SPAW","Spaw Editor");
define("_MI_SIMPLENEWSLETTER_FORM_HTMLAREA","HtmlArea Editor");
define("_MI_SIMPLENEWSLETTER_FORM_FCK","FCK Editor");
define("_MI_SIMPLENEWSLETTER_FORM_KOIVI","Koivi Editor");
define("_MI_SIMPLENEWSLETTER_FORM_TINYEDITOR","TinyEditor");

define("_MI_SIMPLENEWSLETTER_PERPAGE", "Elements count per page");
define("_MI_SIMPLENEWSLETTER_SENDER_EMAIL", "Expeditor - Email address");
define("_MI_SIMPLENEWSLETTER_SENDER_NAME", "Expeditor - Name");
define("_MI_SIMPLENEWSLETTER_URL_REWRITE", "Do you want to use url rewriting ?");
define("_MI_SIMPLENEWSLETTER_CHAR_CUT", "Characters count of each newsletter to display in the module index page");
define("_MI_SIMPLENEWSLETTER_WELCOME_EMAIL", "Send a welcome message to subscribers ?");
define("_MI_SIMPLENEWSLETTER_BYEBYE_EMAIL", "Send a goodbye message to the users ?");
define("_MI_SIMPLENEWSLETTER_PACKET_SIZE", "Send emails by group of ...");

define("_MI_SIMPLENEWSLETTER_ADMENU0", "Create a newsletter");
define("_MI_SIMPLENEWSLETTER_ADMENU1", "Previous newsletters");
define("_MI_SIMPLENEWSLETTER_ADMENU2", "Subscribers");
define("_MI_SIMPLENEWSLETTER_ADMENU3", "Messages");
define("_MI_SIMPLENEWSLETTER_ADMENU4", "Texts");
define("_MI_SIMPLENEWSLETTER_ADMENU5", "CSV Import");
define("_MI_SIMPLENEWSLETTER_ADMENU6", "Blocks");

define("_MI_SIMPLENEWSLETTER_CRON_PASSWORD", "Password for CRON");
define("_MI_SIMPLENEWSLETTER_CRON_PASSWORD_DSC", "If you use a CRON system, then you <b>must</b> define a password and call the script like this : http://www.example.com/modules/simplenewsletter/cron.php?password=mypassword");

define("_MI_SIMPLENEWSLETTER_USE_TAGS", "Do you want to use the tags system ?");
define("_MI_SIMPLENEWSLETTER_OPEN_SUBSCRIPTION", "Open the subscriptions to the people who are not members of the site ?");
define("_MI_SIMPLENEWSLETTER_AUTO_APPROVE", "Automatically approve subscriptions of non members ?");
define("_MI_SIMPLENEWSLETTER_USE_CAPTCHA", "Do you want to use a CAPTCHA on the subscription form ?");
define("_MI_SIMPLENEWSLETTER_VALIDATE_BCC", "Send a blind copy of verifications email to this address ?");
define("_MI_SIMPLENEWSLETTER_VALIDATE_BCC_DSC", "Leave this area if you don't want to receive copies of validations mails");

define("_MI_SIMPLENEWSLETTER_MIME_TYPE", "Autorized mime types");
define("_MI_SIMPLENEWSLETTER_MAX_UPLOAD", "Max size of uploads files and pictures");
define("_MI_SIMPLENEWSLETTER_PATH_ATTACHMENTS", "Path where to save attached files (without trailing slash)");
define("_MI_SIMPLENEWSLETTER_URL_ATTACHMENTS", "URL where to save attached files (without trailing slash)");

define("_MI_SIMPLENEWSLETTER_CSV_SEP", "Separator for CSV export files");
define("_MI_SIMPLENEWSLETTER_NEW_FIELDS", "Do you want to use the additional fields ?");
define("_MI_SIMPLENEWSLETTER_NEWS_FIELDS_DSC", "Title, Streetaddress, City, State, Zip, Telephone, Fax");
define("_MI_SIMPLENEWSLETTER_PASSWORD_MIN_LENGTH", "Minimal password's length ?");
?>