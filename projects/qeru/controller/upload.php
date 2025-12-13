<?php
	$op 		= tdc::r('op');
	$path 		= $filename = '';
	$is_temp	= true;

	switch($op){
		case 'chat':
			$arquivo 	= $_FILES["image"];
			$filename	= 'chat-' . md5( tdc::r('pedido') . tdc::r('token') . date('YmdHis')) . "." . getExtensao($arquivo["name"]);
			$path		= PATH_CURRENT_FILE;
			$is_temp	= false;
		break;
		case 'item':
			$arquivo 	= isset($_FILES["image"])?$_FILES["image"]:'';
			$filename 	= md5( date('YmdHis') ) . "." . getExtensao($arquivo["name"]);
			$path		= PATH_CURRENT_FILE_TEMP;
		break;
		case 'loja-logo':
			$loja_id	= tdc::r('loja');
			$arquivo 	= $_FILES["image"];
			$filename	= 'logo-'. getEntidadeId('td_ecommerce_loja') . '-' . $loja_id . "." . getExtensao($arquivo['name']);
			$path		= PATH_CURRENT_FILE;
			$is_temp	= false;

			$loja 			= tdc::p('td_ecommerce_loja',$loja_id);
			$loja->logo 	= $arquivo['name'];
			$loja->armazenar();
		break;
		case 'propaganda-banner-temp':
			$loja_id	= tdc::r('loja');
			$arquivo 	= $_FILES["image"];
			$filename	= md5($loja_id . '-' . date('Y-m-d H:i:s')) . "." . getExtensao($arquivo["name"]);
			$path		= PATH_CURRENT_FILE_TEMP;
		break;
		case 'produto-loja':
			$loja_id	= tdc::r('loja');
			$arquivo 	= $_FILES["image"];
			$filename	= md5($loja_id . '-' . date('Y-m-d H:i:s')) . "." . getExtensao($arquivo["name"]);
			$path		= PATH_CURRENT_FILE_TEMP;
		break;
		case 'foto-perfil':
			$cliente_id	= tdc::r('cliente');
			$arquivo 	= $_FILES["image"];
			$filename	= 'fotoperfil-'. getEntidadeId('td_ecommerce_cliente') . '-' . $cliente_id . "." .getExtensao($arquivo['name']);
			$path		= PATH_CURRENT_FILE;
			$is_temp	= false;

			$cliente 				= tdc::p('td_ecommerce_cliente',$cliente_id);
			$cliente->fotoperfil 	= $arquivo['name'];
			$cliente->armazenar();
		break;
		case 'loja-topo':
			$loja_id	= tdc::r('loja');
			$arquivo 	= $_FILES["image"];
			$filename	= 'bannertopo-'. getEntidadeId('td_ecommerce_loja') . '-' . $loja_id . "." .getExtensao($arquivo['name']);
			$path		= PATH_CURRENT_FILE;
			$is_temp	= false;

			$loja 				= tdc::p('td_ecommerce_loja',$loja_id);
			$loja->bannertopo 	= $arquivo['name'];
			$loja->armazenar();
		break;
		case 'loja-topo':
			$loja_id	= tdc::r('loja');
			$arquivo 	= $_FILES["image"];
			$filename	= 'bannertopo-'. getEntidadeId('td_ecommerce_loja') . '-' . $loja_id . "." .getExtensao($arquivo['name']);
			$path		= PATH_CURRENT_FILE;
			$is_temp	= false;

			$loja 				= tdc::p('td_ecommerce_loja',$loja_id);
			$loja->bannertopo 	= $arquivo['name'];
			$loja->armazenar();
		break;
		case 'produto-referencia':
			$subcategoria_id	= tdc::r('subcategoria');
			$arquivo 			= $_FILES["image"];
			$filename			= 'imagem-'. getEntidadeId('td_ecommerce_subcategoria') . '-' . $subcategoria_id . "." .getExtensao($arquivo['name']);
			$path				= PATH_CURRENT_FILE;
			$is_temp			= false;

			$subcategoria 				= tdc::p('td_ecommerce_subcategoria',$subcategoria_id);
			$subcategoria->imagem 		= $arquivo['name'];
			$subcategoria->armazenar();
		break;
	}

	if ($op != ''){
		$path	 	= ($is_temp?PATH_CURRENT_FILE_TEMP:PATH_CURRENT_FILE) . $filename;
		$src 		= ($is_temp?URL_CURRENT_FILE_TEMP:URL_CURRENT_FILE) . $filename;

		if ($src != ''){
			if (isset($arquivo["tmp_name"]) && file_exists($arquivo["tmp_name"])){
				move_uploaded_file($arquivo["tmp_name"], $path);
				echo json_encode(array(
					"filename" 	=> $filename,
					"src"		=> $src,
					'file'		=> $arquivo
				));
			}
		}

		// Encerra a transação para salvar os dados
		Transacao::Commit();
		exit;
	}