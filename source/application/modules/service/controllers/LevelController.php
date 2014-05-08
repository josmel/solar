<?php

class Service_LevelController extends Core_Controller_ActionService {

    protected $_config;

    public function init() {
        $this->_helper->layout()->disableLayout();
        $this->_config = $this->getConfig();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function getConfig() {
        return Zend_Registry::get('config');
    }

    public function insertLevelAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $rpta = array();
        if ($this->_request->isPost()) {
            try {
                $category = $this->_getParam('category', 0);
                $level = $this->_getParam('level', 0);
                $idUser = $this->_getParam('idUser', 0);
                if (!empty($category) && !empty($idUser)) {
                    $objUser = new Application_Model_Level();
                    $result = $objUser->getOneUserLevel($idUser, $category);
                    $params['idLevel'] = $result['idLevel'];
                    $obj = new Application_Entity_RunSql('Level');
                    $params['idUser'] = $idUser;
                    $params['category'] = $category;
                    $params['level'] = $level;
                    if (empty($params['idLevel'])) {
                        $obj->save = $params;
                        $id = $obj->save;
                        $rpta = array('msj' => 'ok', 'id' => $id);
                    } else {
                        $obj->edit = $params;
                        $rpta = array('msj' => 'nivel actualizado');
                    }
                    $cod = 200;
                } else {
                    $cod = 200;
                    $rpta['msj'] = 'missing parameters';
                }
            } catch (Exception $e) {
                $cod = 500;
                $rpta = $e->getMessage();
            }
        } else {
            $cod = 401;
            $rpta = array('msj' => 'no authorized, method not post');
        }
        $this->getResponse()
                ->setHttpResponseCode($cod)
                ->setHeader('Content-type', 'application/json;charset=UTF-8', true)
                ->appendBody(json_encode($rpta));
    }

    
    
    
    public function getCategoryUserAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $rpta = array();
        if ($this->_request->isGet()) {
            try {
                $idUser = $this->_getParam('idUser', 0);
                if (!empty($idUser)) {
                    $objUser = new Application_Model_Level();
                    $result = $objUser->getCategoryUserLevel($idUser);
                    if ($result) {
                        $rpta = $result;
                    } else {
                        $rpta = array('msj' => 'no hay datos');
                    }
                    $cod = 200;
                } else {
                    $cod = 200;
                    $rpta['msj'] = 'missing parameters';
                }
            } catch (Exception $e) {
                $cod = 500;
                $rpta = $e->getMessage();
            }
        } else {
            $cod = 401;
            $rpta = array('msj' => 'no authorized, method not post');
        }
        $this->getResponse()
                ->setHttpResponseCode($cod)
                ->setHeader('Content-type', 'application/json;charset=UTF-8', true)
                ->appendBody(json_encode($rpta));
    }
    
    
    
    
    
    
    
    
    
    
    public function getLevelAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $rpta = array();
        if ($this->_request->isGet()) {
            try {
                $category = $this->_getParam('category', 0);
                $idUser = $this->_getParam('idUser', 0);
                if (!empty($category) && !empty($idUser)) {
                    $objUser = new Application_Model_Level();
                    $result = $objUser->getOneUserLevel($idUser, $category);
                    if ($result) {
                        $rpta = $result;
                    } else {
                        $rpta = array('msj' => 'no hay datos');
                    }
                    $cod = 200;
                } else {
                    $cod = 200;
                    $rpta['msj'] = 'missing parameters';
                }
            } catch (Exception $e) {
                $cod = 500;
                $rpta = $e->getMessage();
            }
        } else {
            $cod = 401;
            $rpta = array('msj' => 'no authorized, method not post');
        }
        $this->getResponse()
                ->setHttpResponseCode($cod)
                ->setHeader('Content-type', 'application/json;charset=UTF-8', true)
                ->appendBody(json_encode($rpta));
    }

    public function getLevelRankingAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $rpta = array();
        if ($this->_request->isGet()) {
            try {
                $category = $this->_getParam('category', 0);
                $idUser = $this->_getParam('idUser', 0);
                if (!empty($category) && !empty($idUser)) {
                    $objUser = new Application_Model_Level();
                    $result = $objUser->getOneUserLevel($idUser, $category);
                    if ($result['level']) {
                        $rpta = $objUser->getDataUserConfirm($result['level']);
                    } else {
                        if ($objUser->getCategory($category)) {
                            $rpta = $objUser->getCategory($category);
                        } else {
                            $rpta['msj'] = 'no hay data';
                        }
                    }
                    $cod = 200;
                } else {
                    $cod = 200;
                    $rpta['msj'] = 'missing parameters';
                }
            } catch (Exception $e) {
                $cod = 500;
                $rpta = $e->getMessage();
            }
        } else {
            $cod = 401;
            $rpta = array('msj' => 'no authorized, method not post');
        }
        $this->getResponse()
                ->setHttpResponseCode($cod)
                ->setHeader('Content-type', 'application/json;charset=UTF-8', true)
                ->appendBody(json_encode($rpta));
    }

}

