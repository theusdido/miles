<?php
    $mysql_ini_desenv = array(
        'usuario='.$_SESSION["db_user"],
        'senha='.$_SESSION["db_password"],
        'base='.$_SESSION["db_base"],
        'host='.$_SESSION["db_host"],
        'tipo='.$_SESSION["db_type"],
        'porta='.$_SESSION["db_port"]
    );
    $fpMySQLINIDesenv    = fopen($path_current_config . 'desenv_mysql.ini',"w");
    foreach($mysql_ini_desenv as $c){
        fwrite($fpMySQLINIDesenv,trim($c)."\n");
    }
    fclose($fpMySQLINIDesenv);