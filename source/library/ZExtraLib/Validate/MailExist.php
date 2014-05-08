<?php

class ZExtraLib_Validate_MailExist extends Zend_Validate_Abstract{
    const MessageEmailValidator = '';
    
    protected $_messageTemplates = array(
        self::MessageEmailValidator => "El correo '%value%' Se encuentra registrado por otro usuario"
    );
    function isValid($value) {
        $this->_setValue($value);
        $user = new Application_Model_UsuarioAnunciante();
        if(($user->verificarEmailUsuario($value))){
            $this->_error(self::MessageEmailValidator);
            return false;
        }
        return true;
    }
}