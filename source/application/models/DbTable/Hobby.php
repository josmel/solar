<?php

/**
 * Table Activities
 * 
 * @author marrselo
 */
class Application_Model_DbTable_Hobby extends Core_Db_Table
{

    protected $_name = "hobby";
    protected $_primary = "idHobby";

    const NAMETABLE = 'hobby';

    static function populate($params)
    {
        $data = array(
            'name' => isset($params['name']) ? $params['name'] : '',
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

