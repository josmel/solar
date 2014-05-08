<?php

class Core_Controller_ActionDefault extends Core_Controller_Action {


    public function init() {
        parent::init();
    }
    
    public function preDispatch() {
        parent::preDispatch();
        $navigatorMain = new Zend_Navigation($this->getNavMain());
        
        $navigatorMain->findAllBy('controller', $this->getRequest()->getControllerName());
        Zend_Registry::set('navigationMais',$navigatorMain);
       
    }
    
    
    public function getNavMain(){
        $pages = array(
        array(
            'label' => '¿QUIENES SOMOS?',
            'module' => 'default',
            'class' => 'first',
            'controller' => 'quienes-somos',
        ),
        array(
            'label' => 'NUESTRA LABOR',
            'module' => 'default',
            'controller' => 'nuestra-labor',
            'action' => 'index',
        ),
        array(
            'label' => 'COLABORA',
            'module' => 'default',
            'controller' => 'colabora',
            'action' => 'index',
        ),
        array(
            'label' => 'UNETE',
            'module' => 'default',
            'controller' => 'unete',
            'action' => 'index',
        ),
        array(
            'label' => 'SALA DE PRENSA',
            'module' => 'default',
            'controller' => 'sala-de-prensa',
            'action' => 'index',
        ),
        array(
            'label' => 'DESCARGA',
            'module' => 'default',
            'controller' => 'descarga',
            'action' => 'index',
        ),
        array(
            'label' => 'VOLUNTARIADO',
            'module' => 'default',
            'controller' => 'voluntariado',
            'action' => 'index',
        ),        
    );
 
return $pages;
    }
}