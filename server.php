<?php
//////////////////////////////////////////////////////
//
// KV FRAMEWORK
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/index.php
//
//////////////////////////////////////////////////////

//
// Define KV_ROOT
//
define ('KV_ROOT', dirname(__FILE__));
define ('KV_SERVER_ROOT', dirname($_POST['server']));

//
// Define KV_THEME
//
define ('KV_THEME', "cm_alpha");

//
// Load kv_framework
//
require( dirname(__FILE__) . "/kv_framework/load_server.php" );

?>