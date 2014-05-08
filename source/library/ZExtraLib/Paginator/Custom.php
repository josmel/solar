<?php

class ZExtraLib_Paginator_Custom implements Zend_Paginator_Adapter_Interface {

    protected $_sql;
    protected $_sqlCount;
    protected $_db;
    /* call nombreSP(parametros,?,?);
     * cal 
     * 
     */

    public function __construct($sql, $sqlCount, $db) {
        $this->_sql = $sql;
        $this->_sqlCount = $sqlCount;
        $this->_db = $db;
    }

    public function getItems($offset, $itemCountPerPage) {
        $smt = $this->_db->query($this->_sql, array($offset, $itemCountPerPage));
        $result = $smt->fetchAll();
        $smt->closeCursor();
        return $result;
    }

    public function count() {
        $smt = $this->_db->query($this->_sqlCount);
        $result = $smt->fetchColumn(0);
        $smt->closeCursor();
        return $result;
    }

}

?>
