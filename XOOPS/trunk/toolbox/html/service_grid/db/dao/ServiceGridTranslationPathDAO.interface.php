<?php
interface ServiceGridTranslationPathDAO{
	public function queryAll();	
	public function queryBySetId($userId, $setId, $sourceLang = null, $targetLang = null);
	public function getTranslationExecs($translationPathObj);
	public function queryByPathId($pathId);
	public function insert($translationPathObj);
	public function deleteBySetId($setId);
}
?>