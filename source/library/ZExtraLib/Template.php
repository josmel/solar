<?php

class ZExtraLib_Template
{

    function load($template, $replace = null)
    {
        $db = ZExtraLib_Server::getDb('query');
        //if (!$result = ZExtraLib_Cache::load('Template' . $template)) {
            $result = $db->fetchOne('SELECT Contenido FROM NPC_Plantilla WHERE Descripcion = ?' , array($template));
       //     ZExtraLib_Cache::save($result, 'Template' . $template);
       // }
        $template = $result;
        if ($replace != null) {
            foreach ($replace as $index => $value) :
                $template = str_replace($index, $value, $template);
            endforeach; //end foreach
        }
        return $template;
    }
    
}

//class Template