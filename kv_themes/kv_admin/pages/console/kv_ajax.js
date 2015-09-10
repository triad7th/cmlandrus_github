//////////////////////////////////////////////////////
//
// JS OBJECT : KV_AJAX
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/kv_admin/pages/console/kv_ajax.js
//
//////////////////////////////////////////////////////

function kv_getPath() {
    var $pathname_arr = window.location.pathname.split("/")
    var $pathname = '';

    $pathname_arr.pop();
    $pathname_arr.forEach(function(elem,index,array) {
        $pathname += elem+"/";
    });
    return window.location.protocol + "//" + window.location.host + $pathname + _PATH;
}

function kv_getHost() {
    var $pathname_arr = window.location.pathname.split("/")
    var $pathname = '';

    $pathname_arr.pop();
    $pathname_arr.forEach(function(elem,index,array) {
        $pathname += elem+"/";
    });
    
    
    //return window.location.protocol + "//" + window.location.host + $pathname.substr(0,$pathname.length-1);
    return $pathname.substr(0,$pathname.length-1);
}

function kv_ajax($args) {
    // set server
    this.path = $args.path;
    this.server = $args.server;
    this.host = kv_getHost();
    
    this.post = function( $args, $function ) {
        var $attrs = new Object;
        
        $.extend($attrs,$args);
        $attrs.host = this.host;
        $attrs.server = this.path+"/"+this.server;
        
        
        console.log($attrs.server+ " " + $attrs.host+ " "+ $attrs.cmd);
        $.post(kv_getHost()+"/server.php",$attrs,$function,"json");
    }
}