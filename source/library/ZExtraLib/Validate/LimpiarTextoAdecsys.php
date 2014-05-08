<?php

class ZExtraLib_Validate_LimpiarTextoAdecsys
{
    protected $_texto = '';
    protected $_numero = '';
    protected $_separador = '';
    
    function __construct($texto,$separador,$number=true) {
        
        $this->_texto = $texto;
        $this->_numero = $number;
        $this->_separador = $separador;
        $exclude = array('%C3%B1',' ',',');

        $texto = str_replace(array("á", "é", "í", "ó", "ú", "ñ"), array("a", "e", "i", "o", "u", "n"), $texto);

        $filter = ($number) ? "/[^0-9a-zA-Z ]/i" : "/[^a-zA-Z ]/i";
        echo $texto = strtolower(preg_replace($filter, '-', $texto));        
        $this->arrayTexto =  array_filter(array_diff(array_unique(explode($separador, $texto)), $exclude));
    }
    
    function stringTokenBusqueda()
    {
        return implode("-",$this->arrayTexto);
        
    }
}
