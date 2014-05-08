<?php
class ZExtraLib_Filter_ExtracString
{
    /**
     * Extrae una porcion de una cadena 
     * @param string $string texto total
     * @param string $strIni texto inicial
     * @param strgng $strFin texto final
     * @return string 
     */
    static public function  extrac($string,$strIni,$strFin)
    {                      
        $cadena = str_replace($strIni,'',trim($string));        
        $total = strpos($cadena,$strFin);                                
        return substr($cadena,0,($total)); 
    }
    
    static public function extractImg($string)
    {             
       $img=substr($string,strpos($string, 'src="')) ;
       $tot=strpos($img,'alt');
       $imagen=str_replace('src="','',substr($img,0,-(strlen($img)-$tot)));
       return substr($imagen,0,-(strlen($imagen)-strpos($imagen,'"')));                     
    }
}