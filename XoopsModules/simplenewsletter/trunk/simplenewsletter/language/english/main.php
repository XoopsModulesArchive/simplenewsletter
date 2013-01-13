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

define("_SIMPLENEWSLETTER_EDIT", "Edit");
define("_SIMPLENEWSLETTER_DELETE", "Delete");
define("_SIMPLENEWSLETTER_VALIDATE", "Validate");
define("_SIMPLENEWSLETTER_ID","ID");
define("_SIMPLENEWSLETTER_DATE","Date");
define("_SIMPLENEWSLETTER_NEWS_TITLE","Newsletter title");
define("_SIMPLENEWSLETTER_UID", "User ID");
define("_SIMPLENEWSLETTER_EMAIL", "Email");
define("_SIMPLENEWSLETTER_ACTION", "Action");
define("_SIMPLENEWSLETTER_UNAME", "User");
define("_SIMPLENEWSLETTER_SUBSCRIBE_MEMBER", "Subscribe a user");
define("_SIMPLENEWSLETTER_LAST_NEWSLETTERS", "Last newsletters");
define("_SIMPLENEWSLETTER_READ_MORE", "Read more");
define("_SIMPLENEWSLETTER_NO_NEWSLETTER", "Sorry, actually there's no newsletters");
define("_SIMPLENEWSLETTER_ERROR1", "Missing argument");
define("_SIMPLENEWSLETTER_ERROR2", "Unkow newsletter");
define("_SIMPLENEWSLETTER_ERROR3", "You must be a regsitred user of this site to subscribe or unsubscribe");
define("_SIMPLENEWSLETTER_ERROR4", "Error, you must fill all the required fields");
define("_SIMPLENEWSLETTER_ERROR5", "Internal error, you are supposed to be subscribed but we can't find your information ! Please contact us");
define("_SIMPLENEWSLETTER_ERROR6", "Error, this email address is already registred to receive newsletter");
define("_SIMPLENEWSLETTER_ERROR7", "Error, passwords do not match");
define("_SIMPLENEWSLETTER_ERROR8", "Error, the result of the operation does not correspond with what is expected");
define("_SIMPLENEWSLETTER_ERROR9", "There was an error while trying to unsubscribe you");
define("_SIMPLENEWSLETTER_ERROR10", "Error, confirmation code is missing or you already validated it");
define("_SIMPLENEWSLETTER_ERROR11", "Error, the email is already verified or this code does not exist");
define("_SIMPLENEWSLETTER_ERROR12", "There was an error while trying to subscribe you");
define("_SIMPLENEWSLETTER_ERROR13", "There was an error during the backup of your information");
define("_SIMPLENEWSLETTER_ERROR14", "Error, unknown email address or bad password");
define("_SIMPLENEWSLETTER_ERROR15", "Please give your email to receive your password");
define("_SIMPLENEWSLETTER_ERROR16", "Error, unknown email address");
define("_SIMPLENEWSLETTER_NEWS_LIST", "Go back to the newsletters list");

define("_SIMPLENEWSLETTER_STATE", "Subscription state");
define("_SIMPLENEWSLETTER_STATE_1_DESC", "You are subscribed to our newsletter");
define("_SIMPLENEWSLETTER_STATE_1_UNSUB", "Click on this link to unsubscribe");
define("_SIMPLENEWSLETTER_STATE_2_DESC", "You are not subscribed to our newsletter");
define("_SIMPLENEWSLETTER_STATE_2_UNSUB", "Click on this link to subscribe");
define("_SIMPLENEWSLETTER_STATE_3_DESC", "To subscribe to our newsletter, you must create an account on this site");

define("_SIMPLENEWSLETTER_WELCOME_TITLE", "Welcome to our newsletter");
define("_SIMPLENEWSLETTER_BYEBYE_TITLE", "Bye bye");

