<?php
//////////////////////////////////////////////////////
//
// THEME : KV_ADMIN
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/kv_admin/pages/console.php
//
//////////////////////////////////////////////////////

// login check
$user = logcheck();
?>

<head>
    <?php kv_head()?>
</head>

<body>
    <div class="container-fluid no-padding">
        <div class="row bg-primary no-margin">
            <div class="col-sm-12 no-padding">
                <h6 class="v-margin-04 h-margin-04 pull-left"> Welcome! <?php echo $user?></h6>

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

    <div class="container-fluid no-padding">
        <div class="v-margin-00"></div>
        <div class="row no-margin">
            <div class="col-md-2 no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li class="active"><?php kv_add_anchor(array("href"=>"console.php","content"=>"Console","type"=>"page"));?> </li>
                    <?php /*
                    <li><?php kv_add_anchor(array("href"=>"pages.php","content"=>"Pages","type"=>"page"));?></li>
                    <li><?php kv_add_anchor(array("href"=>"posts.php","content"=>"Posts","type"=>"page"));?></li></li>
                    */?>
                    <li><?php kv_add_anchor(array("href"=>"settings.php","content"=>"Settings","type"=>"page"));?></li></li>
                    <?php /*
                    <li><?php kv_add_anchor(array("href"=>"main.php","content"=>"Home","type"=>"page"));?></li>
                    */?>
                </ul>
            </div>
            <div class="col-md-10 no-padding">
                <pre class="kv-console-box no-margin" id="console"><?php echo file_get_contents(KV_THEME_PAGES_ROOT."/console/read.me")?></pre>
                <input type="text" class="form-control kv-console-input" id="console-input" autofocus>
            </div>
    
            <div class="clearfix visible-lg"></div>
        </div>
    </div>


    <?php kv_script(); ?>
    <?php kv_add_script_here(kv_theme_root()."/pages/console/kv_console.js"); ?>
    <?php kv_add_script_here(kv_theme_root()."/pages/console/kv_ajax.js"); ?>
    <?php kv_add_script_here(kv_theme_root()."/pages/console/client.js"); ?>

</body>