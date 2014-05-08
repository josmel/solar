<?php

/**
 * shortDescription
 *
 * longDescription
 *
 * @category   category
 * @package    package
 * @subpackage subpackage
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer archivo LICENSE
 * @version    Release: @package_version@
 */
class ZExtraLib_Utils
{
    const key='feRr=sp5vuda8ePrubudra2reBequrRr';
    protected $_filterSEO;
    /**
     * description
     * @param paramType paramName paramDescription
     * @uses class::name()
     * @return returnType returnDescription
     */
    static function arrayToObject($array)
    {
        if (is_array($array)) {
            return (object) array_map(array('ZExtraLib_Utils', 'arrayToObject'), $array);
        } else {
            return $array;
        }
    }

    /**
     * description
     * @param paramType paramName paramDescription
     * @uses class::name()
     * @return returnType returnDescription
     */
    static function toSEO($url)
    {
        if (!isset($this->_filterSEO)) {
            $this->_filterSEO = new ZExtraLib_Filter_Alnum();
        }
        return $this->_filterSEO->filter(trim($url), '-');
    }

    /**
     * Funcón para encritar una cadena
     * @param string $msg Cadena a encriptar
     * @uses ZExtraLib_Utils::encrypt()
     * @return string Cadena encriptada
     */
    static function encrypt($rawPassword,$algo='sha1')
    {                          # return iv+ciphertext+mac
        /*return hash('sha256', $rawPassword, false);*/
        
        $salt = substr(md5(rand(0, 999999) + time()), 6, 5);
        $passw = '';

        if ($algo == 'sha1') {
            $passw = $algo . '$' . $salt . '$' . sha1($salt . $rawPassword);
        } else {
            $passw = $algo . '$' . $salt . '$' . md5($salt . $rawPassword);
        }
       // ZExtraLib_Log::err('password : ->'.$passw);
        return $passw;
        
    }

    /**
     * Genera un código Hash
     * @param boolean $returnLast Indica si desea capturar el ultimo hash generado
     * @return string Codigo Hash
     */
    static function hashCode($returnLast=false)
    {
        $session = new Zend_Session_Namespace('HashCode');
        if (!$returnLast) {
            $session->hashCode = md5(time());
        }
        return $session->hashCode;
    }

    static function to32($cant)
    {
        $q = 10000000 + $cant;
        $alfabet = '4agf2hkve3prq7stu9jmnyzwx5b6d8c';
        $l = strlen($alfabet);
        $splitAlfabet = str_split($alfabet);
        $dataResult = array();
        while ($q != 0) {
            $r = $q % $l;
            $q = intval($q / $l);
            $dataResult[] = $splitAlfabet[$r];
        }
        $dataResultString = "";
        krsort($dataResult);
        foreach ($dataResult as $index)
            $dataResultString = $dataResultString . $index;
        return $dataResultString;
    }

    static function createFileImagenPersonaNatural($idUsr)
    {
        $file = ZExtraLib_Server::getFile();
        //print_r($file);
        $ftp = new ZExtraLib_UploadFtpImgServer();
        $ftp->connect();
        //print_r($file);
        $rutasExtras = $file->upload['autosUsados']['rutaExtra'];
        $rutaRemota = $file->upload['fileBase'] .
                $file->upload['autosUsados']['rutaBase'] .
                $file->upload['autosUsados']['rutaOrigin'];
        $ftp->newDirectory($rutaRemota, $idUsr);
        $rutasExtras = explode(',', $rutasExtras);
        foreach ($rutasExtras as $index) {

            $index2 = explode('-', $index);
            $rutaFile = $index2[0];
            $rutaRemota = $file->upload['fileBase'] .
                    $file->upload['autosUsados']['rutaBase'] . '/' .
                    $rutaFile;
            $ftp->newDirectory($rutaRemota, $idUsr);
        }
    }
     
    static function extractTag($words,
            $number=true)
    {

        $_exclude = array('el', 'la', 'los', 'las', 'esto', 'esto', 'es', 'de', 'asi',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
            'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
            'w', 'x', 'y', 'z', 'tipo', 'marca', 'modelo','select','update','delete');

        $words = str_replace(array("á", "é", "í", "ó", "ú", "ñ"), array("a", "e", "i", "o", "u", "n"), $words);
        $filter = ($number)
                ? "/[^0-9a-zA-Z ]/i"
                : "/[^a-zA-Z ]/i";
        $words = strtolower(preg_replace($filter, '', $words));
        return array_diff(array_unique(explode(' ', $words)), $_exclude);
    }

