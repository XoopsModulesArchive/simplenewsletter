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

define("_AM_SIMPLENEWSLETTER_GO_TO_MODULE", "Go to the module");
define("_AM_SIMPLENEWSLETTER_PREFERENCES", "Preferences");
define("_AM_SIMPLENEWSLETTER_MAINTAIN", "Maintain tables and cache");
define("_AM_SIMPLENEWSLETTER_ADMINISTRATION", "Administration");
define("_AM_SIMPLENEWSLETTER_SAVE_OK", "Data was saved with success");
define("_AM_SIMPLENEWSLETTER_SAVE_PB","Problem whilesaving data");
define("_AM_SIMPLENEWSLETTER_NOT_FOUND", "Error, data not found");
define("_AM_SIMPLENEWSLETTER_ACTION","Action");
define("_AM_SIMPLENEWSLETTER_NEWS_CREATE","Create a newsletter");
define("_AM_SIMPLENEWSLETTER_NEWS_EDIT","Edit a newsletter");
define("_AM_SIMPLENEWSLETTER_NEWS_PREVIEW","Preview newsletter before to send it");
define("_AM_SIMPLENEWSLETTER_NEWS_BODY","Newsletter content");
define("_AM_SIMPLENEWSLETTER_SEND_HTML","Send in html ?");
define("_AM_SIMPLENEWSLETTER_SEND_PAQUET","Send by groups of");
define("_AM_SIMPLENEWSLETTER_SEND","Send");
define("_AM_SIMPLENEWSLETTER_SAVE","Save");
define("_AM_SIMPLENEWSLETTER_DELETE_MEMBER","Do you really want to remove this member's inscription ?");
define("_AM_SIMPLENEWSLETTER_DELETE_NEWSLETTER","Do you really want to delete this newsletter ?");
define("_AM_SIMPLENEWSLETTER_MEMBERS_LIST","Subscribers list");
define("_AM_SIMPLENEWSLETTER_MESSAGES","Welcome and goodbye messages");
define("_AM_SIMPLENEWSLETTER_MESSAGE1","Welcome message");
define("_AM_SIMPLENEWSLETTER_MESSAGE2","Goodbye  message");
define("_AM_SIMPLENEWSLETTER_MESSAGE3","Text to display on the module's index page");
define("_AM_SIMPLENEWSLETTER_ERROR_1", "Error, no ID");
define("_AM_SIMPLENEWSLETTER_ERROR_2", "You have already clicked on this link");
define("_AM_SIMPLENEWSLETTER_ERROR_3", "Error, impossible to create your CSV file");
define("_AM_SIMPLENEWSLETTER_SENDING", "Actually sending newsletter");
define("_AM_SIMPLENEWSLETTER_STATUS", "Status");
define("_AM_SIMPLENEWSLETTER_STATUS_SENT", "Sent");
define("_AM_SIMPLENEWSLETTER_STATUS_NOTSENT", "Currently sending it");
define("_AM_SIMPLENEWSLETTER_STOP", "Stop");
define("_AM_SIMPLENEWSLETTER_RELAUNCH", "Relaunch");
define("_AM_SIMPLENEWSLETTER_SUBSCRIBE_ALL", "Subscribe all members of your website");
define("_AM_SIMPLENEWSLETTER_CONF_SUBSCRIBE_ALL", "Do you really want to subscribe all members of your website ?");
define("_AM_SIMPLENEWSLETTER_UNSUBSCRIBE_ALL", "Unsubscribe all members");
define("_AM_SIMPLENEWSLETTER_CONF_UNSUBSCRIBE_ALL", "Do you really want to unsubscribe all members");
define("_AM_SIMPLENEWSLETTER_MODIFY", "Modify");
define("_AM_SIMPLENEWSLETTER_ADD", "Add");
define("_AM_SIMPLENEWSLETTER_VALIDATION_MESSAGE", "Validation message sent to anonymous users");
define("_AM_SIMPLENEWSLETTER_VALIDATION_WHERE", "You will find it in ");
define("_AM_SIMPLENEWSLETTER_PROCESS", "Process ");
define("_AM_SIMPLENEWSLETTER_PROCESS_ERROR", "There was an error during this process");
define("_AM_SIMPLENEWSLETTER_PROCESS_END", "End of the upgrade process");
define("_AM_SIMPLENEWSLETTER_SUBSCRIPTION", "subscription");
define("_AM_SIMPLENEWSLETTER_VERIFIED", "Verified");
define("_AM_SIMPLENEWSLETTER_EXPORT_MEMBERS", "Export members");
define("_AM_SIMPLENEWSLETTER_CSV_READY", "Your CSV file is ready for download, click on this link to get it");
define("_AM_SIMPLENEWSLETTER_STATISTICS", "Statistics");
define("_AM_SIMPLENEWSLETTER_STATISTICS1", "%u registrations verified");
define("_AM_SIMPLENEWSLETTER_STATISTICS2", "%u registrations not verified");
define("_AM_SIMPLENEWSLETTER_STATISTICS3", "Total %u registrations");
define("_AM_SIMPLENEWSLETTER_STATISTICS4", "%u members of the site");
define("_AM_SIMPLENEWSLETTER_STATISTICS5", "%u not member of the site");
define("_AM_SIMPLENEWSLETTER_SUBSCRIPTION_DATE", "Subscription");
define("_AM_SIMPLENEWSLETTER_SITE_MEMBER", "Site member");
define("_AM_SIMPLENEWSLETTER_VALIDATE_MEMBER","Do you really want to validate this member ?");
define("_AM_SIMPLENEWSLETTER_YOU_CAN_USE","You can use the variables below:");
define("_AM_SIMPLENEWSLETTER_SENDED", "Sended");
define("_AM_SIMPLENEWSLETTER_CSV_PARAMETERS", "Parameters of the CSV import");
define("_AM_SIMPLENEWSLETTER_CSV_UPLOAD_FILE", "Upload your CSV file");
define("_AM_SIMPLENEWSLETTER_CSV_SELECT_FILE", "Or select it in the list (it must be in this folder : %s)");
define("_AM_SIMPLENEWSLETTER_CSV_SKIP_FIRST", "Skip the first line of the file ?");
define("_AM_SIMPLENEWSLETTER_CSV_NEXT_STEP", "Go to the next step");
define("_AM_SIMPLENEWSLETTER_CSV_FIELDS_SEP", "Fields separator");
define("_AM_SIMPLENEWSLETTER_CSV_STRINGS_SEP", "Strings separator");
define("_AM_SIMPLENEWSLETTER_CSV_EMPTY_CONTENT", "Empty the list of current subscribers ?");
define("_AM_SIMPLENEWSLETTER_FIELDS_MAPPING", "Fields mapping");
define("_AM_SIMPLENEWSLETTER_ERROR_4", "Error, no file selected or uploaded");
define("_AM_SIMPLENEWSLETTER_ERROR_5", "Error, the csv file can't be opened");
define("_AM_SIMPLENEWSLETTER_ERROR_6", "Error, impossible to create the member from the line ");
define("_AM_SIMPLENEWSLETTER_CSV_HELP", "<br />On the left you have the fields list in the databse with an explanation, on the right you must select the mapping field in your file<br /><br />");
define("_AM_SIMPLENEWSLETTER_CSV_HELP1", "If the upload does not runs, rename your file with a .txt extension or copy it to your 'upload' folder with an FTP program and use the list below");

