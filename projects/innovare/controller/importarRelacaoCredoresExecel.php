<?php
	/*
		#Importação completa de credores 
		- Serve tanto para o edital como para comunicação ao correios
		- Versão sem as descrições, apenas os código do gabarito

		Layout
			0 - Código
			1 - Nome
			2 - Tipo ( 1 - Jurídica / 2 - Física )
			3 - CPF/CNPJ
			4 - Código Classificação
			5 - Código da Natureza
			6 - Código da Moeda
			7 - Valor do Crédito
			8 - E-Mail
			9 - CEP
			10 - Logradouro
			11 - Número
			12 - Complemento
			13 - Bairro
			14 - Código da Cidade
			15 - Sigla do Estado
			16 - País
			17 - Endereço Exterior
	*/

	// Camada de Modelo ( MVC )
	include_once PATH_CURRENT_MODEL . 'importarrelacaocredoresexcel.php';

	set_time_limit(7200);
	
	if (isset($_POST['b-gerar'])){
		$is_file_uploaded = isset($_FILES["arquivo"]) ? ($_FILES["arquivo"]['tmp_name'] == '' ? false : true) : false;

		if (!$is_file_uploaded){
			exit;
		}

		$bootstrap 			= tdClass::Criar("link");
		$bootstrap->href 	= URL_LIB . 'bootstrap/3.3.1/css/bootstrap.css';
		$bootstrap->rel 	= 'stylesheet';
		$bootstrap->mostrar();
		
		$processo 				= explode("^",$_POST["retorno_empresa"]);
		$origemcredor 			= isset($_POST["credorescomunicacao"]) ? 10 : 1 ;
		$relacaoinicial			= isset($_POST["relacaoinicial"]) ? true : false;
		$credoresestrangeiro	= isset($_POST["credoresestrangeiro"]) ? true : false;
		$isagruparcredor		= isset($_POST["isagruparcredor"]) ? true : false;
		$numerorelacao			= isset($_POST["numerorelacao"]) ? ($_POST["numerorelacao"]==''?1:$_POST["numerorelacao"]) : 1;
		
		if (!is_numeric($numerorelacao)){
			msgError('Valor do número da relação inválido => <b>' . $numerorelacao . '</b>');
			exit;
		}else{
			if ($numerorelacao <= 0) $numerorelacao = 1;
		}

		try{
			$xml 		= simplexml_load_file($_FILES["arquivo"]["tmp_name"]);
		}catch(Throwable $e){
			setErrorImportacao('Erro na importação do arquivo de relação de credores.');
			exit;
		}

		try{
			verificaLayout($xml->Worksheet->Table->Row);
		}catch(Throwable $e){
			setErrorImportacao('Layout do arquivo inválido.');
			if (IS_SHOW_ERROR_MESSAGE){
				echo $e->getMessage();
			}
			exit;
		}
		
		$cLinha	= 0;
		if ($conn = Transacao::get()){
			
			echo '	<table class="table table-hover table-bordered">
						<tr>
							<th width="%3">Cód.</th>
							<th width="35%">Nome</th>
							<th width="1%">Tp</th>
							<th width="5%">CNPJ / CPF</th>
							<th width="1%">Clas.</th>
							<th width="1%">Nat.</th>
							<th width="1%">Moeda</th>
							<th width="1%">Valor</th>
							<th width="7%">E-Mail</th>
							<th width="1%">CEP</th>
							<th width="1%">Logradouro</th>
							<th width="1%">Número</th>
							<th width="1%">Complemento</th>
							<th width="1%">Bairro</th>
							<th width="1%">Cidade</th>
							<th width="1%">UF</th>
							<th width="1%">Processo</th>
							<th width="1%">Origem</th>
							<th width="1%">Farein</th>
						</tr>
			';
			$valores 			= array();
			$linhaDadosCredor 	= "";
			$error 				= 0;
			foreach ($xml->Worksheet->Table->Row as $cell){
				$cLinha++;
				$codigo = $cell->Cell[0]->Data;
				if ($cLinha<=1 || $codigo=="") continue; # Pula a primeira linha
				$nome 				= trocavazio("{$cell->Cell[1]->Data}");
				$tipo 				= (int)trocavazio(($cell->Cell[2]->Data==""?2:$cell->Cell[2]->Data));
				$cpfj 				= validaColuna($cell->Cell,3);
				$classificacao 		= trocavazio((int)$cell->Cell[4]->Data);
				$natureza 			= trocavazio($cell->Cell[5]->Data,0);
				$moeda 				= trocavazio((int)$cell->Cell[6]->Data);
				$vlr 				= validaColuna($cell->Cell,7);
				$valor 				= number_format((double)$vlr, 2, ',', '.');
				//$tipo_empresa		= trocavazio("{$cell->Cell[8]->Data}")
				$email 				= trocavazio("{$cell->Cell[9]->Data}");
				$cep_				= trocavazio("{$cell->Cell[10]->Data}");
				$cep 				= $cep_ == '' ? '' : completaString($cep_,8);
				$logradouro 		= trocavazio("{$cell->Cell[11]->Data}");
				$numero 			= trocavazio("{$cell->Cell[12]->Data}");
				$complemento 		= trocavazio("{$cell->Cell[13]->Data}");
				$bairro 			= trocavazio("{$cell->Cell[14]->Data}");

				$estado 			= trocavazio("{$cell->Cell[16]->Data}");
				if ($estado != ''){
					$cidade 			= getCidadeId(trocavazio(trim($cell->Cell[15]->Data)),$estado);
				}else{
					$cidade				= '';
				}
				

				$pais				= trocavazio("{$cell->Cell[17]->Data}"); # Implementar país
				$enderecoexterior 	= validaColuna($cell->Cell,18);
				$linhaDadosCredor =		"<td>" . $codigo . "</td>".
										"<td>" . $nome . "</td>".
										"<td>" . $tipo . "</td>".
										"<td>" . $cpfj . "</td>".
										"<td>" . $classificacao . "</td>".
										"<td>" . $natureza . "</td>".
										"<td>" . $moeda . "</td>".
										"<td>" . $valor  . "</td>".
										"<td>" . $email . "</td>".
										"<td>" . $cep . "</td>".
										"<td>" . $logradouro . "</td>".
										"<td>" . $numero . "</td>".
										"<td>" . $complemento . "</td>".
										"<td>" . $bairro . "</td>".
										"<td>" . $cidade . "</td>".
										"<td>" . $estado . "</td>".
										"<td>" . $processo[1] . "</td>".
										"<td>" . $origemcredor . "</td>".
										"<td>" . (int)$processo[0] . "</td>";
				
				if (!$credoresestrangeiro) {
					if (!($tipo == 1 || $tipo == 2)){
						$error = 1;
						msgErroValidacao('<b>Tipo</b> não é um número válido.',$linhaDadosCredor);
						break;
					}
				}
				
				if ($cpfj != "" && !$credoresestrangeiro){
					if (!isCPFJ($cpfj)){
						$error = 2;
						msgErroValidacao('<b>CPF ou CNPJ</b> não é válido. => <b>' . $cpfj . '</b>',$linhaDadosCredor);
						break;
					}
				}

				if (!is_numeric($classificacao)){
					$error = 3;
					msgErroValidacao('<b>Classificação</b> não é válido.',$linhaDadosCredor);
					break;
				}
				
				if ($classificacao <= 0){
					$error = 13;
					msgErroValidacao('Código da Classificação => <b>'.$classificacao.'</b> não é válido.',$linhaDadosCredor);
					break;
				}

				if (!is_numeric($natureza) && $natureza != "#"){
					$error = 4;
					msgErroValidacao('<b>Natureza</b> não é válido.',$linhaDadosCredor);
					break;
				}

				if ($natureza <= 0){
					$error = 14;
					msgErroValidacao('Código da Natureza => <b>'.$natureza.'</b> não é válido.',$linhaDadosCredor);
					break;
				}
				
				if (!is_numeric($moeda)){
					$error = 5;
					msgErroValidacao('<b>Moeda</b> não é válido.',$linhaDadosCredor);
					break;
				}

				if ($moeda <= 0){
					$error = 15;
					msgErroValidacao('Código da Moeda => <b>'.$moeda.'</b> não é válido.',$linhaDadosCredor);
					break;
				}

				if (!is_money($valor)){
					$error = 6;
					msgErroValidacao('<b>Valor</b> não está num formato válido.',$linhaDadosCredor);
					break;
				}

				# Não validar endereço quando:
				# - For uma relação com credores estrangeiros
				# - For uma relação apenas para compor o edital inicial expedido pelo juiz
				if (!$credoresestrangeiro && !$relacaoinicial) {
					if ($email != ""){
						if (!isemail($email)){
							$error = 8;
							msgErroValidacao('<b>E-Mail ('.$email.')</b> não está num formato válido.',$linhaDadosCredor);
							break;
						}
					}
					
					if ($cep == ''){
						$error = 16;
						msgErroValidacao('<b>CEP </b> não pode estar em branco.',$linhaDadosCredor);
						break;
					}else if (!isCEP($cep)){							
						$error = 9;
						msgErroValidacao('<b>CEP ['.$cep.']</b> não está num formato válido.',$linhaDadosCredor);
						break;
					}

					if (!is_numeric($cidade)){
						$error = 10;
						msgErroValidacao('<b>Cidade ['.$cidade.']</b> não é um número válido.',$linhaDadosCredor);
						break;
					}

					if (!isUFSigla(strtoupper($estado))){
						$error = 11;
						msgErroValidacao('<b>Estado (UF)</b> não está num formato válido.',$linhaDadosCredor);
						break;
					}else{
						$estado = retornaEstado($estado);
						if ($estado <= 0){
							$error = 12;
							msgErroValidacao('<b>Estado (UF)</b> não está cadastrado no sistema.',$linhaDadosCredor);
							break;
						}
					}
				}else{
					$cidade = 0;
					$estado = 0;					
				}

				$sqlExiste = "SELECT id FROM td_relacaocredores WHERE (replace(replace(replace(cnpj,'/',''),'-',''),'.','') = '$cpfj' or replace(replace(cpf,'-',''),'.','') = '$cpfj') AND (cpf <> '$cpfj' or cnpj <> '$cpfj') AND classificacao = {$classificacao} AND processo = {$processo[1]} AND farein = {$processo[0]} AND moeda = {$moeda}";
				$queryExiste = $conn->query($sqlExiste);
				
				if ($queryExiste->rowcount() > 0 && $cpfj != "" && $isagruparcredor){
					$linhaExiste = $queryExiste->fetch();
					$credorUpdate = tdClass::Criar("persistent",array("td_relacaocredores",$linhaExiste["id"]));
					if (!isset($valores[$cpfj])){
						$valores[(int)$cpfj] = moneyToFloat($vlr);	
					}else{
						$valores[(int)$cpfj] += moneyToFloat($vlr);
					}
					$credorUpdate->contexto->valor = $valores[$cpfj];
					$credorUpdate->contexto->armazenar();
				}else{
					$valores[(int)$cpfj] = moneyToFloat($vlr);
					echo '<tr>' .$linhaDadosCredor . '</tr>';
					if (isset($credoresestrangeiro)) {
						$entidadecredor = "td_relacaocredores";
					}else{
						$entidadecredor = "td_relacaocredores_temp";
					}
					$credor = tdClass::Criar("persistent",array($entidadecredor));
					$idCredor 							= $credor->contexto->getUltimo() + 1;
					$credor->contexto->codigo 			= trocavazio("{$codigo}");
					$credor->contexto->nome				= $nome;
					$credor->contexto->tipo 			= $tipo;
					if ($cpfj == ""){
						$credor->contexto->cnpj 		= '';
						$credor->contexto->cpf 			= '';
					}else{
						if (strlen($cpfj) > 11){
							$credor->contexto->cnpj 		= formatarCNPJ(completaString($cpfj,14));
						}else{
							$credor->contexto->cpf			= formatarCPF(completaString($cpfj,11));
						}
					}
					$credor->contexto->classificacao 	= $classificacao;
					$credor->contexto->natureza 		= $natureza;
					$credor->contexto->moeda 			= $moeda;
					$credor->contexto->valor 			= $valor;
					$credor->contexto->email 			= $email;
					$credor->contexto->cep 				= $cep;
					$credor->contexto->logradouro 		= $logradouro;
					$credor->contexto->numero 			= $numero;
					$credor->contexto->complemento 		= $complemento;
					$credor->contexto->bairro 			= $bairro;
					$credor->contexto->cidade 			= $cidade;
					$credor->contexto->estado 			= $estado;
					$credor->contexto->enderecoexterior = $enderecoexterior;
					$credor->contexto->codigocredor 	= completaString($processo[0],5) . "." . completaString($processo[1],10) . "." . completaString($idCredor,10);

					$credor->contexto->processo			= (int)$processo[1];
					$credor->contexto->origemcredor		= $origemcredor;
					$credor->contexto->farein			= (int)$processo[0];
					$credor->contexto->numerorelacao	= $numerorelacao;
					$credor->contexto->armazenar();
					
					if ($credoresestrangeiro) { // Adiciona na lista caso seja estrangeiro
						// Salvando Relcionamento na Lista
						$lista 					= tdClass::Criar("persistent",array(LISTA))->contexto;
						$lista->entidadepai 	= $processo[2];
						$lista->entidadefilho 	= 20;
						$lista->regpai 			= $processo[0];
						$lista->regfilho 		= $idCredor;
						$lista->id 				= $lista->getUltimo() + 1;
						$lista->armazenar();
					}
				}
			}
			echo '</table>';
			
			if ($error > 0){
				exit;
			}
			if (!$credoresestrangeiro) { // Não pode conciliar com CNPJ/CPF os credores estrangeiro
				// Concilia CNPJ e CPF
				$sql 	= "
					SELECT
						nome,tipo,cnpj,cpf,classificacao,natureza,moeda,email,
						cep,logradouro,numero,complemento,bairro,cidade,estado,enderecoexterior,
						SUM(valor) soma
					FROM td_relacaocredores_temp 
					WHERE farein = ".(int)$processo[0]." 
					AND origemcredor = {$origemcredor} 
					AND (cpf IS NOT NULL OR cnpj IS NOT NULL) 
					GROUP BY classificacao,cnpj,cpf,nome,tipo,natureza,moeda,email,
					cep,logradouro,numero,complemento,bairro,cidade,estado,enderecoexterior;
				";
				$query 	= $conn->query($sql);
				$soma 	= 0;
				$codigo = 1;
				while ($linha = $query->fetch()){
					$soma += $linha["soma"];
					
					$credor = tdClass::Criar("persistent",array("td_relacaocredores"));
					$idCredor 							= $credor->contexto->getUltimo() + 1;
					$credor->contexto->codigo 			= $codigo;
					$credor->contexto->nome				= $linha["nome"];
					$credor->contexto->tipo 			= $linha["tipo"];
					$credor->contexto->cnpj 			= substr($linha["cnpj"],0,1)=="#"?"null":$linha["cnpj"];
					$credor->contexto->cpf				= substr($linha["cpf"],0,1)=="#"?"null":$linha["cpf"];
					$credor->contexto->classificacao 	= $linha["classificacao"];
					$credor->contexto->natureza 		= $linha["natureza"];
					$credor->contexto->moeda 			= $linha["moeda"];
					$credor->contexto->valor 			= $linha["soma"];
					$credor->contexto->email 			= $linha["email"];
					$credor->contexto->cep 				= $linha["cep"];
					$credor->contexto->logradouro 		= $linha["logradouro"];
					$credor->contexto->numero 			= $linha["numero"];
					$credor->contexto->complemento 		= $linha["complemento"];
					$credor->contexto->bairro 			= $linha["bairro"];
					$credor->contexto->cidade 			= $linha["cidade"];
					$credor->contexto->estado 			= $linha["estado"];
					$credor->contexto->enderecoexterior = $linha["enderecoexterior"];
					$credor->contexto->codigocredor 	= completaString($processo[0],5) . "." . completaString($processo[1],10) . "." . completaString($idCredor,10);

					$credor->contexto->processo			= (int)$processo[1];
					$credor->contexto->origemcredor		= $origemcredor;
					$credor->contexto->farein			= (int)$processo[0];
					$credor->contexto->numerorelacao	= $numerorelacao;
					$credor->contexto->armazenar();

					// Salvando Relcionamento na Lista
					$lista 					= tdClass::Criar("persistent",array(LISTA))->contexto;
					$lista->entidadepai 	= $processo[2];
					$lista->entidadefilho 	= 20;
					$lista->regpai 			= $processo[0];
					$lista->regfilho 		= $idCredor;
					$lista->id 				= $lista->getUltimo() + 1;
					$lista->armazenar();

					$codigo++;
				}

				$conn->exec("truncate table td_relacaocredores_temp;");
			}
			Transacao::Commit();
			echo '<div class="alert alert-success" role="alert"><b> Que Bom !</b> Os arquivos foram importados com sucesso</div>';
			hideProgressBar();
		}
		exit;
	}

	// Camada de Visualização ( MVC )
	include_once PATH_CURRENT_VIEW . 'importarRelacaoCredoresExcel.php';