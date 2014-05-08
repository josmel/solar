<?php
/**
 * Table Activities
 * 
 * @author marrselo
 */
class Application_Model_DbTable_Level extends Core_Db_Table
{   
    protected  $_name = "Level";
    protected  $_primary = "idLevel";       
    const NAMETABLE='Level';
    
    static function populate($params)
    {
        $data= array(
            'idUser'=>isset($params['idUser'])?$params['idUser']:'',
            'category'=>isset($params['category'])?$params['category']:'',
            'level'=>isset($params['level'])?$params['level']:'',
            'lastUpdate'=>date('Y-m-d H:i:s')
        );
        $data=  array_filter($data);
        $data['flagAct']=isset($params['flagAct'])?$params['flagAct']:1;
        return $data;
    }

    /**
     * 
     * @param obj DB $resulQuery
     */
    public function columnDisplay()
    {
        return array('name','imageIcon','imageMedium','imageLarge');
    }
        
    public function getPrimaryKey()
    {
        return $this->_primary;
    }
    
    public function getWhereActive()
    {
        return " AND flagAct= 1";
    }
    
    public function getIdUser($idFacebook)
    {
        $smt = $this->getAdapter()->select()
                ->from(array('u' => 'User'), array('*'))
                ->where('u.idFacebook = ?', $idFacebook)
                ->where('u.flagAct = ?', 1)
                ->query()
                ;
        $result = $smt->fetch();
        $smt->closeCursor();
        return $result;
    }
}

