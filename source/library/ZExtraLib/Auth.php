<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ZExtraLib_Auth
 *
 * @author nazart
 */
class ZExtraLib_Auth extends Zend_Auth {
     /**
     * Singleton instance
     *
     * @var Zend_Auth
     */
    protected static $_instance = null;
    
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    public function authenticates(Zend_Auth_Adapter_Interface $adapter,$noauth=null)
    {
        $result = $adapter->authenticate();
        /**
         * ZF-7546 - prevent multiple succesive calls from storing inconsistent results
         * Ensure storage has clean state
         */
        if ($this->hasIdentity()) {
            if($noauth!=1){
                $this->clearIdentity();
            }
        }

        if ($result->isValid()) {
            if($noauth!=1){
                $this->getStorage()->write($result->getIdentity());
            }
        }

        return $result;
    }
    
}

?>
