<?php
//////////////////////////////////////////////////////
//
// KV FRAMEWORK
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_framework/functions.php
//
//////////////////////////////////////////////////////


//
// kv_add_script(string)
//
// add script document
//
function kv_add_script_here($fn) {
    $args = array (
        "src" => $fn
    );
    
    echo kv::form_load(KV_RESOURCES_ROOT."/forms/form_script.html",$args);
}

//
// kv_add_css(string)
//
// add stylesheet
//
function kv_add_css_here($fn) {
    $args = array (
        "href" => $fn
    );
    echo kv::form_load(KV_RESOURCES_ROOT."/forms/form_css.html",$args);
}

//
// kv_add_anchor(array)
//
// add anchor tag
//
function kv_add_anchor(array $args=array()) {
    $args = array_merge(array(
        "type" => "urllink",
        "href" => "http://www.google.com",
        "content" => "add_anchor"
    ),$args);
    
    switch ($args["type"]) {
        case "pages"        :
        case "page"         :   $args["href"] = kv_get_page_url($args["href"]);
                                break;
        case "link"         :
        case "url"          :
        case "urllink"      :   break;
        default        :   break;
    }
                                
    echo kv::form_load(KV_RESOURCES_ROOT."/forms/form_anchor.html",$args);    
}

//
// kv_dir_to_url
// 
// returns url of givien file string without server url and http:// notation
//
function kv_dir_to_url($dir) {
    $path = substr($dir,strlen($_SERVER['DOCUMENT_ROOT']));
    return $path;
}

//
// kv_dir_to_full_url( filename
// 
// returns url of givien file string with server url and http:// notation
//
function kv_dir_to_full_url($dir) {
    //      http://     finishes with slash /                   starts with non-slash
    return kv_path_wo_last_slash('http://'.kv_server_name($_SERVER['SERVER_NAME']).kv_path_name(kv_dir_to_url($dir)));
}

// short name version
function kv_url($dir) {
    //      http://     finishes with slash /                   starts with non-slash
    return kv_path_wo_last_slash('http://'.kv_server_name($_SERVER['SERVER_NAME']).kv_path_name(kv_dir_to_url($dir)));
}

// server name ending slash manipulation
function kv_server_name ($sn) {
    if($sn == '') return '';
    if(substr($sn,-1) == '/') return $sn;
        else return $sn.'/';
}

// path name starting with alphabet
function kv_path_name ($pn) {
    if($pn == '') return '';
    if(substr($pn,0,1) == '/') return kv_path_wo_last_slash(substr($pn,1));
        else return kv_path_wo_last_slash($pn);
}

// path without last slash
function kv_path_wo_last_slash ($pn) {
    if(substr($pn,-1) == '/') return substr($pn,0,-1);
        else return $pn;
}

    
//
// kv_get_image_url( image filename )
// 
// returns full url for image filename
//
function kv_get_image_url ( $img_fn ) {
    if(defined('KV_THEME_IMAGES_ROOT')) return kv_dir_to_full_url(KV_THEME_IMAGES_ROOT)."/$img_fn";
        else kv_dir_to_full_url(KV_RS_NOIMAGE);
}
//
// kv_root()
// 
// returns KV_ROOT url
//
function kv_root() {
    return kv_dir_to_full_url(KV_ROOT);
}

//
// kv_theme_root()
// 
// returns KV_THEME ROOT url if it exists, or return just KV_ROOT url
//
function kv_theme_root() {
    if(defined('KV_THEME_ROOT')) return kv_dir_to_full_url(KV_THEME_ROOT);
        else return kv_root();
}

//
// kv_load_modules()
// 
// load all the fundamental modules for kv_framework
//
function kv_load_modules() {
    require_once( KV_MODULES_ROOT."/kv_user/load.php" );
    kv_load_leftovers();
}


//
// kv_load_theme()
// 
// load the theme
//
function kv_load_theme() {
    require_once( KV_THEME_ROOT."/load.php");
    kv_load_leftovers();
}

//
// kv_load_page()
//
// run the front page of the theme
//
function kv_load_page() {
    if( isset($_GET['kv_fn'])) {
        $fn = $_GET['kv_fn'];
        require_once( KV_THEME_PAGES_ROOT."/{$_GET['kv_fn']}");
    } else {
        if( defined('KV_THEME_FRONTPAGE') ) {
            require_once( KV_THEME_PAGES_ROOT."/".KV_THEME_FRONTPAGE );
        } else return false;
    }
    return true;
}

