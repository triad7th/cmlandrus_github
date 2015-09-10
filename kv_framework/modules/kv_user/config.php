<?php
//////////////////////////////////////////////////////
//
// MODULE : KV_USER
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_framework/modules/kv_user/config.php
//
//////////////////////////////////////////////////////

//
// KV GLOBAL
//
if ( !defined('KV_ROOT') ) define ('KV_ROOT', dirname(__FILE__));

//
// DIRECTORIES
//
define ('KVUSERDB_ROOT', dirname(__FILE__));
define ('KVUSER_ROOT', dirname(__FILE__));
define ('KVFUNDAMENTAL_ROOT',dirname(__FILE__));
define ('KVDB_ROOT',dirname(__FILE__));

//
// URL
//
define ('KVUSER_ROOT_URL', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);

//
// GLOBAL VARIABLES
//

?>