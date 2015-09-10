<?php
//////////////////////////////////////////////////////
//
// THEME : KV_ADMIN_CONSOLE
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/kv_admin/pages/console/server.php
//
//////////////////////////////////////////////////////

//
// inputs
//
// cmd : commands
//

//
// json returns
//
// errmsg : error message
// value_str : return string
//

if( isset($_POST["cmd"]) ) {
    switch ($_POST["cmd"]) {
        //
        //
        // 'list' series
        //
        //
        case "list"         :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                $output = $db->printEntries($_POST['direction'],$_POST['pad'],$_POST["table_name"]);
                echo json_encode(array ("value_str" => $output, "errmsg" => $db->returnErrMsg()) );
                break;
            } else echo json_encode (array ("value_str"=> "err", "errmsg" => "db open error!"));
            break;
        
        case "list_db" :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                $db_arr = $db->getDbList();
                if($db_arr !== false) {
                    echo json_encode(array ("value_str"=>"executed", "value_arr"=>json_encode($db_arr),"errmsg" => $db->returnErrMsg()) );
                } else echo json_encode( array ("value_str"=>"err", "value_arr"=>"err", "errmsg" => $db->returnErrMsg()) );
                break;
            } else echo json_encode (array ("value_str"=>"err", "errmsg" => "db open error!"));
            break;  

        case "list_schema"    :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                $templates_arr = $db->getSchemaList();
                if($templates_arr !== false) {
                    echo json_encode(array ("value_str"=>$templates_arr[1], "value_arr"=>json_encode($templates_arr),"errmsg" => $db->returnErrMsg()) );
                } else echo json_encode( array ("value_str"=>"err", "value_arr"=>"err", "errmsg" => $db->returnErrMsg()) );
                break;
            } else echo json_encode (array ("value_str"=>"err", "errmsg" => "db open error!"));
            break;
 
        case "tables"       :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                $output = $db->printEntries($_POST['direction'],$_POST['pad'],'tables');
                echo json_encode(array ("value_str" => $output, "errmsg" => $db->returnErrMsg()) );
                break;
            } else echo json_encode (array ("value_str"=> "err", "errmsg" => "db open error!"));
            break;

        case "schema"       : 
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                $output = $db->getSchema($_POST["table_name"]);
                echo json_encode(array ("value_str" => "table : ".$_POST["table_name"]."\n".var_export($output,true), "errmsg" => $db->returnErrMsg()) );
                break;
            } else echo json_encode (array ("value_str"=> "err", "errmsg" => "db open error!"));
            break;

        //
        //
        // 'add & create' series
        //
        //
        case "add_table"    :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                if( $db->createTable($_POST["scheme"], $_POST["table_name"]) ) {
                    echo json_encode(array ("value_str" => "create table succeed", "errmsg" => $db->returnErrMsg()) );
                } else echo json_encode (array ("value_str"=>"err", "errmsg" => $db->returnErrMsg()) );
            } else echo json_encode( array ("value_str"=>"err", "errmsg" => "db open error!"));
            break;
        
        case "add_entry"     :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                $schema = $db->getSchema($_POST["table_name"]);
                if($schema !== false) echo json_encode(array ("value_str" => "lookup value_arr", "value_arr" => json_encode($schema), "errmsg" => $db->returnErrMsg()) );
                    else echo json_encode (array ("value_str" => "err", "errmsg" => $db->returnErrMsg()) );
                break;
            } else echo json_encode (array ("value_str"=> "err", "errmsg" => "db open error!"));
            break;
        
        case "add_entry_execute" :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                $entry = json_decode($_POST["entry"],true);
                if( $db->addEntry($entry, $_POST["table_name"]) === true ) {
                    echo json_encode(array ("value_str" => "add entry succeed", "errmsg" => $db->returnErrMsg()) );
                } else echo json_encode(array ("value_str" => "err", "errmsg" => $db->returnErrMsg()) );
            } else echo json_encode (array ("value_str"=> "err", "errmsg" => "db open error!"));
            break;
        
        case 'create_db' :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                if( $db->createDb($_POST["db_to_create"]) !== false ) {
                    echo json_encode(array ("value_str" => $_POST["db_to_create"]." is created", "errmsg" => $db->returnErrMsg()) );
                } else echo json_encode(array ("value_str" => "err", "errmsg" => $db->returnErrMsg()) );
            } else echo json_encode (array ("value_str"=> "err", "errmsg" => "db open error!"));
            break;          

        //
        //
        // 'open' series
        //
        //
        case 'open_db' :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                if( $db->openDb($_POST["db_to_open"]) === true ) {
                    echo json_encode(array ("value_str" => $_POST["db_to_open"]." is opened", "errmsg" => $db->returnErrMsg()) );
                } else echo json_encode(array ("value_str" => "err", "errmsg" => $db->returnErrMsg()) );
            } else echo json_encode (array ("value_str"=> "err", "errmsg" => "db open error!"));
            break;        

        //
        //
        // 'update' series
        //
        //
        case "update_settings"  :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                $entry = json_decode($_POST["entry"],true);
                //echo json_encode(array ("value_str" => var_export($entry,true)));
                if( ($kv_order = $db->updateEntry($entry, $_POST["table_name"]) ) !== false ) {
                    echo json_encode(array ("value_str" => "settings autosave succeed", "errmsg" => $db->returnErrMsg()) );
                } else echo json_encode(array ("value_str" => "err", "errmsg" => $db->returnErrMsg()) );
            } else echo json_encode (array ("value_str"=> "err", "errmsg" => "db open error!"));
            break; 
        
        case "update_entry"     :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                $schema = $db->getSchema($_POST["table_name"]);
                $schema['selected_kv_order'] = $_POST["selected_kv_order"];
                
                if($schema !== false) echo json_encode(array ("value_str" => "lookup value_arr", "value_arr" => json_encode($schema), "errmsg" => $db->returnErrMsg()) );
                    else echo json_encode(array ("value_str" => "err", "errmsg" => $db->returnErrMsg() ));
            } else echo json_encode (array ("value_str"=> "err", "errmsg" => "db open error!"));
            break;
        
        case "update_entry_execute" :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                $entry = json_decode($_POST["entry"],true);
                if( ($kv_order = $db->updateEntry($entry, $_POST["table_name"]) ) !== false ) {
                    echo json_encode(array ("value_str" => "update entry succeed", "errmsg" => $db->returnErrMsg()) );
                } else echo json_encode(array ("value_str" => "err", "errmsg" => $db->returnErrMsg()) );
            } else echo json_encode (array ("value_str"=> "err", "errmsg" => "db open error!"));
            break; 
        
        //
        //
        // 'delete & erase' series
        //
        //
        case "del"          :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                $db->deleteByPrimeKey($_POST["prime_key"],$_POST["table_name"]);
                echo json_encode(array ("value_str" => "executed","errmsg" => $db->returnErrMsg()) );
                break;
            } else echo json_encode (array ("value_str"=>"err", "errmsg" => "db open error!"));
            break;
        
        case "del_table"    :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                if( $db->deleteTable($_POST["table_name"]) ) {
                    echo json_encode(array ("value_str" => "succeed", "errmsg" => $db->returnErrMsg()) );
                    break;
                } else echo json_encode (array ("value_str"=>"err", "errmsg" => $db->returnErrMsg()) );
            } else echo json_encode (array ("value_str"=>"err", "errmsg" => "db open error!"));
            break;

        case "erase_table"    :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                if( $db->eraseTable($_POST["table_name"]) ) {
                    echo json_encode(array ("value_str" => "succeed", "errmsg" => $db->returnErrMsg()) );
                    break;
                } else echo json_encode (array ("value_str"=>"err", "errmsg" => $db->returnErrMsg()) );
            } else echo json_encode (array ("value_str"=>"err", "errmsg" => "db open error!"));
            break;
        
        case "del_db"     :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                if( $db->deleteDb($_POST["db_to_del"]) !== false ) {
                    echo json_encode(array ("value_str"=>$_POST["db_to_del"]." is deleted","errmsg" => $db->returnErrMsg()) );
                } else echo json_encode( array ("value_str"=>"err", "errmsg" => $db->returnErrMsg()) );
            } else echo json_encode( array ("value_str" =>"err", "errmsg" => "db open error!"));
            break;
        
        //
        //
        // MISC commands
        //
        //                
        case "test"         :   
            echo json_encode(array ("value_str" => "hello ajax!"));
            break;
        
        case "help"         :   
            $contents = file_get_contents(KV_SERVER_ROOT."/read.me");
            echo json_encode(array ("value_str" => $contents));
            break;
        

        case "settings"     :
            $db = new KvDB($_POST["db_name"]);
            if($db !== false) {
                if($_POST["setting_id"] == "first_row") $settings = $db->getEntry( array ( "kv_order" => 1 ), $_POST["table_name"] );
                    else $settings = $db->getEntry( array ( "id" => $_POST["setting_id"] ), $_POST["table_name"] );
                if( $settings !== false ) {
                    echo json_encode(array ("value_str"=>"succeed", "value_arr"=>json_encode($settings),"errmsg" => $db->returnErrMsg()) );
                } else echo json_encode( array ("value_str"=>"err", "value_arr"=>"err", "errmsg" => $db->returnErrMsg()) );
            } else echo json_encode( array ("value_str" =>"err", "errmsg" => "db open error!"));
            break;

        //
        //
        // default
        //
        //
        default             :   
            echo json_encode(array ("value_str" => "default"));
            break;
        
    }
} else {
    echo json_encode(array ( "errmsg" => "no command" ));
}

?>