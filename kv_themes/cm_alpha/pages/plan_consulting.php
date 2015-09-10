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
    <?php
        echo "<link href=\"".kv_theme_root()."/pages/documents/plan_consulting.css\" rel=\"stylesheet\">";
    ?>
</head>

<body style="background-color: white">
    <?php 
        // logout menu
        require_once(KV_THEME_PAGES_ROOT."/logout_menu.php"); 
    ?>
    
    <div class="fadein-screen">
        <div class="container cm-container">
           <?php
                echo file_get_contents(KV_THEME_PAGES_ROOT."/documents/plan_consulting.htm");
            ?>
        </div>
    </div>
    
    <?php kv_plugin(KV_THEME_PAGES_ROOT."/plugins/go_back.htm",array("page_url"=>"main.php"));?>     
    <?php kv_script(); ?>
</body>