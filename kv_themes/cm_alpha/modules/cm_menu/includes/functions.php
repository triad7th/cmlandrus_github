<?php
//////////////////////////////////////////////////////
//
// THEME_MODULE : CM_ALPHA/CM_MENU
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/cm_alpha/modules/cm_menu/includes/functions.php
//
//////////////////////////////////////////////////////

function kvcm_load_menu($args= array()) {
    $args = array_merge(array(
        "db_name" => KV_MODULE_CMMENU_DB_NAME,
        "tbl_name" => KV_MODULE_CMMENU_TBL_NAME,
        "form" => "cm_menu.html",
        "img_root" => KV_MODULE_CMMENU_RESOURCES_ROOT
    ),$args);
    
    $kvcm_db = new KvDb($args['db_name']);
    //$contents = file_get_contents(KV_MODULE_CMMENU_RESOURCES_ROOT."/{$args["menu_item"]}");
    $contents = $kvcm_db->getAllEntries($args['tbl_name']);
    
    if($contents !== false) {
        
        $menu = array();
        $attr = array();
        $url = array();
        $target = array();
        
        foreach($contents as $key=>$value) {
            $imglink = explode(':',$value['name']);
            if( count($imglink) > 1 ) {
                $value['name'] = kv::form_load(KV_MODULE_CMMENU_RESOURCES_ROOT."/img_link.html",array ( "img" => $args["img_root"]."/".$imglink[1]));
            }
            
            $target_item = '';
            $type = explode(',',$value['type']);
            
            switch (count($type)) {
                case false :
                case 0 :
                case 1 :
                    $target_item = "_self";
                    break;
                case 2 :
                    $target_item = $type[1];
                    break;
            }
            
            //kv::kprint(var_export($type,true).','.$target_item);

            switch ($type[0]) {
                case 'pages'    :   $url_item = kv_get_page_url($value['destination']);
                                    break;
                case 'weblink'  :   $url_item = $value['destination'];
                                    break;
                default         :   $url_item = $value['destination'];
                                    break;
            }
            list($menu[],$attr[],$url[],$target[]) = array( $value['name'], $value['type'], $url_item, $target_item );
        }

        echo kv::form_load(KV_MODULE_CMMENU_RESOURCES_ROOT."/{$args["form"]}",array (
            'href'=>$url,
            "target"=>$target,
            'menu'=>$menu
        ));
    }
}

?>