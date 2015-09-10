<?php
//////////////////////////////////////////////////////
//
// THEME_MODULE : CM_ALPHA/CM_MENU
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/cm_alpha/modules/cm_menu/config.php
//
//////////////////////////////////////////////////////

//
// DIRECTORIES    
//
if( !defined('KV_MODULE_CMMENU_ROOT') ) define ('KV_MODULE_CMMENU_ROOT',dirname(__FILE__));
if( !defined('KV_MODULE_CMMENU_RESOURCES_ROOT') ) define ('KV_MODULE_CMMENU_RESOURCES_ROOT',KV_MODULE_CMMENU_ROOT.'/resources');

//
// CONSTANTS
//
if( !defined('KV_MODULE_CMMENU_DB_NAME') ) define ('KV_MODULE_CMMENU_DB_NAME','cmlandrus');
if( !defined('KV_MODULE_CMMENU_TBL_NAME') ) define ('KV_MODULE_CMMENU_TBL_NAME','main_menu');

//
// HEAD
//

kv_add_module_head('<link href="'.kv_url(KV_MODULE_CMMENU_ROOT).'/css/cm_menu.css" rel="stylesheet">');

//
// SCRIPTS
//

kv_add_module_script('<script src="'.kv_url(KV_MODULE_CMMENU_ROOT).'/js/cm-menu.js"></script>');

?>