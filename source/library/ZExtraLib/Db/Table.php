<?php

class ZExtraLib_Db_Table
        extends Zend_Db_Table
{

    function __construct($config = array(), $definition = null)
    {
        $this->_db = ZExtraLib_Server::getDb('query');
        parent::__construct($config, $definition);
    }

    function insert(array $data)
    {
        $this->_db = ZExtraLib_Server::getDb('process');
        parent::insert($data);
    }
    
    function update(array $data, $where)
    {
        $this->_db = ZExtraLib_Server::getDb('process');
       return parent::update($data, $where);
    }

    function delete($where)
    {
        $this->_db = ZExtraLib_Server::getDb('process');
        parent::delete($where);
    }
    
    public function getName() {
        return $this->_name;
    }
    
    public function getCols(){
        return $this->_getCols();
    }
    
}
