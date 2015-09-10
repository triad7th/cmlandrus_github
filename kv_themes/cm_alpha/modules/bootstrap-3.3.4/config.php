<?php
//////////////////////////////////////////////////////
//
// THEME_MODULE : CM_ALPHA/BOOTSTRAP-3.3.4
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/cm_alpha/modules/bootstrap-3.3.4/config.php
//
//////////////////////////////////////////////////////

//
// DIRECTORIES    
//
if( !defined('KV_MODULE_BOOTSTRAP_ROOT') ) define ('KV_MODULE_BOOTSTRAP_ROOT',dirname(__FILE__));
if( !defined('KV_MODULE_BOOTSTRAP_CSS_ROOT') ) define ('KV_MODULE_BOOTSTRAP_CSS_ROOT',KV_MODULE_BOOTSTRAP_ROOT.'/dist/css');
if( !defined('KV_MODULE_BOOTSTRAP_JS_ROOT') ) define ('KV_MODULE_BOOTSTRAP_JS_ROOT',KV_MODULE_BOOTSTRAP_ROOT.'/dist/js');

//
// CONSTANTS
//


//
// HEAD
//

kv_add_module_head('<link href="'.kv_url(KV_MODULE_BOOTSTRAP_CSS_ROOT).'/bootstrap.min.css" rel="stylesheet">');

//
// SCRIPTS
//

kv_add_module_script('<script src="'.kv_url(KV_MODULE_BOOTSTRAP_JS_ROOT).'/bootstrap.min.js"></script>');

?>