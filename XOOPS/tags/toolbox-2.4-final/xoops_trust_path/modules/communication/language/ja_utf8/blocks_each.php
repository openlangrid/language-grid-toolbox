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
define($constpref."_FORUM","フォーラム");
define($constpref."_TOPIC","トピック");
define($constpref."_REPLIES","返信");
define($constpref."_VIEWS","閲覧");
define($constpref."_VOTESCOUNT","投票");
define($constpref."_VOTESSUM","得票");
define($constpref."_LASTPOST","最終投稿");
define($constpref."_LASTUPDATED","最終更新");
define($constpref."_LINKTOSEARCH","フォーラム内検索へ");
define($constpref."_LINKTOLISTCATEGORIES","カテゴリー一覧へ");
define($constpref."_LINKTOLISTFORUMS","フォーラム一覧へ");
define($constpref."_LINKTOLISTTOPICS","トピック一覧へ");
define($constpref."_ALT_UNSOLVED","未解決トピック");
define($constpref."_ALT_MARKED","注目トピック");

}

?>