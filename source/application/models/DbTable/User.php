<?php

/**
 * Table Activities
 * 
 * @author marrselo
 */
class Application_Model_DbTable_User extends Core_Db_Table
{

    protected $_name = "user";
    protected $_primary = "idUser";

    const NAMETABLE = 'user';

    static function populate($params)
    {
        $data = array(
            'name' => isset($params['name']) ? $params['name'] : '',
            'lastName' => isset($params['lastName']) ? $params['lastName'] : '',
            'firstName' => isset($params['firstName']) ? $params['firstName'] : '',
            'age' => isset($params['age']) ? $params['age'] : '',
            'mail' => isset($params['mail']) ? $params['mail'] : '',
            'lastUpdate' => date('Y-m-d H:i:s')
        );
        $data = array_filter($data);
        $data['flagAct'] = isset($params['flagAct']) ? $params['flagAct'] : 1;
        return $data;
    }

    public function getPrimaryKey()
    {
        return $this->_primary;
    }

    

}

