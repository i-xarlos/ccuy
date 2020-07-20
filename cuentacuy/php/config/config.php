<?php

$isLocalhost = $_SERVER['HTTP_HOST'] === 'localhost';

$server = "localhost" ;
$user = "cuentacuyperu" ;
$password = "cuentacuyperu" ;
$db ="cuenta-cuy-2020" ;

if($isLocalhost){
    $server = "localhost" ;
    $user = "root" ;
    $password = "root" ;
    $db ="cuenta-cuy";
}

define('DB_SERVER', $server);
define('DB_USERNAME', $user);
define('DB_PASSWORD', $password);
define('DB_DATABASE', $db);
