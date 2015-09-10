//////////////////////////////////////////////////////
//
// JS OBJECT : KV_CONSOLE
//
// Kevin Lee, Hollywood Music Productions LLC
//
// KV_ROOT/kv_themes/kv_admin/pages/console/kv_console.js
//
//////////////////////////////////////////////////////

function kv_commands() {
    this.arr = [];
    this.index = 0;
    
    this.pushCmd = function ($val) {
        this.arr.push($val);
        this.index = this.arr.length;
    }
    
    this.arrowUp = function () {
        if(this.index>0) this.index--;
        return this.arr[this.index];
    }
    
    this.arrowDown = function () {
        if(this.index< this.arr.length) this.index++;
        if(this.index< this.arr.length) return this.arr[this.index];
            else return '';
    }
    
    this.cmdList = function () {
        var str = '';
        for( var i=0; i< this.arr.length; i++) {
            str += this.arr[i] + '\n';
        }
        str += this.index;
        return str;
    }
}

function kv_console($args) {
    // commands list
    this.commands = new kv_commands();

    // set ids
    this.console_id = $args.console_id;
    this.input_id = $args.input_id;
    
    // set objects
    this.console = $("#"+this.console_id);
    this.input = $("#"+this.input_id);
    
    // methods
    this.append = function($str) {
        var $console = this.console;
        
        if( $console.html().length > 0) $console.append("\n");
        $console.append($str);
        $console.animate({ scrollTop: $console[0].scrollHeight}, 100,function() {
            
        });
        if($console.html().length >= 10240) $console.html($console.html().substr(-10240));
        console.log($console.html().length);
    }
    
    this.print = function($str) {
        var $console = this.console;
        
        $console.append($str);
        $console.animate({ scrollTop: $console[0].scrollHeight}, 100,function() {
            
        });
        if($console.html().length >= 10240) $console.html($console.html().substr(-10240));
        console.log($console.html().length);
    }
    
    this.printCommand = function($val) {
        this.append('>'+$val);
        this.commands.pushCmd($val);
    }
    
    
    this.clean = function() {
        this.console.html('');
    }
}

function kv_input($args) {
    // set kv_console;
    this.kv_console = $args.kv_console;

    // mode flag
    this.inputMode = false;
    
    // variables
    this.schema = false;
    this.keys = [];
    this.selector = 0;
    this.command = "";
    this.db = "";
    this.table ="";
    
    // methods
    this.checkInputMode = function() {
    // check input mode (true or false)
        return this.inputMode;
    }
    
    this.isInputMode = function() {
    // check input mode (true or false)
        return this.inputMode;
    }
    
    this.fieldType = function ($str) {
        if( $str.indexOf("INT") > 0 ) return "int";    
        if( $str.indexOf("CHAR") > 0 ) return "string";      
    }
    
    this.storeValue = function($key, $input_val) {
    // store value according to its schema
       
        switch (this.fieldType(this.schema[$key])) {
            case 'int'      :
            case 'integer'  :
                //if($input_val == '') $input_val = 1;
                this.schema[$key] = parseInt($input_val);
                break;

            case 'str'      :
            case 'string'   :
                //if($input_val == '') $input_val = "NULL";
                this.schema[$key] = $input_val;
                break;
            default         :
                this.schema[$key] = $input_val;
                break;
        }
    }
    
    this.storeInput = function($input_val) {
        this.kv_console.print($input_val);
        this.storeValue(this.getCurrentKey(),$input_val);
        this.selector++;
        if(this.keys[this.selector] == "selected_kv_order") {
            this.selector++;
        }
        this.printQuery();
    }
    
    this.printQuery = function() {
        if(this.keys.length > 0) {
            if(this.selector < this.keys.length ) this.kv_console.append(this.keys[this.selector]+"("+this.schema[this.keys[this.selector]]+")? ");
        }
    }
    
    this.inputFinished = function() {
        if(this.selector >= this.keys.length) return true;
            else false;
    }
    
    this.trimKeys = function() {
    // delete keys which have a blank string
        for( var $i=0; $i<this.keys.length ; $i++ ) {
            if(this.schema[this.keys[$i]] == '') delete this.schema[this.keys[$i]];
        }
    }   
    
    this.initialize = function() {
        this.schema = false;
        this.selector = 0;
        this.keys = [];
        this.command = "";
        this.db = "";
        this.table = "";
        this.inputMode = false;
    }
    
    this.stringify = function() {
        this.trimKeys(); // trim keys
        return JSON.stringify(this.schema);
    }
    
    this.startInput = function( $schema, $command, $db, $table ) {
        var $i=0;
        for( var $key in $schema ) {
            this.keys[$i] = $key;
            $i++;
        } 
        this.schema = $schema;
        this.selector = 0;
        this.command = $command;
        this.db = $db;
        this.table = $table;
        this.printQuery();
        this.inputMode = true;
    }

    this.finishInput = function() {
        this.schema = false;
        this.selector = 0;
        this.keys = [];
        this.command = "";
        this.db = "";
        this.table = "";
        this.inputMode = false;
    }
    
    this.getCurrentKey = function() {
        var $i = 0;
        for ( var $key in this.schema ) {
            if( $i == this.selector) {
                return $key;
            }
            $i++;
        }
        return false;
    }
    
    this.processInputMode = function($input_val, $kv_ajax) {
    // process input mode ( procedure )
        this.storeInput($input_val);   // store current input
        $kv_console = this.kv_console;

        // if all input is finished
        if(this.inputFinished()) {
            // execute ajax call fro each command
            switch (this.command) {
                case "add_entry" :
                    $kv_ajax.post( { cmd: "add_entry_execute", db_name: this.db, table_name: this.table, entry: this.stringify()}, function(data,status) {
                        kv_ajax_response($kv_console,data, status, this.command);
                    });
                    break;
                case "update_entry" :
                     $kv_ajax.post( { cmd: "update_entry_execute", db_name: this.db, table_name: this.table, entry: this.stringify()}, function(data,status) {
                        kv_ajax_response($kv_console,data, status, this.command);
                    });
                    break;                                                 
                default: break;
            }

            // finish input mode
            this.finishInput();
            this.kv_console.append("[input finished]");
        }        
    }
}

function kv_function() {
    
    
    this.isNumber = function ($str) {
    //
    // does this string look like number?
    //
        
        if( parseInt($str).toString() !== 'NaN' ) return true;
            else return false;    
    }
    
    this.var_dump = function($obj, $flag) {
    //
    // if flag is ture, only returns for each key with is non-numeric
    //
        var $output='';
        
        for( var key in $obj ) {
            if( ($flag == true) && ( this.isNumber(key) == true )) continue;
            $output += key+ " : " + $obj[key] + "\n";
        }
        
        return $output;
    }
    
}