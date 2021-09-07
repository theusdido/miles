<?php
    $op         = tdc::r('op');
    $usuario 	= tdc::r('usuario');
	$senha 		= tdc::r('senha');
	$base 		= tdc::r('base');
	$host		= tdc::r('host');
	$tipo		= tdc::r('tipo');
	$porta		= tdc::r('porta');
	
    switch($op){
        case 'testar':
            $conexao    = Conexao::getConnection($tipo,$host,$base,$usuario,$senha,$porta);
            if ($conexao){
                $status = 1;
            }else{
                $status = 0;
            }
            echo json_encode(
                array(
                    "status" => $status 
                )
            );            
        break;
        case 'criar':
            // Para criar o schema não pode pegar a conexão com a base
            $conexao    = Conexao::getConnection($tipo,$host,'',$usuario,$senha,$porta);

            try{
                $conexao->exec("CREATE DATABASE IF NOT EXISTS {$base};");
                $conexao->exec("USE {$base};");
                $conexao->exec("CREATE TABLE IF NOT EXISTS td_instalacao (id int not null primary key, bancodedadoscriado tinyint, sistemainstalado tinyint, pacoteconfigurado tinyint);");
                $conexao->exec("INSERT INTO td_instalacao (id,bancodedadoscriado,sistemainstalado,pacoteconfigurado) VALUES (1,1,0,0);");
                $conexao->exec("CREATE TABLE IF NOT EXISTS td_conexoes (id int not null primary key, host varchar(60), base varchar(60), porta varchar(15) , usuario varchar(60) , senha varchar(200) , tipo varchar(15));");
                $conexao->exec("INSERT INTO td_conexoes (id,usuario,senha,base,host,tipo,porta) VALUES (1,'$usuario','$senha','$base','$host','$tipo','$porta');");

                // Grava os dados na Sessão para ser usado no processo de instalação
                $_SESSION["db_type"]        = $tipo;
                $_SESSION["db_host"]        = $host;
                $_SESSION["db_base"]        = $base;
                $_SESSION["db_user"]        = $usuario;
                $_SESSION["db_password"]    = $senha;
                $_SESSION["db_port"]        = $porta;

                echo 1;
            }catch(Exception $e){
                echo '<div class="alert alert-danger" role="alert">Erro ao conexão base de dados. Motivo: ';
                foreach ($conn->errorInfo() as $erro){
                    echo $erro . "</br>";
                }
                echo '</div>';
            }
        break;
    }