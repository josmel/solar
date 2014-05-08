<?php

class ZExtraLib_TextTime
{

    protected $_time;
    protected $_textElapsedTime;

    public function __construct($date=Null)
    {
        if(empty ($date)){
                $this->_time = date('YYYY-MM-DD');
                }
        else{
            $this->_time = date($date);
        }
    }
    
    public function ElapsedTime()
    {
        $intervalos = array("segundo", "minuto", "hora", "día", "semana", "mes", "año");
        $duraciones = array("60", "60","60", "24", "7", "4.35", "12");
        $ahora = time();
        $Fecha_Unix = strtotime($this->_time);

        if (empty($Fecha_Unix)) {
            return "Fecha incorrecta";
        }
        if ($ahora > $Fecha_Unix) {
            $diferencia = $ahora - $Fecha_Unix;
            $tiempo = "Hace";
        } else {
            $diferencia = $Fecha_Unix - $ahora;
            $tiempo = "Dentro de";
        }
        for ($j = 0; $diferencia >= $duraciones[$j] && $j < count($duraciones) - 1; $j++) {
            $diferencia /= $duraciones[$j];
        }

        $diferencia = round($diferencia);

        if ($diferencia != 1) {
            $intervalos[5].="e"; //MESES
            $intervalos[$j].= "s";
        }

        $this->_textElapsedTime = "$tiempo $diferencia $intervalos[$j]";
    }

    public function setTime($time)
    {
        $this->_time = date($time);
    }
    
    

    public function getTextElapsedTime()
    {
        return $this->_textElapsedTime;
    }
    

}

// Ejemplos de uso
// fecha en formato yyyy-mm-dd
// echo tiempo_transcurrido('2010/02/05');
// fecha y hora
// echo tiempo_transcurrido('2010/02/10 08:30:00');