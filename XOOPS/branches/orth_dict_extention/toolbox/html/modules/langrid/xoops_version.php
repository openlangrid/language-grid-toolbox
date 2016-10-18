<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
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

$modversion['name'] = _MI_LANGRID_NAME;
$modversion['version'] = 0.47;
$modversion['description'] = _MI_LANGRID_NAME_DESC;
$modversion['author'] = "Language Grid Project, NICT";
$modversion['credits'] = "";
$modversion['license'] = "GPL";
$modversion['image'] = "images/logo.gif";
$modversion['dirname'] = "langrid";

// Menu
$modversion['hasMain'] = 1;
// Templates
$modversion['templates'][1]['file'] = 'langridmain.html';
$modversion['templates'][1]['description'] = 'langrid';
$modversion['templates'][2]['file'] = 'langrid-test.html';
$modversion['templates'][2]['description'] = 'langrid-test';
$modversion['templates'][3]['file'] = 'langrid_modules_style.html';
$modversion['templates'][3]['description'] = 'langrid-module-css-style';
$modversion['templates'][4]['file'] = 'langrid_imported_services.html';

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][0] = "translation_logs";
$modversion['tables'][1] = "default_dictionary_setting";
$modversion['tables'][2] = "default_dictionary_bind";

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';
$modversion['hasconfig'] = 1;
$modversion['read_any'] = 1;

$modversion['config'][1] =
    array('name'        => 'core_node_url',
          'title'       => '_MI_LGD_CONF_LANGRID_URL',
          'description' => '_MI_LGD_CONF_LANGRID_URL_D',
          'formtype'    => 'textbox',
          'valuetype'   => 'text',
          'default'     => 'http://langrid.org/service_manager/'
          );

$modversion['config'][] =
    array('name'        => 'core_node_grid_id',
          'title'       => '_MI_LGD_CONF_GRID_ID',
          'description' => '_MI_LGD_CONF_GRID_ID_D',
          'formtype'    => 'textbox',
          'valuetype'   => 'text',
          'default'     => 'kyoto1.langrid'
          );

$modversion['config'][] =
    array('name'        => 'langrid_id',
          'title'       => '_MI_LGD_CONF_LANGRID_ID',
          'description' => '_MI_LGD_CONF_LG_ID_D',
          'formtype'    => 'textbox',
          'valuetype'   => 'string',
          'default'     => ''
          );

$modversion['config'][] =
    array('name'        => 'langrid_pass',
          'title'       => '_MI_LGD_CONF_LANGRID_PASS',
          'description' => '_MI_LGD_CONF_LG_PASS_D',
          'formtype'    => 'password',
          'valuetype'   => 'string',
          'default'     => ''
          );

$modversion['config'][] =
    array('name'        => 'proxy_host',
          'title'       => '_MI_LGD_CONF_PROXY_HOST',
          'description' => '_MI_LGD_CONF_PROXY_HOST_D',
          'formtype'    => 'textbox',
          'valuetype'   => 'string',
          'default'     => ''
          );

$modversion['config'][] =
    array('name'        => 'proxy_port',
          'title'       => '_MI_LGD_CONF_PROXY_PORT',
          'description' => '_MI_LGD_CONF_PROXY_PORT_D',
          'formtype'    => 'textbox',
          'valuetype'   => 'string',
          'default'     => ''
          );

$modversion['config'][] =
    array('name'        => 'is_update_service',
          'title'       => '_MI_LGD_CONF_IS_UP',
          'description' => '_MI_LGD_CONF_IS_UP_D',
          'formtype'    => 'yesno',
          'valuetype'   => 'int',
          'default'     => '1'
          );

$modversion['config'][] =
    array('name'        => 'last_update_time',
          'title'       => '_MI_LGD_CONF_LAST_UPDT',
          'description' => '_MI_LGD_CONF_LAST_UPDT_D',
          'formtype'    => 'textbox',
          'valuetype'   => 'text',
          'default'     => '0'
          );

$modversion['config'][] =
    array('name'        => 'update_interval',
          'title'       => '_MI_LGD_CONF_INTERVAL',
          'description' => '_MI_LGD_CONF_INTERVAL_D',
          'formtype'    => 'textbox',
          'valuetype'   => 'text',
          'default'     => '86400'
          );

/*
$modversion['config'][9]['name'] = 'group_key';
$modversion['config'][9]['title'] = '_MI_LGD_CONF_GROUP_KEY';
$modversion['config'][9]['description'] = '_MI_LGD_CONF_GROUP_KEY_D';
$modversion['config'][9]['formtype'] = 'textbox';
$modversion['config'][9]['valuetype'] = 'text';
$modversion['config'][9]['default'] = '';
*/
$modversion['onUpdate'] = 'onupdate.php' ;
?>