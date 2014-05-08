<?php

class ZExtraLib_Filter_RangoAnos extends Zend_Filter
{
    
    public function filter($rangos)
    {
        $valid = array();
        $v = new ZExtraLib_Validate_RangoAnos();
        foreach($rangos as $rango){
            if($v->isValid($rango)){
                $valid[] = $rango;
            }
        }
        return $valid;
    }

}
