<?php
    // $type       = 'mysql';
    // $host       ='goes_miles.mysql.dbaas.com.br';
    // $base       ='goes_miles';
    // $user       = 'goes_miles';
    // $password   = 'goes@Teia#25';
    // $port       = '3306';

    $type       = 'mysql';
    $host       ='localhost';
    $base       ='goesimoveis';
    $user       = 'root';
    $password   = 'spespcfc@Dido10-';
    $port       = '3306';

    try{
        $conn = new PDO(
            "$type:host=$host;port=$port;dbname=$base;",$user,$password,
            [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']
        );

        $conn->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION					
        );
    }catch(PDOException $e){        
        echo $e->getMessage();
    }