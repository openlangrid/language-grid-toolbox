<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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

require_once XOOPS_ROOT_PATH.'/service_grid/client/text_to_speech/TextToSpeech.class.php';

class VoiceManager {
	
	public static $cachePath;
	public static $maxCacheTime;

	/**
	 * Constructor
	 */
	public function __construct() {
		self::$cachePath = XOOPS_ROOT_PATH.'/cache/';
		self::$maxCacheTime = 24*60*60;// 24 hours
		$this->removeCache();
	}
	
	/**
	 * Create voice files
	 * @param String $lang
	 * @param String[] $sources
	 */
	public function createVoices($lang, $sources) {
		if (!$this->valid($lang, $sources)) {
			throw new Exception('ERROR!');
		}
		
		$result = array();
		$converter = new TextToSpeech($lang);
		foreach ($sources as $source) {
			if (!$source) {
				continue;
			}
			
			$filename = $this->generateFilename($lang, $source);
			
			if (!is_file($filename)) {
				$contents = $converter->speak($source);

				if ($contents) {
					$this->createCache($filename, $contents);
				} else {
					copy(dirname(__FILE__).'/../no-sound.wav', $filename);
				}
			} else {
				touch($filename); 
			}

			$result[] = XOOPS_URL.'/cache/'.basename($filename);
		}
		
		return $result;
	}
	
	/**
	 * 
	 * @param String $lang
	 * @param String[] $sources
	 */
	private function valid($lang, $sources) {
		// $langが対応していてない
		// $langに対応するボイスがない
		// sourcesが実質空なら
		return true;
	}
	
	private function generateFilename($lang, $source) {
		require_once dirname(__FILE__).'/../../langrid_config/class/manager/VoiceSettingManager.class.php';
		$wsdl = VoiceSettingManager::getEndpointUrlByLanguage($lang);
		$userId = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;
		return self::$cachePath.'/voice-'.md5($userId.$wsdl.$lang.$source).'.wav';
	}
	
	/**
	 * Create cache file
	 */
	private function createCache($filename, $contents) {
		$fp = fopen($filename, 'wb');
		fwrite($fp, $contents);
		fclose($fp);
	}
	
	private function getCacheFles() {
		$result = array();
		
		if (!($handle = opendir(self::$cachePath))) return;
		
		while (false !== ($file = readdir($handle))) {
		   	if ($this->isCacheFile($file)) {
		  		$result[] = $file;
		   	}
		}
		closedir($handle);
		
		return $result;
	}
	
	private function isCacheFile($filename) {
		return preg_match('/^voice-[a-f0-9]{32}\.wav$/', $filename);
	}
	
	private function shouldDeleteCacheFile($filename) {
		$diff = time() - filemtime($filename);
		return ($diff > self::$maxCacheTime);
	}
	
	/**
	 * Remove voice files that was created more than $maxCacheTime seconds ago.
	 */
	private function removeCache() {
		$files = $this->getCacheFles();

		foreach ($files as $file) {
			if ($this->shouldDeleteCacheFile(self::$cachePath.$file)) {
				unlink(self::$cachePath.$file);
			}
		}
	}
}
?>