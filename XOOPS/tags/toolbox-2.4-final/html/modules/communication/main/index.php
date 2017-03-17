<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
if( ! empty( $_GET['word'] ) ) {
	include dirname(dirname(__FILE__)).'/main/search-result.php' ;
} else if( ! empty( $_GET['topicId'] ) ) {
	include dirname(dirname(__FILE__)).'/main/post-list.php' ;
} else if( ! empty( $_GET['forumId'] ) ) {
	include dirname(dirname(__FILE__)).'/main/topic-list.php' ;
} else if( ! empty( $_GET['categoryId'] ) ) {
	include dirname(dirname(__FILE__)).'/main/forum-list.php' ;
} else {
	include dirname(dirname(__FILE__)).'/main/category-list.php' ;
}
include XOOPS_ROOT_PATH.'/footer.php';
?>