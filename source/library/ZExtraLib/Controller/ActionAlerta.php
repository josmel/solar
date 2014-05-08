<?php
class ZExtraLib_Controller_ActionAlerta extends ZExtraLib_Controller_Action {

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
        // $this->session = (!isset($this->session)) ? new Zend_Session_Namespace('Neoauto') : null;
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
         
        
        if ( isset($this->_identity) && ($this->_identity->TipoUsuarioAnunciante == 1 || $this->_identity->TipoUsuarioAnunciante == 2)){
            $modelEmpresa = new Application_Model_Empresa();
            $cantidad = $modelEmpresa->cantidadEmpresaporPersonaNatural($this->_identity->IdUsuarioAnunciante);
            $cantidadEmpresas = $cantidad['cantEmpresas'];
        }
	//$this->view->identityTemp = ZExtraLib_Utils::getIdentityTemp();
        /*if (isset($this->_identity)) {  //3 menus si es revendedor     
            $flagAvisosImportados = 0;
            $modelAvisoInactivos = new Application_Model_AvisoInactivo();
            $this->view->ListaAvisosInactivos = $modelAvisoInactivos->
                listarAvisosInactivoPorUsuario($this->_identity->IdUsuarioAnunciante);
            $countAvisosImport = count($this->view->ListaAvisosInactivos);
            if ($countAvisosImport > 0) {
                $flagAvisosImportados = 1;
            }

            if(isset($cantidadEmpresas) && $cantidadEmpresas>0){
                    $opcionDatosFacturacion = array("nombre" => "Datos de Facturación",
                        "ruta" => '/anunciante/miperfil/empresas-registradas',
                        "arbol" =>
                        array("nueva-empresa" =>
                            array("nombre" => "Nueva Empresa",
                                "ruta" => '/anunciante/miperfil/nueva-empresa'),
                            "empresas-registradas" =>
                            array("nombre" => "Empresas Registradas",
                                "ruta" => '/anunciante/miperfil/empresas-registradas')
                        )
                    );
            }else{
              $opcionDatosFacturacion = array("nombre" => "Datos de Facturación",
                "ruta" => '/anunciante/miperfil/nueva-empresa',
                "arbol" =>
                array("nueva-empresa" =>
                    array("nombre" => "Nueva Empresa",
                        "ruta" => '/anunciante/miperfil/nueva-empresa')
                )
            );  
            }

            if ($flagAvisosImportados != 1) {
                $opcionMicuenta = array("nombre" => "Mi Cuenta",
                    "ruta" => '/anunciante/micuenta/avisos-activos',
                    "arbol" =>
                    array("misAvisos" =>
                        array("nombre" => "Mis Avisos",
                            "ruta" => '/anunciante/micuenta/avisos-activos',
                            "arbol" =>
                            array("avisos-activos" =>
                                array("nombre" => "Avisos Activos",
                                    "ruta" => '/anunciante/micuenta/avisos-activos'),
                                "avisos-baja" =>
                                array("nombre" => "Avisos de Baja",
                                    "ruta" => '/anunciante/micuenta/avisos-baja'
                                )
                            )
                        )
                    )
                );
            } else {
                $opcionMicuenta = array("nombre" => "Mi Cuenta",
                    "ruta" => '/anunciante/micuenta/avisos-activos',
                    "arbol" =>
                    array("misAvisos" =>
                        array("nombre" => "Mis Avisos",
                            "ruta" => '/anunciante/micuenta/avisos-activos',
                            "arbol" =>
                            array("avisos-activos" =>
                                array("nombre" => "Avisos Activos",
                                    "ruta" => '/anunciante/micuenta/avisos-activos'),
                                "avisos-baja" =>
                                array("nombre" => "Avisos de Baja",
                                    "ruta" => '/anunciante/micuenta/avisos-baja'),
                                "avisos-importados" =>
                                array("nombre" => "Del Papel <span class='red xactive' style='font-size:0.8em;'>($countAvisosImport por activar)</span>",
                                    "ruta" => '/anunciante/micuenta/avisos-importados'
                                )
                            )
                        )
                    )
                );
            }
            $opcionMisDatos = array("nombre" => "Mis Datos",
                "ruta" => '/anunciante/miperfil/datos-personales',
                "arbol" =>
                array("datos-personales" =>
                    array("nombre" => "Datos Personales",
                        "ruta" => '/anunciante/miperfil/datos-personales'),
                    "cambio-clave" =>
                    array("nombre" => "Cambio de Clave",
                        "ruta" => '/anunciante/miperfil/cambio-clave'),
                    "mis-favoritos" =>
                    array("nombre" => "Mis Favoritos",
                        "ruta" => '/anunciante/miperfil/mis-favoritos')
                )
            );
            $usuarioAnunciante = new Application_Model_UsuarioAnunciante();
            $cantidadAvisos = $usuarioAnunciante->verificarCantidadAvisos($this->_identity->IdUsuarioAnunciante);
            if ($this->_identity->TipoUsuarioAnunciante == 1 && count($cantidadAvisos) < 2) {
                $this->_arrayAclAnunciante =
                        $arrayAcl =
                        array("miperfil" =>
                            array("nombre" => "Mi perfil",
                                "ruta" => ('/anunciante/miperfil/datos-personales'),
                                "arbol" => array(
                                    "misdatos" => $opcionMisDatos,
                                    "datosFacturacion" => $opcionDatosFacturacion,
                                )
                            ),
                            "micuenta" => $opcionMicuenta
                );
            } elseif ($this->_identity->TipoUsuarioAnunciante == 1 && count($cantidadAvisos) > 1) {
                $this->_arrayAclAnunciante =
                        $arrayAcl =
                        array("miperfil" =>
                            array("nombre" => "Mi perfil",
                                "ruta" => ('/anunciante/miperfil/datos-personales'),
                                "arbol" => array(
                                    "misdatos" => $opcionMisDatos,
                                    "datosFacturacion" => $opcionDatosFacturacion
                                )
                            ),
                            "micuenta" => $opcionMicuenta,
                            "oportunidad" =>
                            array("nombre" => "Oportunidad de Negocio",
                                "ruta" => '/anunciante/miperfil/oportunidad-de-negocio',
                                "arbol" => array(),
                            )
                );
            } elseif ($this->_identity->TipoUsuarioAnunciante == 2) {

                $this->_arrayAclAnunciante =
                        $arrayAcl =
                        array("miperfil" =>
                            array("nombre" => "Mi perfil",
                                "ruta" => ('/anunciante/miperfil/datos-personales'),
                                "arbol" => array(
                                    "misdatos" => $opcionMisDatos,
                                    "datosFacturacion" => $opcionDatosFacturacion
                                )
                            ),
                            "micuenta" => $opcionMicuenta,
                            "vendedor" =>
                            array("nombre" => "Vendedor",
                                "ruta" => '/anunciante/vendedor/inicio',
                                "arbol" => array(
                                    "inicio" =>
                                    array("nombre" => "Inicio",
                                        "class" => "inicio",
                                        "ruta" => '/anunciante/vendedor/inicio',
                                        "arbol" => ''),
                                    "misdatos" =>
                                    array("nombre" => "Mis Datos",
                                        "class" => "mis-datos",
                                        "ruta" => '/anunciante/vendedor/mis-datos',
                                        "arbol" => ''),
                                    "avisos-activos-paquete" =>
                                    array("nombre" => "Mis Avisos",
                                        "class" => "avisos-activos-paquete",
                                        "ruta" => '/anunciante/vendedor/avisos-activos-paquete',
                                        "arbol" =>
                                        array("anadir-avisos-paquete" =>
                                            array("nombre" => "Añadir Avisos",
                                                "ruta" => '/anunciante/vendedor/anadir-avisos-paquete'),
                                            "avisos-activos-paquete" =>
                                            array("nombre" => "Avisos Activos",
                                                "ruta" => '/anunciante/vendedor/avisos-activos-paquete'),
                                            "listar-avisos-baja" =>
                                            array("nombre" => "De baja",
                                                "ruta" => '/anunciante/vendedor/listar-avisos-baja')
                                        )
                                    ),
                                    "Mis Paquetes" =>
                                    array("nombre" => "Mis Paquetes",
                                        "class" => "mis-paquetes",
                                        "ruta" => '/anunciante/vendedor/mis-paquetes',
                                        "arbol" => ''
                                    ),
                                    "Nota Informativa" =>
                                    array("nombre" => "Nota Informativa",
                                        "class" => "nota-informativa",
                                        "ruta" => '/anunciante/vendedor/nota-informativa',
                                        "arbol" => ''
                                    ),
                                    "utilidades" =>
                                    array("nombre" => "Utilidades",
                                        "class" => "utilidades",
                                        "ruta" => '/anunciante/vendedor/utilidades',
                                        "arbol" => '')
                                ),
                            )
                );
            } elseif ($this->_identity->TipoUsuarioAnunciante == 3) {
                $modeloMarca = new Application_Model_Marca();
                $this->view->marca = $modeloMarca->getDatosCero0km($this->_identity->IdUsuarioAnunciante);
                $this->_arrayAclAnunciante =
                        $arrayAcl =
                        array("miperfil" =>
                            array("nombre" => "cerokm",
                                "ruta" => '/cerokm/admin/inicio',
                                "arbol" => array(
                                    "inicio" => array("nombre" => "Inicio",
                                        "class" => "inicio",
                                        "ruta" => '/cerokm/admin/inicio',
                                        "arbol" => ''),
                                    "mis-datos" => array("nombre" => "Mis Datos",
                                        "class" => "mis-datos",
                                        "ruta" => '/cerokm/admin/mis-datos',
                                        "arbol" => ''),
                                    "vehiculos" => array("nombre" => "Mis Vehículos",
                                        "class" => "mis-vehiculos",
                                        "ruta" => '/cerokm/vehiculos/mis-vehiculos',
                                        "arbol" =>
                                        array("mis-vehiculos" =>
                                            array("nombre" => "Vehículos Activos",
                                                "action" => 'mis-vehiculos',
                                                "ruta" => '/cerokm/vehiculos/mis-vehiculos')
                                        )
                                    ),
                                    "nota informativa" => array("nombre" => "Nota Informativa",
                                        "class" => "nota-informativa",
                                        "ruta" => '/cerokm/admin/nota-informativa',
                                        "arbol" => ''),
                                    "utilidades" => array("nombre" => "Utilidades",
                                        "class" => "utilidades",
                                        "ruta" => '/cerokm/admin/utilidades',
                                        "arbol" => '')
                                )
                            )
                );
            } elseif ($this->_identity->TipoUsuarioAnunciante == 4) {
                $concesionario = new Application_Model_Concesionario();
                $this->view->getConcesionario = $concesionario
                    ->concesionarioMisDatos(
                        $this->_identity->IdEnte,$this->_identity->IdUsuarioAnunciante);
                $this->_arrayAclAnunciante =
                        $arrayAcl =
                        array("miperfil" =>
                            array("nombre" => "Concesionario",
                                "ruta" => '/concesionario/admin/inicio',
                                "arbol" => array(
                                    "inicio" =>
                                    array("nombre" => "Inicio",
                                        "class" => "inicio",
                                        "ruta" => '/concesionario/admin/inicio',
                                        "arbol" => ''),
                                    "mis-datos" =>
                                    array("nombre" => "Mis Datos",
                                        "class" => "mis-datos",
                                        "ruta" => '/concesionario/admin/mis-datos',
                                        "arbol" => ''),
                                    "avisos" =>
                                    array("nombre" => "Mis Avisos",
                                        "class" => "anadir-avisos",
                                        "ruta" => '/concesionario/avisos/anadir-avisos',
                                        "arbol" =>
                                        array("anadir-avisos" =>
                                            array("nombre" => "Añadir Avisos",
                                                "action" => 'anadir-avisos',
                                                "ruta" => '/concesionario/avisos/anadir-avisos'),
                                            "avisos-activos" =>
                                            array("nombre" => "Avisos Activos",
                                                "action" => 'avisos-activos',
                                                "ruta" => '/concesionario/avisos/avisos-activos'),
                                            "avisos-baja" =>
                                            array("nombre" => "De baja",
                                                "action" => 'avisos-baja',
                                                "ruta" => '/concesionario/avisos/avisos-baja')
                                        )
                                    ),
                                    "tienda" =>
                                    array("nombre" => "Mi Tienda",
                                        "class" => "destacados",
                                        "ruta" => '/concesionario/tienda/destacados',
                                        "arbol" =>
                                        array("destacados" =>
                                            array("nombre" => "Adm. destacados",
                                                "action" => 'destacados',
                                                "ruta" => '/concesionario/tienda/destacados'),
                                            "nota-informativa" =>
                                            array("nombre" => "Nota Informativa",
                                                "action" => 'nota-informativa',
                                                "ruta" => '/concesionario/tienda/nota-informativa')
                                        )
                                    ),
                                    "Mis Paquetes" =>
                                    array("nombre" => "Mis Paquetes",
                                        "class" => "mis-paquetes",
                                        "ruta" => '/concesionario/admin/mis-paquetes',
                                        "arbol" => ''
                                    ),
                                    "utilidades" =>
                                    array("nombre" => "Utilidades",
                                        "class" => "utilidades",
                                        "ruta" => '/concesionario/admin/utilidades',
                                        "arbol" => '')
                                )
                            )
                );
            }
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
        $this->initMenuPortal();
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
        $this->initView();*/
//        $recorrido = array(
//            'nombreAction'=>$this->_request->getActionName(),
//            'nombreController'=>$this->_request->getControllerName(),
//            'nombreModuelo'=>$this->_request->getModuleName(),
//            'nombreParametros'=> $this->_request->getParams()
//                );
//        ZExtraLib_Log::err(
//                print_r($recorrido,true)
//                );
        //echo ZExtraLib_Utils::encrypt('123456');
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
        $this->initMenuPortal();
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
//            switch ($this->_request->getControllerName()) {
//                case 'admin':$this->view->menuAdminSelectd_1 = 'selected';
//                    break;
//                case 'avisos':$this->view->menuAdminSelectd_2 = 'selected';
//                    break;
//                case 'tienda':$this->view->menuAdminSelectd_3 = 'selected';
//                    break;
//                case 'utilidades':$this->view->menuAdminSelectd_4 = 'selected';
//                    break;
//            }
//        } elseif($this->_request->getModuleName() == 'concesionario'){
//            if (!$this->_identity){
//                $this->_redirect(ZExtraLib_Server::getContent()->host);
//            }
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