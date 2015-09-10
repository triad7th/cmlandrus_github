<?php
//////////////////////////////////////////////////////
//
// THEME : CM_ALPHA
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/cm_alpha/pages/front.php
//
//////////////////////////////////////////////////////


function login_form() {
    kv::form_login(array(
        'action'=>kv_get_page_url(__FILE__),
        'id'=>'id',
        'pw'=>'pw',
        'hidden'=>'LOGIN',
        'hidden_value'=>''
    ));        
}

if( KV_THEME_CMALPHA_LOGCHECK ) {
    if( isset($_POST['LOGIN']) ) {
        if( $GLOBALS['users']->login($_POST['id'],$_POST['pw']) === true ) {
            kv_redirect_page('main.php');
        } else {
            kv_print("login failed...!");
            login_form();
        }

    } else if( isset($_POST['LOGOUT'])) {
        if( $GLOBALS['users']->logout() === true ) {
            kv_print("logout succeed!");
            login_form();
        } else
        {
            kv_print("logout failed!");
            login_form();
        }
    } else {
        if( $GLOBALS['users']->loggedUser() === false) {
            login_form();
        } else {
            kv_redirect_page('main.php');
        }
    }

    $GLOBALS['users']->flushErrMsg();
} else
{
    kv_redirect_page('main.php');
}
?>