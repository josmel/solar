<?php
class ZExtraLib_PaginatorCustom extends Zend_Paginator{
    public function __construct($adapter) {
        parent::__construct($adapter);
    }

    public function setearPagina($param) {
        
        $this->_pageCount = $param;
    }
    public function setCurren($param){
        $this->current = $param;
        
    }
}

?>