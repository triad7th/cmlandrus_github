<?php
//////////////////////////////////////////////////////
//
// THEME : CM_ALPHA
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/cm_alpha/pages/script_consulting.php
//
//////////////////////////////////////////////////////

// login check
$user = logcheck();
?>

<head>
    <?php kv_head()?>
</head>

<body>
    <div class="fadein-screen"></div>
    <div class="container-fluid cm-top-menu">
        <div class="row bg-primary">
            <div class="col-sm-12 no-padding">
                <h6 class="v-margin-04 h-margin-04 pull-left"> CMLANDRUS.COM </h6>

                <?php
                kv::form_logout(array(
                    'action' => kv_get_page_url("front.php"),
                    'form_class' => 'form-inline no-margin',
                    'btn_class' => 'padding-logout btn pull-right btn-primary h-padding-10 textsize-h6',
                    'hidden' => 'LOGOUT'
                ));
                ?>
             </div>
        </div>
    </div>
    <div class="fadein-screen">
        <div class="container">
            <div class="row">
                <div class="col-xs-2 col-sm-2 col-md-3"></div>
                <div class="col-xs-8 col-sm-8 col-md-6">
                <?php
                    logo_load(array ("cmlandrus_logo_background.png","cmlandrus_logo.png"));
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