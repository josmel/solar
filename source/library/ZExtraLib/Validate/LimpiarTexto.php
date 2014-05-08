<?php

class ZExtraLib_Validate_LimpiarTexto
{
    protected $_texto = '';
    protected $_numero = '';
    protected $_separador = '';
    
    function __construct($texto,$separador,$number=true) {
        
        $this->_texto = $texto;
        $this->_numero = $number;
        $this->_separador = $separador;
        $exclude = array('el', 'la', 'los', 'las', 'esto', 'esto', 'es', 'de', 'asi', 'select', 'delete', 'from',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
            'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
            'w', 'x', 'y', 'z', 'a%C3%B1o', 'tipo', 'texto', 'precio-min', 'precio-max');

        $texto = str_replace(array("á", "é", "í", "ó", "ú", "ñ"), array("a", "e", "i", "o", "u", "n"), $texto);

        $filter = ($number) ? "/[^0-9a-zA-Z ]/i" : "/[^a-zA-Z ]/i";
        $texto = strtolower(preg_replace($filter, '-', $texto));        
        $this->arrayTexto =  array_filter(array_diff(array_unique(explode($separador, $texto)), $exclude));
    }
    
    function stringTokenBusqueda()
    {
        return "'".implode("','",$this->arrayTexto)."'";
        
    }
}
