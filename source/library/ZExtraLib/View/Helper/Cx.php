<?php

class ZExtraLib_View_Helper_Cx
        extends Zend_View_Helper_Abstract
{
    /**
     *
     * @var Zend_Controller_Front
     */
    protected $_nameAction = null;
    
    public function __construct()
    {
        $this->_nameAction = Zend_Controller_Front::getInstance()->getRequest()
            ->getActionName();        
    }
    
                    
    public function cx()
    {     
        
       switch($this->_nameAction){
           case 'verdetalle' :                
           case 'registro'   : $return = '';
               break;
           default :
               $return = $this->view->layout()->render('_cxense');
       } 
       return $return;
    }
        
}