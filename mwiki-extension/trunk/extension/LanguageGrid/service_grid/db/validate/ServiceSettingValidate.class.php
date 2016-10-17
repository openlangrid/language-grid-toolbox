<?php

class ServiceSettingValidate {

    function ServiceSettingValidate() {
    }

    static function validateRequireStringOrInteger($var) {
    	if ($var == null || empty($var)) {
    		var_dump(debug_backtrace());
    		die('is empty.');
    	}
    	if (!is_string($var) && !is_numeric($var)) {
    		var_dump(debug_backtrace());
    		die('different type. string or integer.'.$var);
    	}
    }
}
?>