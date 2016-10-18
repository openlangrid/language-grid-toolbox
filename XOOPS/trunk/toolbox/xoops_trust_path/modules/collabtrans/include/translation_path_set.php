<?php

require_once(dirname(__FILE__) . '/spyc.php');

function insertTranslationSet() {

    $db =& Database::getInstance() ;

    $sqls = getSqlSet($db->prefix());

    $data = Spyc::YAMLLoad(dirname(__FILE__) . '/collabtrans_transSet.yml');
    $transSets = $data["translation_set"];
    $transPaths = $data['translation_path'];

    foreach ($transSets as $transSet) {
        $transSetAvail = isTransSetAvailable($db, $transSet['set_name']);
        
        if ($transSetAvail == false) {
            translation_path_set_addWarning("Table translation_set was not found in database. Install the module translation_settings before use this module.");
            return true;
        }

        if ($transSetAvail > 0) {
            translation_path_set_addInfo( $transSet['set_name']." translation set has been available." );
            continue;
        }

        $user_id = $transSet['user_id'];
        $sql= sprintf($sqls["ins_translation_set"], $transSet['set_name'], $user_id);

        if( ! $db->query($sql) ) {
            translation_path_set_addError( $db->error() );
            return false ;
        }

        if( ! $set_id = getLastId($db) ) {
            return false ;
        }

        foreach ($transPaths as $transPath) {
            $sql= sprintf($sqls["ins_translation_path_l2r"],
                          $user_id, $set_id, $transPath['source_lang'], $transPath['target_lang']);

            if( ! $db->query($sql) ) {
                translation_path_set_addError( $db->error() );
                return false ;
            }

            if( ! $path_id_l2r = getLastId($db) ) {
                return false ;
            }

            $sql= sprintf($sqls["ins_translation_path_r2l"],
                          $user_id, $set_id, $transPath['target_lang'], $transPath['source_lang'], $path_id_l2r);

            if( ! $db->query($sql) ) {
                translation_path_set_addError( $db->error() );
                return false ;
            }

            if( ! $path_id_r2l = getLastId($db) ) {
                return false ;
            }

            if( ! insertExec($db, $path_id_l2r, $transPath['translation_exec_l2r']) ) {
                return false;
            }
            if( ! insertExec($db, $path_id_r2l, $transPath['translation_exec_r2l']) ) {
                return false;
            }
        }
        translation_path_set_addInfo($transSet['set_name']." translation set has installed successfully.");
    }
    return true;
}

function insertExec($db, $path_id, $transExecs) {
    $sqls = getSqlSet(0);

    $exec_id = 1;
    foreach ($transExecs as $transExec) {
        $sql= sprintf($sqls["ins_translation_exec"],
                      $path_id, $exec_id, $exec_id, $transExec['source_lang'],
                      $transExec['target_lang'], $transExec['service_id'], $transExec['dictionary_flag']);
        if( ! $db->query($sql) ) {
            translation_path_set_addError( $db->error() );
            return false ;
        }

        if( @$transExec['translation_bind'] && !insertBind($db, $path_id, $exec_id, $transExec['translation_bind']) ) {
            return false;
        }
        $exec_id ++;
    }
    return true;
}

function insertBind($db, $path_id, $exec_id, $transBinds) {
    $sqls = getSqlSet(0);

    $bind_id = 1;
    foreach ($transBinds as $transBind) {
        $sql = sprintf($sqls["ins_translation_bind"],
                       $path_id, $exec_id, $bind_id, $transBind['bind_type'], $transBind['bind_value']);
        if( ! $db->query($sql) ) {
            translation_path_set_addError( $db->error() );
            return false ;
        }
        $bind_id ++;
    }
    return true;
}

function getSqlSet($prefix = "") {
    static $src;

    if(isset($src))
        return $src;

    $src = array('get_last_id' =>              ("select LAST_INSERT_ID() as last_id;"),
                 'find_trans_set' =>            ("select count(*) from ".$prefix."_translation_set where set_name = '%s';"),
                 'ins_translation_set' =>      ("insert into ".$prefix."_translation_set ".
                                                "(set_name, user_id, shared_flag, create_user_id, create_time) ".
                                                "values ('%s', %s, 0, 1, UNIX_TIMESTAMP());"),
                 "ins_translation_path_l2r" => ("insert into ".$prefix."_translation_path ".
                                                "(user_id, set_id, path_name, source_lang, target_lang, revs_path_id, ".
                                                "create_user_id, create_time) ".
                                                "values (%s, %s, '', '%s', '%s', 0, 1, UNIX_TIMESTAMP());"),
                 'ins_translation_path_r2l' => ("insert into ".$prefix."_translation_path ".
                                                "(user_id, set_id, path_name, source_lang, target_lang, revs_path_id, ".
                                                "create_user_id, create_time)".
                                                "values (%s, %s, '', '%s', '%s', %s, 1, UNIX_TIMESTAMP());"),
                 'upd_translation_path_l2r' => ("update ".$prefix."_translation_path " .
                                                "set revs_path_id = %s ".
                                                "where path_id = %s"),
                 'ins_translation_exec' =>     ("insert into ".$prefix."_translation_exec ".
                                                "(path_id, exec_id, exec_order, source_lang, target_lang, service_type, ".
                                                "service_id, dictionary_flag, create_user_id, create_time) ".
                                                "values (%s, %s, %s, '%s', '%s', 0, '%s', %s, 1, UNIX_TIMESTAMP())"),
                 'ins_translation_bind' =>     ("insert into ".$prefix."_translation_bind ".
                                                "(path_id, exec_id, bind_id, bind_type, bind_value, create_user_id, create_time) ".
                                                "values (%s, %s, %s, %s, '%s', 1, UNIX_TIMESTAMP())"));

    return $src;
}

function getLastId($db) {
    static $cache;

    if (!isset($cache)) {
        $sqls = getSqlSet();
        $cache = $sqls['get_last_id'];
    }

    if( ! $result = $db->query($cache) ) {
        translation_path_set_addError( $db->error() );
        return false ;
    }

    $row = $db->fetchRow($result);
    return $row[0];
}

function isTransSetAvailable($db, $setName) {

    $sqls = getSqlSet();
    $sql = sprintf($sqls["find_trans_set"], $setName);

    if( ! $result = $db->query($sql) ) {
        translation_path_set_addError( $db->error() );
        return false ;
    }

    $row = $db->fetchRow($result);
    return $row[0];
}

function translation_path_set_addInfo($msg) {
    translation_path_set_appendLog(htmlspecialchars($msg).'<br />');
}

function translation_path_set_addError($msg) {
    translation_path_set_appendLog('<b><span style="font-color: #FF0000">'.htmlspecialchars($msg).'</span></b><br />');
}

function translation_path_set_addWarning($msg) {
    translation_path_set_appendLog('<b><span style="font-color: #FF8000">'.htmlspecialchars($msg).'</span></b><br />');
}

function translation_path_set_appendLog($formattedMsg) {
    $GLOBALS['ret'][] = $formattedMsg;
}
