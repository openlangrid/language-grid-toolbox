<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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
/* $Id: PathDataConvertUtils.class.php 6254 2012-01-23 05:33:54Z infonic $ */

class PathDataConvertUtils {

	public static function format($settings){
//		$sev = new LangridServicesClass();
		$response = array();

		$tmp = array();
		foreach($settings as $path){
			$rs = array();
			$rs['id'] = $path->getPathId();
			$rs['name'] = $path->getPathName();
			$rs['uid'] = $path->getUserId();
			$rs['revs_id'] = $path->getRevsPathId();
			$rs['isDelete'] = 0;
			$rs['source_lang'] = $path->getSourceLang();
			$rs['target_lang'] = $path->getTargetLang();
			$rs['flow'] = 'left';
			$rs['inter_lang_1'] = '';
			$rs['inter_lang_2'] = '';
			for ($i = 1; $i <= 3; $i++) {
				$rs['lang'.$i] = '';
				$rs['translator_service_'.$i] = array();
				$rs['global_dict_'.$i] = '';
				$rs['local_dict_'.$i] = '';
				$rs['temp_dict_'.$i] = '';
				$rs['dict_flag_'.$i] = '';
				$rs['morph_analyzer'.$i] = '';
				$rs['similarity'.$i] = '';
				$rs['parallel'.$i] = '';
				$rs['translationTemplates'.$i] = '';
			}
			$rs['lang4'] = '';
			$rs['morph_analyzer4'] = '';

			$execs = $path->getTranslationExecs();
			foreach($execs as $exec){
				$cnt = $exec->getExecOrder();

				$rs['lang'.$cnt] = $exec->getSourceLang();
				$rs['lang'.($cnt+1)] = $exec->getTargetLang();

				$rs['dict_flag_'.$cnt] = $exec->getDictionaryFlag();

				if($cnt >= 2){
					$rs['inter_lang_'.($cnt-1)] = $exec->getSourceLang();
				}
				
				$binds = $exec->getTranslationBinds();
				$ids = array(
					0 => array(),
					1 => array(),
					2 => array(),
					3 => array(),
					4 => array(),
					5 => array()
				);
				
				foreach($binds as $bind){
					$btype = intval($bind->getBindType());
					if ($btype == 9) {
						$rs['morph_analyzer'.$cnt] = $bind->getBindValue();
					} else if ($btype == 0) {
						$ids[0][] = $bind->getBindValue();
					} else if ($btype == 6) {
						$rs['similarity'.$cnt] = $bind->getBindValue();
					} else {
						$ids[$btype][] = $bind->getBindValue();
					}
				}
				
				if (empty($ids[0])) {
					$rs['translator_service_'.$cnt] = array($exec->getServiceId());
				} else {
					$rs['translator_service_'.$cnt] = $ids[0];
				}
				
				$rs['global_dict_'.$cnt] = implode(',', $ids[1]);
				$rs['local_dict_'.$cnt] = implode(',', $ids[2]);
				$rs['temp_dict_'.$cnt] = implode(',', $ids[3]);
				$rs['parallel'.$cnt] = implode(',', $ids[4]);
				$rs['translationTemplates'.$cnt] = implode(',', $ids[5]);
			}
			$tmp[] = $rs;
		}

		foreach($tmp as $key => &$rs){
			if($rs['revs_id'] > 0){
				foreach($tmp as $key2 => &$rs2){
					if($rs2['id'] == $rs['revs_id']){
						$rs['id'] .= ",".$rs2['id'];
						$rs2['id'] = "";

						$rs['flow'] = 'both';
						if($rs2['lang1'] == $rs['lang4']){
							$rs['morph_analyzer4'] = $rs2['morph_analyzer1'];
						}elseif($rs2['lang1'] == $rs['lang3']){
							$rs['morph_analyzer3'] = $rs2['morph_analyzer1'];
						}elseif($rs2['lang1'] == $rs['lang2']){
							$rs['morph_analyzer2'] = $rs2['morph_analyzer1'];
						}
						unset($tmp[$key2]);
						break;
					}
				}
			}
			if($rs['id'] != ""){
				$response[] = $rs;
			}
		}

		return $response;
	}

	public static function parse($data) {
		return null;
	}
}
?>