//
// kv_get_page_url( filename )
//
// return a fullpath url for given filename
//
function kv_get_page_url($fn) {
    // special themes treatment
    switch (KV_URL_METHOD) {
        case 'KV_PRETTY_URL' :
            switch (KV_THEME) {
                case 'kv_admin'     :   return kv_root().'/admin.php?kv_fn='.kv::filename($fn);
                                        break;
                default             :   return kv_root().'/'.kv::filename_wo_ext($fn);
                                        break;
            }
        break;
        
        default :
            switch (KV_THEME) {
                case 'kv_admin'     :   return kv_root().'/admin.php?kv_fn='.kv::filename($fn);
                                        break;
                default             :   return kv_root().'/?kv_fn='.kv::filename($fn);
                                        break;
            }
        break;
    }
}

//
// kv_get_file_url( filename )
//
// return a fullpath url for given filename
//
function kv_get_file_url($fn) {
    // special themes treatment
    
    // check url method
    switch (KV_URL_METHOD) {
        case 'KV_PRETTY_URL' :
            switch (KV_THEME) {
                case 'kv_admin'     :   return kv_root().'/admin.php?kv_fn='.kv::filename($fn);
                                        break;
                default             :   return kv_root().'/'.kv::filename_wo_ext($fn);
                                        break;
            }
        break;
        
        default :
            switch (KV_THEME) {
                case 'kv_admin'     :   return kv_root().'/admin.php?kv_fn='.kv::filename($fn);
                                        break;
                default             :   return kv_root().'/?kv_fn='.kv::filename($fn);
                                        break;
            }
        break;
    }
}

//
// kv_redirect_page( filename )
//
// redirect page into the given filename
//
function kv_redirect_page($fn) {
    header('Location: '.kv_get_page_url($fn));
    exit();
}

//
// kv_add_head( string )
//
// add the matrials which is going to be <head> section
//
function kv_add_head($str) {
    $GLOBALS['kv_head'][] = $str;
}

function kv_add_module_head($str) {
    $GLOBALS['kv_module_head'][] = $str;
}

//
// kv_add_script( string )
//
// add the script files for the pages ( including theme's own scripts )
//
function kv_add_script($str) {
    $GLOBALS['kv_script'][] = $str;
}

function kv_add_module_script($str) {
    $GLOBALS['kv_module_script'][] = $str;
}

//
// kv_load_module_head
//
// load leftover module head
function kv_load_module_head() {
    if(isset($GLOBALS['kv_module_head'])) {
        // if module head is set, load it first
        foreach($GLOBALS['kv_module_head'] as $item) $GLOBALS['kv_head'][] = $item;
        // clear module head
        unset($GLOBALS['kv_module_head']);
    }
}

//
// kv_load_module_scripts
//
// load leftover module scripts
function kv_load_module_scripts() {
    if(isset($GLOBALS['kv_module_script'])) {
        foreach($GLOBALS['kv_module_script'] as $item) $GLOBALS['kv_script'][] = $item;
        // clear module_script
        unset($GLOBALS['kv_module_script']);
    } 
}

//
// kv_load_leftovers()
//
// load all leftover things
//
function kv_load_leftovers() {
    kv_load_module_scripts();
    kv_load_module_head();
}
    

//
// kv_head()
//
// printout <head> section
//
function kv_head() {
    $head ='';
    foreach($GLOBALS['kv_head'] as $item) {
        $head.= trim($item)."\n";
    }
    echo $head;
}

//
// kv_script()
//
// printout script section
//
function kv_script() {
    $script ='';
    foreach($GLOBALS['kv_script'] as $item) {
        $script.= trim($item)."\n";
    }
    echo $script;
}

//
// kv_plugin(filename)
// 
// put plugin scripts/html
//
function kv_plugin($fn,array $args) {
    foreach($args as $key=>$val) {
        switch ($key) {
            case "page_url" :
                $args["$key"] = kv_get_page_url($val);
                break;
        }
    }
    echo kv::form_load($fn,$args);
}

?>