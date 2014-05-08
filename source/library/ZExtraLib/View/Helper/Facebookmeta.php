<?php

class ZExtraLib_View_Helper_Facebookmeta
        extends Zend_View_Helper_Abstract
{
    /**
     * $dataAviso 
     * @var array 
     */
    protected $_dataAviso = array();
                        
    public function facebookmeta()
    {     
        //Zend_Debug::dump($this->view->mostrarDetalleAutoUsado['TituloAviso']);
        if(!empty($this->view->mostrarDetalleAutoUsado) &&
            isset($this->view->mostrarDetalleAutoUsado)){
            $dataAviso = $this->view->mostrarDetalleAutoUsado;
            $dataAviso['DescripcionAviso'] = !empty($dataAviso['DescripcionAviso'])?
                $dataAviso['DescripcionAviso'] : $dataAviso['TituloAviso'];
            $metafacebook = '
            <meta property="og:site_name" content="Neo Auto" />
            <meta property="og:title" content="'.
                $dataAviso['TituloAviso'].'" />
            <meta property="og:description" content="'.
                $dataAviso['DescripcionAviso'].'" />
            <meta property="og:type" content="website" />
            <meta property="og:url" content="'.
                $this->view->hostContent.'/autos-usados/'
                .$dataAviso['SlugUrl'].'-'.
                $dataAviso['UrlId'].'" />';
            $colFotos = '';
            foreach($this->view->mostrarFotos as $value){
                $colFotos .= '<meta property="og:image" content="'.
                $this->view->hostImg.$this->view->configImg['autosUsados']['rutaBase'].
                $this->view->configImg['autosUsados']['rutaExtraSmallx'].'/'.
                $dataAviso['IdUsuarioAnunciante'].'/'.$value['NombreFoto'].'" />';                
            }                                
        return $metafacebook.$colFotos ;            
        }elseif(!empty($this->view->listaAuto) && 
            isset($this->view->listaAuto)){
            $dataAviso = $this->view->listaAuto;                                    
            $metafacebook = '
            <meta property="og:site_name" content="Neo Auto" />    
            <meta property="og:title" content="'.
                $dataAviso['TituloAviso'].'" />
            <meta property="og:description" content="'.
                $dataAviso['TituloAviso'].'" />
            <meta property="og:type" content="website" />
            <meta property="og:url" content="'.
                $this->view->hostContent.'/0km/'.$this->view->slugEnte.'/'
                .$dataAviso['SlugUrl'].'-'.
                $dataAviso['UrlId'].'" />';                        
            $colFotos = '';
            foreach(explode(',',$dataAviso['Fotos']) as $value){
                $colFotos .= '<meta property="og:image" content="'.
                $this->view->hostImg.$this->view->configImg['concesionario']['rutaBase'].
                $this->view->configImg['concesionario']['rutaExtraSmallx'].'/'.
                $dataAviso['IdUsuarioAnunciante'].'/'.$value.'" />';                
            }
            return $metafacebook.$colFotos;
        }else{
            return  '' ; 
        }
    }
        
}