define("_AM_SIMPLENEWSLETTER_CSV_HELP2", "This is the user subscription's date, it's a <a target='_blank' href='http://en.wikipedia.org/wiki/Unix_time'>timestamp</a>");
define("_AM_SIMPLENEWSLETTER_CSV_HELP3", "Create it automatically with the current date");
define("_AM_SIMPLENEWSLETTER_CSV_HELP4", "this is the user number in the Xoops database<br />the value '0' is used for a user not registred to your site");
define("_AM_SIMPLENEWSLETTER_CSV_HELP5", "Set it to");
define("_AM_SIMPLENEWSLETTER_CSV_HELP6", "This is the first name");
define("_AM_SIMPLENEWSLETTER_CSV_HELP7", "This is the last name");
define("_AM_SIMPLENEWSLETTER_CSV_HELP8", "This field is used to say if the user subscription is verified (only used for anonymous users)<br />0 = subscription NOT verified, 1 = subscription verified");
define("_AM_SIMPLENEWSLETTER_CSV_HELP9", "User email's address");
define("_AM_SIMPLENEWSLETTER_CSV_HELP10", "Password use to manage my account. Only used with anonymous users");
define("_AM_SIMPLENEWSLETTER_CSV_HELP11", "User title");
define("_AM_SIMPLENEWSLETTER_CSV_HELP12", "User street");
define("_AM_SIMPLENEWSLETTER_CSV_HELP13", "User city");
define("_AM_SIMPLENEWSLETTER_CSV_HELP14", "User state");
define("_AM_SIMPLENEWSLETTER_CSV_HELP15", "User zip");
define("_AM_SIMPLENEWSLETTER_CSV_HELP16", "User telephone");
define("_AM_SIMPLENEWSLETTER_CSV_HELP17", "User fax");
define("_AM_SIMPLENEWSLETTER_CSV_STARTING", "Starting data import, the operation is not finished as long as the module does not tell you it is finished");
define("_AM_SIMPLENEWSLETTER_CSV_END", "The import is finished, I have imported %u users, don't forget to remove the import file : %s  (<a href='%s'>click on this link to remove it</a>)");
define("_AM_SIMPLENEWSLETTER_SEND_TEST", "Send it, for test, to the webmaster group");
?>