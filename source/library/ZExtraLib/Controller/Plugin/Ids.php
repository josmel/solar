<?php

class ZExtraLib_Controller_Plugin_Ids
        extends Zend_Controller_Plugin_Abstract
{

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {

        require_once 'IDS/Init.php';
        $request = array('REQUEST' => $_REQUEST, 'GET' => $_GET, 'POST' => $_POST, 'COOKIE' => $_COOKIE);
        $config= new Zend_Config_Ini(APPLICATION_PATH.'/configs/ids.ini');
        //$init = IDS_Init::init(APPLICATION_PATH . '/../library/IDS/Config/Config.ini.php');
        $init = IDS_Init::init(APPLICATION_PATH.'/configs/ids.ini');
        $ids = new IDS_Monitor($request, $init);
        $result = $ids->run();
        if (!$result->isEmpty()) {
            // This is where you should put some code that
            // deals with potential attacks, e.g. throwing
            // an exception, logging the attack, etc.
            echo $result;
        }
        return $request;
    }

}

