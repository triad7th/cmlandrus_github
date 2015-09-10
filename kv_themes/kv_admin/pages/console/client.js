//////////////////////////////////////////////////////
//
// THEME : KV_ADMIN_CONSOLE
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/kv_admin/pages/console/console.js
//
//////////////////////////////////////////////////////

var _PATH = "kv_themes/kv_admin/pages/console";

var $kv_console = new kv_console({ console_id: "console", input_id: "console-input" });
var $kv_function = new kv_function();
var $kv_input = new kv_input({ kv_console: $kv_console});
var $kv_ajax = new kv_ajax({ server: "server.php" , path: _PATH });

var $kv_table = 'users';
var $kv_db = 'kv_user';
var $_direction = 'horizontal';
var $_pad = 10;

function kv_ajax_response( $console, data, status, $cmd ) {
    if( typeof data.value_str != 'undefined' ) {
        switch (data.value_str) {
            case false :
            case 0 :
            case '' :
            case 'err' :
            case 'executed' :
            case 'lookup value_arr' : break;
            default : 
                if($cmd === 'no_line_change') $console.print(data.value_str); 
                    else $console.append(data.value_str);
                break;
        }
    }
    
    if( typeof data.errmsg != 'undefined' ) {
        switch (data.errmsg) {
            case false :
            case 0 :
            case '' : break;
            default : $console.append(data.errmsg); break;
        }
    }

    console.log(status);
}

function kv_windowSize() {
    var myWidth = 0, myHeight = 0;
    if( typeof( window.innerWidth ) == 'number' ) {
        //Non-IE
        myWidth = window.innerWidth;
        myHeight = window.innerHeight;
        } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
        //IE 6+ in 'standards compliant mode'
        myWidth = document.documentElement.clientWidth;
        myHeight = document.documentElement.clientHeight;
        } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
        //IE 4 compatible
        myWidth = document.body.clientWidth;
        myHeight = document.body.clientHeight;
    }
    return { width: myWidth, height: myHeight };
}

function kv_windowHeight() {
    return kv_windowSize().height;
}

function console_maxheight() {
    $console = $("#console");
    $max_height = kv_windowHeight() - $console.offset().top - $("#console-input").height()-20;
    $console.css( "max-height", $max_height.toString() );
    if($console.height()+20 > $max_height) {
        $console.css("height",$max_height.toString());
    }
}

function initialize() {
    console_maxheight();
    $kv_console.append("system initialize...");
    $kv_ajax.post({ cmd: "settings", db_name: 'settings', table_name: 'primary_settings', setting_id: 'system'}, function(data,status) {
        kv_ajax_response($kv_console,data,status,"no_line_change");

        $settings = JSON.parse(data.value_arr);

        $kv_console.append( $kv_function.var_dump($settings,true) );
        $_pad = $settings.console_pad;
        $_direction = $settings.console_direction;
        $kv_db = $settings.console_db;
        $kv_table = $settings.console_table;

    });
}

function update_settings() {
    $entry = {
        id: 'system',
        'console_pad': $_pad,
        'console_direction': $_direction,
        'console_db': $kv_db,
        'console_table': $kv_table
    };
    
    $kv_ajax.post({ cmd: "update_settings", db_name: 'settings', table_name: 'primary_settings', entry: JSON.stringify($entry) }, function(data,status) {
        kv_ajax_response($kv_console,data,status);
        //$kv_console.append( $kv_function.var_dump($settings,true) );
    });  
}
    

