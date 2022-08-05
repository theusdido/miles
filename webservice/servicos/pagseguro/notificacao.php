<<<<<<< HEAD
<?php
	$filenameLog = 'servicos/pagseguro/log/log-'.$projetoconsumidor.'.txt';
	if (file_exists($filenameLog)) unlink($filenameLog);
	logPagSeguro("RETORNO => " . date("d/m/Y H:i:s") . "\n -------------------------------------------------------------------------------------------------- \n");

	if(isset($_POST['notificationType'])){
		if ($_POST['notificationType'] == 'transaction'){

			try{
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

				// Pedido
				$sqlVerificaPedido = "SELECT id FROM td_ecommerce_pedido WHERE carrinhocompras = " . $linhaCarrinho["id"] . " ORDER BY id DESC LIMIT 1;";
				logPagSeguro($sqlVerificaPedido);
				$queryVerificaPedido = $conn->query($sqlVerificaPedido);
				if ($queryVerificaPedido->rowcount() > 0){
					$linhaVerificaPedido = $queryVerificaPedido->fetch();
					$idPedido = $linhaVerificaPedido["id"];

					$sqlAtualizarPedido = "UPDATE td_ecommerce_pedido SET cliente = ".$linhaCarrinho["cliente"].", datahoraretorno = now() , status = ".$transaction->status." , metodopagamento = ".$transaction->paymentMethod->type.", qtdetotaldeitens = ".$transaction->itemCount.", valortotal = ".$valortotal.", valorfrete = ".$valorfrete." WHERE id = " . $idPedido . ";";
					logPagSeguro($sqlAtualizarPedido);
					$queryPedido = $conn->query($sqlAtualizarPedido);
				}else{
					$idPedido = getProxId("ecommerce_pedido",$conn);
					// Inseri o pedido
					$sqlInserirPedido = "INSERT INTO td_ecommerce_pedido (id,cliente,datahoraenvio,datahoraretorno,carrinhocompras,status,metodopagamento,qtdetotaldeitens,valortotal,valorfrete) VALUES(
					".$idPedido.",".$linhaCarrinho["cliente"].",'".$linhaCarrinho["datahoracriacao"]."',now(),".$linhaCarrinho["id"].",".$transaction->status.",".$transaction->paymentMethod->type.",".$transaction->itemCount.",".$valortotal.",".$valorfrete.");";
					logPagSeguro($sqlInserirPedido);
					$queryPedido = $conn->query($sqlInserirPedido);
				}

				// Itens do Pedido
				if ($queryPedido){
					$sqlItensCarrinho = "SELECT * FROM td_ecommerce_carrinhoitem WHERE carrinho = " . $linhaCarrinho["id"] . ";";
					logPagSeguro($sqlItensCarrinho);
					$queryItensCarrinho = $conn->query($sqlItensCarrinho);
					While ($linhaItensCarrinho = $queryItensCarrinho->fetch()){
						$valorTotalItem = $linhaItensCarrinho["qtde"] * $linhaItensCarrinho["valor"];
						$sqlVerificaItemPedido = "SELECT id FROM td_ecommerce_pedidoitem WHERE pedido = " .$idPedido . " AND produto = " . $linhaItensCarrinho["produto"];
						$queryVerificaItemPedido = $conn->query($sqlVerificaItemPedido);
						if ($queryVerificaItemPedido->rowcount() > 0){
							$linhaVerificaItemPedido = $queryVerificaItemPedido->fetch();
							$idItemPedido = $linhaVerificaItemPedido["id"];
						}else{
							$idItemPedido = getProxId("ecommerce_pedidoitem",$conn);
							$sqlInserirItemPedido = "INSERT INTO td_ecommerce_pedidoitem (id,pedido,produto,qtde,descricao,valor,valortotal) VALUES (
							".$idItemPedido.",".$idPedido.",".$linhaItensCarrinho["produto"].",".$linhaItensCarrinho["qtde"].",'".$linhaItensCarrinho["descricao"]."',".$linhaItensCarrinho["valor"].",{$valorTotalItem}
							);";
							logPagSeguro($sqlInserirItemPedido);
							$queryInserirItemPedido = $conn->query($sqlInserirItemPedido);
						}
						$valortotalproduto = $linhaItensCarrinho["qtde"] * $linhaItensCarrinho["valor"];

						/* ******************************
							Atualização no Estoque - Inicio
						****************************** */
						$iscontrolaestoque 	= true;
						$tipooepracaoestoque = 2;
						if ($iscontrolaestoque){
							// Selecione a Operação de Estoque					
							$sqlTipoOperacaoEstoque = "SELECT ifnull(operacaoestoque,{$tipooepracaoestoque}) operacao FROM td_ecommerce_statuspedido WHERE id = " . $transaction->status. ";";
							logPagSeguro($sqlTipoOperacaoEstoque);
							$queryTipoOperacaoEstoque = $conn->query($sqlTipoOperacaoEstoque);
							if ($queryTipoOperacaoEstoque->rowCount() > 0){
								$linhaTipoOperacaoEstoque = $queryTipoOperacaoEstoque->fetch();
								$tipooepracaoestoque = $linhaTipoOperacaoEstoque["operacao"];
							}
						}
						
						/*
						$data = array(
							"controller" => "ecommerce/posicaogeralestoque",
							"key" => "k",
							"quantidade" => $linhaItensCarrinho["qtde"],
							"operacao" => $tipooepracaoestoque,
							"variacaoproduto" => $linhaItensCarrinho["produto"]
						);
	
						$curl = curl_init();
						curl_setopt_array($curl, [
							CURLOPT_RETURNTRANSFER => 1,
							CURLOPT_URL => $_SESSION["PATH_URL_SYSTEM"] . 'index.php',
							CURLOPT_POSTFIELDS => $data
						]);
						$response = curl_exec($curl);
						curl_close($curl);
						*/
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
				var_dump(curl_error($curl));
				curl_close($curl);
				logPagSeguro("Aplicativo:");
				logPagSeguro($response);

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
				
				$conn->commit();
				*/
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
		fclose($file);
=======
<?php
	$filenameLog = 'servicos/pagseguro/log/log-'.$projetoconsumidor.'.txt';
	if (file_exists($filenameLog)) unlink($filenameLog);
	logPagSeguro("RETORNO => " . date("d/m/Y H:i:s") . "\n -------------------------------------------------------------------------------------------------- \n");

	if(isset($_POST['notificationType'])){
		if ($_POST['notificationType'] == 'transaction'){

			try{
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

				// Pedido
				$sqlVerificaPedido = "SELECT id FROM td_ecommerce_pedido WHERE carrinhocompras = " . $linhaCarrinho["id"] . " ORDER BY id DESC LIMIT 1;";
				logPagSeguro($sqlVerificaPedido);
				$queryVerificaPedido = $conn->query($sqlVerificaPedido);
				if ($queryVerificaPedido->rowcount() > 0){
					$linhaVerificaPedido = $queryVerificaPedido->fetch();
					$idPedido = $linhaVerificaPedido["id"];

					$sqlAtualizarPedido = "UPDATE td_ecommerce_pedido SET cliente = ".$linhaCarrinho["cliente"].", datahoraretorno = now() , status = ".$transaction->status." , metodopagamento = ".$transaction->paymentMethod->type.", qtdetotaldeitens = ".$transaction->itemCount.", valortotal = ".$valortotal.", valorfrete = ".$valorfrete." WHERE id = " . $idPedido . ";";
					logPagSeguro($sqlAtualizarPedido);
					$queryPedido = $conn->query($sqlAtualizarPedido);
				}else{
					$idPedido = getProxId("ecommerce_pedido",$conn);
					// Inseri o pedido
					$sqlInserirPedido = "INSERT INTO td_ecommerce_pedido (id,cliente,datahoraenvio,datahoraretorno,carrinhocompras,status,metodopagamento,qtdetotaldeitens,valortotal,valorfrete) VALUES(
					".$idPedido.",".$linhaCarrinho["cliente"].",'".$linhaCarrinho["datahoracriacao"]."',now(),".$linhaCarrinho["id"].",".$transaction->status.",".$transaction->paymentMethod->type.",".$transaction->itemCount.",".$valortotal.",".$valorfrete.");";
					logPagSeguro($sqlInserirPedido);
					$queryPedido = $conn->query($sqlInserirPedido);
				}

				// Itens do Pedido
				if ($queryPedido){
					$sqlItensCarrinho = "SELECT * FROM td_ecommerce_carrinhoitem WHERE carrinho = " . $linhaCarrinho["id"] . ";";
					logPagSeguro($sqlItensCarrinho);
					$queryItensCarrinho = $conn->query($sqlItensCarrinho);
					While ($linhaItensCarrinho = $queryItensCarrinho->fetch()){
						$valorTotalItem = $linhaItensCarrinho["qtde"] * $linhaItensCarrinho["valor"];
						$sqlVerificaItemPedido = "SELECT id FROM td_ecommerce_pedidoitem WHERE pedido = " .$idPedido . " AND produto = " . $linhaItensCarrinho["produto"];
						$queryVerificaItemPedido = $conn->query($sqlVerificaItemPedido);
						if ($queryVerificaItemPedido->rowcount() > 0){
							$linhaVerificaItemPedido = $queryVerificaItemPedido->fetch();
							$idItemPedido = $linhaVerificaItemPedido["id"];
						}else{
							$idItemPedido = getProxId("ecommerce_pedidoitem",$conn);
							$sqlInserirItemPedido = "INSERT INTO td_ecommerce_pedidoitem (id,pedido,produto,qtde,descricao,valor,valortotal) VALUES (
							".$idItemPedido.",".$idPedido.",".$linhaItensCarrinho["produto"].",".$linhaItensCarrinho["qtde"].",'".$linhaItensCarrinho["descricao"]."',".$linhaItensCarrinho["valor"].",{$valorTotalItem}
							);";
							logPagSeguro($sqlInserirItemPedido);
							$queryInserirItemPedido = $conn->query($sqlInserirItemPedido);
						}
						$valortotalproduto = $linhaItensCarrinho["qtde"] * $linhaItensCarrinho["valor"];

						/* ******************************
							Atualização no Estoque - Inicio
						****************************** */
						$iscontrolaestoque 	= true;
						$tipooepracaoestoque = 2;
						if ($iscontrolaestoque){
							// Selecione a Operação de Estoque					
							$sqlTipoOperacaoEstoque = "SELECT ifnull(operacaoestoque,{$tipooepracaoestoque}) operacao FROM td_ecommerce_statuspedido WHERE id = " . $transaction->status. ";";
							logPagSeguro($sqlTipoOperacaoEstoque);
							$queryTipoOperacaoEstoque = $conn->query($sqlTipoOperacaoEstoque);
							if ($queryTipoOperacaoEstoque->rowCount() > 0){
								$linhaTipoOperacaoEstoque = $queryTipoOperacaoEstoque->fetch();
								$tipooepracaoestoque = $linhaTipoOperacaoEstoque["operacao"];
							}
						}
						
						/*
						$data = array(
							"controller" => "ecommerce/posicaogeralestoque",
							"key" => "k",
							"quantidade" => $linhaItensCarrinho["qtde"],
							"operacao" => $tipooepracaoestoque,
							"variacaoproduto" => $linhaItensCarrinho["produto"]
						);
	
						$curl = curl_init();
						curl_setopt_array($curl, [
							CURLOPT_RETURNTRANSFER => 1,
							CURLOPT_URL => $_SESSION["PATH_URL_SYSTEM"] . 'index.php',
							CURLOPT_POSTFIELDS => $data
						]);
						$response = curl_exec($curl);
						curl_close($curl);
						*/
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
				var_dump(curl_error($curl));
				curl_close($curl);
				logPagSeguro("Aplicativo:");
				logPagSeguro($response);

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
				
				$conn->commit();
				*/
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
		fclose($file);
>>>>>>> dfd2109f (#qeru - iniciando fase de teste)
	}