<?php

class ZExtraLib_View_Helper_Certifica
        extends Zend_View_Helper_Abstract
{
    function certifica($codigo,$url)
    {
        $result = '<!-- Certifica.com -->
            <script type="text/javascript" src="http://c.scorecardresearch.com/certifica-js14.js"></script>
            <script type="text/javascript" src="http://c.scorecardresearch.com/certifica.js"></script>
            <script type="text/javascript">
            <!--
            tagCertifica('.$codigo.',"'.$url.'");
            //
            -->
            </script>
            ';
        return $result;
    }

}