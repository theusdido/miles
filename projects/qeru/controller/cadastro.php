<?php
	include_once PATH_CURRENT_CLASS_PROJECT . 'enviar.class.php';

	$payload	= json_decode(tdc::r("dados"),true);
	$op 		= $payload["op"];	

	switch($op){
		case "salvar":
			$dados		= $payload['dados'];
			$is_error	= false;
			$nome		= $dados['nome'];
			$email		= $dados['email'];
			$senha		= $dados['senha'];
			$perfil		= $dados['perfil'];
			$hash		= md5($email);
			if ($nome == ''){
				$is_error = true;
				$retorno = array(
					'status' 	=> 1,
					'msg'		=> 'O campo nome não pode ser vazio.'
				);
			}
			
			if (!isemail($email)){
				$is_error = true;
				$retorno = array(
					'status' 	=> 2,
					'msg'		=> 'O campo e-mail está inválido.'
				);
			}

			if (!$is_error){

				// Usuário 
				$usuario 						= tdc::p("td_usuario");
				$usuario->nome 					= $nome;
				$usuario->email					= $email;
				$usuario->login					= $email;
				$usuario->senha					= md5($senha);
				$usuario->permitirexclusao 		= 0;
				$usuario->permitirtrocarempresa = 0;
				$usuario->grupousuario 			= $perfil=='C'?4:3;
				$usuario->perfil 				= 0;
				$usuario->perfilusuario			= 0;
				$usuario->inativo 				= 1;
				$usuario->armazenar();

				// Criar Conta
				$conta 				= tdc::p('td_carteiradigital_conta');
				$conta->saldo 		= 0;
				$conta->usuario	= $usuario->id;
				$conta->armazenar();

				$cliente_id = $loja_id = 0;

				// Cliente
				$cliente 							= tdc::p("td_ecommerce_cliente");
				$cliente->nome 						= $nome;
				$cliente->email						= $email;
				$cliente->senha						= md5($senha);
				$cliente->is_exibirmensagemparceiro = true;
				$cliente->tipopessoa				= $perfil=='C'?1:2;
				$cliente->armazenar();

				// Lista - Cliente x Usuário
				$lista = tdc::p(LISTA);
				$lista->entidadepai 	= getEntidadeId("ecommerce_cliente");
				$lista->entidadefilho 	= getEntidadeId("usuario");
				$lista->regpai 			= $cliente->id;
				$lista->regfilho 		= $usuario->id;
				$lista->armazenar();

				// Adicionar Confirmação de Cadastro
				$confirmacao 					= tdc::p('td_usuario_cadastro_confirmacao');
				$confirmacao->usuario 			= $usuario->id;
				$confirmacao->cliente			= $cliente->id;  
				$confirmacao->hash				= $hash;
				$confirmacao->datahoracriacao	= date('Y-m-d H:i:s');

				if ($confirmacao->armazenar() == false) {
					$retorno = array(
						"status" 	=> 2
					);
				}else{

					# URL Confirmação
					$url_confirmacao_cadastro = $_url_root.'confirmacao/'.$hash;

					# /-- E-Mail de Confirmação - Início
					$mail 			= new Enviar();
					$mail->subject 	= "Confirmação de Cadastro";
					$mail->AddAddress("{$email}","{$nome}");
					$mail->setHeader(
						$nome,
						"Confirme seu cadastro",
						"Você precisa confirmar seu cadastro para que você possa utilizar todas as 
						funcionalidades da nossa plataforma."
					);
					$mail->setBody("
						<p>
							<a 
								href='{$url_confirmacao_cadastro}'
								target='_blank'
								style='background-color:#f9503f;color:#FFF;width:100px;padding:10px;'
							><strong>Clique Aqui</strong> para confirmar seu cadastro.</a>
						</p>
					");
					if(!$mail->send()){
						echo '<center><h4 style="color:#FF0000;font-weight:bold;font-size:16px;">Erro ao enviar E-Mail. Motivo: '.$mail->ErrorInfo.'</h4></center>';
						exit;
					}
					# E-Mail de Confirmação --/
					$retorno = array(
						"status" 	=> 1,
						"msg" 		=> "Salvo com Sucesso",
						"userid" 	=> $usuario->id,
						"username" 	=> $usuario->nome,
						"usergroup" => $usuario->grupousuario,
						"cliente"	=> $cliente->id,
						"loja" 		=> $loja_id,
					);
				}
			}
			echo json_encode($retorno);
		break;
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
			$email				= $payload['email'];
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
							href='{$_url_root}/alteracaosenha/{$hash}'
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
			echo json_encode( array ('status' => $status) );
		break;
		case 'alterarsenha':
			$retorno			= array ('status' => 0);
			$senha				= md5( $payload['senha'] );
			$recuperacao		= tdc::d('td_usuario_recuperar_senha',tdc::f('hash','=',$payload['hash']));
			foreach ($recuperacao as $r){
				$datahora				= date('Y-m-d H:i:s');
				
				$usuario			= tdc::p('td_usuario',$r->usuario);
				$usuario->senha		= $senha;
				$usuario->armazenar();
				
				$ft_lista	= tdc::f();
				$ft_lista->addFiltro('entidadepai','=', getEntidadeId("ecommerce_cliente"));
				$ft_lista->addFiltro('entidadefilho','=', getEntidadeId("usuario"));
				$ft_lista->addFiltro('regfilho','=', $usuario->id);
				$cliente	= tdc::pa('td_ecommerce_cliente',tdc::d(LISTA)[0]->regpai);

				$ft_lista	= tdc::f();
				$ft_lista->addFiltro('entidadepai','=', getEntidadeId("ecommerce_loja"));
				$ft_lista->addFiltro('entidadefilho','=', getEntidadeId("usuario"));
				$ft_lista->addFiltro('regfilho','=', $usuario->id);
				$loja	= tdc::pa('td_ecommerce_loja',tdc::d(LISTA)[0]->regpai);

				$r->datahoraalteracao	= $datahora;
				$r->isUpdate();
				$r->armazenar();

				$categorias			= array();
				$is_loja			= false;

				if (sizeof($loja) > 0){
					$is_loja			= true;
					$categorias_lista	= getListaRegFilhoObject(
						getEntidadeId("ecommerce_loja"),
						getEntidadeId("ecommerce_categoria"),
						$loja['id']
					);
					foreach($categorias_lista as $c){
						array_push($categorias, $c->id);
					}					
				}else{
					$loja = array(
						'id'	=> 0,
						'nomefantasia'	=> ''
					);
				}
				$retorno = array (
					'status' => 1,
					'usuario'	=> [
						'id' => $usuario->id,
						'email' => $usuario->email,
						'nome'	=> $usuario->nome
					],
					'cliente'		=> $cliente,
					'loja'			=> $loja,
					'categorias'	=> $categorias,
					'is_loja'		=> $is_loja
				);
			}
			echo json_encode( $retorno );
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