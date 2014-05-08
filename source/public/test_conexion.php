<?php

$enlace = mysql_connect('localhost', 'root', '123456789');
if  (!$enlace) {
    die('No pudo conectarse: ' . mysql_error());
} else echo 'entrooooooooooooo';
