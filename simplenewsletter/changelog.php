<?php exit(); ?>

***************
* Version 2.3 *
***************
- Ajout d'un test dans la page qui permet de voir le contenu d'une newsletter (c�t� utilisateur) pour le module TAG

***************
* Version 2.2 *
***************
- Bug correction in the module's administration (in the part used to sew previous newsletters)
- changelog.txt was renamed to changelog.php

***************
* Version 2.1 *
***************
- Ajout d'un onglet "Blocs" qui permet de g�rer les blocs du module
- Ajout d'une nouvelle option permettant de choisir le s�parateur de champs � utiliser pour l'export au format CSV
- Ajout d'un onglet "Import CSV" permettant d'importer des fichiers CSV d'adresses email
- Ajout d'une option permettant de choisir la longueur minimale des mots de passe (option utilis�e dans le cas des inscriptions d'anonymes)
- Durant la cr�ation d'une newsletter, avant de l'envoyer d�finitivement, vous pouvez l'envoyer au groupe des webmaster.
	Par contre, dans ce cas l�, les variables des utilisateurs ne sont pas substitu�es ({UID}, {SUB_DATE}, {MEMBER_SENT}, {MEMBER_UID}, {MEMBER_FIRSTNAME}, {MEMBER_LASTNAME}, {MEMBER_PASSWORD}, {MEMBER_VERIFIED}, {MEMBER_EMAIL}, {MEMBER_TEMPORARY}, {MEMBER_USER_PASSWORD}, {MEMBER_TITLE}, {MEMBER_STREET}, {MEMBER_CITY}, {MEMBER_STATE}, {MEMBER_ZIP}, {MEMBER_TELEPHONE}, {MEMBER_FAX})
- Ajout de nouveaux champs � la fiche des membres inscrits (titre, adresse, ville, �tat, code postal, t�l�phone)
  et d'une option qui permet de choisir si vous souhaitez utiliser ces nouveaux champs

 Notes :
	- Vous devez vous rendre au moins une fois dans l'administration du module
	- Vous devez remettre � jour le module dans le gestionnaire de modules de Xoops


***************
* Version 2.0 *
***************
- Les anonymes peuvent maintenant s'inscrire (les personnes ne disposant pas d'un compte Xoops sur le site)
- Ajout d'une pr�f�rence permettant de choisir si les inscriptions des utilisateurs anonymes doivent �tre v�rifi�es (auto approbation)
- Ajout d'une option permettant de demander aux anonymes de saisir un CAPTCHA s'ils veulent s'inscrire
- Ajout d'une nouvelle pr�f�rence permettant de choisir si les inscriptions sont ouvertes aux personnes qui ne sont pas membres du site
- Il est possible de joindre un fichier � chaque newsletter
- Les traductions fran�aises en anglaises du module en UTF8 sont maintenant disponibles dans les r�pertoires frenchUTF8 et englishUTF8 du module
   Si vous utilisez ces r�pertoires, supprimez les r�pertoires french et english et renommez englishUTF8 et frenchUTF8 en english et french
- Dans l'administration, la partie permettant de g�rer les inscrits a �t� retravaill�e, avec notamment :
	Ajout de statistiques sur les inscriptions
	Possibilit� de valider l'inscription d'un anonyme depuis l'administration
	Possibilit� d'�diter chaque inscrit
	Export possible de la liste des inscrits
	Filtrage des membres
- Enregistrement du nombre d'envois de chaque newsletter
- Possibilit� d'utiliser, dans tous les mails envoy�s, des informations sur les utilisateurs (par exemple, nom, pr�nom)
- Les pages c�t� utilisateur renseignent les metas du site sur la disponibilit� d'un flux RSS
- Notes :
	- Vous devez vous rendre au moins une fois dans l'administration du module
	- Vous devez remettre � jour le module dans le gestionnaire de modules de Xoops
Un grand merci � Klaus pour tous ses tests et sa patience !


***************
* Version 1.2 *
***************
- Pour des raisons de comodit�s, ajout d'une table interm�diaire de travail

***************
* Version 1.1 *
***************
- Ajout de la possibilit� d'utiliser le syst�me de TAGS du module TAG de Xoops (il faut aller dans les pr�f�rences du module pour l'activer)
Le module doit �tre mis � jour dans le gestionnaire de modules de Xoops
