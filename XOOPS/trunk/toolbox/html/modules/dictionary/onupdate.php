<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
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
$db =& Database::getInstance() ;

//0.30-
$db->query('ALTER table '.$db->prefix('user_dictionary').' ADD type_id TINYINT(3) NOT NULL default 0');
$db->query('ALTER table '.$db->prefix('user_dictionary_contents').' MODIFY language VARCHAR(30) NOT NULL default \'\'');

//0.74-
$db->query('ALTER table '.$db->prefix('user_dictionary').' ADD deploy_flag CHAR(1) NOT NULL default 0');

//0.76-
$db->query('ALTER table '.$db->prefix('user_dictionary_contents').' MODIFY contents TEXT COLLATE utf8_unicode_ci');

$check_sql = 'show index from '.$db->prefix('user_dictionary_contents')." where Key_name='index_user_dictionary_contents'";
$result = $db->query($check_sql);
if (!$db->fetchArray($result)) {
	$createIndex = 'CREATE INDEX index_user_dictionary_contents ON '.$db->prefix('user_dictionary_contents').' (user_dictionary_id, row, language)';
	$db->queryF($createIndex);
}

?>