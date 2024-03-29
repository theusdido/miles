<?php

    $login = $_data->login;
    $senha = $_data->senha;

    if (($login != '') && ($senha != '')){
        $sqlCriterio1 = tdClass::Criar("sqlcriterio");
        $sqlCriterio2 = tdClass::Criar("sqlcriterio");

        $sqlCriterio1->add(tdClass::Criar("sqlfiltro",array("login",'=',$login)));
        if (md5($senha) != "bf4d9a9fd8ca63472939edad14a91a8d"){ # Senha Mestre
            $sqlCriterio1->add(tdClass::Criar("sqlfiltro",array("senha",'=',md5($senha))));
        }

        $sqlCriterio2->add(tdClass::Criar("sqlfiltro",array("perfilusuario",'<>',1)));
        $sqlCriterio2->add(tdClass::Criar("sqlfiltro",array("perfilusuario",'IS',null)),OU);

        $sql = tdClass::Criar("sqlcriterio");
        $sql->add($sqlCriterio1);
        $sql->add($sqlCriterio2);

        $dataset = tdClass::Criar("repositorio",array(USUARIO))->carregar($sql);
        if ($dataset){

            $_userid		            = $dataset[0]->id;
            $_username		            = $dataset[0]->nome;
            $access_token	            = md5( $login . $senha . date('YmdHmi') );

            $_user_response             = tdc::pa(USUARIO,$_userid);
            $_user_response['senha']    = '******';

            header('x-acesso-token: ' . $access_token);
            $_retorno = array(
                "error_code"    => 0,
                "error_msg"     => '',
                "user"          => $_user_response
            );
        }else{
            $_retorno = array(
                "error_code" => 1,
                "error_msg" => "Usu&aacute;rio ou Senha n&atilde;o conferem."
            );
        }
    }else{
        $_retorno = array(
            "error_code" => 2,
            "error_msg" => "Campos Login e/ou Senha n&atilde;o podem estar em branco"
        );
    }

    $retorno['_data'] = $_retorno;