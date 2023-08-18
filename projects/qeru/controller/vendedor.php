<?php
	switch($op){
		case 'salvar':

			// Vendedor
			$vendedor				= tdc::p('td_ecommerce_vendedor');
			$vendedor->nome			= $dados['nome'];
			$vendedor->cpf			= $dados['cpf'];
			$vendedor->email		= $dados['email'];
			$vendedor->telefone		= $dados['telefone'];
			$vendedor->loja			= $dados['loja'];
			$vendedor->armazenar();

			$retorno = array(
				"id"		=> $vendedor->id,
				"status" 	=> 0,
				"msg" 		=> "Salvo com Sucesso"
			);
			echo json_encode($retorno);
		break;

		case 'all':
			$ft = tdc::f();
			$ft->onlyActive();
			echo json_encode( tdc::da('td_ecommerce_vendedor',$ft) );
		break;

		case 'sugestao':
			echo json_encode( tdc::da('td_ecommerce_cliente') );
		break;

		case 'enviar-convite-vendedor':
			$datahora 	= date('Y-m-d H:i:s');
			$email 		= $dados['email'];
			$loja		= tdc::p('td_ecommerce_loja',$dados['loja']);
			$hash		= md5( $email . $loja->id . $datahora );
			
			$filtro		= tdc::f();
			$filtro->addFiltro('email','=',$email);
			$filtro->onlyActive();

			$ds_vendedor 	= tdc::d('td_ecommerce_vendedor',$filtro);
			if (sizeof($ds_vendedor) > 0){
				echo json_encode(array(
					"status" 	=> 2,
					"msg" 		=> "Este e-mail já cadastrado para outro vendedor."
				));
				return;
			}
			
			$filtro		= tdc::f();
			$filtro->addFiltro('email','=',$email);
			$filtro->addFiltro('grupousuario','=',4);
			$ds_usuario 	= tdc::d('td_usuario',$filtro);
			if (sizeof($ds_usuario) <= 0){
				echo json_encode(array(
					"status" 	=> 3,
					"msg" 		=> "Não existe usuário cadastrado com este e-mail."
				));
				return;
			}

			$usuario			= tdc::p('td_usuario',$ds_usuario[0]->id);
			$vendedor 			= tdc::p('td_ecommerce_vendedor');
			$vendedor->nome 	= $usuario->nome;
			$vendedor->email	= $usuario->email;
			$vendedor->loja		= $loja->id;
			$vendedor->inativo 	= false;
			$vendedor->armazenar();

			$convite 					= tdc::p('td_ecommerce_vendedorconvite');
			$convite->email 			= $email;
			$convite->loja				= $loja->id;
			$convite->datahoraeenvio 	= $datahora;
			$convite->token				= $hash;
			$convite->is_expirou		= false;
			$convite->is_aceito			= false;
			$convite->vendedor			= $vendedor->id;
			$convite->salvar();

			# /-- E-Mail de Convite para Vendedor
			$mail 			= new Enviar();
			$mail->subject 	= "Convinte para ingressar como vendedor. #Qeru";
			$mail->AddAddress("{$email}","{$vendedor->nome}");
			$mail->setHeader(
				$vendedor->nome ,
				"Você recebeu um convite para ser vendedor"
			);
			$mail->setBody("
				<small>
					O estabelecimento <b>".strtoupper($loja->nomefantasia)."</b> 
					está lhe convitando para atuar como vendedor na plataforma 
					<a href='{$_url_root}'><b>Qeru</b></a>
				</small>
				<br/><br/>
				<p>
					<a 
						href='{$_url_root}confirmacaovendedor/{$hash}'
						target='_blank'
						style='background-color:#f9503f;color:#FFF;width:100px;padding:10px;'
					>Clique Aqui para aceitar o convite</a>
				</p>
			");
			if(!$mail->Send()){
				echo '<center><h4 style="color:#FF0000;font-weight:bold;font-size:16px;">Erro ao enviar E-Mail. Motivo: '.$mail->ErrorInfo.'</h4></center>';
				exit;
			}

			$retorno = array(
				"status" 	=> 1,
				"msg" 		=> "Enviado com sucesso"
			);
			
			echo json_encode($retorno);
		break;
		
		case 'confirmacao':
			$hash 		 		= $dados['hash'];
			foreach(tdc::d('td_ecommerce_vendedorconvite',tdc::f('token','=',$hash)) as $confirmacao){

				// Ativa o vendedor
				$vendedor			= tdc::p('td_ecommerce_vendedor',$confirmacao->vendedor);
				$vendedor->inativo 	= false;
				$vendedor->armazenar();

				// Atualiza a confirmação
				$confirmacao->isUpdate();
				$confirmacao->is_aceito 		= true;
				$confirmacao->datahoraativacao 	= date('Y-m-d H:i:s');
				$confirmacao->armazenar();
			}
			echo json_encode(['status' => 1]);
			Transacao::Commit();
		break;

		case 'lista-loja':
			$ft = tdc::f();
			$ft->addFiltro('loja','=',$dados['loja']);
			$ft->addFiltro('is_aceito','=',true);
			$vendedores = array();

			foreach(tdc::da('td_ecommerce_vendedorconvite',$ft) as $v){
				$vendedor = tdc::pa('td_ecommerce_vendedor',$v['vendedor']);
				if (!$vendedor['inativo']){
					array_push($vendedores, $vendedor);
				}
			}
			echo json_encode($vendedores);
		break;
		case 'inativar':
			// Inativa Vendedor
			$vendedor			= tdc::p('td_ecommerce_vendedor',$dados['id']);
			$vendedor->inativo 	= true;
			$vendedor->armazenar();
		break;
	}