<?php
	
	include_once PATH_CURRENT_CLASS_PROJECT . 'enviar.class.php';

	$payload	= json_decode(tdc::r("dados"),true);
	$op 		= $payload["op"];	

	switch($op){
		case "chat_interacao":
			$payload		= json_decode(tdc::r("dados"),true);
			$dados			= $payload['dados'];

			if ($dados['perfil'] == 'L')
			{
				$pessoa	= tdc::p('td_ecommerce_cliente',$dados['remetente_id']);
				$nome	= $pessoa->nome;
				$email 	= $pessoa->email;
				if ($email == ''){
					$usuario        = getListaRegFilhoArray(getEntidadeId("ecommerce_cliente"),getEntidadeId("usuario"),$pessoa->id)[0];
					$email			= $usuario['email'];
				}
			}else{
				$pessoa	= tdc::p('td_ecommerce_loja',$dados['remetente_id']);
				$nome	= $pessoa->nomefantasia;
				$email 	= $pessoa->email;
				if ($email == ''){
					$usuario        = getListaRegFilhoArray(getEntidadeId("td_ecommerce_loja"),getEntidadeId("usuario"),$pessoa->id)[0];
					$email			= $usuario['email'];
				}
			}

			$retorno = array();

			# /-- E-Mail de Notificação
			$mail 			= new Enviar();
			$mail->subject 	= "Mensagem Recebida";
			$mail->AddAddress("{$email}","{$nome}");
			$mail->setHeader(
				$nome,
				"Você recebeu uma mensagem",
				"Clique no link abaixo para acessar a plataforma e visualizar a mensagem."
			);
			$mail->setBody("
				<a 
					href='{$_url_root}'
					target='_blank'
					style='background-color:#f9503f;color:#FFF;width:100px;padding:10px;'
				>Visualizar Nova Mensagem</a>
				<br/>
			");
			if(!$mail->Send()){
				array_push($retorno,array(
					'error_code' 	=> 1,
					'error_msg'		=> $mail->ErrorInfo
				));
			}else{
				array_push($retorno,array(
					'error_code' 	=> 0,
					'error_msg'		=> 'E-Mail enviado com sucesso.'
				));
			}
			echo json_encode($retorno);
		break;
	}