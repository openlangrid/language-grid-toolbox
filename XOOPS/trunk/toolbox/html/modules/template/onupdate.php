<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
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
/**
 * @author kinoshita
 */

$mydirname = basename(dirname( __FILE__ ));
$mydirpath = dirname( __FILE__ ) ;
$module = basename(dirname( __FILE__ ));

eval( ' function xoops_module_update_'.$mydirname.'( $module ) { return template_onupdate_base( $module , "'.$mydirname.'" ) ; } ' ) ;


if( ! function_exists( 'template_onupdate_base' ) ) {

    function template_onupdate_base( $module , $mydirname )
    {
        // transations on module update

        global $msgs ;

        // for Cube 2.1
        if( defined( 'XOOPS_CUBE_LEGACY' ) ) {
            $root =& XCube_Root::getSingleton();
            $root->mDelegateManager->add( 'Legacy.Admin.Event.ModuleUpdate.' . ucfirst($mydirname) . '.Success', 'template_message_append_onupdate' ) ;
            $msgs = array() ;
        } else {
            if( ! is_array( $msgs ) ) $msgs = array() ;
        }

        $db =& Database::getInstance() ;
        $mid = $module->getVar('mid') ;

        // 0.xx -> 0.81 regenerate expression table
        $expressions = $db->prefix("${mydirname}_translation_template_expressions");
        $currentVersion = 0.0;
        $result = $db->query("SHOW INDEX FROM ${expressions}");
        while ($row = $db->fetchArray($result)) {
            if ($row['Column_name'] == 'ngram') {
                $currentVersion = 0.81;
            }
        }

        if ($currentVersion < 0.81) {
            $msgs[] = 'Old table found. Migrating all records...';
            $workTB = "${expressions}_work_table";
            $db->queryF("
                CREATE TABLE ${workTB} (
                	translation_template_id INT(11) UNSIGNED NOT NULL,
                	language_code VARCHAR(30) NOT NULL,
                	expression TEXT,
                	PRIMARY KEY (translation_template_id, language_code)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8
                ");
            $db->queryF("
                INSERT INTO ${workTB} (
                    translation_template_id,
                    language_code,
                    expression    
                ) SELECT 
                	translation_template_id,
                	language_code,
                	expression
                FROM ${expressions}
                ");
            $db->queryF("
                DROP TABLE ${expressions}
                ");
            // create 0.81 table
            $db->queryF("
                CREATE TABLE ${expressions} (
                	translation_template_id INT(11) UNSIGNED NOT NULL,
                	language_code VARCHAR(30) NOT NULL,
                	expression TEXT,
                	ngram TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci,
                	PRIMARY KEY (translation_template_id, language_code)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8
                ");

            require_once(XOOPS_ROOT_PATH . '/api/class/manager/translation_template/ngram_converter.php');
            $ng = new NgramConverter();
            $result = $db->query("SELECT * FROM ${workTB}");
            while ($row = $db->fetchArray($result)) {
                $id = mysql_real_escape_string($row['translation_template_id']);
                $lcode = mysql_real_escape_string($row['language_code']);
                $exp = mysql_real_escape_string($row['expression']);
                $ngram = mysql_real_escape_string($ng->to_fulltext($exp, 2));
                $db->queryF("
                    INSERT INTO ${expressions} (
                        translation_template_id,
                        language_code,
                        expression,
                        ngram
                    ) VALUES (
                        ${id},
                        '${lcode}',
                        '${exp}',
                        '${ngram}'
                    )
                    ");
            }
            // FULLTEXT KEY `ngram` (`ngram`)
            $db->queryF("DROP TABLE ${workTB}");
            $msgs[] = 'Migration finished successfully.';
        } else {
            $msgs[] = 'Updating fulltext index...';
            $db->queryF("DROP INDEX `ngram` ON ${expressions} (`ngram`)");
            $msgs[] = 'Updating fulltext index finished.';
        }

        $db->queryF("CREATE FULLTEXT INDEX `ngram` ON ${expressions} (`ngram`)");

        return true ;
    }

    function template_message_append_onupdate( &$module_obj , &$log )
    {
        if( is_array( @$GLOBALS['msgs'] ) ) {
            foreach( $GLOBALS['msgs'] as $message ) {
                $log->add( strip_tags( $message ) ) ;
            }
        }

        // use mLog->addWarning() or mLog->addError() if necessary
    }

}

?>
