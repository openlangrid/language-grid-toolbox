<?php
interface ServiceGridTranslationExecDAO{
	public function queryAll();
	public function queryByPathId($pathId);	
	public function getTranslationBinds($translationExecObj);
	public function getExecObjects($pathId);
	public function deleteByPathId($pathId);
}
?>
