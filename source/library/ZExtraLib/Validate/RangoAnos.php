<?php
class ZExtraLib_Validate_RangoAnos extends Zend_Validate_Abstract{
    
    const INVALID1 = 'invalid1';
    const INVALID2 = 'invalid2';
    const INVALID3 = 'invalid3';
    const INVALID4 = 'invalid4';
    
    protected $_messageTemplates = array(
        self::INVALID1 => "Rango inválido 1",
        self::INVALID2 => "Rango inválido 2",
        self::INVALID3 => "Rango inválido 3",
        self::INVALID4 => "Rango inválido 4"
    );
    
    
    function isValid($value) {
        $this->_setValue($value);
        $parts = explode('-', $value);
                 
        if(count($parts)!=2){ 
            $this->_error(self::INVALID1);
            return false;
        }
        foreach($parts as $anyo){
            $anyo = trim($anyo);
            if( !is_numeric($anyo) || $anyo < 1800 || $anyo > date('Y')+1 ){
                $this->_error(self::INVALID2);
                return false;
            }
        }
        
        if(trim($parts[1])>=trim($parts[0])){
            $this->_error(self::INVALID3);
            return false;
        }
        
        return true;
        
    
    }
}