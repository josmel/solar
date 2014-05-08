<?php
class ZExtraLib_RegistroEmpresaUsuarioAnunciante {
    protected $_mensaje;
    protected $_conEnte;
    protected $_idEnte;
    protected $_nombreEnte;
    protected $_nroDocumento;
    public function __construct($idUsuario,$nombreEmpresa,$direccion,$ruc,$tipoDocumento= NULL,$arrayDatos = NULL) {
        
        $Numero_Documento = $ruc;
        $Ape_Paterno = $arrayDatos==null?$arrayDatos['apellidoPaterno']:'';
        $Ape_Materno = $arrayDatos==null?$arrayDatos['apellidoMaterno']:'';
        $PreNombre = $arrayDatos==null?$arrayDatos['nombre']:'';
        $Nombre_RznComc = $nombreEmpresa;           
        $NombreCalle = $direccion;
        $tipDoc = $tipoDocumento!=''? '2' :'1';
        
        // Buscar empresa en adecsys
        $strTipoDoc = $tipDoc==1? 'DNI':'RUC';
        $EnteWs = $this->buscarEmpresaWS($strTipoDoc, $Numero_Documento);
        
        
        if ($EnteWs =='') { // Ente no registrado en adecsys, debe registrarse
            $arrayNuevoEnte = array(
                'Tipo_Documento' => $strTipoDoc,
                'Numero_Documento'=>$Numero_Documento,
                'Ape_Paterno' => $Ape_Paterno,
                'Ape_Materno' => $Ape_Materno,
                'Nombres_RznSocial' => $Nombre_RznComc,
                'Nombre_RznComc' => $Nombre_RznComc,
                'Nombre_Calle' => $NombreCalle                
            );
            
            $CodEnteWs = $this->registrarEmpresaWS($arrayNuevoEnte);
            
            // Enviar mensaje de registro de ente
            $dataCorreo = array('[NroDocumento]'=>$ruc,
                                                      '[RzSocial]'=>$nombreEmpresa, 
                                                      '[Direccion]'=>$direccion,
                                                      '[FechaRegistro]'=>date('d-m-Y'),
                                                      '[codAdecsys]'=>$CodEnteWs,
                                                      '[fecha]'=>date('Y')
                                                     );
                                  $this->enviarCorreo($dataCorreo);
                                  
            $EnteWs = $this->buscarEmpresaWS($strTipoDoc, $Numero_Documento); // Recuperar toda la data
            
        }
        
        // Buscamos ente en neoauto
        $modelEnte = new Application_Model_Ente();
        $EnteDb = $modelEnte->getEntexDoc($tipDoc, $ruc);
        
        try {
            $db = ZExtraLib_Server::getDb('process');
            $db->beginTransaction();
            
            if($EnteDb == ''){ // Si no existe el ente en neoauto, se registra

                $filter = new ZExtraLib_Filter_SeoUrl();
                $arrayNuevoEnteDb = array(
                        'NombreEnte' => $EnteWs->RznSoc_Nombre,
                        'DireccionEnte' => $EnteWs->Nom_Calle,
                        'IdTipoDocumento' => $tipDoc,
                        'NroDocumento' => $EnteWs->Num_Doc,
                        'IdUsuarioAnunciante' => $idUsuario,
                        'FechaEnte' => date("Y-m-d"),
                        'ApellidoPaterno' => $EnteWs->Ape_Pat,
                        'ApellidoMaterno' => $EnteWs->Ape_Mat,
                        'PreNombre' => $EnteWs->Pre_Nom,
                        'RazonSocialComercial' => $EnteWs->RznSoc_Nombre,
                        'TipoPersona' => $EnteWs->Tip_Per,
                        'Email' => $EnteWs->Email,
                        'Telefono' => $EnteWs->Telf,
                        'Ciudad' => $EnteWs->Ciudad,
                        'TipoCentroPoblado' => $EnteWs->Tip_Cen_Pob,
                        'TipoCalle' => $EnteWs->Tip_Calle,
                        'NombreCalle' => $EnteWs->Nom_Calle,
                        'NumeroPuerta' => $EnteWs->Num_Pta,
                        'EstadoActual' => $EnteWs->Est_Act,
                        'CodDireccion' => $EnteWs->Cod_Direccion,
                        'SlugEnte' => $filter->urlFriendly($EnteWs->RznSoc_Nombre, '-', 0),
                        'IdAdecsys' => $EnteWs->Id,
                        'NombreComercial' => $EnteWs->RznCom
                    );

                    $EnteDbId = $modelEnte->registrarEnte($arrayNuevoEnteDb);
                    
                    $this->_mensaje = 'El perfil ha sido guardado.';

            } else{// Actualizar ente en neoauto

                $arrayEnte = array(
                        'ApellidoPaterno' => $EnteWs->Ape_Pat,
                        'ApellidoMaterno' => $EnteWs->Ape_Mat,
                        'PreNombre' => $EnteWs->Pre_Nom,
                        'RazonSocialComercial' => $EnteWs->RznSoc_Nombre,
                        'TipoPersona' => $EnteWs->Tip_Per,
                        'Telefono' => $EnteWs->Telf,
                        'Ciudad' => $EnteWs->Ciudad,
                        'TipoCentroPoblado' => $EnteWs->Tip_Cen_Pob,
                        'TipoCalle' => $EnteWs->Tip_Calle,
                        'NombreCalle' => $EnteWs->Nom_Calle,
                        'NumeroPuerta' => $EnteWs->Num_Pta,
                        'EstadoActual' => $EnteWs->Est_Act,
                        'CodDireccion' => $EnteWs->Cod_Direccion,
                        'IdAdecsys' => $EnteWs->Id
                    );
                $EnteDbId = $EnteDb['IdEnte'];
                $modelEnte->actualizarEnte($EnteDbId,$arrayEnte);
            }

            // Buscamos la asociacion del usuario con la empresa
            // Si la empresa ya está asociada al usuario, retorna
            // Si la empresa no está asociada al usuario, se asocia
            
            
            $this->_codEnte = $EnteWs->Id;
            $this->_nombreEnte = $EnteWs->Pre_Nom;
            $this->_nroDocumento = $EnteWs->Num_Doc;
            $this->_idEnte = $EnteDbId;
            
            $modelEmpresa = new Application_Model_Empresa();
            
            if($modelEmpresa->registrarIntermediaria($idUsuario, $EnteDbId)){
                $this->_mensaje = 'El perfil ha sido guardado.';
            } else {
                $this->_mensaje = 'Ya se encuentra asociado a esta empresa.';
            }
            
            $db->commit();
            
        } catch (Exception $e) {
                    $db->rollBack();
                    ZExtraLib_Log::err($e->getMessage().
                            '__________ Error al Insertar una Empresa:'.print_r(func_get_args(), true));
                    $this->_mensaje = 'Error al registrar la Empresa.';

        }
        
    }
    function buscarEmpresaWS($tipoDoc, $numDoc) {
        try {
            $frontController = Zend_Controller_Front::getInstance();
            $uriEnc = $frontController->getParam('bootstrap')->getOption('Adecsys');
            $client = new Zend_Soap_Client($uriEnc['empresa']['wsEmpresa']);
            $array=array('Tipo_Documento' => $tipoDoc,'Numero_Documento' => $numDoc);
            $response=$client->Validar_Cliente($array);
            if(isset($response->Validar_ClienteResult))
                return $response->Validar_ClienteResult;
            else
                return '';
        }        
        catch (Exception $e) {
                     return '';
            }
    }
    
