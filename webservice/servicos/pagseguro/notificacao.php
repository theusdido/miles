<?php
	$filenameLog = 'servicos/pagseguro/log/log-'.$projetoconsumidor.'.txt';
	if (file_exists($filenameLog)) unlink($filenameLog);
	logPagSeguro("RETORNO => " . date("d/m/Y H:i:s") . "\n -------------------------------------------------------------------------------------------------- \n");

	$notification_type 	= tdc::r('notificationType');
	$notification_code	= tdc::r('notificationCode');
	logPagSeguro('Notification Type => ' . $notification_type);
	if($notification_type != ''){
		
		if ($notification_type == 'transaction'){
			try{
				$configucao_ecommerce = tdc::ru('td_ecommerce_configuracoes');
				$sqlVendedor = "SELECT email,token,notificacaourl,producao FROM td_ecommerce_pagseguro WHERE id = 1;";
				logPagSeguro($sqlVendedor);
				$queryVendedor = $conn->query($sqlVendedor);
				if ($linhaVendedor = $queryVendedor->fetch()){
					// Dados do Vendedor
					$emailVendedor 		= trim($linhaVendedor["email"]);
					$tokenVendedor 		= trim($linhaVendedor["token"]);
					$notificacaourl 	= trim($linhaVendedor["notificacaourl"]);
					$isproducao 		= trim($linhaVendedor["producao"]) == 0 ? false : true;
				}else{
					echo '<div class="alert alert-danger text-center" role="alert"><b>Ops!</b> Credenciais do vendedor do PagSeguro não encontrada.</div>';
					exit;
				}

				$url = 'https://ws'.($isproducao?"":".sandbox").'.pagseguro.uol.com.br/v2/transactions/notifications/' . $notification_code . '?email=' . $emailVendedor . '&token=' . $tokenVendedor;
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
				//var_dump($transaction);
				$retorno['transaction'] = $transaction;

				// Inativa o Carrinho de Compras
				if ($transaction->status == 2 || $transaction->status == 3 || $transaction->status == 4){
					
					$sqlUpdateCariinho = "UPDATE td_ecommerce_carrinhocompras SET inativo = 1 WHERE id = " . $transaction->reference . ";";
					$conn->exec($sqlUpdateCariinho);
					logPagSeguro($sqlUpdateCariinho);
				}

				// Seleciona dados do carrinho de compras
				$sqlCarrinho = "SELECT * FROM td_ecommerce_carrinhocompras WHERE id = " . $transaction->reference . ";";
				logPagSeguro($sqlCarrinho);
				$queryCarrinho 	= $conn->query($sqlCarrinho);
				$linhaCarrinho 	= $queryCarrinho->fetch();
				$valorfrete 	= $linhaCarrinho["valorfrete"]==''?0:$linhaCarrinho["valorfrete"];
				$valortotal		= $transaction->grossAmount;

				$carrinho_cliente			= $linhaCarrinho["cliente"];
				$carrinho_transportadora	= $linhaCarrinho["transportadora"];

				// Pedido
				$sqlVerificaPedido = "SELECT id FROM td_ecommerce_pedido WHERE carrinhocompras = " . $linhaCarrinho["id"] . " ORDER BY id DESC LIMIT 1;";
				logPagSeguro($sqlVerificaPedido);
				$queryVerificaPedido = $conn->query($sqlVerificaPedido);
				if ($queryVerificaPedido->rowcount() > 0){
					$linhaVerificaPedido 	= $queryVerificaPedido->fetch();
					$idPedido 				= $linhaVerificaPedido["id"];

					$sqlAtualizarPedido = "UPDATE td_ecommerce_pedido SET cliente = ".$linhaCarrinho["cliente"].", datahoraretorno = now() , status = ".$transaction->status." , metodopagamento = ".$transaction->paymentMethod->type.", qtdetotalitens = ".$transaction->itemCount.", valortotal = ".$valortotal.", valorfrete = ".$valorfrete." WHERE id = " . $idPedido . ";";
					logPagSeguro($sqlAtualizarPedido);
					$queryPedido = $conn->query($sqlAtualizarPedido);
				}else{
					$idPedido = getProxId("ecommerce_pedido",$conn);
					// Inseri o pedido
					$sqlInserirPedido = "INSERT INTO td_ecommerce_pedido (id,cliente,datahoraenvio,datahoraretorno,carrinhocompras,status,metodopagamento,qtdetotalitens,valortotal,valorfrete) VALUES(
					".$idPedido.",".$carrinho_cliente.",'".$linhaCarrinho["datahoracriacao"]."',now(),".$linhaCarrinho["id"].",".$transaction->status.",".$transaction->paymentMethod->type.",".$transaction->itemCount.",".$valortotal.",".$valorfrete.");";
					logPagSeguro($sqlInserirPedido);
					$queryPedido = $conn->query($sqlInserirPedido);
				}

				// Itens do Pedido
				if ($queryPedido){
					$sqlItensCarrinho = "SELECT * FROM td_ecommerce_carrinhoitem WHERE carrinho = " . $linhaCarrinho["id"] . ";";
					logPagSeguro($sqlItensCarrinho);
					$queryItensCarrinho = $conn->query($sqlItensCarrinho);
					While ($linhaItensCarrinho = $queryItensCarrinho->fetch()){
						$valorTotalItem 			= $linhaItensCarrinho["qtde"] * $linhaItensCarrinho["valor"];
						$sqlVerificaItemPedido 		= "SELECT id FROM td_ecommerce_pedidoitem WHERE pedido = " .$idPedido . " AND produto = " . $linhaItensCarrinho["produto"];
						$queryVerificaItemPedido 	= $conn->query($sqlVerificaItemPedido);
						if ($queryVerificaItemPedido->rowcount() > 0){
							$linhaVerificaItemPedido = $queryVerificaItemPedido->fetch();
							$idItemPedido = $linhaVerificaItemPedido["id"];
						}else{
							$idItemPedido = getProxId("ecommerce_pedidoitem",$conn);
							$sqlInserirItemPedido = "
								INSERT INTO td_ecommerce_pedidoitem 
								(
									id,
									pedido,
									produto,
									qtde,
									descricao,
									valor,
									valortotal,
									carrinhoitem,
									produtonome
								) VALUES (
									".$idItemPedido.",
									".$idPedido.",
									".$linhaItensCarrinho["produto"].",
									".$linhaItensCarrinho["qtde"].",
									'".$linhaItensCarrinho["descricao"]."',
									".$linhaItensCarrinho["valor"].",
									{$valorTotalItem},
									".$linhaItensCarrinho["id"].",
									'".$linhaItensCarrinho["produtonome"]."'
							);";
							logPagSeguro($sqlInserirItemPedido);
							$queryInserirItemPedido = $conn->query($sqlInserirItemPedido);
						}
						$valortotalproduto = $linhaItensCarrinho["qtde"] * $linhaItensCarrinho["valor"];						

						// Atualização no Estoque - Inicio
						logPagSeguro('Antes de realizar a baixa no estoque!');
						if ($configucao_ecommerce->is_control_inventory){
							Estoque::Baixar($linhaItensCarrinho["produto"],$linhaItensCarrinho["qtde"],$transaction->status);
						}
						logPagSeguro('Após de realizar a baixa no estoque!');
						if ($configucao_ecommerce->is_control_commission){
							/*
							// Comissão
							$valorcomissao = 0;
							$sqlComissaoProduto = "SELECT valorfixo,percentualdesconto FROM td_comissaoproduto WHERE representante = {$linhaCarrinho["representante"]} AND produto = {$linhaItensCarrinho["produto"]}";
							$queryComissaoProduto = $conn->query($sqlComissaoProduto);
							if ($queryComissaoProduto->rowCount() > 0){
								$linhaComissaoProduto = $queryComissaoProduto->fetch();
								if ($linhaComissaoProduto["valorfixo"] == "" || $linhaComissaoProduto["valorfixo"] == 0){
									$valorcomissao = ($linhaComissaoProduto["percentualdesconto"] * $valortotalproduto) / 100;
								}else{
									$valorcomissao = $linhaComissaoProduto["valorfixo"];
								}
							}else{
								$sqlComissaoRepresentante = "SELECT valorfixo,percentualdesconto FROM td_comissaorepresentante WHERE representante = {$linhaCarrinho["representante"]}";
								$queryComissaoRepresentante = $conn->query($queryComissaoRepresentante);
								if ($queryComissaoRepresentante->rowCount() > 0){
									$linhaComissaoRepresentante = $queryComissaoRepresentante->fetch();
									if ($linhaComissaoRepresentante["valorfixo"] == "" || $linhaComissaoRepresentante["valorfixo"] == 0){
										$valorcomissao = ($linhaComissaoRepresentante["percentualdesconto"] * $valortotalproduto) / 100;
									}else{
										$valorcomissao = $linhaComissaoRepresentante["valorfixo"];
									}
								}else{
									$sqlComissaoGeral = "SELECT valorfixo,percentualdesconto FROM td_comissaogeral WHERE (valorfixo <> '' AND valorfixo <> 0) OR  (percentualdesconto <> '' AND percentualdesconto <> 0)";
									$queryComissaoGeral = $conn->query($sqlComissaoGeral);
									if ($queryComissaoGeral->rowCount() > 0){
										$linhaComissaoGeral = $queryComissaoGeral->fetch();
										if ($linhaComissaoGeral["valorfixo"] == "" || $linhaComissaoGeral["valorfixo"] == 0){
											$valorcomissao = ($linhaComissaoGeral["percentualdesconto"] * $valortotalproduto) / 100;
										}else{
											$valorcomissao = $linhaComissaoGeral["valorfixo"];
										}
									}
								}
							}
							if ($valorcomissao > 0){
								$sqlComissaoPedido = "SELECT 1 FROM td_comissaopedido WHERE pedido = {$idItemPedido}";
								$queryComissaoPedido = $conn->query($sqlComissaoPedido);
								if ($queryComissaoPedido->rowCount() > 0){
									$sqlUpdateComissaoPedido = "UPDATE td_comissaopedido SET valor = {$valorcomissao} WHERE pedido = {$idItemPedido}";
									$conn->query($sqlUpdateComissaoPedido);
								}else{
									$sqlInsertComissaoPedido = "INSERT INTO td_comissaopedido (valor,pedido) VALUES ({$valorcomissao},{$idItemPedido});";
									$conn->query($sqlInsertComissaoPedido);
								}
							}
							*/
						}

					}
					logPagSeguro('Antes dos Contas a Receber');
					/* ***********************************
						CONTAS À RECEBER DO PEDIDO
					*********************************** */
					try{
						$_contas_receber 						= tdc::p('td_erp_financeiro_contasareceber');
						$_contas_receber->inativo 				= true;
						$_contas_receber->cliente				= $carrinho_cliente;
						$_contas_receber->documento				= $idPedido;
						$_contas_receber->valor					= $valortotal;
						$_contas_receber->dataemissao			= date('Y-m-d');
						$_contas_receber->datavencimento		= date('Y-m-d');
						$_contas_receber->datarecebimento		= date('Y-m-d');
						$_contas_receber->formarecebimento		= 1;
						$_contas_receber->pago					= true;
						#$_contas_receber->comprovante			=
						$_contas_receber->receita				= 1;
						#$_contas_receber->referencia			=
						$_contas_receber->armazenar();
					}catch(Exception $e){
						logPagSeguro($e->getMessage());
					}
					logPagSeguro('Após dos Contas a Receber');

					logPagSeguro('Antes dos Contas à Pagar');
					/* ***********************************
						CONTAS À PAGAR FRETE
					*********************************** */
					try{
						$_contas_pagar 						= tdc::p('td_erp_financeiro_contasapagar');
						$_contas_pagar->inativo 			= true;
						$_contas_pagar->fornecedor			= $carrinho_transportadora;
						$_contas_pagar->documento			= $idPedido;
						$_contas_pagar->valor				= $valorfrete;
						$_contas_pagar->dataemissao			= date('Y-m-d');
						#$_contas_pagar->datavencimento		= date('Y-m-d');
						#$_contas_pagar->datapagamento		= date('Y-m-d');
						$_contas_pagar->formapagamento		= 1;
						$_contas_pagar->pago				= false;
						#$_contas_pagar->comprovante		=
						$_contas_pagar->elementocusto		= 1;
						#$_contas_pagar->referencia			=
						$_contas_pagar->armazenar();
					}catch(Exception $e){
						logPagSeguro($e->getMessage());
					}
					logPagSeguro('Depois dos Contas à Pagar');
				}
				
				logPagSeguro("\nParametro para enviar por e-mail => {$configucao_ecommerce->is_send_order_email} \n");
				if ($configucao_ecommerce->is_send_order_email){
					/* ***********************************
						ENVIA PEDIDO POR E-MAIL
					*********************************** */
					$_pedido_email 				= tdc::ru('td_ecommerce_pedidoemail');
					$_email_config				= tdc::ru('td_emailconfiguracao');
					$_ecommerce_config			= tdc::ru('td_ecommerce_configuracoes');
					include(PATH_LIB . "phpmailer/PHPMailerAutoload.php");
					$mail 						= new PHPMailer();
					$mail->SetLanguage("en",PATH_LIB . "phpmailer/language/");
					$mail->CharSet 				= $_email_config->chartset;
					$mail->SMTPDebug 			= 0;
					$mail->SMTPAuth 			= $_email_config->smtpauth ? true : false;
					$mail->Username 			= $_pedido_email->username;
					$mail->Password 			= $_pedido_email->password;
					$mail->SMTPSecure 			= $_email_config->smtpsecure;
					$mail->Host 				= $_email_config->host;
					$mail->Port 				= $_email_config->port;
					$mail->From 				= $_pedido_email->email;
					$mail->FromName 			= $_pedido_email->destinatario == '' ? 'E-Commerce - #automatico#' : $_pedido_email->destinatario;
					$mail->WordWrap 			= 50;
					$mail->Subject 				= $_pedido_email->assunto == '' ? "PEDIDO REALIZADO" : $_pedido_email->assunto;

					# E-Mail enviado para a loja
					$mail->AddAddress($_ecommerce_config->emailenviopedido,"E-Mail Pedido");
					$mail->AddAddress('edilson@teia.tec.br',"DESENVOLVIMENTO");
					if ($_email_config->issmtp){
						$mail->IsSMTP();
					}

					$mail->IsHTML(true);	
					# Requisição da impressão do pedido
					$_params_pedido 		= "registro=" . $idPedido;
					$url_impressao_pedido 	= URL_MILES. "index.php?controller=ecommerce/pedidoenvioemail/pedidoenvioemail&$_params_pedido";
					$curl = curl_init();
					curl_setopt_array($curl, [
						CURLOPT_RETURNTRANSFER => 1,
						CURLOPT_URL => $url_impressao_pedido
					]);
					$response = curl_exec($curl);
					curl_close($curl);

					$_link_visualizar_pedido 	= '
						<html>
							<body>
								<img src="http://dev.opticaadolfo.com.br/loja/img/logo.png" width="100" />
								<br/><br/><br/>
								<h3>Visualizar Pedido:</h3>
								<p>
									<a href="'.$url_impressao_pedido.'" target="_blank">Clique aqui para visualizar o pedido</a>
								</p>
								<br/>
								<p><i>Esta &eacute; uma mensagem autom&aacute;tica. Por favor, n&atilde;o responda este e-mail.</i></p>
							</body>
						</html>
					';
					$mail->Body 				= $_link_visualizar_pedido;
					if(!$mail->Send())
					{
						$retorno['msg'] 	= $mail->ErrorInfo;
						$retorno['status'] 	= 'error';
					}else{
						$retorno['msg'] 	= 'E-Mail enviado com sucesso!';
						$retorno['link']	= $url_impressao_pedido;
					}
					$retorno['email']  	= $_ecommerce_config->emailenviopedido;
				}
				if ($configucao_ecommerce->is_send_app_mobile){
					logPagSeguro("Chegou aqui >>>");
					/* ***********************************
						ENVIA PEDIDO PARA O APLICATIVO
					*********************************** */
					// Dados do cliente
					$clienteID = $linhaCarrinho["cliente"];
					$sqlCliente = "SELECT nome FROM td_ecommerce_cliente WHERE id = " . $linhaCarrinho["cliente"] .";";
					logPagSeguro($sqlCliente);
					$queryCliente = $conn->query($sqlCliente);
					if ($linhaCliente = $queryCliente->fetch()){
						$nomeClienteAPP = $linhaCliente["nome"];
					}else{
						$nomeClienteAPP = "";
					}

					logPagSeguro("Aplicativo: D1");
					$datahoracriacaoAPP 	= datetimeToMysqlFormat($linhaCarrinho["datahoracriacao"],true);
					$valorfreteAPP 			= $valorfrete<=0?"0,00":moneyToFloat((double)$valorfrete,true);
					$valortotalAPP 			= $valortotal<=0?"0,00":moneyToFloat((double)$valortotal,true);
					$idPedidoFormat			= completaString($idPedido,3,"0");
					logPagSeguro("Aplicativo: D2");
					
					// Aparelhos
					$aparelhos = [];
					$sqlAparelho = "SELECT token FROM td_aplicativo_dispositivo;";
					$queryAparelho = $conn->query($sqlAparelho);
					while ($linhaAparelho = $queryAparelho->fetch()){
						array_push($aparelhos,$linhaAparelho["token"]);
					}

					// CURL de envio
					$curl = curl_init();
					curl_setopt_array($curl, array(
					CURLOPT_URL => 'https://fcm.googleapis.com/fcm/send',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'POST',
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_POSTFIELDS => json_encode(array(
						//['d5AIGfoST5aq8qSL4LoWBH:APA91bGDBg3jgMUZcPHaeDNFz4nd_6h4W6i_MKUvyECQEIcJS8-W9JDsGlEcYzJY4a0FfAYElNBGmM5hi62NAOZkiVEb3kbxPcxXe8z5PxDX3jxmijeBEfAFKNv5bEpYDU-rLgNJcGGj']
						"registration_ids" => $aparelhos,
						"notification" => [
							"title" => "Pedido: #" . $idPedidoFormat,
							"body" => $nomeClienteAPP . " - " . $datahoracriacaoAPP . " - \n Frete: " . $valorfreteAPP . " - Total: " . $valortotalAPP,
						],
						"data" => 
							[
								"id" 			=> $idPedidoFormat, 
								"cliente" 		=> $nomeClienteAPP,
								"datahoraenvio" => $datahoracriacaoAPP,
								"valorfrete" 	=> $valorfreteAPP,
								"valortotal" 	=> $valortotalAPP
							]
						)),
					CURLOPT_HTTPHEADER => array(
						'Authorization: key=AAAAdDWOqak:APA91bE83mH3PLYMxsZgnS4gHAPUydsW9dWFy6x2V-wD7y9Kos5cCp6Vj_mtkGSqLKMTZAwaoMZqmKI4PWmsrJsSRjuSMatUZPUQJkBj3CiI76tHgnHiE8Wbc3FbnwaF585MauzMr_vM',
						'Content-Type: application/json',
						'project_id: 499114748329'
					),
					));
					logPagSeguro("Aplicativo: D3");
					$response = curl_exec($curl);
					curl_close($curl);
					logPagSeguro("Aplicativo:");
					logPagSeguro($response);
				}
			}catch(Exception $e){
				$conn->rollback();
			}finally{
				
			}
		}
	}

	function logPagSeguro($string){
		global $filenameLog;
		$file = fopen($filenameLog, 'a');
		fwrite($file,"\n" . $string);
	}