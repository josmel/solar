<?php

class Application_Entity_User extends Core_Model
{

    protected $_tableUser;

    public function __construct()
    {
        $this->_tableUser = new Application_Model_DbTable_User();
    }

    public function findAll($idUser)
    {
        $select = $this->_tableUser->getAdapter()->select()
                ->from(array('f' => $this->_tableUser->getName()), array('f.idUser')
                )->join(array('tf' => 'hobbyuser'), "f.idUser = tf.idUser"
                        
                )->join(array('tr' => 'hobby'), "tr.idHobby = tf.idHobby", array('name' => 'tr.name')
                )
                ->where("f.idUser = ?", $idUser);
        $select = $select->query();
        $result = $select->fetchAll();
        $select->closeCursor();
        return $result;
    }
    
     public function listAll() {
        $smt = $this->_tableUser->getAdapter()->select()->distinct()
                        ->from($this->_tableUser->getName())
                        ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

}
