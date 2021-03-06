<?php
require_once ( KVUSER_ROOT."/includes/kv_functions.php");

//
// CLASS : KvFundamental
//
// class for fundamental operation
if( !class_exists('KvFundamental') ){
class KvFundamental {
    
    //
    //
    // VARIABLES
    //
    //
    //
    
    protected $kv_err; // array of error messages
   
    
    //
    //
    // PUBLIC FUNCTIONS
    //
    //
    
    //
    // consturctor / desturctor
    //
    public function __construct() {
        $this->kv_err = array();
    }
    
    public function errMsg($msg) {
        if( count($this->kv_err) > 255 ) unset ($this->kv_err[0]);
        $this->kv_err[] = $msg;
    }
    
    public function returnFalse($msg) {
        $this->errMsg($msg);
        return false;
    }
    
    public function flushErrMsg() {
        if(!empty($this->kv_err)) foreach ($this->kv_err as $msg) kv::kprint($msg);
        unset($this->kv_err);
    }
    
    public function returnErrMsg() {
        $output ='';
        if(!empty($this->kv_err)) foreach ($this->kv_err as $msg) $output.=$msg."\n";
        unset($this->kv_err);
        
        return $output;
    }
}
}

?>