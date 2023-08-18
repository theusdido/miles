<?php
	$filenameLog = 'log-pagseguro.txt';
	if (file_exists($filenameLog)) unlink($filenameLog);

	logPagSeguro("RETORNO => " . date("d/m/Y H:i:s") . "\n -------------------------------------------------------------------------------------------------- \n");

	if(isset($_POST['notificationType'])){
		if ($_POST['notificationType'] == 'transaction'){
			
			try{
				$sqlVendedor 	= "SELECT email,token,url_notification,is_producao FROM td_ecommerce_pagseguro WHERE id = 1;";
				logPagSeguro($sqlVendedor);
				$queryVendedor 	= $conn->query($sqlVendedor);
				if ($linhaVendedor = $queryVendedor->fetch()){
					// Dados do Vendedor
					$emailVendedor 		= trim($linhaVendedor["email"]);
					$tokenVendedor 		= trim($linhaVendedor["token"]);
					$notificacaourl 	= trim($linhaVendedor["url_notification"]);
					$isproducao 		= trim($linhaVendedor["is_producao"]);
				}else{
					echo '<div class="alert alert-danger text-center" role="alert"><b>Ops!</b> Credenciais do vendedor do PagSeguro não encontrada.</div>';
					exit;
				}

				$url = 'https://ws'.($isproducao?"":".sandbox").'.pagseguro.uol.com.br/v2/transactions/notifications/' . $_POST['notificationCode'] . '?email=' . $emailVendedor . '&token=' . $tokenVendedor;
				logPagSeguro($url);
				$curl = curl_init($url);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$transaction = curl_exec($curl);
				if($transaction == 'Unauthorized' || $transaction == ""){
					//Insira seu código avisando que o sistema está com problemas, sugiro enviar um e-mail avisando para alguém fazer a manutenção 
				   exit;//Mantenha essa linha
				}
				curl_close($curl);
				$transaction = simplexml_load_string($transaction);
				
				// Inativa o Carrinho de Compras
				if ($transaction->status == 2 || $transaction->status == 3 || $transaction->status == 4){
					
					// ID da Movimentação
					$referencia = (array)$transaction->reference;

					// Finaliza a movimentação
					$movimentacao = tdc::p('td_carteiradigital_movimentacao',$referencia[0]);
					$movimentacao->is_finalizada = true;
					$movimentacao->armazenar();
					
					// Atualiza Saldo
					$conta 				= tdc::d('td_carteiradigital_conta',tdc::f('td_usuario','=',$movimentacao->td_usuario))[0];					
					$conta->isUpdate();
					$conta->saldo 		= (double)$conta->saldo + (double)$movimentacao->valor;
					$conta->armazenar();

				}

				logPagSeguro("Chegou aqui >>>");

				/* ***********************************
					ENVIA PEDIDO POR E-MAIL
				*********************************** */
				/*
				$sqlPedidoEmail = "
					SELECT * FROM td_lista 
					WHERE entidadepai = getEntidadeId('td_ecommerce_pedidoemail')
					and entidadefilho = getEntidadeId('td_email')
					AND regpai = 1;
				";
				$queryPedidoEmail = $conn->query($sqlPedidoEmail);
				if ($queryPedidoEmail->rowCount() > 0){
					$linhaPedidoEmail 	= $queryPedidoEmail->fetch();
					$emailRemetente		= getRegistro($conn,"td_email","*","id=".$linhaPedidoEmail["regpai"],"LIMIT 1");
					$emaildestinatario 	= getRegistro($conn,"td_ecommerce_pedidoemail","email,descricao","id=1","LIMIT 1");
					include("../tdlib/phpmailer/PHPMailerAutoload.php");

					$mail 						= new PHPMailer();
					$mail->SetLanguage("en","../tdlib/phpmailer/language/");
					$mail->CharSet 				='UTF-8';
					$mail->SMTPDebug 			= 0;
					$mail->SMTPAuth 			= true;
					$mail->Username 			= $emailRemetente["username"];
					$mail->Password 			= $emailRemetente["password"];
					$mail->SMTPSecure 			= $emailRemetente["smtpsecure"];
					$mail->Host 				= $emailRemetente["host"];
					$mail->Port 				= $emailRemetente["port"];
					$mail->From 				= $emailRemetente["username"];
					$mail->FromName 			= "E-Commerce - #automatico#";
					$mail->WordWrap 			= 50;
					$mail->Subject 				= "PEDIDO REALIZADO";
					$mail->AddAddress($emaildestinatario["email"],$emaildestinatario["descricao"]);
					if ($emailRemetente["issmtp"]){
						$mail->IsSMTP();
					}
					$mail->IsHTML(true);					
					$msg = file_get_contents($_SESSION["URL_SYSTEM"] . "index.php?controller=website/ecommerce/pedidoenvioemail/pedidoenvioemail&registro=1&currentproject=27&key=k");
					$mail->Body = $msg;
					if(!$mail->Send())
					{
						echo $mail->ErrorInfo;
						exit;
					}
				}
				*/
			}catch(Exception $e){

			}finally{

			}
		}
	}
	function logPagSeguro($string){
		global $filenameLog;
		$file = fopen($filenameLog, 'a');
		fwrite($file,"\n" . $string);
		fclose($file);
	}