    function registrarEmpresaWS($arrayDatosNuevoEnte)
    {
        try {
            $frontController = Zend_Controller_Front::getInstance();
            $uriEnc = $frontController->getParam('bootstrap')->getOption('Adecsys');
            $client = new Zend_Soap_Client($uriEnc['empresa']['wsEmpresa']);  
            
            $arrayRegistroEnte=array(                
                'Tipo_Documento'=> $arrayDatosNuevoEnte['Tipo_Documento'],
                'Numero_Documento' => $arrayDatosNuevoEnte['Numero_Documento'],
                'Ape_Paterno' => strtoupper($arrayDatosNuevoEnte['Ape_Paterno']),
                'Ape_Materno' => strtoupper($arrayDatosNuevoEnte['Ape_Materno']),
                'Nombres_RznSocial' => strtoupper($arrayDatosNuevoEnte['Nombres_RznSocial']),
                'Email' => '',
                'Telefono' => '',
                'Tipo_Cen_Poblado' => '',
                'Nombre_Cen_Poblado' => '',
                'Tipo_Calle' => 'CA',
                'Nombre_Calle' => $arrayDatosNuevoEnte['Nombre_Calle'],
                'Numero_Puerta' => '',
                'CodCiudad' => '1',
                'Nombre_RznComc'  => strtoupper($arrayDatosNuevoEnte['Nombre_RznComc']));
            
            $response=$client->Registrar_Cliente($arrayRegistroEnte); 
            
            if($response->Registrar_ClienteResult!=''){
                return $response->Registrar_ClienteResult;
                
            }else{
                return '';
            }
            
        } catch (Exception $e) {
                ZExtraLib_Log::err($e->getMessage().
                        '__________ Error al Insertar en el web service :'.print_r($e, true));
        }
    }
    
    function getMessage() 
    {
        return $this->_mensaje;
    }
    function getConEnte()
    {
        return $this->_codEnte;
    }
    function getIdEnte()
    {
        return $this->_idEnte;
    }
    function getNombreEnte()
    {
        return $this->_nombreEnte;
    }
    function getNroDocumento()
    {
        return $this->_nroDocumento;
    }
    
    function enviarCorreo($dataCorreo)
    {
        $template = new ZExtraLib_Template();
        try {
                                                                              
            $textoCorreo = $template->load('RegistrarEnte',$dataCorreo);
            $correo = Zend_Registry::get('mail');
            $correoUsuario = new Application_Model_UsuarioCorreo();
            $usuarioCorreo = $correoUsuario->listarUsuarioCorreo(7);
            foreach ($usuarioCorreo as $index):
                $correoAdmin[] = $index['CorreoUsuarioAdmin'];
            endforeach;
            $correo->addTo($correoAdmin)
                ->clearSubject()
                ->setSubject('Registro de nuevo Ente - Neoauto')
                ->setBodyHtml($textoCorreo);
            $correo->send();
        } catch (Exception $exc) {
            ZExtraLib_Log::err('Registrar nuevo Ente Adecsys'. $exc->__toString(),' --- ' . print_r($correoAdmin));            
        } 
    }
}