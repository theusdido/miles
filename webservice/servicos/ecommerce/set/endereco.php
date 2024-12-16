<?php

	$enderecojson			= json_decode(tdClass::Read("dados"));
	
	$entidadePai 			= getEntidadeId("aplicativo_usuario");
	$entidadeFilho			= getEntidadeId("ecommerce_endereco");
	$regPai					= tdClass::Read("cliente");
	
	$endereco 				= tdClass::Criar("persistent",array("td_ecommerce_endereco"))->contexto;
	
	// Verifica se o cliente já tem endereço
	$enderecoV = getListaRegFilho($entidadePai,$entidadeFilho,$regPai);
	if (sizeof($enderecoV) > 0){
		$endereco->id 			= $enderecoV[0]->regfilho;
		$endereco->isUpdate();
	}else{
		$idEndereco 			= $endereco->proximoID();
		$endereco->id 			= $idEndereco;

		$lista 					= tdClass::Criar("persistent",array(LISTA))->contexto;
		$lista->entidadepai 	= $entidadePai;
		$lista->entidadefilho 	= $entidadeFilho;
		$lista->regpai 			= $regPai;
		$lista->regfilho 		= $idEndereco;
		$lista->armazenar();
	}

	$endereco->cep 			= $enderecojson->cep;
	$endereco->td_bairro 	= (int)$enderecojson->bairro;
	$endereco->logradouro 	= $enderecojson->logradouro;
	$endereco->numero 		= isset($enderecojson->numero)?$enderecojson->numero:'S/N';
	$endereco->complemento 	= isset($enderecojson->complemento)?$enderecojson->complemento:'';
	$endereco->inativo 		= 0;
	$endereco->armazenar();