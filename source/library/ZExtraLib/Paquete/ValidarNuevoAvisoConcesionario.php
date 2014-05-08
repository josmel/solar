<?php

class ZExtraLib_Paquete_ValidarNuevoAvisoConcesionario {

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
    public function __construct($idConcesionario, $idTipoVendedor) {

        $this->_paqueteEnte = new Application_Model_PaqueteEnte();
        $this->_aviso = new Application_Model_Aviso();
        
        $this->paqueteWebDisponible='';
        $this->paqueteImpresoDisponible = '';
        

        $paquetesListos = $this->_paqueteEnte->getPaquetesListosConcesionario($idConcesionario, $idTipoVendedor);
        
        if (count($paquetesListos) > 0) {
            
            foreach ($paquetesListos as $getPaqueteEnte) {
                                
                if(!empty($getPaqueteEnte['AvisoWebDisponible']) && empty($this->paqueteWebDisponible)) {
                    $this->paqueteWebDisponible=$getPaqueteEnte;
                    
                }
                if(!empty($getPaqueteEnte['AvisoPapelFotoDisponible']) && empty($this->paqueteImpresoDisponible)){
                    $this->paqueteImpresoDisponible=$getPaqueteEnte;
                    
                }
                
                if(!empty($this->paqueteWebDisponible) && !empty($this->paqueteImpresoDisponible)){
                    break;
                }
            }
            
        }
    }


    public function getPaqueteWebDisponible() {
        return $this->paqueteWebDisponible;
    }

    public function getPaqueteImpresoDisponible() {
        return $this->paqueteImpresoDisponible;
    }
    
    public function activarWebPaqueteEnte(){
        if($this->paqueteWebDisponible['ActivoPaqueteEnte']!=1){
            $weeks = $this->paqueteWebDisponible['SemanaDuracionPaqueteEnte'];
            $data['ActivoPaqueteEnte'] = 1;
            $data['FechaInicioPaqueteEnte'] = date('Y-m-d H:i:s');
            $data['FechaFinPaqueteEnte'] = date("Y-m-d ", strtotime(date("m/d/Y")." +".$weeks ." week"));
            $this->_paqueteEnte->actualizarPaqueteEnte($this->paqueteWebDisponible['IdPaqueteEnte'], $data);
        }
    }
    public function activarImpresoPaqueteEnte(){
        if($this->paqueteImpresoDisponible['ActivoPaqueteEnte']!=1){
            $weeks = $this->paqueteImpresoDisponible['SemanaDuracionPaqueteEnte'];
            $data['ActivoPaqueteEnte'] = 1;
            $data['FechaInicioPaqueteEnte'] = date('Y-m-d H:i:s');
            $data['FechaFinPaqueteEnte'] = date("Y-m-d ", strtotime(date("m/d/Y")." +".$weeks ." week"));
            $this->_paqueteEnte->actualizarPaqueteEnte($this->paqueteImpresoDisponible['IdPaqueteEnte'], $data);
        }
    }

    


}
