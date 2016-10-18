<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
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

require_once dirname(__FILE__).'/../../../mainfile.php';
$root = &XCube_Root::getSingleton();
$root->mController->executeHeader();

$myDirName = basename(dirname(dirname(__FILE__)));
$constPrefix = strtoupper($myDirName);

$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$file = XOOPS_ROOT_PATH.'/modules/langrid_config/class/manager/GlobalServicesLoader.class.php';
	if (file_exists($file)) {
		require_once $file;
		$globalLoader = new GlobalServicesLoader();
		$globalLoader->refresh();
	}

	$isSuccess = true;
}


?>
<h1><?php echo 'Refresh global services.'; ?></h1>
<?php
if ($isSuccess) {
	echo '<p>'.'Complete.'.'</p>';
}
?>
<form action="" method="post">
<input type="submit" value="<?php echo 'refresh now!'; ?>" />
</form>
<?php
$root->mController->executeView();
?>