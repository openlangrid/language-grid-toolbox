<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //

define('_MD_A_MYMENU_MYTPLSADMIN','Templates');
define('_MD_A_MYMENU_MYBLOCKSADMIN','Blocks/Permissions');
define('_MD_A_MYMENU_MYPREFERENCES','Préférences');

// forum_access and category_access
define('_MD_A_D3FORUM_LABEL_SELECTFORUM','Choisissez un forum');
define('_MD_A_D3FORUM_LABEL_SELECTCATEGORY','Choisissez une catégorie');
define('_MD_A_D3FORUM_H2_GROUPPERMS','Permissions des groupes');
define('_MD_A_D3FORUM_H2_USERPERMS','Permissions des utilisateurs');
define('_MD_A_D3FORUM_TH_CAN_READ','Lire');
define('_MD_A_D3FORUM_TH_CAN_POST','Poster');
define('_MD_A_D3FORUM_TH_CAN_EDIT','Éditer');
define('_MD_A_D3FORUM_TH_CAN_DELETE','Supprimer');
define('_MD_A_D3FORUM_TH_POST_AUTO_APPROVED','Auto Approver');
define('_MD_A_D3FORUM_TH_IS_MODERATOR','Modérateur');
define('_MD_A_D3FORUM_TH_CAN_MAKEFORUM','Créer forums');
define('_MD_A_D3FORUM_TH_UID','uid');
define('_MD_A_D3FORUM_TH_UNAME','uname');
define('_MD_A_D3FORUM_TH_GROUPNAME','groupname');
define('_MD_A_D3FORUM_NOTICE_ADDUSERS','Ajoutez au choix : uid ou uname.');
define('_MD_A_D3FORUM_ERR_CREATECATEGORYFIRST','Créez premièrement une Catégorie');
define('_MD_A_D3FORUM_ERR_CREATEFORUMFIRST','Créez premièrement un Forum');

// advanced
define('_MD_A_D3FORUM_H2_SYNCALLTABLES','Synchronisez les informations superflues');
define('_MD_A_D3FORUM_MAX_TOPIC_ID','Id id maximum de Sujet');
define('_MD_A_D3FORUM_LABEL_SYNCTOPICS_START','sujet commencé depuis');
define('_MD_A_D3FORUM_LABEL_SYNCTOPICS_NUM','Ensemble des Sujets');
define('_MD_A_D3FORUM_BTN_DOSYNCTABLES','Synchroniser');
define('_MD_A_D3FORUM_FMT_SYNCTOPICSDONE','%s sujets synchronisés');
define('_MD_A_D3FORUM_MSG_SYNCTABLESDONE','Synchronisé avec succès');
define('_MD_A_D3FORUM_HELP_SYNCALLTABLES','Exécutez cette commande si votre forum affiche des données contradictoires. Vous devez exécuter ceci juste aprés une IMPORTATION d\'un autre module');
define('_MD_A_D3FORUM_H2_IMPORTFROM','Importer');
define('_MD_A_D3FORUM_H2_COMIMPORTFROM','Importer depuis les commentaires XOOPS');
define('_MD_A_D3FORUM_LABEL_SELECTMODULE','Choississez le module');
define('_MD_A_D3FORUM_BTN_DOIMPORT','Importez');
define('_MD_A_D3FORUM_CONFIRM_DOIMPORT','Êtes-vous sûr?');
define('_MD_A_D3FORUM_MSG_IMPORTDONE','Importé avec succès');
define('_MD_A_D3FORUM_MSG_COMIMPORTDONE','Les commentaires de modules XOOPS sont importés comme intégration-commentaires');
define('_MD_A_D3FORUM_ERR_INVALIDMID','Vous avez spécifié un module à importer invalide');
define('_MD_A_D3FORUM_ERR_SQLONIMPORT','Echec lors de l\'importation. Vous devez vérifier les versions de chaque module');
define('_MD_A_D3FORUM_HELP_IMPORTFROM','Vous pouvez importer de newbb1, xhnewbb, et autres versions de d3forum.  Et vous devez savoir que ce n\'est pas une copie parfaite. Vous devriez vérifier, en particulier, les permissions. Vous devez également savoir que toutes les données dans ce module seront perdues lorsque vous exécutez l\'importation.');
define('_MD_A_D3FORUM_HELP_COMIMPORTFROM','Les commentaires de XOOPS seront importés comme des contributions de d3forum. En outre vous devez permettre au dispositif d\'intégration-commenteraires de les employer (en éditant les templates ou modifiant les préférences etc...)');

// post_histories
define('_MD_A_D3FORUM_H2_POSTHISTORIES','Historique de l\'édition/suppression de messages');
define('_MD_A_D3FORUM_LINK_REFERDELETED','Supprimé');

?>