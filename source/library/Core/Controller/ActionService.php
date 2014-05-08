<?php

class Core_Controller_ActionService extends Core_Controller_Action {
                    
    protected $_identity;
    public function init() {
        parent::init();
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);     
    }
    
    public function preDispatch()
    {        
        $this->_identity = Zend_Auth::getInstance()->getIdentity();      
//        $this->permisos();
    }
    function permisos()
    {
        $auth = Zend_Auth::getInstance();
        $controller=$this->_request->getControllerName();
        if ($auth->hasIdentity()) {                    
        }else{
            if ($controller!='index') {
            $this->_redirect('/admin');
            }
        }
        
    }
   
     
    public function auth($usuario,$password)
    {              
        
            $dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
            $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        
            $authAdapter
                ->setTableName('User')
                ->setIdentityColumn('mail')
                ->setCredentialColumn('idFacebook')
                ->setIdentity($usuario)
                ->setCredential($password);
            try{
            $select = $authAdapter->getDbSelect();
            $select->where('flagAct = 1');
            $result = Zend_Auth::getInstance()->authenticate($authAdapter);
            if ($result->isValid()){
                $storage = Zend_Auth::getInstance()->getStorage();
                $bddResultRow = $authAdapter->getResultRowObject();
                $storage->write($bddResultRow);
                $msj = 'Bienvenido Usuario '.$result->getIdentity();              
                $this->_identity = Zend_Auth::getInstance()->getIdentity(); 
                $return = true;
            } else { 
                switch ($result->getCode()) {
                    case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                        $msj = 'El usuario no existe';
                        break;
                    case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                        $msj = 'Password incorrecto';
                        break;
                    default:
                        $msj='Datos incorrectos';
                        break;
                }           
                $return = false;
            }
            
            }catch(Exception $e){
                echo $e->getMessage();exit;
            }
             return $return;
    }
}
