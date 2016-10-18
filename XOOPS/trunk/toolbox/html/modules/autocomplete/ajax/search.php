<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides accurate
// translation using the autocomplete feature based on parallel texts and
// translation template.
// Copyright (C) 2010  CITY OF KYOTO
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
/*
 * $Id: search.php 3611 2010-04-07 05:27:37Z yoshimura $
 */

//
$keyword = $_POST['keyword'];
$language = $_POST['language'];

$c = new AutoComplete();
$c->initSearch($language, $keyword);
$dist = $c->find($keyword);
if ($dist === false) {
	die();
}

header('Content-Type: application/json; charset=utf-8;');
echo json_encode($dist);
?>
