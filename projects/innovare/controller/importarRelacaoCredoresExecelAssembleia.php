<?php
	/*
		#Importação de Credores para a Assembleia
		- Serve para enviar apenas para a assembleia
		- Sem endereço
		- Sem E-Mail

		Layout
			0 - Nome
			1 - CPF/CNPJ
			2 - Código Classificação
			3 - Código da Moeda
			4 - Valor do Crédito
	*/

	// Camada de Modelo ( MVC )
	include_once PATH_CURRENT_MODEL . 'importarRelacaoCredoresExecelAssembleia.php';

	set_time_limit(3600);
	if (isset($_GET["op"])){
		if ($_GET["op"] == "excluircredores"){
			$conn = Transacao::Get();
			$sql = "SELECT id FROM td_relacaocredores WHERE tipo = 0;";
			$query = $conn->query($sql);
			while ($linha = $query->fetch()){
				$conn->exec("DELETE FROM td_relacaocredores WHERE id = " . $linha["id"]);
				$conn->exec("DELETE FROM td_lista WHERE entidadepai = 16 AND entidadefilho = 20 AND regpai = 18 AND regfilho = " . $linha["id"]);
			}
			Transacao::Fechar();
		}
	}
	if (isset($_FILES["arquivo"])){
		$processo 		= explode("^",$_POST["retorno_empresa"]);
		$xml 			= simplexml_load_file($_FILES["arquivo"]["tmp_name"]);
		$cLinha 		= 0;
		if ($conn = Transacao::get()){
			
			$bootstrap 			= tdClass::Criar("link");
			$bootstrap->href 	= URL_LIB . 'bootstrap/3.3.1/css/bootstrap.css';
			$bootstrap->rel 	= 'stylesheet';
			$bootstrap->mostrar();

			echo '	<table class="table table-hover table-bordered">
						<tr>
							<th width="50%">Nome</th>
							<th width="20%">CNPJ / CPF</th>
							<th width="5%">Clas.</th>
							<th width="5%">Moeda</th>
							<th width="5%">Valor</th>
							<th width="5%">Processo</th>
							<th width="5%">Origem</th>
							<th width="5%">Farein</th>
						</tr>
			';

			$linhaDadosCredor 	= "";
			$error 				= 0;
			// Os credores importados ficaram com a origem 8
			$_origem_credor 		= 8;

			foreach ($xml->Worksheet->Table->Row as $cell){
				$cLinha++;
				if ($cLinha<=1) continue; # Pula a primeira linha

				$nome 				= trocavazio("{$cell->Cell[0]->Data}");
				$cpfj 				= validaColuna($cell->Cell,1);
				$cpfjSemFormatar 	= ($cpfj!=""?(strlen($cpfj) > 11?$cpfj!=""?(completaString(trim($cpfj),14)):"":(completaString(trim($cpfj),11))):"");
				$classificacao 		= trocavazio((int)$cell->Cell[2]->Data);
				$moeda 				= trocavazio((int)$cell->Cell[3]->Data);
				$vlr 				= $cell->Cell[4]->Data;
				$valor 				= number_format((double)$vlr, 2, ',', '.');
				$linhaDadosCredor 	=
										"<td>" . $nome . "</td>".
										"<td>" .$cpfj . "-" . ($cpfj!=""?(strlen($cpfj) > 11?$cpfj!=""?formatarCNPJ(completaString(trim($cpfj),14)):"":formatarCPF(completaString(trim($cpfj),11))):"") . "</td>".
										"<td>" . $classificacao . "</td>".
										"<td>" . $moeda . "</td>".
										"<td>" . $valor  . "</td>".										
										"<td>" . $processo[1] . "</td>".
										"<td>" . $_origem_credor . "</td>".
										"<td>" . (int)$processo[0] . "</td>";

				if ($cpfjSemFormatar != ""){
					if (!isCPFJ($cpfjSemFormatar)){
						$error = 2;
						msgErroValidacao('<b>CPF ou CNPJ</b> não é válido. => <b>' . $cpfjSemFormatar . '</b>',$linhaDadosCredor);
						break;
					}
				}

				if (!is_numeric($classificacao)){
					$error = 3;
					msgErroValidacao('<b>Classificação</b> não é válido.',$linhaDadosCredor);
					break;
				}

				if (!is_numeric($moeda)){
					$error = 5;
					msgErroValidacao('<b>Tipo de Moeda</b> não é válido.',$linhaDadosCredor);
					break;
				}

				if (!is_money($valor)){
					$error = 6;
					msgErroValidacao('<b>Valor</b> não está num formato válido.',$linhaDadosCredor);
					break;
				}

				$sqlExiste = "
					SELECT id FROM td_relacaocredores 
					WHERE (REPLACE(REPLACE(REPLACE(cnpj,'/',''),'-',''),'.','') = '$cpfj' 
					OR REPLACE(REPLACE(cpf,'-',''),'.','') = '$cpfj') 
					AND (
						cpf <> '$cpfj' OR cnpj <> '$cpfj'
					) 
					AND (
						cpf IS NOT NULL OR cnpj IS NOT NULL
					) 
					AND classificacao = {$classificacao} 
					AND processo = {$processo[1]} 
					AND farein = {$processo[0]} 					
					AND origemcredor = $_origem_credor;
				";
				$queryExiste = $conn->query($sqlExiste);
				if ($queryExiste->rowcount() <= 0){
					$entidadecredor 					= "td_relacaocredores";
					$credor 							= tdClass::Criar("persistent",array($entidadecredor));
					$idCredor 							= $credor->contexto->getUltimo() + 1;
					$credor->contexto->id 				= $idCredor;
					$credor->contexto->nome				= tdc::utf8($nome);

					if ($cpfj == ""){
						$credor->contexto->cnpj 		= "#" . $idCredor;
						$credor->contexto->cpf 			= "#" . $idCredor;
					}else{
						if (strlen($cpfjSemFormatar) > 14){
							$credor->contexto->cnpj 		= formatarCNPJ(completaString($cpfjSemFormatar,14));
							$credor->contexto->tipo			= 1;
						}else{
							$credor->contexto->cpf			= formatarCPF(completaString($cpfjSemFormatar,11));
							$credor->contexto->tipo			= 2;
						}
					}

					$credor->contexto->classificacao 	= $classificacao;					
					$credor->contexto->moeda 			= $moeda;
					$credor->contexto->valor 			= $valor;

					$credor->contexto->codigo			= 0;
					$credor->contexto->natureza 		= 0;
					$credor->contexto->numerorelacao	= 0;

					// Dados do Endereço
					$credor->contexto->logradouro		= '#';
					$credor->contexto->cep				= '#';
					$credor->contexto->numero			= '#';
					$credor->contexto->cidade			= 0;
					$credor->contexto->estado			= 0;

					$credor->contexto->processo			= (int)$processo[1];
					$credor->contexto->origemcredor		= $_origem_credor;
					$credor->contexto->farein			= (int)$processo[0];
					$credor->contexto->armazenar();

					// Salvando Relcionamento na Lista
					$lista = tdClass::Criar("persistent",array(LISTA))->contexto;
					$lista->entidadepai 			= $processo[1];
					$lista->entidadefilho 			= 20;
					$lista->regpai 					= $processo[0];
					$lista->regfilho 				= $idCredor;
					$lista->id 						= $lista->getUltimo() + 1;

					$lista->armazenar();
				}else{
					$linhaExiste = $queryExiste->fetch();
					if (is_numeric($linhaExiste["id"])){
						if ($linhaExiste["id"] > 0){
							$credor 						= tdClass::Criar("persistent",array("td_relacaocredores",$linhaExiste["id"]));
							$credor->contexto->moeda 		= $moeda;
							$credor->contexto->valor 		= $valor;
							$credor->contexto->armazenar();
						}
					}
				}
				echo '<tr>' .$linhaDadosCredor . '</tr>';
			}
			echo '</table>';

			if ($error > 0){
				finalizar();
				exit;
			}
			Transacao::Commit();
			echo '<div class="alert alert-success" role="alert"><b> Que Bom !</b> Os arquivos foram importados com sucesso</div>';
			finalizar();
		}
		exit;
	}

	// Camada de Visualização ( MVC )
	include_once PATH_CURRENT_VIEW . 'importarRelacaoCredoresExecelAssembleia.php';