    static function redondear_dos_decimal($valor)
    {
        $float_redondeado = round($valor * 100) / 100;
        return $float_redondeado;
    }

    static function getLocate()
    {
        $locale = new Zend_Locale('es_PE');
        return $locale;
    }

    static function convertUrlQuery($query)
    {
        if($query!=''){
        $queryParts = explode('&', $query);
        $params = array();
        foreach ($queryParts as $param) {
            $item = explode('=', $param);
            $params[$item[0]] = $item[1];
        }
        return $params;}
    }

    static function validateUrlYoutube($uri='')
    {
        $arrayReturn = array();
        if($uri==''){
            $arrayReturn['validate']=false;
            return $arrayReturn;
        }
        $array = parse_url($uri);
        if(isset($array['query']))
        $array = self::convertUrlQuery($array['query']);
        
        $yt = new Zend_Gdata_YouTube();
        $objUri = Zend_Uri::factory($uri);
        
        $arrayReturn['validate'] = TRUE;
        if ($objUri->valid() && strpos($uri,"http://www.youtube.com/watch?v=")===0) {
            try {
                $videoEntry = $yt->getVideoEntry($array['v']);
                $arrayReturn['Video'] = $videoEntry->getVideoTitle();
                $arrayReturn['Video_ID'] = $videoEntry->getVideoId();
                $arrayReturn['Updated'] = $videoEntry->getUpdated();
                $arrayReturn['Description'] = $videoEntry->getVideoDescription();
                $arrayReturn['Category'] = $videoEntry->getVideoCategory();
                $arrayReturn['Tags'] = implode(", ", $videoEntry->getVideoTags());
                $arrayReturn['Watch_page'] = $videoEntry->getVideoWatchPageUrl();
                $arrayReturn['Flash_Player_Url'] = $videoEntry->getFlashPlayerUrl();
                $arrayReturn['Duration'] = $videoEntry->getVideoDuration();
                $arrayReturn['View_count'] = $videoEntry->getVideoViewCount();
                $arrayReturn['message'] = $videoEntry->getVideoViewCount();
            } catch (Exception $e) {
                $arrayReturn['validate'] = FALSE;
                $arrayReturn['message'] = $e->getMessage();
            }
        } else {
            $arrayReturn['validate'] = FALSE;
            $arrayReturn['message'] = 'La ruta no es valida';
        }

        return $arrayReturn;
    }
    static function  generaClave($lengCadena) {
        $minuscula = 'abcdefghijklnmopqrstuvwxyz';
        $mayuscula = strtoupper($minuscula);
        $numero = '1234567890';
        $arrayLetras = array(str_split($minuscula),str_split($mayuscula),str_split($numero));
        $letra ='';
        for($i=0; $i<=$lengCadena;$i++){
        $index = rand(0,2);
        $index2 = rand(0,count($arrayLetras[$index]));
        $letra = $letra.$arrayLetras[$index][$index2];
        }
        return $letra;
    }
    
    static function getIdentityTemp ()
    {
            //ZExtraLib_Utils::consoleLog('getIdentityTemp');
            $space = new Zend_Session_Namespace('Neoauto');		
            if (!isset($space->_identityTemp)){			
                    $space->_identityTemp = Zend_Auth::getInstance()->getIdentity();
            //	ZExtraLib_Utils::consoleLog('Asignar : ' . json_encode($space->_identityTemp));
            }
            //ZExtraLib_Utils::consoleLog('getIdentityTemp');
            return $space->_identityTemp;
    } //end function setIdentityTemp
    
    static function consoleLog ($string)
    { 
            echo "<script>console.log('" . $string . "');</script>";
    } //end function consoleLog
    
    /**
     * Consider the following production envs:
     * pre, preproduction, production
     * @return boolean
     */
    static public function isDevEnv() 
    {
        if (APPLICATION_ENV == 'pre' 
           || APPLICATION_ENV == 'preproduction'
           || APPLICATION_ENV == 'production'
        ) {
            return false;
        }
        
        return true;
    }
}