<?php
require_once dirname(__FILE__).'/../../../mainfile.php';
$root = &XCube_Root::getSingleton();
$root->mController->executeHeader();

$myDirName = basename(dirname(dirname(__FILE__)));
$constPrefix = strtoupper($myDirName);

require_once dirname(__FILE__).'/class/import-module-data.php';
$imd = new ImportModuleData();
if ($imd->isPost()) {
	if ($imd->validate($imd->get('module'))) {
		try {
			$imd->import($imd->get('module'), $myDirName);
		} catch (Exception $e) {
			$imd->addErrorMessage($e->getMessage());
		}
	}
}
?>
<h1><?php echo _MI_SBBS_MSG_IMPORT_DATA; ?></h1>
<?php
foreach ($imd->getErrorMessages() as $m) {
	echo '<p>'.$m.'</p>';
}
if ($imd->isSuccess()) {
	echo '<p>'._MI_SBBS_MSG_IMPORT_DATA_SUCCESS.'</p>';
}
?>
<form action="" method="post">
<input type="text" name="module" value="" />
<input type="submit" value="<?php echo _MI_SBBS_MSG_IMPORT_BUTTON; ?>" />
</form>
<?php
$root->mController->executeView();
?>