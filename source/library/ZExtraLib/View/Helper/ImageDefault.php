<?php

class ZExtraLib_View_Helper_ImageDefault
        extends Zend_View_Helper_Abstract
{
    function ImageDefault($img,$dimension='',$varIndex = null)
    {        
        $arrayDimension = array(
            '86x85',
            '640x1000',
            '188x142',
            '110x60',
            '434x195',
            '138x98',
            '400x300',
            '800x600',
            '60x70',
            '86x61',
            '100x100',
            '170x126',
            '371x280',
            '288x216',
            '30x18',
            '120x50',
            '140x70',
            '144x108',
            
            '176x132',            
            '168x126',
            '465x320');
        $fp = @fopen($img, "rb");
        
        if ($fp) {
            fclose($fp);
            $result = $img;
        } else {
            if (!in_array($dimension, $arrayDimension)) {
                $dimension = $arrayDimension[2];
            }
            if(!isset($varIndex) and empty($varIndex)){
                $result = ZExtraLib_Server::getFile(0)
                    ->host . '/defaultImg/' . $dimension . '.jpg';
            }else{
                $result = ZExtraLib_Server::getFile(0)
                    ->host . '/defaultImg/default.gif';
            }
        }
        @fclose($fp);
        //return $img;                
        return $result;
    }
    
}