<?php
class ZExtraLib_Validate_ValidarRUC extends Zend_Validate_Abstract{
    const MessageValidarRUC = '';
    
    protected $_messageTemplates = array(
        self::MessageValidarRUC => "El RUC '%value%' no es valido"
    );
    function isValid($value) {
        $this->_setValue($value);
                 
        $factor = "5432765432";
        $ruc = trim($value);

        if ((!is_numeric($ruc)) || (strlen($ruc) != 11)) {
            return false;
        }
        $dig_valid= array("10", "20" ,"17", "15");
        $dig=substr($ruc, 0, 2);
        if (!in_array($dig, $dig_valid, true)) {
            $this->_error(self::MessageValidarRUC);
            return false;
        }
        
        $dig_verif = substr($ruc, 10, 1);

        for ($i = 0; $i < 10; $i++) {
            $arr[] = substr($ruc, $i, 1) * substr($factor, $i, 1);
        }

        $suma = 0;
        foreach ($arr as $a) {
            $suma = $suma + $a;
        }
        
    //Calculamos el residuo
        $residuo = $suma%11;              
        $resta = 11 - $residuo;
        $dig_verif_aux = substr($resta, -1);
        if ($dig_verif == $dig_verif_aux) {
            return true;
        } else {
            $this->_error(self::MessageValidarRUC);
            return false;
        }
        
        
        
    }
}

?>
