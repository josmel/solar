<?php

class ZExtraLib_Validate_UrlYoutube extends Zend_Validate_Abstract{
    const MessageUrlYoutubeValidator = '';
    
    protected $_messageTemplates = array(
        self::MessageUrlYoutubeValidator => "La Url '%value%' No es Valida"
    );
    function isValid($value) {
        $this->_setValue($value);
        $arrayData = ZExtraLib_Utils::validateUrlYoutube($value);
        $value = trim($value);
        if($value != '' && !$arrayData['validate']){
            $this->_error(self::MessageUrlYoutubeValidator);
            return false;
        }
        return true;
    }
}