$(document).ready( function() {
    initialize();
    
    $(window).resize(function($event) {
        console_maxheight();
    });
    
    $("#console-input").keydown(function($event) {
        //
        // keyboard up & down
        //
        switch ($event.which) {
            case 38 :
                $(this).val($kv_console.commands.arrowUp());
                break;
            case 40 :
                $(this).val($kv_console.commands.arrowDown());
                break;
        }
    });
    
    $("#console-input").keypress(function($event) {
        //
        // press enter
        //
        if($event.which == 13) {
            var $input_val = $(this).val();
            var $args = $input_val.trimRight().split(" ");
            var $cmd = $args;
            
            // update settings for each 10 enters
            if( (($kv_console.commands.arr.length) % 50 == 0) && ($kv_console.commands.arr.length > 1) ) update_settings();
            
            // input mode handler
            if( $kv_input.isInputMode()) {
            //
            // input mode
            //
                $kv_input.processInputMode($input_val, $kv_ajax);              
                $cmd = "input_mode";
            } else {
            //
            // command mode
            //             
                // seperate command and args
                $cmd = $cmd[0];          
                $args.shift();

                console.log($cmd+" "+$args.toString());
                
                for( var $i=0; $i<$args.length; $i++ ) {
                    if( $args[$i].substr(0,1) == '$' ) {
                        $cmd = $cmd+'_'+$args[$i].substr(1);
                        $args.splice($i,1);
                    }
                }

                // write user typed string
                $kv_console.printCommand($input_val);
                //$kv_console.append('>'+$input_val);
                //$kv_console.append('$'+$cmd+" "+$args.toString());
            }
            
            // command handler
            switch($cmd) {
                
                //
                //
                // 'list' series
                //
                // 
                case "ls_tables" :
                case "ls_tbls" :
                case "list_table" :
                case "list_tables" :
                case "list_tbls" :
                case "tables" :
                case "tbls" :
                    if( typeof $args[0] == 'undefined' ) $args[0] = $kv_db;
                    console.log($args[0]);
                    
                    $kv_ajax.post({ cmd: "tables", db_name: $args[0], direction: $_direction, pad: $_pad}, function(data,status) {
                        kv_ajax_response($kv_console,data,status,$cmd);
                    });
                    break;
                
                case "ls_entry" :
                case "ls_entries" :
                case "ls" :
                case "list_entry" :
                case "list_entries" :
                case "list" :
                    switch ( $args.length ) {
                        case 0 : $args = [ $kv_db, $kv_table ]; break;
                        case 1 : $args = [ $kv_db, $args[0] ]; break;
                        case 2 : break;
                    }
                    console.log($args[0]+" "+$args[1]);
                    
                    $kv_ajax.post({ cmd: "list", db_name: $args[0], table_name: $args[1], direction: $_direction, pad: $_pad}, function(data,status) {
                        kv_ajax_response($kv_console,data,status,$cmd);
                    });
                    break;
                
                case "ls_schema" :
                case "ls_scheme" :
                case "list_schema" :
                case "list_scheme" :
                    switch ( $args.length ) {
                        case 0 : $args = [ $kv_db, $kv_table ]; break;
                        case 1 : $args = [ $kv_db, $args[0] ]; break;
                        case 2 : break;
                        default :
                            $kv_console.append('list_schema {db=current db} {table[required]}');
                            break;
                    }

                    console.log($args[0]+" "+$args[1]);
                    
                    $kv_ajax.post({ cmd: "list_schema", db_name: $args[0], table_name: $args[1]}, function(data,status) {
                        var $schemalist = JSON.parse(data.value_arr);
                        if( typeof $schemalist == 'object') {
                            for ( var $key in $schemalist ) {
                                if($schemalist[$key].substr(0,1) !=='.') $kv_console.append($schemalist[$key]);
                            }
                        } else $kv_console.append(data.value_str);
                        
                        $kv_console.append(data.errmsg);
                        console.log(status);
                    });
                    break;
                
                case "dbs" :
                case "list_db" :
                case "list_dbs" :
                case "ls_db" :
                case "ls_dbs":                    
                    if ( $args.length == 0 ) $args[0] = $kv_db;
                    
                    $kv_ajax.post({ cmd: "list_db", db_name: $args[0] }, function(data,status) {
                        kv_ajax_response($kv_console,data,status,$cmd);
                        
                        var $dblist = JSON.parse(data.value_arr);
                        if( typeof $dblist == 'object') {
                            for ( var $key in $dblist ) {
                                if($dblist[$key].substr(0,1) !=='.') $kv_console.append($dblist[$key]);
                            }
                        }
                    });
                    break;
                
                case "show_schema" :
                case "show_scheme" :
                case "scheme" :
                case "schema" :
                    switch ( $args.length ) {
                        case 0 : $args = [ $kv_db, $kv_table ]; break;
                        case 1 : $args = [ $kv_db, $args[0] ]; break;
                        case 2 : break;
                        default :
                            $kv_console.append('schema {db=current db} {table=current table}');
                            break;
                    }
                    console.log($args[0]+" "+$args[1]);
                    
                    $kv_ajax.post({ cmd: "schema", db_name: $args[0], table_name: $args[1], direction: $_direction, pad: $_pad}, function(data,status) {
                        kv_ajax_response( $kv_console, data, status );
                    });
                    break;
                    
                //
                //
                // 'add & create' series
                //
                //
                case "add" :
                case "add_entry" :
                    switch ( $args.length ) {
                        case 0 : $args = [ $kv_db, $kv_table ]; break;
                        case 1 : $args = [ $kv_db, $args[0] ]; break;
                        case 2 : break;
                        default :
                            $kv_console.append('add_entry {db=current db} {table=current table}');
                            break;
                    }
                    if( $args.length > 2 ) break;
                    console.log($args[0]+" "+$args[1]);
                    
                    $kv_ajax.post({ cmd: "add_entry", db_name: $args[0], table_name: $args[1], direction: $_direction, pad: $_pad}, function(data,status) {
                        kv_ajax_response( $kv_console, data, status );
                        
                        if( typeof data.value_arr !== 'undefined' ) {
                            var $schema = JSON.parse(data.value_arr);
                            $kv_console.append('input values on table : '+$args[1]);
                            $kv_input.startInput($schema, "add_entry", $args[0], $args[1]);
                        }
                    });
                    break;
                    
                case "create_tbl" :
                case "add_tbl" :
                case "create_table" :
                case "add_table" :
                    switch ( $args.length ) {
                        case 1 : $args = [ $kv_db, $args[0], $args[0] ]; break;
                        case 2 : $args = [ $kv_db, $args[0], $args[1] ]; break;
                        case 3 : break;
                        case 0 :
                        default :
                            $kv_console.append('add_table {db=current db} {scheme[required]} {tbl_name = same as "table"}');
                            break;
                    }
                    if( $args.length == 0 ) break;
                    
                    console.log($args[0]+" "+$args[1]);
                    $kv_ajax.post({ cmd: "add_table", db_name: $args[0], scheme: $args[1], table_name: $args[2], direction: $_direction, pad: $_pad}, function(data,status) {
                        kv_ajax_response( $kv_console, data, status );
                    });
                    break;
                    
                case "create_db" :
                case "add_db" :
                    switch( $args.length ) {
                        case 1 : $args = [ $kv_db, $args[0] ]; break;
                        case 2 : break;
                        case 0 :
                        default :
                            $kv_console.append('create_db {db[required]}');
                            break;
                    }
                    if( $args.length == 0 ) break;
                    
                    console.log($args[0]+" "+$args[1]);
                    $kv_ajax.post({ cmd: "create_db", db_name: $args[0], db_to_create: $args[1]}, function(data,status) {
                        kv_ajax_response( $kv_console, data, status );
                    });
                    break;
                //
                //
                // 'open' series
                //
                //
                case "open" :
                case "open_db" :
                    switch( $args.length ) {
                        case 1 : $args = [ $kv_db, $args[0] ]; break;
                        case 2 : break;
                        case 0 :
                        default :
                            $kv_console.append('open_db {db[required]}');
                            break;
                    }
                    if( $args.length == 0 ) break;
                    
                    console.log($args[0]+" "+$args[1]);
                    $kv_ajax.post({ cmd: "open_db", db_name: $args[0], db_to_open: $args[1]}, function(data,status) {
                        kv_ajax_response( $kv_console, data, status );
                        if( data.value_str !== "err" ) {
                            $kv_db = $args[1];
                        }
                    });
                    break;
                
                //
                //
                // 'update' series
                //
                //                
                case "update" :
                case "update_entry" :
                case "update_entries" :
                    switch ( $args.length ) {
                        case 1 : $args = [ $kv_db, $kv_table, $args[0] ]; break;
                        case 2 : $args = [ $kv_db, $args[0], $args[1]]; break;
                        case 3 : break;
                        default :
                            $kv_console.append('add_entry {db=current db} {table=current table} {kv_order[required]}');
                            break;
                    }
                    if( $args.length > 3 || $args.length == 0 ) break;
                    
                    console.log($args[0]+" "+$args[1]);
                    
                    $kv_ajax.post({ cmd: "update_entry", selected_kv_order: $args[2], db_name: $args[0], table_name: $args[1], direction: $_direction, pad: $_pad}, function(data,status) {
                        kv_ajax_response($kv_console,data,status,$cmd);
                        if( typeof data.value_arr !== 'undefined' ) {
                            var $schema = JSON.parse(data.value_arr);
                            $kv_console.append('update values on table : '+$args[1]+", kv_order :"+$schema['selected_kv_order']);
                            $kv_input.startInput($schema, "update_entry", $args[0], $args[1]);
                        }

                    });
                    break;
                case "update_settings" :
                    update_settings();
                    break;
                    
                //
                //
                // 'delete & erase' series
                //
                //
                case "del" :
                case "delete" :
                case "deleterow" :
                case "delete_row" :
                    if( typeof $args[0] == 'undefined' ) {
                        $kv_console.append('del {row[required]}');
                        break;
                    }
                    
                    $kv_ajax.post({ cmd: "del", prime_key: $args[0], db_name: $kv_db, table_name: $kv_table}, function(data,status) {
                        kv_ajax_response($kv_console,data,status,$cmd);
                    });
                    break;
                case "delete_table" :
                case "del_table" :
                case "delete_tbl" :
                case "del_tbl" :
                case "remove_table" :
                case "remove_tbl" :
                    switch ( $args.length ) {
                        case 1 : $args = [ $kv_db, $args[0] ]; break;
                        case 2 : break;
                        case 0 :
                        default :
                            $kv_console.append('del_table {db=current db} {table[required]}');
                            break;
                    }
                    if ($args.length == 0) break;
                    
                    $kv_ajax.post({ cmd: "del_table", db_name: $args[0], table_name: $args[1]}, function(data,status) {
                        kv_ajax_response($kv_console,data,status,$cmd);
                    });
                    break;
                
                case "delete_db" :
                case "del_db" :
                case "remove_db" :
                    switch( $args.length ) {
                        case 1 : $args = [ $kv_db, $args[0] ]; break;
                        case 0 :
                        default :
                            $kv_console.append('del_db {db[required]}');
                            break;
                    }
                    if ($args.length == 0) break;
                    
                    $kv_ajax.post({ cmd: "del_db", db_name: $args[0], db_to_del: $args[1]}, function(data,status) {
                        kv_ajax_response($kv_console,data,status,$cmd);
                    });
                    break;
                case "erase_table" :
                    switch ( $args.length ) {
                        case 1 : $args = [ $kv_db, $args[0] ]; break;
                        case 2 : break;
                        case 0 :
                        default :
                            $kv_console.append('erase_table {db=current db} {table[required]}');
                            break;
                    }
                    if ($args.length == 0) break;
                    
                    $kv_ajax.post({ cmd: "erase_table", db_name: $args[0], table_name: $args[1]}, function(data,status) {
                        kv_ajax_response($kv_console,data,status,$cmd);
                    });
                    break;
                    
                //
                //
                // MISC commands
                //
                //
                case "cmdlist" :
                    $kv_console.append($kv_console.commands.cmdList());
                    break;
                    
                case "clean" :
                    $kv_console.clean();
                    break;
                    
                case "help" :
                    $kv_ajax.post({ cmd: "help" }, function(data,status) {
                        kv_ajax_response($kv_console,data,status,$cmd);
                    });
                    break;
                    
                case "direction" :
                    if( typeof $args[0] !== 'undefined' ) {
                        switch ($args[0]) {
                                case "horizontal" :
                                case "vertical" :
                                case "Horizontal" :
                                case "Vertical" :
                                    $_direction = $args[0];
                                    $kv_console.append('direction is '+$_direction+' now.');
                                    break;
                                default :
                                    $kv_console.append('direction [horizontal/vertical]');
                                    break;
                        }
                    } else {
                        $kv_console.append('direction is '+$_direction );
                        break;
                    }
                    break;
                    
                case "pad" :
                    if( typeof $args[0] !== 'undefined' ) $_pad = $args[0];
                    $kv_console.append('pad is '+$_pad);
                    break;
                
                case "tbl" :
                case "table" :
                    if( typeof $args[0] !== 'undefined' ) $kv_table = $args[0];
                    $kv_console.append('table is '+$kv_table+' now.');
                    break;
                    
                case "db" :
                    if( typeof $args[0] !== 'undefined' ) $kv_db = $args[0];
                    $kv_console.append('db is '+$kv_db+' now.');
                    break;
                    
                case "test" :
                    $kv_ajax.post({ cmd: "test" }, function(data,status) {
                        kv_ajax_response($kv_console,data,status,$cmd);
                    });
                    break;
                
                case "settings" :
                    
                    $kv_console.append( $kv_function.var_dump( {
                        db : $kv_db,
                        table : $kv_table,
                        pad : $_pad,
                        direction : $_direction
                    },true) );
                    /*
                    switch ( $args.length ) {
                        case 0 : $args = [ $kv_db, "settings", "first_row" ]; break;
                        case 1 : $args = [ $kv_db, "settings", $args[0] ]; break;
                        case 2 : $args = [ $kv_db, $args[0], $args[1] ]; break;
                        case 3 : break;
                        default :
                            $kv_console.append('settings {db=current db} {table="settings"} {setting_id[required]}');
                            break;
                    }
                    if( $args.length > 3 ) break;
                    
                    $kv_ajax.post({ cmd: "settings", db_name: $args[0], table_name: $args[1], setting_id: $args[2]}, function(data,status) {
                        kv_ajax_response($kv_console,data,status,$cmd);
                        
                        $settings = JSON.parse(data.value_arr);
                        
                        $kv_console.append( $kv_function.var_dump($settings,true) );
                        $_pad = $settings.console_pad;
                        $_direction = $settings.console_direction;
                        $kv_db = $settings.console_db;
                        $kv_table = $settings.console_table;
                        
                    });
                    */
                    break;
                //
                //
                // default and input_mode
                //
                //
                case "input_mode" :
                    // this is input mode and I'm not gonna do anything
                    break;
                    
                default :
                    $kv_console.append('Syntax Error');
                    break;
            }
            
            $("#console-input").val("");
        }
        
    });        
});