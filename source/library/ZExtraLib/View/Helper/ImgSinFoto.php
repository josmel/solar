<?php

class ZExtraLib_View_Helper_ImgSinFoto
        extends Zend_View_Helper_Abstract
{
    function ImgSinFoto($img)
    {
        $fp = @fopen($img, "rb");
        if ($fp) {
            fclose($fp);
            $result = true;
        } else {
            
            $result = false ;
        }
        @fclose($fp);
        //return $img;
        return $result;
    }

}