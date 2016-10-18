<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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

define('APP_ROOT_PATH', dirname(__FILE__));
define('APP_DIR_NAME', basename(dirname(__FILE__)));
define('APP_DEBUG_MODE', 0);

if (!class_exists('Logger')) {
	class Logger {

		public static function log($t) {
			error_log(print_r($t, 1), 3, self::getFileName());
		}

		public static function getFileName($count = 0) {
			$fileName = dirname(__FILE__).'/logs/'.time().'-'.$count.'.log';

			if (!file_exists($fileName)) {
				return $fileName;
			}

			return self::getFileName(++$count);
		}
	}
}
?>