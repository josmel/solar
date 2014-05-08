<?php

class ZExtraLib_Paquete_ValidarNuevoAviso {

    protected $_tipoAnunciante;
    protected $_paqueteEnte;
    protected $_aviso;
    protected $_getPaqueteEnte;
    protected $_idPaqueteEnte;
    protected $_numAvisosDisponiblePorSemana;
    public $idusuario;
    /*$tipoAnunciante Revendedor/Concesionario
     * $isWeb TRUE (Valida para avisos web) / false (valida para avisos impresos)
     * $noActivar null (solo valida no activa)
     */
    public function __construct($tipoAnunciante, $idEnte, $isWeb = TRUE,$noActivar = null,$idusuario=null) {

        $this->_paqueteEnte = new Application_Model_PaqueteEnte();
        $this->_aviso = new Application_Model_Aviso();
        $this->_idEnte = $idEnte;
        $this->_numAvisosDisponiblePorSemana = '';

        if ($tipoAnunciante == 2 || $tipoAnunciante == 4) {
            $idUser= !empty($idusuario)? $idusuario : '';
            $existenPaquetesPagados = $this->_paqueteEnte->buscarPaqueteListo($idEnte,$idUser);
            if (count($existenPaquetesPagados) > 0) {
                foreach ($existenPaquetesPagados as $getPaqueteEnte) {
                    $idPaqueteEnte = $getPaqueteEnte['IdPaqueteEnte'];
                    $numeroAvisosWebPermitidos = $getPaqueteEnte['AvisoWebPaqueteEnte'];
                    $numeroAvisosImpresosPermitidos = $getPaqueteEnte['AvisoPapelFotoPaqueteEnte'];
                    $numeroAvisosImpresosUsados = $getPaqueteEnte['AvisoPapelFotoPaqueteEnte'] - $getPaqueteEnte['AvisoPapelFotoDisponible']; //$arrAvisosImpresosUsados['numeroAvisosImpresos'];
                    $this->numeroAvisosImpresosUsados = $numeroAvisosImpresosUsados;
                    $numeroAvisosWebUsados = $numeroAvisosWebPermitidos - $getPaqueteEnte['AvisoWebDisponible']; //$arrAvisosWebUsados['numeroAvisosPaqueteEnte'];
                    $this->_numeroAvisosWebUsados = $numeroAvisosWebUsados;
                    $diasDuracion = $getPaqueteEnte['SemanaDuracionPaqueteEnte'] * 7;
                    $this->_getPaqueteEnte = $getPaqueteEnte;
                    if ($getPaqueteEnte['ActivoPaqueteEnte'] == 1) {
                        //* verificar asignar aviso a paquete
                        if ($isWeb) {
                            if ($getPaqueteEnte['FlagNumAvisoWeb'] != 1) {
                                if (($numeroAvisosWebPermitidos - $numeroAvisosWebUsados) > 0) {
                                    break;
                                } else {

                                    $this->_getPaqueteEnte = 0;
                                }
                            } else {
                                break;
                            }
                        } else {
                            if ($getPaqueteEnte['FlagAvisoPapelFoto'] != 1) {

                                if (($numeroAvisosImpresosPermitidos - $numeroAvisosImpresosUsados) > 0) {
                                    break;
                                } else {
                                    $this->_getPaqueteEnte = 0;
                                }
//                                if($tipoAnunciante==2){ // Revendedor
//                                    if(($numeroAvisosImpresosPermitidos - $numeroAvisosImpresosUsados) > 0 ){ 
//                                        break;
//                                    }else{
//                                        $this->_getPaqueteEnte = 0;
//                                    }
//                                }else{ //Concesionario
//                                    $promedioImpresoSemanal = $getPaqueteEnte['PromedioSemanalAvisoImpreso'];
//                                    
//                                    $avisoImpreso = new Application_Model_AvisoImpreso();
//                                    $numAvisosUsadosSemana = $avisoImpreso->validarNumeroAvisoSemanal($this->_idEnte);                                    
//                                    if(($promedioImpresoSemanal - $numAvisosUsadosSemana)>0){                                    
//                                        $this->_numAvisosDisponiblePorSemana = ($promedioImpresoSemanal - $numAvisosUsadosSemana);
//                                        break;                                        
//                                    }else{                                    
//                                        $this->_numAvisosDisponiblePorSemana = 0 ;
//                                        $this->_getPaqueteEnte = 0;
//                                        break;
//                                    }                                                                                                  
//                                }
                            } else {
                                break;
                            }
                        }
                    } else {
                        if(empty($noActivar)){
                            $this->_getPaqueteEnte = $this->activarPaqueteEnte($idPaqueteEnte, $diasDuracion);
                        }
                        break;
                    }
                }
            } else {
                $this->_getPaqueteEnte = 0;
            }
        } else {
            $this->_getPaqueteEnte = 0;
        }
    }

    public function getPaqueteEnte() {
        return $this->_getPaqueteEnte;
    }

    public function getNumeroAvisosWebUsados() {
        return $this->_numeroAvisosWebUsados;
    }

    public function getNumeroAvisosImpresosUsados() {
        return $this->numeroAvisosImpresosUsados;
    }

    function activarPaqueteEnte($idPaqueteEnte, $dias) {
        if (!empty($idPaqueteEnte) && !empty($dias)) {
            $date = new Zend_Date();
            $horas = $dias * 24;
            $date->add($horas . ':00:00', Zend_Date::TIMES);
            $fechaFin = explode(' ', $date);
            $fechaFin2 = explode('/', $fechaFin[0]);
            $data = array();
            $data['ActivoPaqueteEnte'] = 1;
            $data['FechaInicioPaqueteEnte'] = date('Y-m-d H:i:s');
            $data['FechaFinPaqueteEnte'] = $fechaFin2[2] . '-' . $fechaFin2[1] . '-' . $fechaFin2[0] . ' ' . $fechaFin[1];
            $this->_paqueteEnte->actualizarPaqueteEnte($idPaqueteEnte, $data);
        }
        return $this->_paqueteEnte->getPaqueteEnte($idPaqueteEnte);
    }

    function getNumeroAvisosDisponiblePorSemana() {
        return $this->_numAvisosDisponiblePorSemana;
    }

}
