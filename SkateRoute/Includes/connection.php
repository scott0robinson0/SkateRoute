<?php
try {
    $host = 'localhost';
    $dbname = 'skateroute';
    $username = 'root';
    $password = '';
    
    $con = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$username,$password);
} catch (Exception $ex) {
    echo $ex->getMessage() . '<br />';
    die('Connection failed');
}