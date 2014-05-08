<?php
class ZExtraLib_Model{
    
    function fetchPairs($array){
        $data=array();
        foreach ($array as $index){
            $arrayKey=array_keys($index);
            $data[$index[$arrayKey[0]]] = $index[$arrayKey[1]];
        }
        return $data;
    }
    function clearDataTable($objTable,$datos){
        $colum=$objTable->getCols();
        $arrayLista = array();
        foreach($datos as $index=>$value){
            if(in_array($index, $colum))
            $arrayLista[$index]=$value;
        }
        return $arrayLista;

    }
    function getArrayFirstValue($array){
        $data=array();
        foreach ($array as $index){
            $arrayKey=array_keys($index);
            $data[] = $index[$arrayKey[0]];
        }
        return $data;
    }
    function arrayAsoccForFirstItem($array){
        $arrayResponse = array();
        foreach($array as $index => $data){
            $arrayResponse[$data[key($data)]][]=$data;
        }
        return $arrayResponse;
    }
}