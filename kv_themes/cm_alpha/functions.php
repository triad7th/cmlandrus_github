<?php
//////////////////////////////////////////////////////
//
// THEME : CM_ALPHA
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/cm_alpha/functions.php
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
    
    if( KV_THEME_CMALPHA_LOGCHECK ) {
        global $users;

        if( ($user=$users->loggedUser()) === false )
        {
            kv_print("you must login");
            exit();
        }  
        return $user;
    } else {
        return true;
    }
}

function logo_load(array $arr) {
//
// load images from the /forms folder
//
    if( isset($arr['images']) === false ) $arr['images'] = $arr;
    $arr = array_merge( array (
                        "images" => array (),
                        "link_type" => false ,
                        "link_dest" => false ,
                        "link_img" => false,
                        "link_class" => "logoload-fadeinout"
                        ),$arr);
    
 
    kv_log(kvar_export($arr,true));
    
    // if there's no type, assign it into 'weblink' type
    if ($arr["link_type"] === false) $arr["link_type"] = "weblink";
    kv_log("link type : ".$arr["link_type"]);
    
    switch($arr["link_type"]){
        case false :
            break;
        case 'pages' :
            if( $arr["link_dest"] !== false ) $arr["link_dest"] = kv_get_page_url($arr["link_dest"]);
                else $arr["link_dest"] = "#";
            break;

        case 'weblink' :
        default :
            if( $arr["link_dest"] !== false ) $arr["link_dest"] = $arr["link_dest"];
                else $arr["link_dest"] = "#";
            break;

    }

    foreach( $arr["images"] as $key=>$value) {
        if ($key == '0' ) $classes = "cm-logo";
            else $classes = "cm-logo cm-logo-same-place";
        echo kv::form_load(KV_THEME_FORMS_ROOT.'/logo_load.html',array (
            "classes" => $classes,
            "img" => kv_get_image_url($value),
            "alt" => explode('.',$value)[0],
            "link" => $arr["link_dest"]
        ));
    }
    
    if( $arr["link_img"] !== false ) {
        $classes = "cm-logo cm-logo-same-place ".$arr["link_class"];
        echo kv::form_load(KV_THEME_FORMS_ROOT."/logo_load.html",array (
            "classes" => $classes,
            "img" => kv_get_image_url($arr["link_img"]),
            "alt" => explode('.',$arr["link_img"])[0],
            "link" => $arr["link_dest"]
        ));
    }
}

function load_txt($fn) {
//
// load txt file from the /documents folder
//
    $content = file_get_contents(KV_THEME_DOCUMENTS_ROOT.'/'.$fn);
    if($content !== false) {
        return $content;
    } else return false;
}

?>