<?php
    $idregistro 		= isset($_POST["idregistro"])?$_POST["idregistro"]:0;
    $valor  			= $_FILES[$id_input]["name"];
    $extensao 			= getExtensao($_FILES[$id_input]["name"]);
    $nomeentidade		= tdClass::Criar("persistent",array(ENTIDADE,$atributo->contexto->entidade))->contexto->nome;
    $retorno		 	= tdFile::uploadTDForm($_FILES,tdc::a($atributo->contexto->id));
    $retorno_json		= json_encode($retorno);

    if (tdc::r('retorno') == 'json'){
        echo $retorno_json;
        exit;
    }    