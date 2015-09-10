<?php
//////////////////////////////////////////////////////
//
// THEME : KV_ADMIN
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/kv_admin/functions.php
//
//////////////////////////////////////////////////////

//
// logcheck()
//
// check if user is logged in
//
// [return]
// user's id
//
function logcheck() {
    global $users;
    
    if( ($user=$users->loggedUser()) === false )
    {
        kv_print("you must login");
        exit();
    }  
    return $user;
}

?>