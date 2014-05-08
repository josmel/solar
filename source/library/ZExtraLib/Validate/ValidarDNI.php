<?php
class ZExtraLib_Validate_ValidarDNI extends Zend_Validate_Abstract{
    const MessageValidarDNI = '';
    
    protected $_messageTemplates = array(
        self::MessageValidarDNI => "El DNI '%value%' no es valido");
    function isValid($value) {
        $this->_setValue($value);
                 
        if(is_numeric($value) && strlen($value)==8){ 
            return true;
        }else{
            $this->_error(self::MessageValidarDNI);
            return false;
        }
    
    }
}

?>
