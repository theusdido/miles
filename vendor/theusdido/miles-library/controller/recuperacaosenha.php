<?php
    $path_enviar_class = PATH_CURRENT_CLASS_PROJECT . 'enviar.class.php';
    if (!file_exists($path_enviar_class)){
        showMessage('Classe enviar não foi encontrada.');
        exit;
    }

    include_once PATH_CURRENT_CLASS_PROJECT . 'enviar.class.php';

    $op 		= tdc::r('op');

    switch($op){
        case 'verifica_email_existe':
            $email	= $payload['email'];
            if (tdc::c('td_usuario',tdc::f('email','=',$email)) > 0)
            {
                echo 1;
            }else{
                echo 0;
            }
        break;
        case 'confirmacao':
            $hash 		 		= $payload['hash'];
            foreach(tdc::d('td_usuario_cadastro_confirmacao',tdc::f('hash','=',$hash)) as $confirmacao){
                // Ativa o usuário
                $usuario			= tdc::p('td_usuario',$confirmacao->usuario);
                $usuario->inativo 	= 0;
                $usuario->armazenar();

                // Cliente
                $cliente = tdc::pa("td_ecommerce_cliente",$confirmacao->cliente);

                // Dados do usuário para ser retornado
                $usuario = tdc::pa('td_usuario',$confirmacao->usuario);

                // Atualiza a efetivação			
                $confirmacao->isUpdate();
                $confirmacao->datahoraativacao = date('Y-m-d H:i:s');
                $confirmacao->armazenar();
            }
            echo json_encode([
                'status' 	=> 1, 
                "usuario"	=> $usuario,
                "cliente"  	=> $cliente
            ]);
        break;
        case 'enviarlink':
            $_url_root          = URL_MILES;
            $email				= tdc::r('email');
            $usuario			= tdc::d('td_usuario',tdc::f('email','=',$email));
            if (sizeof($usuario) > 0){
                $datahora			= date('Y-m-d H:i:s');
                $hash				= md5( $email . $datahora );
                $nome 				= $usuario[0]->nome;

                # /-- E-Mail de Recuperação de senha
                $mail 			= new Enviar();
                $mail->subject 	= "Alteração de Senha";
                $mail->AddAddress("{$email}","{$nome}");
                $mail->setHeader(
                    $nome,
                    "Alteração de Senha",
                    "Você solicitou uma alteração de senha, caso não tenho sido você, basta ignorar este e-mail."
                );
                $mail->setBody("
                    <p>
                        <a 
                            href='{$_url_root}alteracaosenha/{$hash}'
                            target='_blank'
                            style='background-color:#f9503f;color:#FFF;width:100px;padding:10px;'
                        >Clique Aqui para alterar sua senha</a>
                    </p>
                ");
                if($mail->Send()){
                    $recuperacao 				= tdc::p('td_usuario_recuperar_senha');
                    $recuperacao->hash			= $hash;
                    $recuperacao->usuario		= $usuario[0]->id;
                    $recuperacao->email			= $email;
                    $recuperacao->datahoraenvio	= $datahora;
                    $recuperacao->armazenar();

                    $status = 1;
                }else{
                    $status = 2;
                }
            }else{
                $status = 3;
            }
            tdc::wj( array('status' => $status) );
        break;
        case 'alterarsenha':
            $retorno			= array ('status' => 0);
            $senha				= md5( tdc::r('senha') );
            $recuperacao		= tdc::d('td_usuario_recuperar_senha',tdc::f('hash','=',tdc::r('hash')));
            foreach ($recuperacao as $r){
                $datahora				= date('Y-m-d H:i:s');
                
                $usuario			= tdc::p('td_usuario',$r->usuario);
                $usuario->senha		= $senha;
                $usuario->armazenar();

                $r->datahoraalteracao	= $datahora;
                $r->isUpdate();
                $r->armazenar();

                $retorno = array (
                    'status' => 1,
                    'usuario'	=> [
                        'id'    => $usuario->id,
                        'email' => $usuario->email,
                        'nome'	=> $usuario->nome
                    ]
                );
            }
            tdc::wj($retorno);
        break;
        case 'atualizar':
            $dados			= $dados['payload'];
            $_cliente		= $dados['cliente'];
            $_endereco		= $dados['endereco'];

            $_uf			= tdc::p('td_ecommerce_uf',$_endereco["uf"]);
            $_cidade_desc	= $_endereco["cidade_desc"];

            // Atualiza a tabela cliente
            $cliente				= tdc::p('td_ecommerce_cliente',$_cliente['id']);
            $cliente->nome			= $_cliente['nome'];
            $cliente->localizacao 	= $_cidade_desc . '/' .$_uf->nome;
            $cliente->armazenar();

            $_entidade_id_cliente 	= getEntidadeId("ecommerce_cliente");
            $_entidade_id_endereco	= getEntidadeId("ecommerce_endereco");
            $lista_endereco  		= getListaRegFilhoArray($_entidade_id_cliente,$_entidade_id_endereco,$cliente->id);			

            $_endereco_id = isset($lista_endereco[0]['id']) ? $lista_endereco[0]['id'] : 0;
            if ($_endereco_id <=0 ){
                // Endereço
                $endereco 				= tdc::p("td_ecommerce_endereco");
            }else{
                $endereco 				= tdc::p("td_ecommerce_endereco",$lista_endereco[0]['id']);
            }

            $endereco->cidade 		= Endereco::addCidade($_cidade_desc,$_endereco["uf"]);
            $endereco->bairro 		= Endereco::addBairro($_endereco["bairro_desc"],$endereco->cidade);
            $endereco->logradouro 	= $_endereco["logradouro"];
            $endereco->numero 		= isset($_endereco["numero"])?$_endereco["numero"]:'';
            $endereco->complemento	= isset($_endereco["complemento"])?$_endereco["complemento"]:'';
            $endereco->cep 			= $_endereco["cep"];
            $endereco->armazenar();

            // Lista - Cliente x Endereço
            $lista 					= tdc::p(LISTA);
            $lista->entidadepai 	= getEntidadeId("ecommerce_cliente");
            $lista->entidadefilho 	= getEntidadeId("ecommerce_endereco");
            $lista->regpai 			= $cliente->id;
            $lista->regfilho 		= $endereco->id;
            $lista->armazenar();

            echo json_encode(array(
                "status" 	=> 1,
                "msg" 		=> "Atualizado com Sucesso"
            ));
        break;
    }    