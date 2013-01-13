SimpleNewsLetter 2.2

Some features and instructions:

Newsletter are made in a texteditor (Notepad, Notepad++). The module cannot store concept newsletters.
Newsletters can be sent for review to webmasters (or another group).
Header and footer are defined in a template: simplenewsletter_html_model.html.

A captcha is included for new subscribers to the newsletter. In fact, you have to make an addition of two numbers. In the future, the regular Xoops-captcha should be included in this module.

In 2.1 en 2.2, a conflict with Extgallery was present. The "Extensions"-tab of Extgallery appeared as a tab in the SimpleNewsLetter admin menu. It could be solved by changing the menu numbers of \admin\menu.php of Simplenewsletter:

E.g.:
$adminmenu[0]['title'] = _MI_SIMPLENEWSLETTER_ADMENU0;
$adminmenu[0]['link'] = "admin/index.php?op=default";

$adminmenu[1]['title'] = _MI_SIMPLENEWSLETTER_ADMENU1;
$adminmenu[1]['link'] = "admin/index.php?op=old";

New:
$adminmenu[1]['title'] = _MI_SIMPLENEWSLETTER_ADMENU0;
$adminmenu[1]['link'] = "admin/index.php?op=default";

$adminmenu[2]['title'] = _MI_SIMPLENEWSLETTER_ADMENU1;
$adminmenu[2]['link'] = "admin/index.php?op=old";

By adding "1" to ALL menu items in menu.php, the Extensions-tab of Extgallery disappeared. Whether this problem is present in Simplenewsletter 2.3 in combination with Extgallery 1.11 is not known to me.

 
Sending of e-letters is controlled by a block, simplenewsletter_block_cron.html, that doesn't show anything on a page. If this page is visited (e.g. the home page) this block is activated and the first batch of newsletters (defined in the settings) is sent. Thereafter, during the cache time of this block, no additional newsletters must be sent. The second batch of newsletters will be sent after expiring the cache time of this block. THIS DOES NOT WORK CORRECTLY AT MY SITE. If the chache time is 1 or 2 hours, and the page with this block is visited 1-2 minutes after sending the first batch of newsletters, a second batch is sent immediately. THIS NEEDS EVALUATION BY A PROGRAMMER.

Several e-mailproviders refuse mails sent by SimpleNewsLetter. They classify the SimpleNewsLetter mail as spam, because of its structure and reject the message. THIS NEEDS ATTENTION AS WELL.