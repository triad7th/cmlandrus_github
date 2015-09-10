<?php
//////////////////////////////////////////////////////
//
// THEME : CM_ALPHA
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/cm_alpha/pages/contact.php
//
//////////////////////////////////////////////////////

// login check
$user = logcheck();
?>

<head>
    <?php kv_head()?>

</head>

<body>
    <?php 
        // logout menu
        require_once(KV_THEME_PAGES_ROOT."/logout_menu.php"); 
    ?>
    <div class="fadein-screen">
        <div class="container">
            <div class="row">
                <div class="col-sm-0 col-md-1"></div>
                <div class="col-sm-12 col-md-10">
                <?php
                    logo_load(array (
                        "images" => array ("cmlandrus_logo_background.png","contact_logo.png"),
                        "link_type" => "pages",
                        "link_dest" => "main.php",
                        "link_img" => "back_to_main.png"));
                ?>
                </div>
                <div class="col-sm-0 col-md-1"></div>
            </div>
        </div>
        
        <div class="row">
            <div class="contact-menu">
            <?php 
                kvcm_load_menu(array ( 
                        "tbl_name" => "social_menu", 
                        "img_root" => kv_dir_to_full_url(KV_THEME_IMAGES_ROOT),
                        "form" => "cm_img_menu.html"
                    ));
            ?>
            </div>
        </div>
    </div>
    <?php kv_plugin(KV_THEME_PAGES_ROOT."/plugins/go_back.htm",array("page_url"=>"main.php"));?>   
    <?php kv_script(); ?>
</body>