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
        $data = array('idUser' => $idUser);
        $this->_tableHobbyUser->delete($data);
    }
}

