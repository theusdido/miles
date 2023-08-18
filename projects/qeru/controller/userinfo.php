<?php
    if ($op == 'brand_image'){
        $retorno    = 'assets/img/usuario.png';
    }else{
        $perfil     = $dados['perfil'];
        switch($perfil){
            case 'L':
			case 1:
                $registro_id    = $dados['loja'];
                $loja           = tdc::p('td_ecommerce_loja',$registro_id);
                $campo_name     = 'logo';
                $entidade_id    = $loja->getID();
                $filename       = $loja->{$campo_name};
            break;
            case 'C':
			case 2:
                $registro_id    = $dados['cliente'];
                $cliente        = tdc::p('td_ecommerce_cliente',$registro_id);
                $campo_name     = 'fotoperfil';
                $usuario        = tdc::p('td_usuario',$dados['usuario']);
                $entidade_id    = $cliente->getID();
                $filename       = $cliente->{$campo_name};
            break;
            default:
                $campo_name     = '';
                $entidade_id    = $registro_id = 0;
                $filename       = '';
        }

        $file_name      = $campo_name . '-' . $entidade_id . '-' . $registro_id . '.' . getExtensao($filename);
        $file_path	 	= PATH_CURRENT_FILE . $file_name;

        if (file_exists($file_path)){
            $retorno =  $file_name;
        }else{
            $retorno = '';
        }
    }

    echo json_encode($retorno);