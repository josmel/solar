<?php
class Application_Model_Level extends Core_Model
{
    protected $_tableLevel; 
    
    public function __construct()
    {
        $this->_tableLevel= new Application_Model_DbTable_Level();
    }
     
    public function getOneUserLevel($idUser,$getOneUserLevel)
    {
         $smt=$this->_tableLevel->getAdapter()->query("
                SELECT * FROM Level
                WHERE idUser='".$idUser."'and category='".$getOneUserLevel."'
                ");
           $result=$smt->fetch();
           $smt->closeCursor();  
           return $result;
    }
    
    public function getCategoryUserLevel($idUser)
    {
         $smt=$this->_tableLevel->getAdapter()->query("
              SELECT * FROM Level where idUser= '".$idUser."';
                ");
           $result=$smt->fetchAll();
           $smt->closeCursor();  
           return $result;
    }
    

     public function getDataUserConfirm($level)
     {
         $smt=$this->_tableLevel->getAdapter()->query("
               ( SELECT U.idUser,L.idLevel,L.level  as Level ,L.flagAct,U.urlImageProfile,U.firstName FROM Level AS L
                INNER JOIN User AS U ON U.idUser= L.idUser
                WHERE L.category='hobby' and L.level <='".$level."' limit 3)
union
(SELECT U.idUser,L.idLevel,L.level  as Level ,L.flagAct,U.urlImageProfile,U.firstName FROM Level AS L
                INNER JOIN User AS U ON U.idUser= L.idUser
                WHERE L.category='hobby' and L.level >'".$level."' limit 6)order by Level asc
                ");
           $result=$smt->fetchAll();
           $smt->closeCursor();  
           return $result;
     }
     
     
     public function getCategory($category)
     {
         $smt=$this->_tableLevel->getAdapter()->query("
              
 SELECT U.idUser, L.idLevel,L.level,L.flagAct,U.urlImageProfile,U.firstName FROM Level AS L
                INNER JOIN User AS U ON L.idUser=U.idUser
                WHERE L.category='".$category."'order by L.level asc limit 20
                ");
           $result=$smt->fetchAll();
           $smt->closeCursor();  
           return $result;
     }
     
     
   
}


