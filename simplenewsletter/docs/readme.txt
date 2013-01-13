SimpleNewsLetter 2.3

Some features and instructions:

Newsletter are made in a texteditor (Notepad, Notepad++). The module cannot store concept newsletters.
Newsletters can be sent for review to webmasters (or another group).
Header and footer are defined in a template: simplenewsletter_html_model.html.

A captcha is included for new subscribers to the newsletter. In fact, you have to make an addition of two numbers. In the future, the regular Xoops-captcha should be included in this module.

Sending of e-letters is controlled by a block, simplenewsletter_block_cron.html, that doesn't show anything on a page. If this page is visited (e.g. the home page) this block is activated and the first batch of newsletters (defined in the settings) is sent. Thereafter, during the cache time of this block, no additional newsletters must be sent. The second batch of newsletters will be sent after expiring the cache time of this block. THIS DOES NOT WORK CORRECTLY AT MY SITE. If the chache time is 1 or 2 hours, and the page with this block is visited 1-2 minutes after sending the first batch of newsletters, a second batch is sent immediately. THIS NEEDS EVALUATION BY A PROGRAMMER.

Several e-mailproviders refuse mails sent by SimpleNewsLetter. They classify the SimpleNewsLetter mail as spam, because of its structure and reject the message. THIS NEEDS ATTENTION AS WELL.