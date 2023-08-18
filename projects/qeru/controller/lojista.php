<?php
	$dados 	= json_decode(tdc::r("dados"),true);
	$op 	= $dados["op"];
	
	switch($op){
		case "salvar":

            $lojista_id    = $dados['lojista']['id'];
            if (is_numeric($lojista_id)){
                $lojista = tdc::p("td_ecommerce_lojista",$lojista_id);
            }else{
                $lojista = tdc::p("td_ecommerce_lojista");
            }

			// Lojista
			$lojista->nome 				= $dados["lojista"]["nome"];
			$lojista->cpf 				= $dados["lojista"]["cpf"];
			$lojista->datanascimento	= dateToMysqlFormat($dados["lojista"]["datanascimento"]);
			$lojista->telefone			= $dados["lojista"]["telefone"];
			$lojista->armazenar();

			echo json_encode(array(
				"status" 	=> 0,
				"msg" 		=> "Salvo com Sucesso",
				"lojista" 	=> $lojista->id
			));
		break;
		case 'load':
			$id 		= $dados["id"];
			$filtro = tdc::f();
			$filtro->addFiltro("id","=",$id);
			$filtro->onlyActive();

			$lojista 	= tdc::da("td_ecommerce_lojista",$filtro)[0];
			$lojista['datanascimento'] = dateToMysqlFormat($lojista['datanascimento'],true);
			echo json_encode($lojista);
		break;
		case 'excluir-loja-logo':
			$loja_id	= $dados['loja'];
			$file_name	= $dados['filename'];
			$filename	= 'logo-'. getEntidadeId('td_ecommerce_loja') . '-' . $loja_id . "." . getExtensao($file_name);
			$path		= PATH_CURRENT_FILE;
			
			$loja 			= tdc::p('td_ecommerce_loja',$loja_id);
			$loja->logo 	= null;
			$loja->armazenar();

			$src 			= $path . $filename;
			if (file_exists($src)){
				unlink($src);
			}
			echo json_encode(array());
		break;
	}