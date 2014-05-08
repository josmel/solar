<?php
class ZExtraLib_Controller_Action extends Zend_Controller_Action {

    protected $_layout;
    protected $_hostFileStatic;
    protected $_arrayAclAnunciante;
    protected $_identity;
    protected $_identityTemp;
    protected $_sessionAdmin;    
    
    /**
     *
     * @var Zend_Session_Namespace
     */
    public $session;

    public function init() {
        
        
        parent::init();
		if (!Zend_Session::isStarted()) {
			echo 'inciando session';
			Zend_Session::start();
        }
       
        $this->session =  new Zend_Session_Namespace('Neoauto');
		if (!isset($this->session->initialized)) {
			Zend_Session::regenerateId();
			$this->session->initialized = true;
		}	
        $this->_sessionAdmin =  new Zend_Session_Namespace('sessionAdmin');
        
        if (isset($this->_sessionAdmin->identity)){
            $this->view->identityAdmin = $this->_sessionAdmin->identity;
        }
        
        $this->_identity = Zend_Auth::getInstance()->getIdentity();                       
        $frontController = Zend_Controller_Front::getInstance();         
        $fechaCierre = date("Y-m-d",strtotime($frontController->getParam('bootstrap')->getOption('MessageClose')));
        $fechaActual = date("Y-m-d");
        $this->view->fechaCierre =  ($fechaActual < $fechaCierre )? $fechaCierre: null;
        $this->view->mensajeFechaCierre = (!empty($this->view->fechaCierre))?
                'Estimados anunciantes, el cierre de publicaciones para la edición impresa del domingo 31 de Marzo será adelantada al día miércoles 27 de Marzo hasta las 6:00 p.m., aplica para todos nuestros canales de venta, incluyendo fonoavisos y agencias concesionarias, toda publicidad ingresada después de esta fecha y hora, pasará a publicarse en la siguiente edición impresa del domingo 07 de Abril.'
                : '';
        $this->view->rutaBlog = $this->_rutaBlog = $frontController->getParam('bootstrap')->getOption('BlogNeoautoHome');
        $this->_hostFileStatic = ZExtraLib_Server::getStatic()->host;
        $this->_hostFileContent = ZExtraLib_Server::getContent()->host;
        $this->_layout = Zend_Layout::getMvcInstance();
        $this->initMenuPortal();

        $this->session->_identityTemp = isset($this->session->_identityTemp) ? $this->session->_identityTemp : $this->_identity;
        
        $this->view->identity = $this->_identity;
        $this->view->hostFileStatic = $this->_hostFileStatic;
        $this->view->cantidadPalabrasImpresoTipoPUblicacion = '';
        $this->_configImg = ZExtraLib_Server::getFile(0)->upload;
        $this->view->configImg = $this->_configImg;
        $this->_versionJs = ZExtraLib_Server::getStatic()->versionJs;
        $this->view->versionJs = $this->_versionJs;
        $this->_hostImg = ZExtraLib_Server::getFile(0)->host;
        $this->view->hostImg = $this->_hostImg;
        $this->view->hostContent = $this->_hostFileContent;
        $detalleBusqueda = new Application_Model_DetalleBusqueda();
        $this->view->contarAvisosMarcaFooter = $detalleBusqueda->contarAvisosMarca(
                Application_Model_DbTable_TipoVehiculo::TipoVehiculoAuto);
        $this->view->contarModelos = $detalleBusqueda->contarModelo();
        $this->view->contarUltimosAvisosFooter = $detalleBusqueda->ultimosAvisosUsados(6);
        $this->view->AclAnunciante = $this->_arrayAclAnunciante;
        $this->view->filterHtml = new Zend_Filter_StripTags();
        $this->verficarPermisos();
        $this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();
        $this->initView();

    }

   
    public function initMenuPortal() {
        if ($this->_request->getModuleName() == 'default') {
            switch ($this->_request->getControllerName()) {
                case 'autosnuevos':$this->view->menuItemSelected_2 = 'selected';
                    break;
                case 'autonuevo-concesionario':$this->view->menuItemSelected_2 = 'selected';
                    break;
                case 'autosusados':$this->view->menuItemSelected_3 = 'selected';
                    break;
                case 'cuenta': $this->view->menuItemSelected_1 = '';
                    break;
                case 'motos' : $this->view->menuItemSelected_5 = 'selected';
                    break;               
                default : $this->view->menuItemSelected_1 = 'selected';
            }
        }
    }

    

