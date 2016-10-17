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

error_reporting(0);

// making response stream by echo command;
$content = Com_Content::findAvailableContentById($_GET['contentId']);


if ($content && $content -> getType() == 'image') {
	header('Content-type: '. $content -> getMimeType());
	header('Cache-control: max-age=31536000');
	header('Expires: '.gmdate("D, d M Y H:i:s", time() + 31536000).'GMT');
	header('Pragma: cache');
	header('Content-disposition: filename='.$content -> getOriginalFilename());
	header('Content-Length: '.strlen($content -> getImageData()));
	header('Last-Modified: '.gmdate("D, d M Y H:i:s", $content -> getCreated()).'GMT');

	echo $content -> getImageData();
	
	exit ();
}
?>