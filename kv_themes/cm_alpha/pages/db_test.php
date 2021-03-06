<?php
//////////////////////////////////////////////////////
//
// THEME : CM_ALPHA
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/cm_alpha/pages/db_test.php
//
//////////////////////////////////////////////////////
?>

<script>
function hide_logout() {
    $(document).ready(function() {
                      $("#logout").css("display","none");
                      });
}

function show_logout() {
    $(document).ready(function() {
                      $("#logout").css("display","inline");
                      });
}
</script>

<?php
    //
    // CONSTANTS
    //
    define('THIS_FILENAME',kv_get_page_url(__FILE__));
    define('README_FILENAME',KV_THEME_PAGES_ROOT.'/read.me');
    

    function initialize() {
        global $print_direction,$print_pad;
        
        $print_direction='horizontal';
        $print_pad=20;
    }

    function parse_cmd($cmd) {
        return kv::multiexplode(array(' ',','),$cmd);
    }
    
    function cmd_form() {
        
        global $users;
        
        $logged_user = '';
        
        if (isset($users)) {
            $logged_user = $users->loggedUser();
            if( $logged_user !==false ) {
                kv::kprint("$logged_user logged on...");
                echo "<script> show_logout(); </script>";
            } else {
                echo "<script> hide_logout(); </script>";
            }
        }
        
        echo "<span>";
        kv::form_cmdline(array(
            'action'=>THIS_FILENAME,
            'id'=>'cmd',
            'size'=>'50'
        ));
        
        echo "</span>";
    }
    function page_header() {
        
        global $users;
        
        if(isset($users)) {
            global $print_direction, $print_pad;

            $users->printUsers($print_direction,$print_pad);
            kv::kprint("\n");
        }
    }
    function get_cookie() {
        global $print_direction, $print_pad;
        
        if(isset($_COOKIE['kv_print_direction'])) $print_direction = $_COOKIE['kv_print_direction'];
        if(isset($_COOKIE['kv_print_pad'])) $print_pad = $_COOKIE['kv_print_pad'];
    }

    global $users,$print_direction,$print_pad;

    initialize();

    get_cookie();

    if(isset($_POST['cmd'])) $cmd = parse_cmd($_POST['cmd']);
        else $cmd = array('list');

    switch($cmd[0]) {
        case 'direction' :
        {
            setcookie('kv_print_direction',$cmd[1]);
            $print_direction = $cmd[1];
            page_header();
            cmd_form();
            break;
        }
        case 'pad' :
        {
            setcookie('kv_print_pad',$cmd[1]);
            $print_pad = $cmd[1];
            page_header();
            cmd_form();
            break;
        }
        case 'deleterow' :
        {
            if(isset($cmd[1])) {
                if ( $users->deleteByPrimekey($cmd[1]) === true ) $output = "Delete {$cmd[1]} Row Succeed";
                else $output = "deletion failed!";
            }
            page_header();
            kv_print($output);
            cmd_form();
            break;
        }
        case 'add_kevin' :
        {
            if($users->addUserStrict( array(
                'id' => 'triad7th',
                'name' => 'kevin lee',
                'email' => 'wonseok.lee80@gmail.com',
                'pw' => '1010triad'
            ))) kv::kprint("add succeed!");

            $users->addUserStrict( array(
                                   'id' => 'carolineyoon.yoon',
                                   'name' => 'caroline soyoung yoon',
                                   'email' => 'carolineyoon.yoon@gmail.com',
                                   'pw' => 'cocacola99',
                                   'address_1' => '15007 burbank blvd #211',
                                   'address_2' => 'van nuys, CA',
                                   'zipcode' => '91411',
                                   'country' => 'United States'
                                   ));

            $users->addUserStrict( array(
                                         'id' => 'babokim',
                                         'name' => 'babo kim',
                                         'email' => 'babokim@gmail.com',
                                         'pw' => 'hulahula',
                                         'address_1' => '0453 babonara blvd',
                                         'address_2' => 'baboland, BA',
                                         'zipcode' => '04533',
                                         'country' => 'Babo Republic'
                                         ));
            page_header();
            cmd_form();
            break;
        }
        case 'list' :
        {
            page_header();
            cmd_form();
            break;
        }
        case 'login' :
        {
            if( $users->loggedUser() === false) {
                page_header();
                kv::form_login(array(
                    'action'=>THIS_FILENAME,
                    'id'=>'id',
                    'pw'=>'pw',
                    'hidden'=>'cmd',
                    'hidden_value'=>'login_validate'
                ));
            } else {
                kv_print("You're already logged");
                cmd_form();
            }
            break;
        }
        case 'login_validate' :
        {
            if( $users->login($_POST['id'],$_POST['pw']) === true) $s="login succeed!";
                else $s="login failed!";

            page_header();

            kv::kprint($s);
            cmd_form();
            break;
        }
        case 'logout' :
        {
            if( $users->logout() === true ) $s="logout succeed!";
                else $s="logout failed!";
            page_header();

            kv::kprint($s);
            cmd_form();
            break;
        }
        case 'adduser' :
        {
            page_header();
            kv::form_adduser(array(
                'action'=>THIS_FILENAME,
                'hidden'=>'cmd',
                'hidden_value'=>'adduser_execute'
            ));
            break;
        }
        case 'adduser_execute' :
        {
            $output ='';
            if( $users->isUserExists(array('id'=>$_POST['id'])) === false ) {
                if( $users->addUser($_POST) === true ) {
                    $output.="add user succeed";
                } else {
                    $output.="add user failed!";
                }
            } else $output.="user already exists!";

            page_header();
            kv::kprint($output);
            cmd_form();
            break;
        }
        case 'help' :
        {
            $output = file_get_contents(README_FILENAME);
            kv_print($output);
            cmd_form();
            break;

        }

        default :
        {
            page_header();
            cmd_form();
            break;
        }

    }                
    
    kv::kprint("\n\n\n");
    $users->flushErrMsg();
    unset($users);

?>
