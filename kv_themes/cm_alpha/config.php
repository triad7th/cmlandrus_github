<?php
//////////////////////////////////////////////////////
//
// THEME : CM_ALPHA
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/cm_alpha/config.php
//
//////////////////////////////////////////////////////

//
// DIRECTORIES    
//
if( !defined('KV_THEME_PAGES_ROOT') ) define ('KV_THEME_PAGES_ROOT',KV_THEME_ROOT.'/pages');
if( !defined('KV_THEME_IMAGES_ROOT') ) define ('KV_THEME_IMAGES_ROOT',KV_THEME_ROOT.'/images');
if( !defined('KV_THEME_FORMS_ROOT') ) define ('KV_THEME_FORMS_ROOT',KV_THEME_ROOT.'/forms');
if( !defined('KV_THEME_DOCUMENTS_ROOT') ) define ('KV_THEME_DOCUMENTS_ROOT',KV_THEME_ROOT.'/documents');
if( !defined('KV_THEME_SCRIPTS_ROOT') ) define ('KV_THEME_SCRIPTS_ROOT',KV_THEME_ROOT.'/js');

// FILES
if( !defined('KV_THEME_FRONTPAGE') ) define('KV_THEME_FRONTPAGE','front.php');

//
// HEAD
//
kv_add_head('<meta charset="utf-8">');
kv_add_head('<meta http-equiv="X-UA-Compatible" content="IE=edge">');
kv_add_head('<meta name="viewport" content="width=device-width, initial-scale=1">');

kv_add_head('<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">');
kv_add_head('<link href="'.kv_theme_root().'/css/kv_basic.css" rel="stylesheet">');
kv_load_module_head();
kv_add_head('<link href="'.kv_theme_root().'/css/style.css" rel="stylesheet">');

//
// SCRIPTS
//
kv_add_script('<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>');
kv_add_script('<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>');
kv_add_script('<script src="'.kv_theme_root().'/js/logo_load.js"></script>');
kv_load_module_scripts();

//
// GLOBAL VARIABLES    
//
if(!isset($GLOBALS['users'])) $GLOBALS['users'] = new KvUser('kv_user');
    
?>