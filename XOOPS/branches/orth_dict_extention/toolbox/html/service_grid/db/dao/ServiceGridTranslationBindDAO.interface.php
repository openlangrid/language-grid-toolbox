<?php

interface ServiceGridTranslationBindDAO{
	public function queryAll();	
	public function queryByExecObject($transltionExecObj);
	public function deleteByPathId($pathId);
}
?>