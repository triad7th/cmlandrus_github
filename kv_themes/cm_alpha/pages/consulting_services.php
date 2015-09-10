<?php
//////////////////////////////////////////////////////
//
// THEME : CM_ALPHA
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/cm_alpha/pages/plan_consulting.php
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
                <div class="col-xs-2 col-sm-2 col-md-3"></div>
                <div class="col-xs-8 col-sm-8 col-md-6">
                <?php
                    logo_load(array (
                        "images" => array ("cmlandrus_logo_background.png","cmlandrus_logo.png"),
                        "link_type" => "pages",
                        "link_dest" => "main.php",
                        "link_img" => "back_to_main.png"
                    ));
                ?>
                </div>
                <div class="col-xs-2 col-sm-2 col-md-3"></div>
            </div>
            <div class="row">
                <div class="col-xs-1 col-sm-1 col-md-2"></div>
                <div class="cm-about col-xs-10 col-sm-10 col-md-8 text-justify">
                <div class="cm-about-screen"></div>

                <?php
                    echo load_txt("script_consulting_basics.txt");
                ?>
                </div>

                <div class="col-sx-1 col-sm-1 col-md-2"></div>
            </div>
        </div>

        <!--?php kvcm_load_menu();?-->
    </div>
    <?php kv_plugin(KV_THEME_PAGES_ROOT."/plugins/go_back.htm",array("page_url"=>"main.php"));?>    
    <?php kv_script(); ?>
</body>