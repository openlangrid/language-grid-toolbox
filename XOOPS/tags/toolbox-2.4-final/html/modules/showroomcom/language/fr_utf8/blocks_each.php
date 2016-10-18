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

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) ) $mydirname = 'd3forum' ;
$constpref = '_MB_' . strtoupper( $mydirname ) ;

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( $constpref.'_LOADED' ) ) {






define( $constpref.'_LOADED' , 1 ) ;

// definitions for displaying blocks 
define($constpref."_FORUM","Forum");
define($constpref."_TOPIC","Sujet");
define($constpref."_REPLIES","Réponses");
define($constpref."_VIEWS","Lectures");
define($constpref."_VOTESCOUNT","Votes");
define($constpref."_VOTESSUM","Points");
define($constpref."_LASTPOST","Dernières contributions");
define($constpref."_LASTUPDATED","Dérnières mises à jour");
define($constpref."_LINKTOSEARCH","Rechercher dans le forum");
define($constpref."_LINKTOLISTCATEGORIES","Index Catégories");
define($constpref."_LINKTOLISTFORUMS","Index Forum");
define($constpref."_LINKTOLISTTOPICS","Index Sujet");
define($constpref.'_ALT_UNSOLVED','Sujet Non résolu');
define($constpref.'_ALT_MARKED','Sujet Marqué');

}

?>