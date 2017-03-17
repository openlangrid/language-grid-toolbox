<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox.
//
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
/* $Id: FileIO.class.php 3930 2010-08-12 07:55:42Z yoshimura $ */
class FileIO {

    function FileIO() {
//    	file_exists()
    }

    public static function openFile($path) {
		$tmpFileLines = file($path);
		$code = mb_detect_encoding($tmpFileLines[0]);

		if (ord($tmpFileLines[0]{0}) == 255 && ord($tmpFileLines[0]{1}) == 254) {
			$code = "UTF-16LE";
		} else if (ord($tmpFileLines[0]{0}) == 254 && ord($tmpFileLines[0]{1}) == 255) {
			$code = "UTF-16BE";
		} else {
//			$error = "Invalid Encoding.";
			$code = '';
		}
		if ($code == '') {
//			$error = _MI_DICTIONARY_ERROR_FILE_FORMAT_INVALID;
//			return $this->_doUploadErrorResponse($error);
			throw new FileIOEncodingException();
		}
		$tmpFileContent = '';
		foreach($tmpFileLines as $aline) {
			$tmpFileContent .= $aline;
		}

		$utf8content = mb_convert_encoding($tmpFileContent, 'UTF-8', $code);
		if (ord($utf8content{0}) == 0xef && ord($utf8content{1}) == 0xbb && ord($utf8content{2}) == 0xbf) {
			$utf8content = substr($utf8content, 3);
		}
		return $utf8content;
    }

	/*
	 * 指定されたパスにファイル上書き
	 */
	public static function overwriteFile($filePath, $contents, $makeBackup = true) {
//		if (file_exists($filePath) && $makeBackup) {
//			$sufx = $makeBackup === true ? '.back' : $makeBackup;
//			rename($filePath, $filePath.$sufx);
//		}
		$fno = fopen($filePath, 'w');
		fwrite($fno, $contents);
		fclose($fno);
	}

	/*
	 * ディレクトリのコピー
	 * @param $imageDir
	 * @param $destDir
	 */
	public static function copyDirectory($imageDir, $destDir) {
		$handle=opendir($imageDir);
		if (!is_dir($destDir)) {
			mkdir($destDir);
		}
		while($filename=readdir($handle)) {
			if(strcmp($filename,".")!=0 && strcmp($filename,"..")!=0 && strcmp($filename, ".svn")) {
				if(is_dir("$imageDir/$filename")) {
					if(!empty($filename) && !file_exists("$destDir/$filename")) {
						mkdir("$destDir/$filename");
					}
					FileIO::copyDirectory("$imageDir/$filename","$destDir/$filename");
				} else {
					if(file_exists("$destDir/$filename")) {
						unlink("$destDir/$filename");
					}
					copy("$imageDir/$filename","$destDir/$filename");
				}
			}
		}
	}

}

class FileIOFileNotFoundException extends Exception {
}
class FileIOEncodingException extends Exception {
}
?>