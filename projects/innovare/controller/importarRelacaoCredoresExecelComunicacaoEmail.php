<?php
	/*
		#Importação de Credores 
		- Serve para enviar apenas a comunicação por e-mail
		- Sem endereço

		Layout
			0 - Código
			1 - Nome
			2 - Tipo
			3 - CPF/CNPJ
			4 - Código Classificação
			5 - Código da Natureza
			6 - Código da Moeda
			7 - Valor do Crédito
			8 - E-Mail
	*/
	set_time_limit(7200);
	if (isset($_FILES["arquivo"])){
		$processo 	= explode("^",$_POST["processo"]);
		$xml 		= simplexml_load_file($_FILES["arquivo"]["tmp_name"]);
		$cLinha 	= 0;
		if ($conn = Transacao::get()){

			$bootstrap = tdClass::Criar("link");
			$bootstrap->href = PATH_LIB . 'bootstrap/3.3.1/css/bootstrap.css';
			$bootstrap->rel = 'stylesheet';
			$bootstrap->mostrar();

			echo '	<table class="table table-hover table-bordered">
						<tr>
							<th width="5%">Código</th>
							<th width="30%">Nome</th>
							<th width="15%">CNPJ / CPF</th>
							<th width="5%">Clas.</th>
							<th width="5%">Natureza.</th>
							<th width="5%">Moeda</th>
							<th width="5%">Valor</th>
							<th width="10%">E-Mail</th>
							<th width="5%">Processo</th>
							<th width="5%">Origem</th>
							<th width="5%">Farein</th>
						</tr>	
			';
			$valores = array();
			$linhaDadosCredor = "";
			$error = 0;
			$origemcredor = 11;
			foreach ($xml->Worksheet->Table->Row as $cell){
				$cLinha++;
				$codigo 			= $cell->Cell[0]->Data;
				if ($cLinha<=1 || $codigo=="" || $codigo == null) continue; # Pula a primeira linha
				$nome 				= trocavazio("{$cell->Cell[1]->Data}");
				$tipo 				= (int)trocavazio(($cell->Cell[2]->Data==""?2:$cell->Cell[2]->Data));				
				$cpfj 				= trocavazio(trim(str_replace(array("-",".","/"),"",$cell->Cell[3]->Data)));
				$cpfjSemFormatar 	= ($cpfj!=""?(strlen($cpfj) > 11?$cpfj!=""?(completaString(trim($cpfj),14)):"":(completaString(trim($cpfj),11))):"");
				$classificacao 		= trocavazio((int)$cell->Cell[4]->Data);
				$natureza			= trocavazio((int)$cell->Cell[5]->Data);
				$moeda 				= trocavazio((int)$cell->Cell[6]->Data);
				$vlr 				= $cell->Cell[7]->Data;
				$valor 				= number_format((double)$vlr, 2, ',', '.');
				$email = trocavazio("{$cell->Cell[8]->Data}");
				$linhaDadosCredor =
										"<td>" . $codigo . "</td>".
										"<td>" . $nome . "</td>".
										"<td>" . ($cpfj!=""?(strlen($cpfj) > 11?$cpfj!=""?formatarCNPJ(completaString(trim($cpfj),14)):"":formatarCPF(completaString(trim($cpfj),11))):"") . "</td>".
										"<td>" . $classificacao . "</td>".
										"<td>" . $natureza . "</td>".
										"<td>" . $moeda . "</td>".
										"<td>" . $valor  . "</td>".
										"<td>" . $email . "</td>".
										"<td>" . $processo[1] . "</td>".
										"<td>" . $origemcredor . "</td>".
										"<td>" . (int)$processo[0] . "</td>";

				if (!isset($_POST["credoresestrangeiro"])) {
					if (!($tipo == 1 || $tipo == 2)){
						$error = 1;
						msgErroValidacao('<b>Tipo</b> não é um número válido.',$linhaDadosCredor);
						break;
					}
				}

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

				$sqlExiste = "SELECT id FROM td_relacaocredores WHERE (replace(replace(replace(cnpj,'/',''),'-',''),'.','') = '$cpfj' or replace(replace(cpf,'-',''),'.','') = '$cpfj') AND (cpf <> '$cpfj' or cnpj <> '$cpfj') AND classificacao = {$classificacao} AND processo = {$processo[1]} AND farein = {$processo[0]} AND moeda = {$moeda} AND origemcredor = {$origemcredor}";
				$queryExiste = $conn->query($sqlExiste);
				if ($queryExiste->rowcount() > 0 && $cpfj != ""){
					$linhaExiste = $queryExiste->fetch();
					$credorUpdate = tdClass::Criar("persistent",array("td_relacaocredores",$linhaExiste["id"]));
					if (!isset($valores[$cpfj])){
						$valores[$cpfj] = moneyToFloat($vlr);	
					}else{
						$valores[$cpfj] += moneyToFloat($vlr);
					}
					$credorUpdate->contexto->valor = $valores[$cpfj];
					$credorUpdate->contexto->armazenar();
				}else{				
					$valores[$cpfj] = moneyToFloat($vlr);
					echo '<tr>' .$linhaDadosCredor. '</tr>';
					if (isset($_POST["credoresestrangeiro"])) {
						$entidadecredor = "td_relacaocredores";
					}else{
						$entidadecredor = "td_relacaocredores_temp";
					}
					$credor = tdClass::Criar("persistent",array($entidadecredor));
					$idCredor 							= $credor->contexto->getUltimo() + 1;
					$credor->contexto->id 				= $idCredor;
					$credor->contexto->codigo 			= trocavazio("{$codigo}");
					$credor->contexto->nome				= utf8_encode($nome);
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
					$credor->contexto->moeda 		= $moeda;
					$credor->contexto->valor 			= $valor;
					$credor->contexto->email 			= $email;
					$credor->contexto->codigocredor 	= completaString($processo[0],5) . "." . completaString($processo[1],10) . "." . completaString($idCredor,10);
					$credor->contexto->numerorelacao	= 1;

					$credor->contexto->processo		= (int)$processo[1];
					$credor->contexto->origemcredor	= $origemcredor;
					$credor->contexto->farein		= (int)$processo[0];
					$credor->contexto->armazenar();
					
					if (isset($_POST["credoresestrangeiro"])) { // Adiciona na lista caso seja estrangeiro
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
			if (!isset($_POST["credoresestrangeiro"])) { // Não pode conciliar com CNPJ/CPF os credores estrangeiro
				// Concilia CNPJ e CPF
				$sql = "select *,sum(valor) soma from td_relacaocredores_temp where farein = ".(int)$processo[0]." and origemcredor = {$origemcredor} and (cpf is not null or cnpj is not null) group by classificacao,cnpj,cpf";
				$query = $conn->query($sql);
				$soma = 0;
				$codigo = 1;
				while ($linha = $query->fetch()){
					$soma += $linha["soma"];
					
					$credor = tdClass::Criar("persistent",array("td_relacaocredores"));
					$idCredor 							= $credor->contexto->getUltimo() + 1;
					$credor->contexto->id 				= $idCredor;
					$credor->contexto->codigo 			= $codigo;
					$credor->contexto->nome				= $linha["nome"];
					$credor->contexto->tipo 			= $linha["tipo"];
					$credor->contexto->cnpj 			= substr($linha["cnpj"],0,1)=="#"?"null":$linha["cnpj"];
					$credor->contexto->cpf				= substr($linha["cpf"],0,1)=="#"?"null":$linha["cpf"];
					$credor->contexto->classificacao = $linha["classificacao"];
					$credor->contexto->moeda 		= $linha["moeda"];
					$credor->contexto->valor 			= $linha["soma"];
					$credor->contexto->codigocredor 	= completaString($processo[0],5) . "." . completaString($processo[1],10) . "." . completaString($idCredor,10);
					$credor->contexto->numerorelacao	= 1;

					$credor->contexto->processo		= (int)$processo[1];
					$credor->contexto->origemcredor	= $origemcredor;
					$credor->contexto->farein			= (int)$processo[0];
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
	$form->legenda->add(utf8_decode("Importar Relação de Credores para Comunicação por E-Mail ( Excel )"));
		
	$form->action = getURLProject("index.php?controller=importarRelacaoCredoresExecelComunicacaoEmail");
	$form->method = "POST";
	$form->enctype = "multipart/form-data";
	$form->target = "retorno";
	
	$select_processo = tdClass::Criar("select");
	$select_processo->class = "form-control";
	$select_processo->id = "processo";
	$select_processo->name = "processo";
	
	// Recuperanda
	$sql = tdClass::Criar("sqlcriterio");
	$dataset = tdClass::Criar("repositorio",array("td_recuperanda"))->carregar($sql);
	foreach ($dataset as $dado){
		$op = tdClass::Criar("option");
		$op->value = $dado->id . "^" . $dado->processo . "^16";
		$op->add("[ ".completaString($dado->id,3)." ][ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj)." ] - " . $dado->razaosocial);
		$select_processo->add($op);
	}
	
	// Falida
	$sql = tdClass::Criar("sqlcriterio");
	$dataset = tdClass::Criar("repositorio",array("td_falencia"))->carregar($sql);
	foreach ($dataset as $dado){
		$op = tdClass::Criar("option");
		$op->value = $dado->id . "^" . $dado->processo . "^19";
		$op->add("[ ".completaString($dado->id,3)." ][ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj)." ] - " . $dado->razaosocial);
		$select_processo->add($op);
	}

	// Insolvente
	$sql = tdClass::Criar("sqlcriterio");
	$dataset = tdClass::Criar("repositorio",array("td_insolvente"))->carregar($sql);
	foreach ($dataset as $dado){
		$op = tdClass::Criar("option");
		$op->value = $dado->id . "^" . $dado->processo . "^18";
		$op->add("[ ".completaString($dado->id,3)." ][ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj)." ] - " . $dado->razaosocial);
		$select_processo->add($op);
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
	$btn_gerar->add($span_gerar,"Importar");	
	$btn_gerar->id = "b-gerar";
	$btn_gerar->type = "submit";

	// Baixar Modelo de Importação do Execel
	$btn_modelo = tdClass::Criar("button");
	$btn_modelo->id 		= "btn-abrir-modelo";
	$btn_modelo->value 		= "Modelo";	
	$btn_modelo->class 		= "btn btn-default";
	$span_modelo 			= tdClass::Criar("span");
	$span_modelo->class 	= "fas fa-file";
	$btn_modelo->add($span_modelo,"Modelo ( Excel )");	
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
	$coluna->data_ncolunas = 3;
	$coluna->add($label,$file);	

	$coluna_processo = tdClass::Criar("div");
	$coluna_processo->class = "coluna";
	$coluna_processo->data_ncolunas = 3;
	$coluna_processo->add($label_processo,$select_processo);
	
	$label = tdClass::Criar("label");
	$label->add("");

	$checkboxEstrangeiro = '
		<div class="checkbox" id="opcoes-importacao">
			<input type="checkbox" id="credoresestrangeiro" name="credoresestrangeiro">
			<label for="credoresestrangeiro"> Sem CNPJ/CPF ou Lote de Credores Estrangeiro</label>
		</div>
	';

	$colunaLoteEstrangeiro = tdClass::Criar("div");
	$colunaLoteEstrangeiro->class = "coluna";
	$colunaLoteEstrangeiro->data_ncolunas = 3;
	$colunaLoteEstrangeiro->add($label,$checkboxEstrangeiro);

	$linha->add($coluna,$coluna_processo,$colunaLoteEstrangeiro);
	
	$iframe = tdClass::Criar("iframe");
	$iframe->id = "retorno";
	$iframe->name = "retorno";
	$iframe->width = "100%";
	$iframe->height = "200px;";
	$iframe->border = "0";
	$iframe->frameborder = "0";
	$iframe->style = "border:0px;";
	
	// CSS
	$css = tdClass::Criar("style");
	$css->add('
		#opcoes-importacao{
			float: right;
			margin-top: 25px;
		}
	');
	
	// JS
	$js = tdClass::Criar("script");
	$js->add('

		$("#btn-abrir-modelo").click(function(e){
			e.stopPropagation();
			e.preventDefault();
			window.open(session.urlsystem + "projects/2/arquivos/modelo-importacao/ModelodeImportacao-RelacaodeCredoresComunicacaoEmail.xlsx","_blank");
		});
	');
	
	$aviso = tdClass::Criar("div");
	$aviso->class = "alert alert-info";
	$aviso->add('
		<ul>
			<li>Importação de credores para comunicação.</li>
			<li>Serve apenas para enviar a comunicação por e-mail.</li>
			<li>Não informar os dados do endereço.</li>
		</ul>
	');

	$form->fieldset->add($aviso,$grupo_botoes,$linha);
	$form_bloco->add($form,$iframe);
	$pagina->add($form_bloco,$js,$css);
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
	function trocavazio($str){
		return trim(str_replace("#","",$str));
	}
	function msgErroValidacao($erroMSG,$td){
		echo '<tr>' . $td . '<tr/>';
		echo '<tr><td colspan="20"><div class="alert alert-danger" role="alert">'.$erroMSG.'</div></td></tr>';
	}