    public function verficarPermisos() {
        $resource = 'mvc:anunciante/index/index';
        $nameCon = $this->_request->getControllerName();
        $nameAct = $this->_request->getActionName();
        $nameMod = $this->_request->getModuleName();
        $resourceMod = 'mvc:' . $nameMod . '/*';
        $resourceCont = 'mvc:' . $nameMod . '/' . $nameCon . '/*';
        $resourceAct = 'mvc:' . $nameMod . '/' . $nameCon . '/' . $nameAct;
        $rol = (!isset($this->_identity->IdRolUsuarioAnunciante) || ($this->_identity->IdRolUsuarioAnunciante == '')) ? 1 : $this->_identity->IdRolUsuarioAnunciante;
        try {
            if (!$this->isAllowed($rol, $resourceMod)) {
                if (!$this->isAllowed($rol, $resourceCont)) {
                    if (!$this->isAllowed($rol, $resourceAct)) {
                        $this->view->headLink()->appendStylesheet(ZExtraLib_Server::getStatic()->host . '/f/css/print.css', 'media');
                        $this->view->headLink()->appendStylesheet(ZExtraLib_Server::getStatic()->host . '/f/css/screen.css');
                        $this->view->headLink()->appendStylesheet(ZExtraLib_Server::getStatic()->host . '/f/css/boutique.css');
                        $request = $this->getRequest();
                        $request = $request->setModuleName('default');
                        $request = $request->setControllerName('error');
                        $request = $request->setActionName('error');
                        $this->_helper->actionStack($request);
                    }
                }
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function isAllowed($rol, $recuros) {
        $acl = Zend_Registry::get('Acl');
        try {
            return $acl->isAllowed($rol, $recuros);
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
            return false;
        }
    }

    function setParamIdentity($parametro, $value) {
        /* $auth = Zend_Auth::getInstance();
          $identity = $auth->getStorage()->read();
          $identity->$parametro=$value;
          $auth = new Zend_Session;
         */
        $obj = $_SESSION['Zend_Auth']['storage'];
        $obj->$parametro = $value;
    }
    
    public function auth($usuario =NULL,
            $password =NULL,
            $encripty = NULL,$noauth = NULL)
    {
        if ($usuario == NULL || $password == NULL) {
            return false;
        } else {
            $auth = Zend_Auth::getInstance();
            if(($encripty == 1)){
            $adapter = new ZExtraLib_Auth_Adapter_ClubDbTable(ZExtraLib_Server::getInstance()->getDb('process'),
                            'NPC_VW_CredencialesUsuarioAnunciante', 'UsuarioCredencial', 'ClaveCredencial');
            }else{
            $adapter = new Zend_Auth_Adapter_DbTable(ZExtraLib_Server::getInstance()->getDb('process'),
                            'NPC_VW_CredencialesUsuarioAnunciante', 'UsuarioCredencial', 'ClaveCredencial');
            }
            $adapter->setIdentity($usuario);
            $adapter->setCredential($password);
            if ($noauth!=1) {
                $resultAut = $auth->authenticate($adapter);
                $resultAut = $resultAut->isValid();
            } else {
                $resultAut = $adapter->authenticate();
                $resultAut = $resultAut->isValid();
            }
            if ($resultAut) {
                if($noauth!=1) {
                        $userInfo = $adapter->getResultRowObject(null, 'ClaveCredencial');
                        $authStorage = $auth->getStorage();
                        $authStorage->write($userInfo);
                        $this->_identity = Zend_Auth::getInstance()->getIdentity();
                    }
            }
            return $resultAut;
        }
    }


}
