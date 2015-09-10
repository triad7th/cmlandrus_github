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
        <div class="row">
            <div class="col-md-2">
                <ul class="nav nav-pills nav-stacked">
                    <li><?php kv_add_anchor(array("href"=>"console.php","content"=>"Console","type"=>"page"));?> </li>
                    <li class="active"><?php kv_add_anchor(array("href"=>"pages.php","content"=>"Pages","type"=>"page"));?></li>
                    <li><?php kv_add_anchor(array("href"=>"posts.php","content"=>"Posts","type"=>"page"));?></li></li>
                    <li><?php kv_add_anchor(array("href"=>"settings.php","content"=>"Settings","type"=>"page"));?></li></li>
                    <li><?php kv_add_anchor(array("href"=>"main.php","content"=>"Home","type"=>"page"));?></li>
                </ul>
            </div>
            <div class="col-md-10">
                <p>Pages</p>
            </div>
            <div class="clearfix visible-lg"></div>
        </div>
    </div>


    <?php kv_script(); ?>
</body>