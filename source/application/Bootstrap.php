<?php


class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initView()
    {
        
        
        $this->bootstrap('layout');        
        $layout = $this->getResource('layout');
        $v = $layout->getView();

        $v->addHelperPath('Core/View/Helper', 'Core_View_Helper');
        $config = Zend_Registry::get('config');
        
        $this->getResourceLoader()->addResourceType('entity', 'entitys/', 'Entity');
        
        //Definiendo Constante para Partials
        defined('STATIC_URL')
            || define('STATIC_URL', $config['app']['staticUrl']);
        defined('DINAMIC_URL')
            || define('DINAMIC_URL', $config['app']['dinamicUrl']);

        defined('SITE_URL')
            || define('SITE_URL', $config['app']['siteUrl']);
        defined('SITE_TEMP')
            || define('SITE_TEMP',$config['app']['elementTemp']);

        defined('ROOT_IMG_DINAMIC')
            || define('ROOT_IMG_DINAMIC',$config['app']['rootImgDinamic']);
                
        $doctypeHelper = new Zend_View_Helper_Doctype();                
        $doctypeHelper->doctype(Zend_View_Helper_Doctype::XHTML1_STRICT);
        $v->headTitle($config['resources']['view']['title'])->setSeparator(' | ');
        $v->headMeta()->appendHttpEquiv('Content-Type',
            'text/html; charset=utf-8');
        $v->headMeta()->appendName("author", "onlineproduction");
        $v->headMeta()->setName("language", "es");
        $v->headMeta()->appendName("description",
            "managent aplication");
        $v->headMeta()->appendName("keywords",
            "ayuda.");        

//        $v->headLink(array(
//            'rel' => 'shortcut icon', 'href' => $v->S('/imagenes/favicon.ico')
//            )
//        );        
        $js = sprintf("var urls = {siteUrl : '%s'}", $config['app']['siteUrl']);
        $v->headScript()->appendScript($js);
        $v->headLink()->appendAlternate($v->S('/humans.txt'), 'text/plain',
            'author', '');      
        
        
    }

    public function _initRegistries()
    {        

        $this->bootstrap('multidb');
        $db = $this->getPluginResource('multidb')->getDb('db');
        Zend_Db_Table::setDefaultAdapter($db);
        //$multidb = $this->getPluginResource('multidb');
        Zend_Registry::set('multidb', $db);
       //Zend_Debug::dump($db); exit;
         $this->bootstrap('cachemanager');
         $cache = $this->getResource('cachemanager')->getCache('file');
         
         Zend_Registry::set('cache', $cache);         
//        $this->_executeResource('log');
//        $log = $this->getResource('log');
//        Zend_Registry::set('log', $log);
    }

    public function _initActionHelpers()
    {
//        Zend_Controller_Action_HelperBroker::addHelper(
//            new Core_Controller_Action_Helper_Auth()
//        );
//        Zend_Controller_Action_HelperBroker::addHelper(
//            new App_Controller_Action_Helper_Security()
//        );
        Zend_Controller_Action_HelperBroker::addHelper(
            new Core_Controller_Action_Helper_Mail()
        );
        
    }


}

