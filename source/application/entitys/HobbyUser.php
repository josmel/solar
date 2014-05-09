<?php

class Application_Entity_HobbyUser extends Core_Model
{

    protected $_tableHobbyUser;

    public function __construct()
    {
        $this->_tableHobbyUser = new Application_Model_DbTable_HobbyUser();
    }

    public function insertUserHobby($idHobby, $idUser)
    {
        $data = array('idHobby' => $idHobby, 'idUser' => $idUser);
        $this->_tableHobbyUser->insert($data);
    }

     public function deleteUserHobby($idUser)
    {

        $where = $this->_tableHobbyUser->getAdapter()
                          ->quoteInto('idUser = ?', $idUser);
       $this->_tableHobbyUser->delete($where);


    }

      public function getHobby($id) {
        $smt = $this->_tableHobbyUser->getAdapter()->select()->distinct()
                ->from(array('fh' => $this->_tableHobbyUser->getName()),
              array('idhobbyuser' => 'fh.idhobbyuser')
                )
                ->join(array('f' => 'hobby'), "f.idHobby = fh.idHobby",array('idHobby' => 'f.idHobby')
                )
                ->where("fh.idUser = ?", $id)
                ->query();
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }
    
}

