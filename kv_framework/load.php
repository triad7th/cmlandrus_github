<?php
//////////////////////////////////////////////////////
//
// KV FRAMEWORK
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_framework/load.php
//
//////////////////////////////////////////////////////


//
// Load Fundamental config and functions
//
require_once ( dirname(__FILE__). "/config.php");
require_once ( dirname(__FILE__). "/functions.php");

//
// Load Framework Modules
//
kv_load_modules();

//
// Load Theme
//
kv_load_theme();

//
// Load First Page
//
kv_load_page();
?>