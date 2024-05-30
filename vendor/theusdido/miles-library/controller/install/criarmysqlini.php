<?php
    
    // Apagar arquivo temporário do banco de dados
    // Arqui está efetivando o arquivo de conexão com o banco
    // Então o arquivo temporário não se faz mais necessário
    $_file_temp_bd = PATH_CONFIG . 'temp_mysql.ini';
    if (file_exists($_file_temp_bd)){
        unlink($_file_temp_bd);
    }

    if (isset($_SESSION["db_user"])){
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
    }