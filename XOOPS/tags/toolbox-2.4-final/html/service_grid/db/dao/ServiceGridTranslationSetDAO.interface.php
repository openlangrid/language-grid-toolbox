<?php
interface ServiceGridTranslationSetDAO{
	public function queryBySetName($name, $userId = null);	
	public function queryBySetId($setId);
	public function queryByUserId($userId);
	public function getTranslationPaths($translationSetObj);
}
?>