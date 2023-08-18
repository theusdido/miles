<?php	
	$op 				= tdc::r('op');
	$path 				= $filename = '';
	$is_temp			= true;
	$_image_file		= isset($_FILES["image"]) ? $_FILES["image"] : $_POST['image'];
	$_image_name		= $_image_file['name'];
	$_image_extensao	= getExtensao($_image_name);
	switch($op){
		case 'chat':
			$filename	= 'chat-' . md5( tdc::r('pedido') . tdc::r('token') . date('YmdHis')) . "." . $_image_extensao;
			$path		= PATH_CURRENT_FILE;
			$is_temp	= false;
		break;
		case 'item':
			$filename 	= md5( date('YmdHis') ) . "." . $_image_extensao;
			$path		= PATH_CURRENT_FILE_TEMP;
		break;
		case 'loja-logo':
			$loja_id	= tdc::r('loja');
			$filename	= 'logo-'. getEntidadeId('td_ecommerce_loja') . '-' . $loja_id . "." . $_image_extensao;
			$path		= PATH_CURRENT_FILE;
			$is_temp	= false;

			$loja 			= tdc::p('td_ecommerce_loja',$loja_id);
			$loja->logo 	= $_image_file['name'];
			$loja->armazenar();
		break;
		case 'propaganda-banner-temp':
			$loja_id	= tdc::r('loja');
			$filename	= md5($loja_id . '-' . date('Y-m-d H:i:s')) . "." . $_image_extensao;
			$path		= PATH_CURRENT_FILE_TEMP;
		break;
		case 'produto-loja':
			$loja_id	= tdc::r('loja');
			$filename	= md5($loja_id . '-' . date('Y-m-d H:i:s')) . "." . $_image_extensao;
			$path		= PATH_CURRENT_FILE_TEMP;
		break;
		case 'foto-perfil':
			$cliente_id	= tdc::r('cliente');
			$filename	= 'fotoperfil-'. getEntidadeId('td_ecommerce_cliente') . '-' . $cliente_id . "." .$_image_extensao;
			$path		= PATH_CURRENT_FILE;
			$is_temp	= false;

			$cliente 				= tdc::p('td_ecommerce_cliente',$cliente_id);
			$cliente->fotoperfil 	= $_image_name;
			$cliente->armazenar();
		break;
		case 'loja-topo':
			$loja_id	= tdc::r('loja');
			$filename	= 'bannertopo-'. getEntidadeId('td_ecommerce_loja') . '-' . $loja_id . "." .$_image_extensao;
			$path		= PATH_CURRENT_FILE;
			$is_temp	= false;

			$loja 				= tdc::p('td_ecommerce_loja',$loja_id);
			$loja->bannertopo 	= $_image_name;
			$loja->armazenar();
		break;
		case 'produto-referencia':
			$subcategoria_id	= tdc::r('subcategoria');
			$filename			= 'imagem-'. getEntidadeId('td_ecommerce_subcategoria') . '-' . $subcategoria_id . "." .$_image_extensao;
			$path				= PATH_CURRENT_FILE;
			$is_temp			= false;

			$subcategoria 				= tdc::p('td_ecommerce_subcategoria',$subcategoria_id);
			$subcategoria->imagem 		= $_image_name;
			$subcategoria->armazenar();
		break;
	}

	if ($op != ''){
		$path	 	= ($is_temp?PATH_CURRENT_FILE_TEMP:PATH_CURRENT_FILE) . $filename;
		$src 		= ($is_temp?URL_CURRENT_FILE_TEMP:URL_CURRENT_FILE) . $filename;
		if ($src != ''){

			if (isset($_image_file["tmp_name"]) && file_exists($_image_file["tmp_name"])){

				ini_set('display_errors',1);
				ini_set('display_startup_erros',1);
				error_reporting(E_ALL);

				if (!move_uploaded_file($_image_file["tmp_name"], $path)){
					copy($_image_file["tmp_name"],$path);
				}
				
				echo json_encode(array(
					"filename" 	=> $filename,
					"src"		=> $src,
					'file'		=> $_image_file
				));
			}
		}

		// Encerra a transação para salvar os dados
		Transacao::Commit();
		exit;
	}