define("_SIMPLENEWSLETTER_FIRST_NAME", "First name");
define("_SIMPLENEWSLETTER_LAST_NAME", "Last name");
define("_SIMPLENEWSLETTER_PASSWORD_SIMPLE", "Password");
define("_SIMPLENEWSLETTER_PASSWORD", "Password to manage your subscription");
define("_SIMPLENEWSLETTER_CONFIRM_PASSWORD", "Please confirm your password");
define("_SIMPLENEWSLETTER_INFORMATION", "Information");
define("_SIMPLENEWSLETTER_REQUIRED_FIELD", "Required field");
define("_SIMPLENEWSLETTER_REQUIRED_PLEASE1", "Please enter your first name");
define("_SIMPLENEWSLETTER_REQUIRED_PLEASE2", "Please provide a password");
define("_SIMPLENEWSLETTER_REQUIRED_PLEASE3", "Your password must be at least %u characters long");
define("_SIMPLENEWSLETTER_REQUIRED_PLEASE4", "Please enter the same password as above");
define("_SIMPLENEWSLETTER_REQUIRED_PLEASE5", "Please enter a valid email address");
define("_SIMPLENEWSLETTER_REQUIRED_PLEASE6", "Please solve the operation");
define("_SIMPLENEWSLETTER_PLEASESOLVE", "Please solve this operation");
define("_SIMPLENEWSLETTER_MODIFY", "Modify");
define("_SIMPLENEWSLETTER_YOU_ARE_SUBSCRIBED", "You are subscribed to our newsletter, use this form to modify your information");
define("_SIMPLENEWSLETTER_YOU_ARE_NOT_SUBSCRIBED", "Fill in this form to subscribe");
define("_SIMPLENEWSLETTER_PLEASE_LOGIN", "If you have an account, fill your email and password below to manage it");
define("_SIMPLENEWSLETTER_REMOVE_SUBSCRIPTION", "Unsubscribe");
define("_SIMPLENEWSLETTER_SUBSCRIBE", "Subscribe");
define("_SIMPLENEWSLETTER_CONNECTION", "Connection");
define("_SIMPLENEWSLETTER_PLEASE_CONFIRM", "Can you please confirm that you want to unsubscribe to our newsletter");
define("_SIMPLENEWSLETTER_SUBSCRIPTION_OK", "Welcome to our newsletter, you successfully subscribed !");
define("_SIMPLENEWSLETTER_SUBSCRIPTION_MODIFY_OK", "Your information has been changed successfully");
define("_SIMPLENEWSLETTER_SUBSCRIPTION_MUST_VALIDATE", "Your registration has been recorded, you will receive an email to verify your email address");
define("_SIMPLENEWSLETTER_LOGOUT", "Logout");
define("_SIMPLENEWSLETTER_YOU_ARE_DECONNECTED", "You are now deconnected");
define("_SIMPLENEWSLETTER_YOU_ARE_UNSUBCRIBED", "You are now unsubscribed from our newsletter");
define("_SIMPLENEWSLETTER_PLEASE_CONFIRM_SUBSCRIPTION", "Please confirm your subscription to our newsletter");
define("_SIMPLENEWSLETTER_SUCCESSFULLY_LOGGED", "You have successfully logged");
define("_SIMPLENEWSLETTER_SUCCESSFULLY_SUBSCIBED", "You have successfully subscribed to our newsletter");
define("_SIMPLENEWSLETTER_LOSTPASSWORD", 'Lost your Password?');
define("_SIMPLENEWSLETTER_ENTER_YOUR_EMAIL", "Enter your email address to receive your password");
define("_SIMPLENEWSLETTER_SEND", "Send");
define("_SIMPLENEWSLETTER_PASSWORD_SENT", "Your password has been sent");
define("_SIMPLENEWSLETTER_PASSWORD_LOST", "Password lost");
define("_SIMPLENEWSLETTER_MANAGE_YOUR_ACCOUNT", "Use this page to manage your subscription");
define("_SIMPLENEWSLETTER_USE_THIS_PAGE", "Use this page to subscribe");
define("_SIMPLENEWSLETTER_YOU_ARE_NOT", "You are not subscribed");
define("_SIMPLENEWSLETTER_YOU_ARE", "You are subscribed to our newsletter");
define("_SIMPLENEWSLETTER_ATTACHED_FILE", "Attached file");
define("_SIMPLENEWSLETTER_FILE", "File");
define("_SIMPLENEWSLETTER_RSS", "RSS");
define("_SIMPLENEWSLETTER_MEMBER_TITLE", "Title");
define("_SIMPLENEWSLETTER_MEMBER_STREET", "Street");
define("_SIMPLENEWSLETTER_MEMBER_CITY", "City");
define("_SIMPLENEWSLETTER_MEMBER_STATE", "State");
define("_SIMPLENEWSLETTER_MEMBER_ZIP", "Zip");
define("_SIMPLENEWSLETTER_MEMBER_TELEPHONE", "Telephone");
define("_SIMPLENEWSLETTER_MEMBER_FAX", "Fax");
define("_SIMPLENEWSLETTER_SUBSCRIPTION", "Subscription");
define("_SIMPLENEWSLETTER_USE_THIS_LINK", "Use this link to manage your subscription");
?>