//////////////////////////////////////////////////////
//
// THEME : CM_ALPHA
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/cm_alpha/js/logo_load.js
//
//////////////////////////////////////////////////////

$(document).ready( function() {
    $(".logoload-fadeinout").mouseenter( function() {
        $(this).stop(true);
        $(this).fadeTo(1000,1); 
    });
    
    $(".logoload-fadeinout").mouseleave( function() {
        $(this).stop(true);
        $(this).fadeTo(1000,0);
    });
});