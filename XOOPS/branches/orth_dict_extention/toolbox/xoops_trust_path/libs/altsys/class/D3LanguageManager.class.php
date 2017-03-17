<?php

class D3LanguageManager {

	var $default_language = 'english' ;
	var $language = 'english' ;
	var $selectedLanguage = array() ;
	var $allLanguages = array() ;
	var $salt ;
	var $cache_path ;
	var $cache_prefix = 'lang' ;
	var $my_language = false ;

	function D3LanguageManager()
	{
		$this->language = preg_replace( '/[^0-9a-zA-Z_-]/' , '' , @$GLOBALS['xoopsConfig']['language'] ) ;

		$languageFileTags = preg_split('/, ?/', CUBE_UTILS_ML_LANGNAMES);
		$languageTags = preg_split('/, ?/', CUBE_UTILS_ML_LANGS);
		$languageNames = preg_split('/, ?/', CUBE_UTILS_ML_LANGDESCS);

		$this->allLanguages = array_combine($languageTags, $languageNames);

		$key = array_search(@$GLOBALS['xoopsConfig']['language'], $languageFileTags);
		$this->selectedLanguage = array(
			$languageTags[$key], $languageNames[$key]
		);
		$this->unselectedLanguages = $this->allLanguages;
		array_splice($this->unselectedLanguages, $key, 1);
		ksort($this->allLanguages);
		ksort($this->unselectedLanguages);

		$this->salt = substr( md5( XOOPS_ROOT_PATH . XOOPS_DB_USER . XOOPS_DB_PREFIX ) , 0 , 6 ) ;
		$this->cache_path = XOOPS_TRUST_PATH.'/cache' ;

		if( defined( 'ALTSYS_MYLANGUAGE_ROOT_PATH' ) && file_exists( ALTSYS_MYLANGUAGE_ROOT_PATH ) ) {
			$this->my_language = ALTSYS_MYLANGUAGE_ROOT_PATH ;
		}
	}

	function &getInstance( $conn = null )
	{
		static $instance ;
		if( ! isset( $instance ) ) {
			$instance = new D3LanguageManager() ;
		}
		return $instance ;
	}


	function read( $resource , $mydirname , $mytrustdirname = null , $read_once = true )
	{
		$d3file = XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/mytrustdirname.php' ;

		if( empty( $mytrustdirname ) && file_exists( $d3file ) ) {
			require $d3file ;
		}

		if( empty( $this->language ) ) $this->language = preg_replace( '/[^0-9a-zA-Z_-]/' , '' , @$GLOBALS['xoopsConfig']['language'] ) ;

		$cache_file = $this->getCacheFileName( $resource , $mydirname ) ;
		$root_file = XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/language/'.$this->language.'/'.$resource ;

		//
		if (file_exists($root_file) === false) {
			$root_file = XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/language/'.$this->default_language.'/'.$resource ;
		}

		// language overriding by XOOPS_ROOT_PATH/my_language
		if( $this->my_language ) {
			$mylang_file = $this->my_language.'/modules/'.$mydirname.'/'.$this->language.'/'.$resource ;
			if( file_exists( $mylang_file ) ) {
				require_once $mylang_file ;
			}
			$original_error_level = error_reporting() ;
			error_reporting( $original_error_level & ~ E_NOTICE ) ;
		}

		if( empty( $mytrustdirname ) ) {
			// conventional module
			$default_file = XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/language/'.$this->default_language.'/'.$resource ;

			if( file_exists( $cache_file ) ) {
				require_once $cache_file ;
			} else if( file_exists( $root_file ) ) {
				require_once $root_file ;
			} else if( file_exists( $default_file ) ) {
				// fall back english
				require_once $default_file ;
			}

		} else {
			// D3 modules
			$trust_file = XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/language/'.$this->language.'/'.$resource ;
			$default_file = XOOPS_TRUST_PATH.'/modules/'.$mytrustdirname.'/language/'.$this->default_language.'/'.$resource ;

			if( file_exists( $cache_file ) ) {
				require_once $cache_file ;
			} else if( file_exists( $root_file ) ) {
				require_once $root_file ;
			} else if( file_exists( $trust_file ) ) {
				if( $read_once ) require_once $trust_file ;
				else require $trust_file ;
			} else if( file_exists( $default_file ) ) {
				// fall back english
				if( $read_once ) require_once $default_file ;
				else require $default_file ;
			}
		}

		if( $this->my_language ) {
			error_reporting( $original_error_level ) ;
		}
	}


	function getCacheFileName( $resource , $mydirname , $language = null )
	{
		if( empty( $language ) ) $language = $this->language ;
		return $this->cache_path . '/' . $this->cache_prefix . '_' . $this->salt . '_' . $mydirname . '_' . $language . '_' . $resource ;
	}

	function getSelectedLanguage () {
		return $this->selectedLanguage;
	}
	function getSelectedLanguageTag () {
		return $this->selectedLanguage[0];
	}

	function getSelectedLanguageName () {
		return $this->selectedLanguage[1];
	}

	function getUnselectedLanguages () {
		return $this->unselectedLanguages;
	}
	function getSourceLanguageTag() {
		return $this->selectedLanguage[0];
	}
	function getSourceLanguageName() {
		return $this->selectedLanguage[1];
	}
	function getSourceLanguage() {
		return $this->selectedLanguage;
	}
	function getTargetLanguages() {
		return $this->unselectedLanguages;
	}

	function getAllLanguages () {
		return $this->allLanguages;
	}

}

?>