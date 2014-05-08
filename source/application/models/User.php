<?php
class Application_Model_User extends Core_Model
{
    protected $_tableUser; 
    
    public function __construct()
    {
        $this->_tableUser= new Application_Model_DbTable_User();
    }

    public function getOneUser($idFacebook)
    {
         $smt=$this->_tableUser->getAdapter()->query("
                SELECT * FROM User
                WHERE idFacebook='".$idFacebook."'
                ");
           $result=$smt->fetch();
           $smt->closeCursor();  
           return $result;
    }
    
 
}


