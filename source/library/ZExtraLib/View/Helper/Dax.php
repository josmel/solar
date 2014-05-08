<?php

class ZExtraLib_View_Helper_Dax
        extends Zend_View_Helper_Abstract
{
    function dax()
    {
        $result = '<!-- Certifica.com -->
            <script language="JavaScript1.3" src="http://b.scorecardresearch.com/c2/6906602/ct.js"></script>';
        return $result;
    }

    
    function googleAnalitysc(){
        $result = "<script type='text/javascript'>

                          var _gaq = _gaq || [];
                          _gaq.push(['_setAccount', 'UA-28124903-1']);
                          _gaq.push(['_setDomainName', 'clasificados.pe']);
                          _gaq.push(['_trackPageview']);
                          _gaq.push(['_trackPageLoadTime']); 

                          (function() {
                            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                          })();

                        </script>";
        return $result ; 
    }
}