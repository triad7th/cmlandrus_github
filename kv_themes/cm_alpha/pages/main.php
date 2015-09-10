<?php
//////////////////////////////////////////////////////
//
// THEME : CM_ALPHA
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/cm_alpha/pages/main.php
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
                    logo_load(array ("cmlandrus_logo_background.png","cmlandrus_logo.png"));
                ?>
                </div>
                <div class="col-sm-0 col-md-1"></div>
            </div>
        </div>

        <div class="row">
            <?php kvcm_load_menu();?>
        </div>
    </div>
    <?php kv_script(); ?>
</body>