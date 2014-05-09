<?php

class Application_Entity_Hobby extends Core_Model
{

    protected $_tableHobbyUser;

    public function __construct()
    {
        $this->_tableHobby = new Application_Model_DbTable_Hobby();
    }

  public function listAll() {
        $smt = $this->_tableHobby->getAdapter()->select()->distinct()
                        ->from($this->_tableHobby->getName())
                        ->query();   
        $result = array();
        while ($row = $smt->fetch()) {
            $result[$row['idHobby']] = $row['name'];
        }
        $smt->closeCursor();
        return $result;
    }
    


}

