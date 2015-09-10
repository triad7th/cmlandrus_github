<?php
require_once ( KVUSERDB_ROOT."/includes/kv_functions.php");
require_once ( KVUSERDB_ROOT."/classes/kv_db.php");

//
// CLASS : KvUserDB
//
// create and management small sqlite db mainly for user management

if( !class_exists('KvUserDB') ) {
class KvUserDB extends KvDB {
    //
    //
    // CONSTANTS
    //
    //
    
    const DEFAULT_TABLE_NAME = 'users';

    //
    //
    // PRIVATE FUNCTIONS
    //
    //
    
    private function nameToArray($name) {
    //
    // break down the given name to first,middle,last name and return the produced array
    //
        $nameArr = explode(' ',$name);
        $retArr = array('first_name'=>'','middle_name'=>'','last_name'=>'');
        switch (count($nameArr)) {
            case 0 :    break;

            case 1 :    $retArr['first_name']=$name;
                        break;
            case 2 :    $retArr['first_name']=$nameArr[0];
                        $retArr['last_name']=$nameArr[1];
                        break;
            case 3 :    $retArr['first_name']=$nameArr[0];
                        $retArr['middle_name']=$nameArr[1];
                        $retArr['last_name']=$nameArr[2];
                        break;
        }
        return $retArr;
    }
    private function keyDuplicate(array &$user) {
    //
    // duplicate some keys for the purpose of multiple key accept
    //
        kv::duplicate_key($user,array('id','ID'));
        kv::duplicate_key($user,array('first_name','fn','first name','fname'));
        kv::duplicate_key($user,array('last_name','ln','last name','lname'));
        kv::duplicate_key($user,array('middle_name','mn','middle name','mname'));
        kv::duplicate_key($user,array('password','pw','PW','pass word'));
        kv::duplicate_key($user,array('email','e-mail'));
        kv::duplicate_key($user,array('last_access','last access'));
        kv::duplicate_key($user,array('address_1','address 1','address1'));
        kv::duplicate_key($user,array('address_2','address 2','address2'));
    }
    private function keyDuplicateName(array &$user) {
    //
    // name key duplication
    //
        kv::duplicate_key($user,array('name','Name','NAME'));
    }
    private function nameExplode(array &$user) {
    //
    //  make first,middle,last name array from one 'name' string and put them to $user array
    //
        // name to name array
        $this->keyDuplicateName($user);
        if(isset($user['name'])) $user = array_merge($user,$this->nameToArray($user['name'])); 
    }
    private function nameImplode(array &$user) {
    //
    //  make one 'name' value using first,middle,last name and put into $user array
    //
        // duplicate keys
        $this->keyDuplicate($user);
        
        $name='';
        // name array to name
        if(isset($user['first_name'])) if (!empty($user['first_name'])) $name.=$user['first_name'];
        if(isset($user['middle_name'])) if (!empty($user['middle_name'])) $name.=' '.$user['middle_name'];
        if(isset($user['last_name'])) if (!empty($user['last_name'])) $name.=' '.$user['last_name'];
        
        $user['name'] = $name;
        // name duplication
        $this->keyDuplicateName($user);
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
        parent::__construct($fn);
        $this->table_name = KvUserDb::DEFAULT_TABLE_NAME;
    }
    function __destruct() {
        parent::__destruct();
    }
    
    //
    // 'is' series
    //
    public function isUserExists(array $user) {
        //
        // isUserExists(array)
        //
        // [return]
        // true : user exists
        // false : user not exist
        
        $user = $this->getUser($user);
        if($user !== false) return true;
        else return false;
    }
    public function isUserExistsById( $id ) {
        return $this->isUserExists(array('id'=>$id));
    }
    
    //
    // 'get' series
    //
    public function getUser($user) {
        //
        // get user ( getEntry wrapper)
        //
        $this->errMsg("[KvUserDb:getUser] ".var_export($user,true)." ".KvUserDb::DEFAULT_TABLE_NAME);
        return $this->getEntry($user,KvUserDb::DEFAULT_TABLE_NAME);
    }
    public function getUserById($id) {
        //
        // get user by id
        //
        $user = $this->getUser(array('id'=>$id));
        if($user !== false) return $user;
        else return false;
    }
    public function getPrimekeyById($id) {
        //
        // get primekey by id
        //
        $ret = $this->getUser(array('id'=>$id));
        if($ret !== false) return $ret['kv_order'];
        else false;
    }
    public function getUserStateById($id) {
        //
        // get user state by id
        //
        $ret = $this->getUser(array('id'=>$id));
        if($ret !== false) {
            $this->errMsg("[KvUserDb:getUserStateById] got state : ".$ret['log_state']);
            return $ret['log_state'];
        }
        else false;
    }
    public function getPasswordById($id) {
        //
        // literally, get password by ID
        //
        if( $row = $this->getUser(array('id'=>$id)) ) {
            return $row['password'];
        } else return false;
    }

    //
    // 'add' series
    //
    public function addUser(array $user) {
    //
    // add a user ( id, firstname, middlename, lastname, password )
    //
        return $this->addEntry($user,KvUserDb::DEFAULT_TABLE_NAME);
    }
    public function addUserStrict(array $user) {
    //
    // add a user with new id
    //
        
        // multiple key accept
        $this->keyDuplicate($user);
        
        if(isset($user['id'])) {
            if($this->isUserExistsById($user['id']) === false) {
                return $this->addUser($user);
            } else return false;
        } else return false;
    }
    
    //
    // 'update' series
    //
    public function updateUser(array $user) {
    //
    // update a user 
    //
        return $this->updateEntry($user,KvUserDb::DEFAULT_TABLE_NAME);
    }
    public function updateUserAccessTimeById($id) {
    //
    // update the last_access column of given user into current time
    //
    // [return]
    // succeed : primekey
    // failed : false
        
        $ret=$this->updateUser(array(
                         'id'=>$id,
                         'last_access'=>time()
                         ));
        if($ret !== false) return $ret;
        else return false;
    }
    public function updateUserStateById($id,$state) {
        //
        // update the log_state column of given user into given state
        //
        // [return]
        // succeed : primekey
        // failed : false
        
        $ret=$this->updateUser(array(
                              'id'=>$id,
                              'log_state'=>$state
                              ));
        if($ret !== false) return $ret;
        else return false;
    }
    //
    // END OF CLASS
    //
}
}
?>