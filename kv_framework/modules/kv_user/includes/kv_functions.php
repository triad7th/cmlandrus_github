<?php
// GLOBAL FUNCTIONS
if( !function_exists('kv_print') ) {
    function kv_print($string) {
        echo '<div style="font-family : Courier; font-size : 10px;">'.kv::add_br($string).'</div>';
    }
}

if ( !function_exists('kv_log') ) {
    function kv_log($string) {
        $js = "<script> console.log('$string'); </script>";
        echo $js;
    }
}

if ( !function_exists('kvar_export') ) {
    function kvar_export($var) {
        $exported = var_export($var,true);
        $edited = str_replace("'","",$exported);
        return "[kvar_export()] not yet developed";
    }
}
    
//
// CLASS kv (static function set)
// 
// collection of functions for kevin's php coding

if( !class_exists('kv') ) {
class kv {
    
    //
    // filename(string)
    //
    // return filename (only)
    public static function filename($str) {
        if( ($pos=strrpos($str,'/')) !== false) {
            return substr($str,$pos+1);
        } else return $str;
    }
    
    //
    // filename_wo_ext(string)
    //
    // return filename only without extension
    public static function filename_wo_ext($str) {
        $fn = kv::filename($str);
        $arr = explode(".",$fn);
        if(isset($arr[0])) return $arr[0];
            else return false;
    }
    
    
    
    //
    // dir_to_url()
    //
    // returns current url
    //
    public static function dir_to_url($dir) {
        $path = substr($dir,strlen($_SERVER['DOCUMENT_ROOT']));

        return $path;
    }
    
    //
    // dir_to_full_url()
    //
    // return current full url
    //
    public static function dir_to_full_url($dir) {
        return $_SERVER['SERVER_NAME'].$path = kv::dir_to_url($dir);
    }
    
    //
    // kv_root()
    //
    // returns kv_root
    //
    public static function kv_root() {
        return kv::dir_to_url(KV_ROOT);
    }
    
    //
    // multiexplode ($dels, $string)
    //
    // multi explode ( from php.net example )
    //
    public static function multiexplode ($delimiters,$string) {
        
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
    
    //
    // get_line
    //
    // search a sub string in given string and return the whole line of it
    //
    // (return)
    // false : failed to find
    
    public static function get_line($key,$str) {
        $lines = kv::multiexplode(array("\r","\n"),$str);
        foreach($lines as $line) {
            if(strpos($line,$key) !==false) return $line;
        }
        return false;
    }
    
    //
    // get_str($key1,$key2,$str)
    //
    // get a string between $key1 and $key2
    //
    // return : gotten string
    // return false : error
    
    public static function get_str($key1, $key2,$str) {
        if( ($start_pos = strpos($str,$key1)) !== false ) {
        // get a start position of target str
            //kv_print("start_pos : ".$start_pos);
            if( ($end_pos = strpos($str,$key2,$start_pos)) !== false) {
            // get a end position of target str
                //kv_print("end_pos : ".$end_pos);
                // get a target str
                $got_str = substr($str,$start_pos+strlen($key1),$end_pos-$start_pos-strlen($key1));
                //kv_print("got string : ".htmlentities(trim($got_str)));
                if($got_str!==false) {
                // if we find one
                    return trim($got_str);
                }
            }
        }
        // something error happened
        return false;
        
    }
    
    //
    // form_load
    //
    // load form from file with arguments and repetition
    //
    public static function form_load($fn,array $args) {
    
        $form=file_get_contents($fn);
        
        if($form !== false) {
            $rep_arr= array();
            $max_count = 0;
            
            //
            // repition string
            //
            
            // get repetition string
            $rep_str = kv::get_str('<!--kv_repetition_start-->','<!--kv_repetition_end-->',$form);
            if($rep_str !== false) {
            // if there is repition string
                foreach( array_keys($args) as $key) {
                    if(strpos($rep_str,$key) !== false) {
                    // if there are keys in $args that rep_str has on its string content
                        $rep_arr["$key"] = $args["$key"];
                        // set max count - biggest count in the $rep_arr[] array
                        if(count($args["$key"]) > $max_count) $max_count=count($args["$key"]);
                        // unset they element of $args[] which is in $rep_arr as well
                        unset($args["$key"]);
                    } else return false;
                }
                
                $rep_strs='';
                for($i=0; $i<$max_count ; $i++) {
                    $temp=$rep_str;
                    foreach($rep_arr as $key=>$subarr) {
                        // if subarr has no items on $i, fillout with ''
                        if(isset($subarr[$i]) === false) $subarr[$i]='';
                        $temp=str_replace("\"#$key\"","\"{$subarr[$i]}\"",$temp);
                        $temp=str_replace("'#$key'","{$subarr[$i]}",$temp);
                    }
                    $rep_strs.=$temp;
                }
                $form=str_replace($rep_str,$rep_strs,$form);
            }

            //
            // single variables
            //
            
            foreach($args as $key => $value) {
                    $form = str_replace("\"\$$key\"","\"$value\"",$form);
                    $form = str_replace("~$key","$value",$form);
                    $form = str_replace("\"@$key\"","\"$value\"",$form);
            }
            return $form;
        }
        return false;
    }
    
    //
    // form_textbox
    //
    // simple textbox input form
    //
    public static function form_textbox(array $args) {
        
        $args=array_merge(array (
            'action' => '',
            'id' => 'kv_textbox',
            'placeholder' => 'type command here',
            'submit' => 'submit',
            'size' => '80',
            'hidden' => 'hidden',
            'hidden_value' => 'hidden_value'
        ),$args);
        
        $form=kv::form_load(KVFUNDAMENTAL_ROOT.'/includes/forms/form_textbox.html',$args);
        
        if($form !== false) echo $form;
            else return false;
    }
    
    //
    // form_login
    //
    // simple login form
    //
    public static function form_login(array $args) {
        
        $args=array_merge(array (
            'action' => '',
            'id' => 'kv_id',
            'pw' => 'kv_pw',
            'id_placeholder' => 'id',
            'pw_placeholder' => 'pw',
            'submit' => 'submit',
            'id_size' => '40',
            'pw_size' => '40',
            'hidden' => 'hidden',
            'hidden_value' => 'hidden_value'
        ),$args);
        
        $form=kv::form_load(KVFUNDAMENTAL_ROOT.'/includes/forms/form_login.html',$args);
        //kv::kprint(htmlentities($form));
        if($form !== false) echo $form;
            else return false;
    }
    
    //
    // form_logout
    //
    // simple logout form
    //
    public static function form_logout(array $args) {
        
        $args=array_merge(array (
            'action' => '',
            'form_class' => 'form_logout',
            'btn_class' => '',
            'submit' => 'logout',
            'hidden' => 'cmd',
            'hidden_value' => 'logout'
        ),$args);
        
        $form=kv::form_load(KVFUNDAMENTAL_ROOT.'/includes/forms/form_logout.html',$args);
        //kv::kprint(htmlentities($form));
        if($form !== false) echo $form;
            else return false;
    }
    
    //
    // form_cmdline
    //
    // simple commandline form
    //
    public static function form_cmdline(array $args) {
        
        $args=array_merge(array (
            'id' => 'kv_cmdline',
            'action' => '',
            'submit' => 'submit',
            'placeholder' => 'type command here',
            'size' => '80',
            'hidden' => 'hidden',
            'hidden_value' => 'hidden_value',
            'logout_submit' => 'logout',
            'logout_hidden' => 'cmd',
            'logout_hidden_value' => 'logout'
            ),$args);
        
        $form=kv::form_load(KVFUNDAMENTAL_ROOT.'/includes/forms/form_cmdline.html',$args);
        if($form !== false) echo $form;
        else return false;
    }
    
    //
    // form_adduser
    //
    // simple adduser form
    //
    public static function form_adduser(array $args) {
        
        $args=array_merge(array (
                                 'action' => '',
                                 'margin' => '5px',
                                 'width' => '500px',
                                 'id_placeholder' => 'id',
                                 'id_size'=> '60%',
                                 'pw_placeholder' => 'password',
                                 'pw_size'=> '30%',
                                 'name_placeholder' => 'name',
                                 'name_size' => '30%',
                                 'email_placeholder' => 'email',
                                 'email_size' => '60%',
                                 'address1_placeholder' => 'address1',
                                 'address1_size' => '91.1%',
                                 'address2_placeholder' => 'address2',
                                 'address2_size' => '91.1%',
                                 'zipcode_placeholder' => 'zipcode',
                                 'zipcode_size' => '30%',
                                 'country_placeholder' => 'country',
                                 'country_size' => '60%',
                                 'submit' => 'submit',
                                 'hidden' => 'cmd',
                                 'hidden_value' => 'adduser_execute'
                                 ),$args);
        
        $form=kv::form_load(KVFUNDAMENTAL_ROOT.'/includes/forms/form_adduser.html',$args);
        if($form !== false) echo $form;
        else return false;
    }
    
    //
    // duplicate_key(destination array, key array)
    //
    // duplicate value of first occurance in $arr which has key in $keys, adding new keys to $arr
    //
    public static function duplicate_key(array &$arr, array $keys) {
    
        foreach($keys as $key) {
            if(isset($arr[$key])) {
                $copy=$arr[$key];
                break;
            }
        }

        if(isset($copy)) {
            foreach($keys as $key) {
                $arr[$key] = $copy;
            }
        } else return false;
    }
    
    //
    // kprint(string)
    //
    // printout html coded string using fixed width font
    //
    public static function kprint($string) {
        echo '<div style="font-family : Courier; font-size : 10px;">'.kv::add_br($string).'</div>';
    }
    
    //
    // add_br(string)
    //
    // encode string to html style
    // 
    public static function add_br($string) {
        $string = str_replace("\n","<br>",$string);
        $string = str_replace(" ","&nbsp",$string);

        return $string;
    }
    
    //
    // kvar_dump(string)
    //
    // kv version of var_dump
    // 
    public static function kvar_dump($string) {
        kv::kprint(kv::add_br(var_export($string,true)));
    }

    //
    // console_mode
    //
    // console mode for kv::add_br
    // 
    public static function console_mode() {
        ob_start('kv::add_br');
    }

    //
    // console_mode_flush
    //
    // flush console mode
    // 
    public static function console_mode_flush() {
        ob_end_flush();
    }

    //
    // image(filename)
    //
    // make a image tag with a given filename
    // 
    public static function image($file) {
        $html = '';

        $html = "<br/><img src='$file' width='auto' height='auto' >";
        return $html;
    }
}
}
?>