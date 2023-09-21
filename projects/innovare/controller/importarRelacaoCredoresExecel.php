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
	set_time_limit(7200);
	if (isset($_FILES["arquivo"])){
		$processo 				= explode("^",$_POST["processo"]);
		$origemcredor 			= isset($_POST["credorescomunicacao"]) ? 10 : 1 ;
		$credoresestrangeiro	= isset($_POST["credoresestrangeiro"]) ? true : false;
		$isagruparcredor		= isset($_POST["isagruparcredor"]) ? true : false;
		$numerorelacao			= isset($_POST["numerorelacao"]) ? ($_POST["numerorelacao"]==''?1:$_POST["numerorelacao"]) : 1;
		
		if (!is_numeric($numerorelacao)){
			msgError('Valor do número da relação inválido => <b>' . $numerorelacao . '</b>');
			exit;
		}else{
			if ($numerorelacao <= 0) $numerorelacao = 1;
		}

		$xml = simplexml_load_file($_FILES["arquivo"]["tmp_name"]);
		$cLinha = 0;
		if ($conn = Transacao::get()){

			$bootstrap = tdClass::Criar("link");
			$bootstrap->href = PATH_LIB . 'bootstrap/3.3.1/css/bootstrap.css';
			$bootstrap->rel = 'stylesheet';
			$bootstrap->mostrar();
			
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
			$valores = array();
			$linhaDadosCredor = "";
			$error = 0;
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
				$vlr 				= $cell->Cell[7]->Data;
				$valor 				= number_format((double)$vlr, 2, ',', '.');
				$email 				= trocavazio("{$cell->Cell[8]->Data}");
				$cep 				= completaString(trocavazio("{$cell->Cell[9]->Data}"),8);
				$logradouro 		= trocavazio("{$cell->Cell[10]->Data}");
				$numero 			= trocavazio("{$cell->Cell[11]->Data}");
				$complemento 		= trocavazio("{$cell->Cell[12]->Data}");
				$bairro 			= trocavazio("{$cell->Cell[13]->Data}");
				$cidade 			= trocavazio(trim($cell->Cell[14]->Data));
				$estado 			= trocavazio("{$cell->Cell[15]->Data}");
				$pais				= trocavazio("{$cell->Cell[16]->Data}"); # Implementar país
				$enderecoexterior 	= validaColuna($cell->Cell,17);
				$linhaDadosCredor =		"<td>" . $codigo . "</td>".
										"<td>" . $nome . "</td>".
										"<td>" . $tipo . "</td>".
										//"<td>" . ($cpfj!=""?(strlen($cpfj) > 11?$cpfj!=""?formatarCNPJ(completaString(trim($cpfj),14)):"":formatarCPF(completaString(trim($cpfj),11))):"") . "</td>".
										"<td>".$cpfj."</td>".
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

				if (!$credoresestrangeiro) {
					if ($email != ""){
						if (!isemail($email)){
							$error = 8;
							msgErroValidacao('<b>E-Mail ('.$email.')</b> não está num formato válido.',$linhaDadosCredor);
							break;
						}
					}
					if (!isCEP($cep)){
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
						msgErroValidacao('<b>Estado</b> não está num válido.',$linhaDadosCredor);
						break;
					}else{
						$estado = retornaEstado($estado);
						if ($estado <= 0){
							$error = 12;
							msgErroValidacao('<b>Estado</b> não está cadastrado no sistema.',$linhaDadosCredor);
							break;						
						}
					}
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
					$credor->contexto->classificacao = $classificacao;
					$credor->contexto->natureza 		= $natureza;
					$credor->contexto->moeda 		= $moeda;
					$credor->contexto->valor 			= $valor;
					$credor->contexto->email 			= $email;
					$credor->contexto->cep 				= $cep;
					$credor->contexto->logradouro 		= $logradouro;
					$credor->contexto->numero 			= $numero;
					$credor->contexto->complemento 		= $complemento;
					$credor->contexto->bairro 			= $bairro;
					$credor->contexto->cidade 			= $cidade;
					$credor->contexto->estado 		= $estado;
					$credor->contexto->enderecoexterior = $enderecoexterior;
					$credor->contexto->codigocredor 	= completaString($processo[0],5) . "." . completaString($processo[1],10) . "." . completaString($idCredor,10);

					$credor->contexto->processo		= (int)$processo[1];
					$credor->contexto->origemcredor	= $origemcredor;
					$credor->contexto->farein			= (int)$processo[0];
					$credor->contexto->numerorelacao	= $numerorelacao;
					$credor->contexto->armazenar();
					
					if ($credoresestrangeiro) { // Adiciona na lista caso seja estrangeiro
						// Salvando Relcionamento na Lista
						$lista = tdClass::Criar("persistent",array(LISTA))->contexto;
						$lista->entidadepai = $processo[2];
						$lista->entidadefilho = 20;
						$lista->regpai = $processo[0];
						$lista->regfilho = $idCredor;
						$lista->id = $lista->getUltimo() + 1;
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
				$sql = "select *,sum(valor) soma from td_relacaocredores_temp where farein = ".(int)$processo[0]." and origemcredor = {$origemcredor} and (cpf is not null or cnpj is not null) group by classificacao,cnpj,cpf";
				$query = $conn->query($sql);
				$soma = 0;
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
					$credor->contexto->classificacao = $linha["classificacao"];
					$credor->contexto->natureza 		= $linha["natureza"];
					$credor->contexto->moeda 		= $linha["moeda"];
					$credor->contexto->valor 			= $linha["soma"];
					$credor->contexto->email 			= $linha["email"];
					$credor->contexto->cep 				= $linha["cep"];
					$credor->contexto->logradouro 		= $linha["logradouro"];
					$credor->contexto->numero 			= $linha["numero"];
					$credor->contexto->complemento 		= $linha["complemento"];
					$credor->contexto->bairro 			= $linha["bairro"];
					$credor->contexto->cidade 			= $linha["cidade"];
					$credor->contexto->estado 		= $linha["estado"];
					$credor->contexto->enderecoexterior = $linha["enderecoexterior"];
					$credor->contexto->codigocredor 	= completaString($processo[0],5) . "." . completaString($processo[1],10) . "." . completaString($idCredor,10);

					$credor->contexto->processo		= (int)$processo[1];
					$credor->contexto->origemcredor	= $origemcredor;
					$credor->contexto->farein			= (int)$processo[0];
					$credor->contexto->numerorelacao	= $numerorelacao;
					$credor->contexto->armazenar();

					// Salvando Relcionamento na Lista
					$lista = tdClass::Criar("persistent",array(LISTA))->contexto;
					$lista->entidadepai = $processo[2];
					$lista->entidadefilho = 20;
					$lista->regpai = $processo[0];
					$lista->regfilho = $idCredor;
					$lista->id = $lista->getUltimo() + 1;
					$lista->armazenar();

					$codigo++;
				}

				$conn->exec("truncate table td_relacaocredores_temp;");
			}
			Transacao::Fechar();
			echo '<div class="alert alert-success" role="alert"><b> Que Bom !</b> Os arquivos foram importados com sucesso</div>';
		}
		exit;
	}
	$pagina = tdClass::Criar("div");
	
	// Bloco do formulario
	$form_bloco = tdClass::Criar("bloco");
	$form_bloco->class="col-md-12";	
	
	$form = tdClass::Criar("tdformulario");
	$form->legenda->add(utf8_decode("Importar Relação de Credores ( Excel )"));
		
	$form->action 	= getURLProject("index.php?controller=importarRelacaoCredoresExecel");
	$form->method 	= "POST";
	$form->enctype 	= "multipart/form-data";
	$form->target 	= "retorno-importacao";
	
	$select_processo 			= tdClass::Criar("select");
	$select_processo->class 	= "form-control";
	$select_processo->id 		= "processo";
	$select_processo->name 		= "processo";

	$opSelect = tdClass::Criar("option");
	$opSelect->add('SELECIONE');
	$select_processo->add($opSelect);

	$filtro = tdc::o("sqlcriterio");
	$filtro->setPropriedade("order","id DESC");
	foreach(tdc::d("td_processo",$filtro) as $processo){
		$descricao 							= tdc::utf8($processo->descricao);
		$groupProcessoRecuperacao 			= tdc::o("optgroup");
		$groupProcessoRecuperacao->label 	= "[ {$processo->id} ][ {$processo->numeroprocesso} ] {$descricao}";

		// Recuperanda
		if ($processo->tipoprocesso == 16){
			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("processo","=",$processo->id);
			$dataset = tdClass::Criar("repositorio",array("td_recuperanda"))->carregar($sql);
			foreach ($dataset as $dado){
				$op = tdClass::Criar("option");
				$op->value = $dado->id . "^" . $dado->processo . "^RE";
				$op->add($dado->id . "-"  . $dado->razaosocial . " [ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj). " ] ");
				$groupProcessoRecuperacao->add($op);
			}
		}
		
		// Falida
		if ($processo->tipoprocesso == 19){
			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("processo","=",$processo->id);
			$dataset = tdClass::Criar("repositorio",array("td_falencia"))->carregar($sql);
			foreach ($dataset as $dado){
				$op = tdClass::Criar("option");
				$op->value = $dado->id . "^" . $dado->processo . "^FA";
				$op->add($dado->id ."-". $dado->razaosocial . " [ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj)." ] ");
				$groupProcessoRecuperacao->add($op);
			}
		}

		// Insolvente
		if ($processo->tipoprocesso == 18){			
			$sql = tdClass::Criar("sqlcriterio");
			$sql->addFiltro("processo","=",$processo->id);
			$dataset = tdClass::Criar("repositorio",array("td_insolvente"))->carregar($sql);
			foreach ($dataset as $dado){
				$op = tdClass::Criar("option");
				$op->value = $dado->id . "^" . $dado->processo . "^IN";
				$op->add($dado->id . "-" . $dado->razaosocial ." [ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj)." ] ");
				$groupProcessoRecuperacao->add($op);
			}
		}

		$select_processo->add($groupProcessoRecuperacao);
	}

	$label_processo = tdClass::Criar("label");
	$label_processo->add("Selecione o processo");

	$file = tdClass::Criar("input");
	$file->type = "file";
	$file->id = "arquivo";
	$file->name = "arquivo";

	// Botão Gerar	
	$btn_gerar = tdClass::Criar("button");
	$btn_gerar->value = "Importar";	
	$btn_gerar->class = "btn btn-primary b-gerar";
	$span_gerar = tdClass::Criar("span");
	$span_gerar->class = "fas fa-file";
	$btn_gerar->add($span_gerar," Importar");	
	$btn_gerar->id = "b-gerar";
	$btn_gerar->type = "submit";

	// Baixar Modelo de Importação do Execel
	$btn_modelo = tdClass::Criar("button");
	$btn_modelo->id = "btn-abrir-modelo";	
	$btn_modelo->value = "Modelo";
	$btn_modelo->class = "btn btn-default";
	$span_modelo = tdClass::Criar("span");
	$span_modelo->class = "fas fa-file";
	$btn_modelo->add($span_modelo, " Modelo ( Excel )");
	$btn_modelo->style="float:right;margin-right:10px;";

	// Grupo de botões
	$grupo_botoes = tdClass::Criar("div");
	$grupo_botoes->class = "form-grupo-botao";
	$grupo_botoes->add($btn_gerar,$btn_modelo);

	$linha = tdClass::Criar("div");
	$linha->class = "row-fluid form_campos";

	$label = tdClass::Criar("label");
	$label->add("Selecione o arquivo");

	$coluna = tdClass::Criar("div");
	$coluna->class = "coluna";
	$coluna->data_ncolunas = 1;
	$coluna->add($label,$file);	

	$coluna_processo = tdClass::Criar("div");
	$coluna_processo->class = "coluna";
	$coluna_processo->data_ncolunas = 1;
	$coluna_processo->add($label_processo,$select_processo);

	$label = tdClass::Criar("label");
	$label->add("");

	$checkboxEstrangeiro = '
		<div id="opcoes-importacao">
			<div class="checkbox">
				<input type="checkbox" id="credoresestrangeiro" name="credoresestrangeiro">
				<label for="credoresestrangeiro"> Lote de Credores Estrangeiro</label>
			</div>
			<div class="checkbox">
				<input type="checkbox" id="credorescomunicacao" name="credorescomunicacao">
				<label for="credorescomunicacao">Comunicação aos Credores</label>
			</div>
			<div class="checkbox">
				<input type="checkbox" id="isagruparcredor" name="isagruparcredor">
				<label for="isagruparcredor">Agrupar Credores por CPF/CNPJ</label>
			</div>
		</div>
	';

	$colunaLoteEstrangeiro = tdClass::Criar("div");
	$colunaLoteEstrangeiro->class = "coluna";
	$colunaLoteEstrangeiro->data_ncolunas = 1;
	$colunaLoteEstrangeiro->add($label,$checkboxEstrangeiro);

	$label = tdClass::Criar("label");
	$label->add("Número Relação");
	
	$selectNumeroRelacao 		= tdClass::Criar("select");
	$selectNumeroRelacao->class = "form-control";
	$selectNumeroRelacao->id 	= "numerorelacao";
	$selectNumeroRelacao->name 	= "numerorelacao";

	$sql = tdClass::Criar("sqlcriterio");
	$dataset = tdClass::Criar("repositorio",array("td_numerorelacaocredores"))->carregar($sql);
	foreach ($dataset as $dado){
		$op = tdClass::Criar("option");
		$op->value = $dado->id;
		$op->add($dado->descricao);
		$selectNumeroRelacao->add($op);
	}

	$colunaNumeroRelacao 	= tdClass::Criar("div");
	$colunaNumeroRelacao->class = "coluna";
	$colunaNumeroRelacao->data_ncolunas = 1;
	$colunaNumeroRelacao->add($label,$selectNumeroRelacao);

	$linha->add($coluna_processo,$coluna,$colunaNumeroRelacao,$colunaLoteEstrangeiro);

	$iframe = tdClass::Criar("iframe");
	$iframe->id 			= "retorno-importacao";
	$iframe->name 			= "retorno-importacao";
	$iframe->width 			= "100%";
	$iframe->height 		= "200px;";
	$iframe->border 		= "0";
	$iframe->frameborder 	= "0";
	$iframe->style 			= "border:0px;";
	
	// CSS
	$css = tdClass::Criar("style");
	$css->add('
		#opcoes-importacao{
			float: left;
			margin-left: 20px;
		}
		.form_campos .coluna{
			margin-top:30px;
		}
	');

	// JS
	$js = tdClass::Criar("script");
	$js->add('
		$("#btn-abrir-modelo").click(function(e){
			e.stopPropagation();
			e.preventDefault();
			window.open(session.urlroot + "projects/2/arquivos/modelo-importacao/ModelodeImportacao-RelacaoodeCredores.xlsx","_blank");
		});
	');
	
	$aviso = tdClass::Criar("div");
	$aviso->class = "alert alert-info";
	$aviso->add('
		<ul>
			<li>Importação de credores com todos os dados.</li>
			<li>Serve tanto para o edital como para comunicação aos correios.</li>
		</ul>
	');

	$form->fieldset->add($aviso,$grupo_botoes,$linha);
	$form_bloco->add($form,$iframe);
	$pagina->add($form_bloco,$css,$js);
	$pagina->mostrar();
	
	function retornaEstado($sigla){
		$retorno = 0;
		if ($conn = Transacao::Get()){
			$sql = "SELECT id FROM td_estado WHERE sigla = '$sigla'";
			$query = $conn->query($sql);
			$linha = $query->fetch();
			$retorno = $linha["id"];
		}else{
			$retorno = 0;
		}
		return $retorno;
	}	
	function trocavazio($str,$replace = ""){
		return trim(str_replace("#",$replace,$str));
	}
	function msgErroValidacao($erroMSG,$td){
		echo '<tr>' . $td . '<tr/>';
		echo '<tr><td colspan="20"><div class="alert alert-danger" role="alert">'.$erroMSG.'</div></td></tr>';
	}
	function msgError($msg){
		echo '<div class="alert alert-danger" role="alert">'.$msg.'</div>';
	}
	function validaColuna($celula,$indice){
		if (isset($celula[$indice])){
			if ($celula[$indice]->Data == '#'){
				return '';
			}else{
				return $celula[$indice]->Data;
			}
		}else{
			return '';
		}
	}