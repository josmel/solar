<?php
/**
 * Description of Querys
 *
 * @author marrselo
 */
class Application_Entity_DataTable {
       
    protected $_tableName;
    protected $_objTable;
    protected $_columnDisplay;
    protected $_columnSearch;
    protected $_dtwhere='';
    protected $_flagActive;
    protected $_primaryKey;
    protected $_numberPage;
    protected $_limit;
    protected $_newIcon=null;
     protected $_setIconAction=null;
    
    public function __construct($nameTable,$limit,$numberPage,$flagActive=true) {
        $this->_tableName=$nameTable;
        $this->_objTable=  $this->factoryTable();
        $this->_columnDisplay=$this->getColumnDisplay();
        //$this->_dtwhere=$dtwhere;
        $this->_flagActive=$flagActive;
        $this->_primaryKey=$this->_objTable->getPrimaryKey();
        $this->_numberPage=$numberPage;
        $this->_limit=" LIMIT $limit ";
        
    }
    
    public function factoryTable()
    {
        $strinNameTable='Application_Model_DbTable_'.$this->_tableName;
        return $objTable = new $strinNameTable();
    }
 
    public function setSearch($sSearch)
    {
        
       return  $this->_dtwhere = !empty($sSearch)? $sSearch :''; 
        
    }
     public function getColumnDisplay()
    {
        return $this->_objTable->columnDisplay();
    }
    
    public function getQuery($displayStart=null,$displayLength=null)
    {       
	if (!empty($displayStart) && $displayLength != '-1' )
	{
		$this->_limit = " LIMIT ".intval($displayStart).", ".
			intval($displayLength);
	}
        $whereActive=($this->_flagActive==true)?$this->_objTable->getWhereActive():'';
        $id=$this->_primaryKey;
        $query="
            SELECT SQL_CALC_FOUND_ROWS ".$id.", ".str_replace(" , ", " ",
                implode(", ",$this->_columnDisplay))."
            FROM 
            ".$this->_tableName."
            WHERE 1 ".$this->_dtwhere .
            $whereActive
            .$this->_limit;
        $smt = $this->_objTable->getAdapter()->query($query);
  
        $output = array(
                'sEcho' =>intval($this->_numberPage),  
                 'iTotalRecords'=> 0,
                 'iTotalDisplayRecords'=>0,
                 'aaData' => array()
                 );
         while ( $aRow = $smt->fetch() )
         {
            $row = array();
                           
            for ( $i=0 ; $i<count($this->_columnDisplay) ; $i++ )
            {
                $row[$i] = $aRow[$this->_columnDisplay[$i]];                     
            }
            if(empty($this->_setIconAction)){
                $row[$i]="<a class=\"tblaction ico-edit\" title=\"Editar\" href=\"/admin/promotion/edit/id/".$aRow[$id]."\">Editar</a>
                    <a data-id=\"$aRow[$id]\" class=\"tblaction ico-delete\" title=\"Eliminar\"  href=\"javascript:;\">Eliminar</a> 
                    ".str_replace("__ID__",$aRow[$id],$this->_newIcon);                     
            // Add the row ID and class to the object            
            }else{
               $row[$i]=str_replace("__ID__",$aRow[$id],$this->_setIconAction);
            }   
            // Add the row ID and class to the object
            $row['DT_RowId'] = 'row_'.$aRow[$id];
            $output['aaData'][] = $row;
         }         
         $total=$smt->getAdapter()->fetchOne('SELECT FOUND_ROWS()');
         $output['iTotalRecords']=$total;
         $output['iTotalDisplayRecords']=$total;              
         $smt->closeCursor();
         return $output;
    }
    public function setNewIcon($stringHTML)
    {
        return $this->_newIcon=$stringHTML;
    }
    
    public function setNameTable($newNameTable)
    {
        $this->_tableName=$newNameTable;
    }
    public function setIconAction($stringHTML)
    {
        $this->_setIconAction=$stringHTML;
    }
}

