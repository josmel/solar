<?php
class Service_UserController extends Core_Controller_ActionService{

 

   public function init()
    {
	$this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }
    public function loginAction()
    {
	$this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);            
        $pass=$this->_getParam('idFacebook',0);
        $email=$this->_getParam('mail',0);
        $rpta=array('msj'=>'');        
        try{            
            if($this->_request->isGet() && !empty($email) && !empty($pass)){  
                $objUser =  new Application_Model_DbTable_User();
                $dataU = $objUser->getIdUser($pass);
                if($dataU != false){
                    $result = $this->auth($email, $pass );
                    if($result){
                        //$this->_identity->urlImageProfile=DINAMIC_URL.'user/origin/'.$this->_identity->urlImageProfile;
                        $rpta=array('msj'=>'ok','identity'=>$this->_identity);
                    }else{
                        $rpta=array('msj'=>'incorrect login');
                    }
                }else{
                    $rpta=array('msj'=>'unregister');
                }
            }else{
                $rpta=array('msj'=>'no authorized, wrong params');
            }
        }catch(Exception $e){
            $rpta=$e->getMessage();
        }
        $this->getResponse()            
	     ->setHttpResponseCode(200)
             ->setHeader('Content-type', 'application/json;charset=UTF-8', true)
             ->appendBody(json_encode($rpta));
    }
   
    
     public function registerAction() {
        $rpta = array('msj' => '');
        try {
            if ($this->_request->isPost()) {
                $params = $this->_getAllParams();
                $idFacebook = $params['idFacebook'];
                $objUser = new Application_Model_User();
                $result = $objUser->getOneUser($idFacebook);
                if (!$result) {
                    $obj = new Application_Entity_RunSql('User');
                    $obj->save = $params;
                    $id = $obj->save;
                    $rpta = array('msj' => 'ok', 'id' => $id);
                } else {
                    $rpta = array('msj' => 'ya existe el usuario');
                }
            } else {
                $rpta = array('msj' => 'no authorized, method not post');
            }
        } catch (Exception $e) {
            $rpta = $e->getMessage();
        }
        $this->getResponse()
                ->setHttpResponseCode(200)
                ->setHeader('Content-type', 'application/json;charset=UTF-8', true)
                ->appendBody(json_encode($rpta));
    }
    
    
    
    
    
    
}
