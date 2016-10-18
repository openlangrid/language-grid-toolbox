<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2010  NICT Language Grid Project
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

require_once dirname(__FILE__).'/common.php';
require_once APP_ROOT_PATH.'/../../mainfile.php';
require_once APP_ROOT_PATH.'/class/TranslationModel.class.php';
require_once APP_ROOT_PATH.'/class/WebCreation.class.php';

$root = XCube_ROOT::getSingleton();

$root->mController->executeHeader();

$delegate = new WebCreation(isset($_GET['admin']));
$root->mController->mExecute->add(array($delegate, 'execute'));

$root->mController->execute();
$root->mController->executeView();

?>