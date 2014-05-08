<?php

class ZExtraLib_Controller_Plugin_Acl
        extends Zend_Controller_Plugin_Abstract
{

    private $_noauth = array('module' => 'default',
        'controller' => 'cuenta',
        'action' => 'login');
    private $_noacl = array('module' => 'default',
        'controller' => 'error',
        'action' => 'error');
    protected $_acl;
    protected $_role;

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->setAcl(Zend_Registry::get('Acl'));
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $user = $auth->getStorage()->read();
            $roleName = $user->IdRolUsuarioAnunciante;
        } else {
            $roleName = 1; //Visitante
        }
        $this->setRole($roleName);
        if (!$this->isValidUrl($request)) {
            $request->setModuleName($this->_noacl['module']);
            $request->setControllerName($this->_noacl['controller']);
            $request->setActionName($this->_noacl['action']);
        }
    }

    function isValidUrl(Zend_Controller_Request_Abstract $request)
    {
        $acl = $this->getAcl();
        $url1 = 'mvc:' . $request->getModuleName() . '/*';
        $url2 = 'mvc:' . $request->getModuleName() . '/' . $request->getControllerName() . '/*';
        $url3 = 'mvc:' . $request->getModuleName() . '/' . $request->getControllerName() . '/' . $request->getActionName();
        return ($acl->has($url1) && $acl->isAllowed($this->getRole(), $url1))
                || $acl->has($url2) && $acl->isAllowed($this->getRole(), $url2)
                || $acl->has($url3) && $acl->isAllowed($this->getRole(), $url3);
    }

    function getAcl()
    {
        return $this->_acl;
    }

    function getRole()
    {
        return $this->_role;
    }

    function setRole($role)
    {
        $this->_role = $role;
    }

    function setAcl($acl)
    {
        $this->_acl = $acl;
    }

}

