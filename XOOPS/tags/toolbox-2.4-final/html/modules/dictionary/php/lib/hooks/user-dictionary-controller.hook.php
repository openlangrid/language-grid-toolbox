<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
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
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');
class UserDictionaryController_Hook {

    /**
     * 言語資源の保存後にCallされるHook関数
     * @param $dictionary 保存された言語資源の情報
     * @return 無し
     */
    function doUpdateAfter($dictionary) {
        // TODO: とりあえずのテスト（Hookを正しく設置する方針）。
        debugLog('Let\'s learning.'.print_r($dictionary, true));

        if ($dictionary['type_id'] == '1') {
            require_once(XOOPS_ROOT_PATH.'/service_grid/manager/EBMTLearningManager.class.php');
            $EBMTLearningManager = new EBMTLearningManager();
            $EBMTLearningManager->reservationLearning($dictionary['dictionary_name']);

            // 学習を非同期で実施する為のHTTPリクエストを発信
            $url = XOOPS_URL . '/modules/langrid_config/ebmt-learning.php';
            if ($this->no_sync_access($url)) {
                ;
            }
        }
    }

    //非同期でURLにアクセスする関数
    function no_sync_access($url){
        if(preg_match('/^(.+?):\/\/(\d+\.\d+\.\d+\.\d+|.+?):?(\d+)?(\/.*)?$/', $url, $matches)){

            $protocol = $matches[1];
            $host = $matches[2];
            $port = $matches[3];
            $path = $matches[4];

            if($port == ''){
                $port = '80';
            }

            if($path == ''){
                $path = '/';
            }

            //接続
            $fp = fsockopen($host, $port, $errno, $errstr, 5);
            if (!$fp) {
                return false;
            } else {
                //リクエストを送信
                $out = "GET $path HTTP/1.0\r\n";
                $out .= "Connection: Close\r\n\r\n";
                fwrite($fp, $out);
                //すぐに閉じる
                fclose($fp);
            }
            return true;
        }else{
            return false;
        }
    }
}
?>
