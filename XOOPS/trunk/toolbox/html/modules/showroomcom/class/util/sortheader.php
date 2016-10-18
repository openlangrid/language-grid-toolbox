<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to view
// contents of BBS without logging into Toolbox.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
class SortHeader {

	private $CurKey;
	private $param = array();

	public function __construct($header_cnt,$sort_key) {
		$sort_key = intval($sort_key);
		if(!$sort_key){$sort_key = 0;}

		$this->CurKey = $sort_key;

		for($i=1;$i<=$header_cnt;$i++){
			$this->param[$i] = array();

			$nextkey = ($i * 2) - 1;
			$label = "";

			if($sort_key == (($i * 2)-1)){
				$nextkey = ($i * 2);
				$label = "&uarr;";
			}elseif($sort_key == ($i * 2)){
				$nextkey = 0;
				$label = "&darr;";
			}
			$this->param[$i]['nextkey'] = $nextkey;
			$this->param[$i]['label'] = $label;
		}
	}

	public function getCurrentKey() {
		return $this->CurKey;
	}

	public function getNextKey($num) {
		return $this->param[$num]['nextkey'];
	}

	public function getLabel($num) {
		return $this->param[$num]['label'];
	}

}

?>
