<?php
require_once ( KVUSERDB_ROOT."/includes/kv_functions.php");
require_once ( KVUSERDB_ROOT."/classes/kv_fundamental.php");

//
// CLASS : KvUserDB
//
// create and management small sqlite db mainly for user management

if( !class_exists('KvDB') ) {
class KvDB extends KvFundamental {
    //
    //
    // CONSTANTS
    //
    //
    
    const DEFAULT_PRINT_PAD = 5;
    const DEFAULT_TABLE_NAME = "users";

    //
    //
    // VARIABLES
    //
    //

    protected $db;
    protected $db_name;
    protected $table_name;
    protected $print_pad;


    //
    //
    // PRIVATE FUNCTIONS
    //
    //
    private function fieldType($str) {
    //
    // fieldType($type)
    //
    // force field type into 'int' or 'string'
    //
    //
        if( strpos($str,"INT") !== false) return "int";
        if( strpos($str,"CHAR") !== false) return "string";
        
        return "string";
    }
    
    private function serializeEntryValues(array $entry,$table= "__default") {
    //
    // serialize user()
    //
    // serialize all user values based on USER_KEYS
    // [return]
    // serialized string
    // false : error
        
        if($table == "__default") $table=$this->table_name;
        
        $output = '';
        
        $keys = $this->getSchema($table);
        //return $this->returnFalse("[KvDb:serializeEntryValues] entry : ".var_export($entry,true));
        if($keys !== false) {
            foreach($keys as $key=>$type) {
                
                if(isset($entry[$key])) {
                    switch ($this->fieldType($type)) {
                        case 'int'      : $output.="{$entry[$key]},";
                            break;

                        case 'string'   : $output.="\"{$entry[$key]}\",";
                            break;
                    }
                } else {
                    switch ($this->fieldType($type)) {
                        case 'int'      : $output.=",";
                            break;
                        case 'string'   : $output.="\"\",";
                            break;
                    }
                }
            }
            $output = substr($output,0,-1);
            return $output;
        } else return false;
        return false;
    }
    
    private function serializeEntryKeyValues(array $entry,$table = "__default") {
    //
    // serializeEntryKeyValues()
    //
    // serialize all user key and values based on schema file(s), no override
    // [return]
    // serialized string
    // false : error
        
        if($table == "__default") $table=$this->table_name;
        $output = '';
        
        $keys = $this->getSchema($table);
        if($keys !== false) {
            foreach($keys as $key=>$type) {
                // skip 'kv_order'
                //if($key=='kv_order') continue;

                if(isset($entry[$key])) {
                    switch ($this->fieldType($type)) {
                        case 'int'      : $output.="\"$key\" = {$entry[$key]},";
                            break;

                        case 'string'   : $output.="\"$key\" = \"{$entry[$key]}\",";
                            break;
                    }
                }
            }
            $output = substr($output,0,-1);

            return $output;
        } else return false;
    }
    
    //
    //
    // PUBLIC FUNCTIONS
    //
    //
    
    //
    // constructor / distructor
    //
    function __construct($fn) {
        //
        // create DB and table 'users'
        //
        // initialize
        $this->print_pad = KvDB::DEFAULT_PRINT_PAD;
        $this->table_name = KvDB::DEFAULT_TABLE_NAME;
        $this->db_name = $fn;
        
        // create DB
        $this->db = new PDO('sqlite:'.KVDB_ROOT.'/db/'.$fn);        
    }
    
    function __destruct() {
        unset($this->db);
    }
    
    //
    // 'set' series
    //
    public function setTable($table) {
        $this->table_name = $table;
    }
    
    //
    // 'create' seris
    //
    public function createTable($scheme = "__default", $tbl_name = "__default") {
        
        if($scheme == "__default") $scheme=$this->table_name;
        if($tbl_name == "__default") $tbl_name = $scheme;
        
        if($this->isConnected()) {
            $q = $this->db->query("SELECT * FROM sqlite_master WHERE type = 'table' AND name = '{$tbl_name}'");  
            if($q->fetch() === false) {
                //
                // a table doesn't exist, add it
                
                // sql to create table
                // name rule : KVDB_ROOT/classes/queries/{table_name}.sql
                //
                //$sql = file_get_contents(KVDB_ROOT.'/classes/queries/'.$scheme.'.sql');
                if( file_exists(KVDB_ROOT.'/classes/queries/'.$scheme.'.sql') === true ) {
                    $sql = kv::form_load(KVDB_ROOT.'/classes/queries/'.$scheme.'.sql', array ( "tbl_name" => "$tbl_name" ));
                } else $sql = false;
                if($sql !== false ) {
                    $this->errMsg("[KvDb:sql] $sql");

                    if($sql !== false) {
                        // table creation
                        if( $this->db->exec(trim($sql)) !== false ) return true;
                            else return $this->returnFalse("[KvDb:createTable] db execution error");
                    } else return $this->returnFalse("[KvDb:createTable] no scheme found"); 
                } else return $this->returnFalse("[KvDb:createTable] no scheme found");
            } else return $this->returnFalse("[KvDb:createTable] table alread exists!!");
        } else return $this->returnFalse("[KvDb:createTable] db not connected");
        
        return $this->returnFalse("[KvDb:createTable] somthing wrong... :/");
    }
    
    public function createDb($db_name) {
    //
    // createDb ( database name )
    //
    // create a new database
    //
        if( file_exists(KVDB_ROOT.'/db/'.$db_name) !== true ) {
            if( ($new_db = new PDO('sqlite:'.KVDB_ROOT.'/db/'.$db_name)) !== false ) {
                return $new_db;
            } else return $this->returnFlase("[KvDb:createDb] db creation error");
        } else return $this->returnFalse("[KvDb:createDb] db already exists");
    }
    
    //
    // 'open' series
    //
    public function openDb($db_name) {
    //
    // openDb ( database name )
    //
    // open a exisitng dabase
        
        if( file_exists(KVDB_ROOT.'/db/'.$db_name) === true ) {
            if( ($new_db = new PDO('sqlite:'.KVDB_ROOT.'/db/'.$db_name)) !== false ) {
                $this->db = $new_db;
                return true;
            } else return $this->returnFlase("[KvDb:openDb] db open error");
        } else return $this->returnFalse("[KvDb:openDb] db not exists");
    }
    
    //
    // 'is' series
    //
    public function isConnected() {
    //
    // check is this db is connected
    //
        return isset($this->db);
    }
    
    public function doesThisTableExist($tbl_name) {
        $tbls = $this->getAllTables();
        
        if($tbls !== false ) {
            foreach($tbls as $key=>$value) {
                if ($value == $tbl_name) return true;
            }
            //return $this->returnFalse("[KvDb:doesThisTableExist] table $tbl_name doesn't exist");
            return false;
        } else return $this->returnFalse("[KvDb:doesThisTableExist] getAllTables err");
    }

    //
    // 'get' series
    //
    public function getSchema($table = "__default") {
    //
    // getSchema($fn)
    //
    // get schema from file and return the array of it
    //
        
        if($table == "__default") $table=$this->table_name;
        /*
        $content = file_get_contents(KVDB_ROOT."/classes/queries/".$table.".txt");
        if($content !== false) {
            $lines = explode("\n",$content);
            $arr = array();

            foreach( $lines as $line ) {
                list($key, $value) = explode(",",$line);
                $arr[$key] = $value;
            }

            return $arr;
        } else return $this->returnFalse("[KvDb:getSchema] no schema file found");
        */
        if($this->doesThisTableExist($table) !== false) {
            if($this->isConnected()) {
                $q = $this->db->query("PRAGMA table_info(" . $table. ")");
                //$q = $this->db->query("SHOW COLUMNS FROM $table");
                if($q !== false) {
                    $table_fields = $q->fetchAll();
                    $schema = array ();

                    for ( $i=0; $i< count($table_fields); $i++) {
                        $field = $table_fields[$i];
                        $name = str_replace('\'',"",$field['name']);
                        $schema["$name"] = str_replace('\'',"",$field['type']);
                    }
                    return $schema;
                } else return $this->returnFalse("[KvDb:getSchema] db execution error");
            } else return $this->returnFalse("[KvDb:getSchema] db connection error");
        } else return $this->returnFalse("[KvDb:getSchema] table doesn't exist");
        
        return $this->returnFalse("[KvDb:getSchema] something wrong... :/");
    }
    
    public function getSchemaList() {
    //
    // getTeamplesList()
    // get Table Templates List
    //
        $list = scandir(KVDB_ROOT."/classes/queries");
        if($list !== false) {
            return $list;
        } else return $this->returnFalse("[KvDB:getSchemaList] error to getting table templates list");
        
        return $this->returnFalse("[KvDB:getSchemaList] something wrong... :/");
    }
    
    public function getDbList() {
    //
    // getDbList()
    // get database list
    //
        $list = scandir(KVDB_ROOT.'/db');
        if($list !== false) {
            return $list;
        } else $this->returnFalse("[KvDB:getDbList] error to getting database list ");
        
        return $this->returnFalse("[KvDB:getDbList] something wrong... :/");
    }
            

    public function getCount($table= "__default") {
    //
    // get count of '{$this->table_name}' table
    //
        
        if($table == "__default") $table=$this->table_name;
        
        if($this->isConnected()) {
        //
        // if db is connected
        //
            $sql = "SELECT * FROM '{$table}'";
            $q = $this->db->query($sql);
            $rs = $q->fetchAll();
            
            return count($rs);
        } else return $this->returnFalse("[KvDb:getCount] db not connected");
    }
    
    public function getAllEntries($table="__default") {
    //
    // get all entries
    //
        if($table == "__default") $table=$this->table_name;
        
        $sql = "SELECT * FROM '{$table}' ORDER BY kv_order";
        
        if($this->isConnected()) {
        // if db is connected
            $q = $this->db->query($sql);
            
            if( $q !== false ) {
                $rs = $q->fetchAll();

                if(count($rs)>0) {
                    return $rs;
                } else return $this->returnFalse("[KvDb:getAllEntries] no data fetched!");
            } else return $this->returnFalse("[KvDb:getAllEntries] query execution error");
        } else return $this->returnFalse("[KvDb:getAllEntries] db connection error");
    }
    
    public function getAllTables() {
    //
    // get all tables
    //
        $sql = "SELECT * FROM sqlite_master WHERE type = 'table'";
        
        if($this->isConnected()) {
        // if db is connected
            $q = $this->db->query($sql);
            $tbls = array ();
            if( $q !== false) {
                $rs = $q->fetchAll();
                if(count($rs)>0) {
                    foreach( $rs as $key=>$value ) {
                        $tbls[] = $value['name'];
                    }
                    return $tbls;
                } else return $this->returnFalse("[KvDb:getAllTables] no tables fetched!");
            } else return $this->returnFalse("[KvDb:getAllTables] db execution error");
        } else return $this->returnFalse("[KvDb:getAllTables] db connection error");
    }
    
    public function getEntryStrict($find_key, $find_value, $table= "__default"){
    //
    // get the first first entry matching with given kev=>value pair
    //
    // returns
    // entry : found entry
    // false : couldn't find it
        
        if($table == "__default") $table=$this->table_name;
        
        if($this->isConnected()) {
            //
            // if db is connected
            //
            $sql = "SELECT * FROM {$table} WHERE \"$find_key\" LIKE \"$find_value\"";
            $rows= $this->db->query($sql);
            $found = false;

            // fetch 1st row
            $row = $rows->fetch();

            // if found anything
            if($row !== false) {
                return $row;
            } else return $this->returnFalse("[KvDb:getEntryStrict] no entry found with ".$find_key."=>".$find_value);
        } else return $this->returnFalse("[KvDb:getEntryStrict] no db connection");      
    }
    
    public function getEntry(array $arg,$table= "__default") {
    //
    // get the first entry matching with given kev=>value pairs that may have wrong information
    //
        // check array has contents

        if($table == "__default") $table=$this->table_name;
        
        if(count($arg) == 0) {
            return false;
        }
        
        // find out any matching pair from the given array $arg
        
        // if kv_order is set, find an entry using that key=>value pair since kv_order is exceptionally important key
        if( isset($arg['kv_order']) ) {
            return $this->getEntryStrict('kv_order', $arg['kv_order'], $table);
        } else {
        // if else, pickup the first found entry of the array
            foreach($arg as $key => $value) {
                $entry = $this->getEntryStrict($key,$value,$table);
                if ( $entry !== false ) return $entry;
            }
            return $this->returnFalse("[KvDb:getEntry] Couldn't find any matching entries from the db, damn it!");
        }
        return $this->returnFalse("[KvDb:getEntry] I don't know well, but it seems that something wrong happened :/");
    }

    public function getLargestPrimekey($table= "__default") {
    //
    // retrieve largest prime key
    //
        
        if($table == "__default") $table=$this->table_name;
        
        if($this->isConnected()) {
            $sql = "SELECT kv_order FROM {$table} ORDER BY kv_order DESC LIMIT 2";
            $rows = $this->db->query($sql);
            
            if($rows === false) return $this->returnFalse("[KvDb:getLargestPrimekey] db execution error!");
            
            // fetch 1st row
            $row = $rows->fetch();
            if($row === false) return $this->returnFalse("[KvDb:getLargestPrimekey] no row(s) found");
            
            // return largest prime key
            return $row['kv_order'];
        }
    }
    
    //
    // 'add' series
    //
    public function addEntry(array $entry,$table = "__default") {
    //
    // add an entry
    //  
        if($table == "__default") $table=$this->table_name;

        if($this->isConnected()) {
            //
            // if db is connected
            //
            if( ($count = $this->getLargestPrimekey($table)) === false) $count=0;
                
            if($count !== false ) {
                // add count
                $count++;
                $entry['kv_order']=$count;
                
                // serialize user
                if( $serialized = $this->serializeEntryValues($entry,$table) ) {
                    // sql to insert table
                    $sql = "INSERT INTO {$table} VALUES (".$serialized.")";
                    if($this->db->exec($sql) !== false) return true;
                    else return $this->returnFalse("[KvDb:addEntry] db execution error ".$sql);
                } else return $this->returnFalse("[KvDb:addEntry] serializeEntryValues error");
            } else return false;
        } else return false;
    }
    
    //
    // 'update' series
    //
    public function updateEntry(array $entry,$table = "__default") {
    //
    // update an entry
    //
        // default for the selected_kv_order
        
        if(isset($entry['selected_kv_order'])) {
            $selected_kv_order = $entry['selected_kv_order'];
        } else {
            $selected_kv_order = "__default";
        }
        
        if($table == "__default") $table=$this->table_name;
                
        if($this->isConnected()) {
        // if db is connected
            
            // save kv_order
            if(isset($entry['kv_order'])) $kv_order = $entry['kv_order'];
            
            // if there's selected_kv_order, temporarily assign selected_kv_order into the kv_order
            if($selected_kv_order != '__default') $entry['kv_order'] = $selected_kv_order;
            
            // delete selected_kv_order entry
            unset($entry['selected_kv_order']);
            
            // findout kv_order
            $gotten_entry = $this->getEntry($entry,$table);
            if($gotten_entry !== false) {
                $selected_kv_order = $gotten_entry['kv_order'];                

                // recover kv_order value
                if(isset($kv_order)) $entry['kv_order'] = $kv_order;

                if( ($serialized = $this->serializeEntryKeyValues($entry,$table)) !== false ) {
                    $q = "UPDATE {$table} SET ".$serialized." WHERE kv_order LIKE ?";
                    $sql = $this->db->prepare($q);
                    if($sql) {
                        if ($sql->execute(array($selected_kv_order)) !==false ) return $selected_kv_order;
                            else return $this->returnFalse("[KvDb:updateEntry] db execution error : ".$q." ".$selected_kv_order);                     
                    } else return $this->returnFalse("[KvDb:updateEntry] db prepare error");
                } else return $this->returnFalse("[KvDb:updateEntry] serializeEntryKeyValues error");              
            } else return $this->returnFalse("[KvDb:updateEntry] couldn't find kv_order".var_export($entry,true));
        } else return false;
    }

    //
    // 'delete' series
    //
    public function deleteByPrimekey($pk,$table = "__default") {
    //
    // Obviously,, delete row by primekey
    //
        if($table == "__default") $table=$this->table_name;
        
        if($this->isConnected()) {
        //
        // if db is connected
        //    
            $sql = "DELETE FROM {$table} WHERE kv_order = \"$pk\"";
            if($this->db->exec($sql) !== false ) return true;
                else return $this->returnFalse("[KvDb:deleteByPrimekey] db execution error");           
        } else return $this->returnFalse("[KvDb:deleteByPrimekey] db connection error");
    }
    
    public function deleteTable($table = "__default") {
    //
    // delete table by $table
    //
        if($table == "__default") $table=$this->table_name;
        
        if($this->isConnected()) {
        //
        // if db is connected
        //
            $sql = "DROP TABLE \"$table\"";
            if($this->db->exec($sql) !== false ) return true;
                else return $this->returnFalse("[KvDb:deleteTable] db execution error");
        } else return $this->returnFalse("[KvDb:deleteTable] db connection error");
    }
    
    public function deleteDb($db_name) {
    //
    // delete an existing db
    //
        if($this->isConnected()) {
            if( file_exists(KVDB_ROOT.'/db/'.$db_name) === true ) {
                if( $db_name !== $this->db_name ) {
                    if ( unlink( KVDB_ROOT.'/db/'.$db_name ) === true ) return true;
                        else return $this->returnFalse("[KvDb:deleteDb] unlink execution error");
                } return $this->returnFalse("[KvDb:deleteDb] can't delete the db you're working on");
            } else return $this->returnFalse("[KvDb:deleteDb] db not exists");
        } else return $this->returnFalse("[KvDb:deleteDb] db connection error");
    }
    
    
    public function eraseTable($table = "__default") {
    //
    // delete table by $table
    //
        if($table == "__default") $table=$this->table_name;
        
        if($this->isConnected()) {
        //
        // if db is connected
        //
            $sql = "DELETE FROM $table";
            if($this->db->exec($sql) !== false ) return true;
                else return $this->returnFalse("[KvDb:eraseTable] db execution error ".$sql);
        } else return $this->returnFalse("[KvDb:eraseTable] db connection error");
    }
    //
    // 'print' series
    //
    public function printEntries($direction = 'horizontal', $pad = KvDB::DEFAULT_PRINT_PAD, $table = "__default") {
        
        if($table == "__default") $table=$this->table_name;
        
        $this->setPrintPad($pad);
        switch(strtolower($direction)) {
            case 'vertical' :
                return $this->printEntriesVertical($table);
                break;
            case 'horizontal' :
                return $this->printEntriesHorizontal($table);
                break;
            default :
                return $this->printEntriesHorizontal($table);
                break;
        }
        
        return false;
    }
    public function printEntriesVertical($table = "__default") {
    //
    // print a table vertically
    //
        if($table == "__default") $table=$this->table_name;
        
        switch ($table) {
            case "tables" :
                $sql = "SELECT * FROM sqlite_master WHERE type = 'table'";
                break;
            case "table" :
                $sql = "SELECT * FROM sqlite_master ORDER BY kv_order WHERE type = 'table' ";
                break;
            default :
                $sql = "SELECT * FROM '{$table}' ORDER BY kv_order";
                break;
        }
        
        if($this->isConnected()) {
        // if db is connected
            $q = $this->db->query($sql);
            
            if($q !== false) {
                $rs = $q->fetchAll();
                $output = '';

                if(count($rs)>0) {
                    // get keys
                    $keys = array_keys($rs[0]);
                    // unset numbered keys
                    $count_keys = count($keys);
                    for($i=1;$i<$count_keys;$i+=2) unset($keys[$i]); // placeholder code

                    foreach ($keys as $key) {
                        $output .= str_pad(
                                           substr($key,0,$this->print_pad),
                                           $this->print_pad,
                                           ' '
                                           ).'|';
                        foreach ($rs as $r) {
                            $output.= str_pad(
                                              substr($r[$key],0,$this->print_pad),
                                              $this->print_pad,
                                              ' '
                                              ).'|';
                        }
                        $output .="\n";
                        if($key=='kv_order') $output .= str_pad(
                                                                '-',
                                                                ($this->print_pad + 1)*(count($rs)+1),
                                                                '-'
                                                                )
                                                        ."\n";
                    }
                    return $output;
                } else return $this->returnFalse("[KvDb:printEntriesVertical] no data fetched!"); 
            } else return $this->returnFalse("[KvDb:printEntriesVertical] query execution error");
        } else return false;
        
        return false;
    }
    public function printEntriesHorizontal($table = "__default") {
    //
    // print a table horizontally
    //
        if($table == "__default") $table=$this->table_name;
        
        switch ($table) {
            case "tables" :
                $sql = "SELECT * FROM sqlite_master WHERE type = 'table'";
                break;
            case "table" :
                $sql = "SELECT * FROM sqlite_master ORDER BY kv_order WHERE type = 'table'";
                break;
            default :
                $sql = "SELECT * FROM '{$table}' ORDER BY kv_order";
                break;
        }
        
        if($this->isConnected()) {
        // if db is connected
            $q = $this->db->query($sql);
            
            if( $q !== false ) {
                $rs = $q->fetchAll();

                if(count($rs)>0) {
                    // printout keys
                    $keys = array_keys($rs[0]);
                    $output = '';
                    $barline = '';
                    foreach ($keys as $n => $key) {
                        if(($n % 2) == 1) continue; // rough code
                        $output.= str_pad(substr($key,0,$this->print_pad),$this->print_pad,' ');
                        $barline.= str_pad('',$this->print_pad+1,'-');
                        $output.='|';
                    }
                    $output.="\n";

                    // printout barline
                    $output.=$barline."\n";

                    // printout rows
                    foreach ($rs as $i => $r) {
                        $n=0;
                        foreach($r as $c) {    
                            if(($n++ % 2) == 1) continue; // rough code
                            $output.= str_pad(substr($c,0,$this->print_pad),$this->print_pad,' ');
                            $output.='|';
                        }
                        $output.="\n";
                    }
                    return $output;
                } else return $this->returnFalse("[KvDb:printEntriesHorizontal] no data fetched!");
            } else return $this->returnFalse("[KvDb:printEntriesHorizontal] query execution error");
        } else return false;
        
        return false;
    }
    
    public function setPrintPad($pad) {
        if($pad>30) $pad=30;
        $this->print_pad = $pad;
    }
    
    //
    // END OF CLASS
    //
}
}
?>