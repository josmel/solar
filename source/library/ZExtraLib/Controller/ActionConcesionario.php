<?php
class ZExtraLib_Controller_ActionConcesionario extends ZExtraLib_Controller_Action {

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
        $this->initValidacionUsuario();
        if (isset($this->_identity)) {                
            $concesionario = new Application_Model_Concesionario();
            $this->view->getConcesionario = $concesionario
                ->concesionarioMisDatos(
                    $this->_identity->IdEnte,$this->_identity->IdUsuarioAnunciante);
            $nombretipoconcesionario = $this->_request->getParam('tipo');
            
            if(empty($this->_identity->TipoVendedorConcesionario)){
            $this->_helper->getHelper('FlashMessenger')
                        ->addMessage('No cuenta con un perfil activo.');
                   $this->_redirect('/');
            }
            /***/
            $this->_arrayAclAnunciante =
                    $arrayAcl =
                    array("miperfil" =>
                        array("nombre" => "Concesionario",
                            "ruta" => '/concesionario/admin/inicio/'.$nombretipoconcesionario,
                            "arbol" => array(
                                "inicio" =>
                                array("nombre" => "Inicio",
                                    "class" => "inicio-".$nombretipoconcesionario,
                                    "ruta" => '/concesionario/admin/inicio/'.$nombretipoconcesionario,
                                    "arbol" => ''),
                                "mis-datos" =>
                                array("nombre" => "Mis Datos",
                                    "class" => "mis-datos-".$nombretipoconcesionario,
                                    "ruta" => '/concesionario/admin/mis-datos/'.$nombretipoconcesionario,
                                    "arbol" => ''),
                                "avisos" =>
                                array("nombre" => "Mis Avisos",
                                    "class" => "anadir-avisos-".$nombretipoconcesionario,
                                    "ruta" => '/concesionario/avisos/anadir-avisos/'.$nombretipoconcesionario,
                                    "arbol" =>
                                    array("anadir-avisos" =>
                                        array("nombre" => "Añadir Avisos",
                                            "action" => "anadir-avisos",
                                            "ruta" => '/concesionario/avisos/anadir-avisos/'.$nombretipoconcesionario),
                                        "avisos-activos" =>
                                        array("nombre" => "Avisos Activos",
                                            "action" => "avisos-activos",
                                            "ruta" => '/concesionario/avisos/avisos-activos/'.$nombretipoconcesionario),
                                        "avisos-baja" =>
                                        array("nombre" => "De baja",
                                            "action" => "avisos-baja",
                                            "ruta" => '/concesionario/avisos/avisos-baja/'.$nombretipoconcesionario)
                                    )
                                ),
                                "tienda" =>
                                array("nombre" => "Mi Tienda",
                                    "class" => "destacados-".$nombretipoconcesionario,
                                    "ruta" => '/concesionario/tienda/destacados/'.$nombretipoconcesionario,
                                    "arbol" =>
                                    array("destacados" =>
                                        array("nombre" => "Adm. destacados",
                                            "action" => "destacados",
                                            "ruta" => '/concesionario/tienda/destacados/'.$nombretipoconcesionario),
                                        "nota-informativa" =>
                                        array("nombre" => "Nota Informativa",
                                            "action" => "nota-informativa",
                                            "ruta" => '/concesionario/tienda/nota-informativa/'.$nombretipoconcesionario) //,
//                                            "promociones" =>
//                                            array("nombre" => "Promociones",
//                                                "action" => 'promociones',
//                                                "ruta" => '/concesionario/tienda/promociones')
                                    )
                                ),
                                "Mis Paquetes" =>
                                array("nombre" => "Mis Paquetes",
                                    "class" => "mis-paquetes-".$nombretipoconcesionario,
                                    "ruta" => '/concesionario/admin/mis-paquetes/'.$nombretipoconcesionario,
                                    "arbol" => ''
                                ),
                                "utilidades" =>
                                array("nombre" => "Utilidades",
                                    "class" => "utilidades-".$nombretipoconcesionario,
                                    "ruta" => '/concesionario/admin/utilidades/'.$nombretipoconcesionario,
                                    "arbol" => '')
                            )
                        )
            );            
        } 
        $frontController = Zend_Controller_Front::getInstance(); 
        $fechaCierre = date("Y-m-d",strtotime($frontController->getParam('bootstrap')->getOption('MessageClose') ));
        $fechaActual = date("Y-m-d");
        $this->view->fechaCierre =  ($fechaActual < $fechaCierre )? $fechaCierre: null;
        $this->view->mensajeFechaCierre = (!empty($this->view->fechaCierre))?
                'Estimados anunciantes, el cierre de publicaciones para la edición impresa del domingo 04 de Noviembre será adelantada al día miércoles 31 de Octubre hasta las 6:00 p.m., aplica para todos nuestros canales de venta, incluyendo fonoavisos y agencias concesionarias, toda publicidad ingresada después de esta fecha y hora, pasará a publicarse en la siguiente edición impresa del domingo 11 de Noviembre.'
                : '';
        $this->view->rutaBlog = $this->_rutaBlog = $frontController->getParam('bootstrap')->getOption('BlogNeoautoHome');
        $this->_hostFileStatic = ZExtraLib_Server::getStatic()->host;
        $this->_hostFileContent = ZExtraLib_Server::getContent()->host;
        $this->_layout = Zend_Layout::getMvcInstance();        
        $this->initMenuAnunciante();
        $this->initPublicacion();
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
        $this->view->contarAvisosMarcaFooter = $detalleBusqueda->contarAvisosMarca();
        $this->view->contarModelos = $detalleBusqueda->contarModelo();
        $this->view->contarUltimosAvisosFooter = $detalleBusqueda->ultimosAvisosUsados(6);
        $this->view->AclAnunciante = $this->_arrayAclAnunciante;
        $this->view->filterHtml = new Zend_Filter_StripTags();
        $this->verficarPermisos();
        $this->view->messages = $this->_helper->getHelper('FlashMessenger')->getMessages();
        $this->initView();

    }
    function initValidacionUsuario() {
        if($this->_request->getModuleName() != "default" ||
           $this->_request->getControllerName() != "index" ||
           $this->_request->getActionName() != "login"){
           
           if(!$this->_identity){
                    $this->_redirect("/cuenta/login"); 
                }
            
        }
    }
    public function initPublicacion() {
        if($this->_getParam('reset')==1){
            
        }
        if($this->_request->getModuleName() == 'anunciante') {
            if ($this->_request->getControllerName() == 'publicacion') {
                $this->_layout->setLayout('layout-anunciante-publicacion');
            } else {
                if (isset($this->session->AvisoRegistrado)) {
                    $this->session->AvisoRegistrado = null;
                    unset($this->session->AvisoRegistrado);
                    $this->session->flagAvisoImportado = null;
                    unset($this->session->flagAvisoImportado);
                }
            }
        }elseif ($this->_request->getModuleName() == 'concesionario') {
            $this->_layout->setLayout('layout-concesionario');
                    $this->session->AvisoRegistrado = null;
                    unset($this->session->AvisoRegistrado);
                    $this->session->flagAvisoImportado = null;
                    unset($this->session->flagAvisoImportado);
        }
        if(
           $this->_request->getModuleName() == 'default' && $this->_request->getControllerName() == 'index'
        ) {
                    $this->session->AvisoRegistrado = null;
                    unset($this->session->AvisoRegistrado);
                    $this->session->flagAvisoImportado = null;
                    unset($this->session->flagAvisoImportado);
        }
    }

    public function initMenuAnunciante() {
        if ($this->_request->getModuleName() == 'anunciante') {
            if (!$this->_identity &&
                    (!($this->_request->getControllerName() == 'publicacion' &&
                    $this->_request->getActionName() == 'index') &&
                    !($this->_request->getControllerName() == 'pagoefectivo' &&
                    $this->_request->getActionName() == 'pago-efectivo-urlok'))
            ) {
                $this->_redirect(ZExtraLib_Server::getContent()->host);
            }
            $this->_layout->setLayout('layout-anunciante');
            switch ($this->_request->getControllerName()) {
                case 'miperfil':$this->view->menuAdminSelectd_1 = 'selected';
                    break;
                case 'micuenta':$this->view->menuAdminSelectd_2 = 'selected';
                    break;
                case 'vendedor':$this->view->menuAdminSelectd_3 = 'selected';
                    break;
            }
        } elseif ($this->_request->getModuleName() == 'concesionario') {
            $this->_layout->setLayout('layout-concesionario');            
            if (!$this->_identity){                
                $this->_redirect(ZExtraLib_Server::getContent()->host);
            }

        }elseif($this->_request->getModuleName() == 'cerokm'){
            if (!$this->_identity){
                $this->_redirect(ZExtraLib_Server::getContent()->host);
            }
        }elseif($this->_request->getModuleName() == 'callCenter'){
            if (!$this->_identity){
                $this->_redirect(ZExtraLib_Server::getContent()->host);
            }
        }elseif($this->_request->getModuleName() == 'anunciante'){
            if (!$this->_identity){
                $this->_redirect(ZExtraLib_Server::getContent()->host);
            }
        }
    }

}