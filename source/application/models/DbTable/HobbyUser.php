<?php

/**
 * Table Activities
 * 
 * @author marrselo
 */
class Application_Model_DbTable_HobbyUser extends Core_Db_Table
{

    protected $_name = "hobbyuser";
    protected $_primary = "idhobbyuser";

    const NAMETABLE = 'hobbyuser';

    static function populate($params)
    {
        $data = array(
            'idUser' => isset($params['idUser']) ? $params['idUser'] : '',
            'idHobby' => isset($params['idHobby']) ? $params['idHobby'] : '',
        );
        $data = array_filter($data);
        return $data;
    }

    /**
     * 
     * @param obj DB $resulQuery
     */
    public function getPrimaryKey()
    {
        return $this->_primary;
    }

  public function insertIdUser($hobby,$user)
    {
        $this->getAdapter()->insert(array('idHobby' => $hobby, 'idUser' => $user));
        //return $result;
    }

}

