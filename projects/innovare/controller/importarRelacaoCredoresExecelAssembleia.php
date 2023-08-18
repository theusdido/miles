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
		$processo 	= explode("^",$_POST["processo"]);
		$xml 		= simplexml_load_file($_FILES["arquivo"]["tmp_name"]);
		$cLinha 	= 0;
		if ($conn = Transacao::get()){
			
			$bootstrap = tdClass::Criar("link");
			$bootstrap->href = PATH_LIB . 'bootstrap/3.3.1/css/bootstrap.css';
			$bootstrap->rel = 'stylesheet';
			$bootstrap->mostrar();

			// Quais credores serão afetados
			$_origem_credor = array();
			if (isset($_POST["origem_credor_4"])) array_push($_origem_credor,'origemcredor = 4');
			if (isset($_POST["origem_credor_7"])) array_push($_origem_credor,'origemcredor = 7');
			if (isset($_POST["origem_credor_8"])) array_push($_origem_credor,'origemcredor = 8');

			if (sizeof($_origem_credor) < 1){
				msgErroValidacao('<b>Origem do Credor</b> não foi selecionado.');
				finalizar();
				exit;
			}
			
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
			$valores 			= array();
			$linhaDadosCredor 	= "";
			$error 				= 0;
			// Os credores importados ficaram com a origem 8
			$origemcredor 		= 8;

			foreach ($xml->Worksheet->Table->Row as $cell){
				$cLinha++;
				if ($cLinha<=1) continue; # Pula a primeira linha
				$nome 				= trocavazio("{$cell->Cell[0]->Data}");
				$cpfj 				= validaColuna($cell->Cell,1);
				$cpfjSemFormatar 	= ($cpfj!=""?(strlen($cpfj) > 11?$cpfj!=""?(completaString(trim($cpfj),14)):"":(completaString(trim($cpfj),11))):"");
				$classificacao 		= trocavazio((int)$cell->Cell[4]->Data);
				$moeda 				= trocavazio((int)$cell->Cell[2]->Data);
				$vlr 				= $cell->Cell[3]->Data;
				$valor 				= number_format((double)$vlr, 2, ',', '.');
				$linhaDadosCredor 	=
										"<td>" . $nome . "</td>".
										"<td>" . ($cpfj!=""?(strlen($cpfj) > 11?$cpfj!=""?formatarCNPJ(completaString(trim($cpfj),14)):"":formatarCPF(completaString(trim($cpfj),11))):"") . "</td>".
										"<td>" . $classificacao . "</td>".
										"<td>" . $moeda . "</td>".
										"<td>" . $valor  . "</td>".										
										"<td>" . $processo[1] . "</td>".
										"<td>" . $origemcredor . "</td>".
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
					AND (
						".implode(' OR ',$_origem_credor)."
					)";
				$queryExiste = $conn->query($sqlExiste);
				if ($queryExiste->rowcount() <= 0){
					$valores[$cpfj] = moneyToFloat($vlr);
					$entidadecredor = "td_relacaocredores";
					$credor = tdClass::Criar("persistent",array($entidadecredor));
					$idCredor 							= $credor->contexto->getUltimo() + 1;
					$credor->contexto->id 				= $idCredor;
					$credor->contexto->nome				= utf8_encode($nome);
					if ($cpfj == ""){
						//var_dump($cpfj);
						$credor->contexto->cnpj 		= "#" . $idCredor;
						$credor->contexto->cpf 			= "#" . $idCredor;
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

					$credor->contexto->processo		= (int)$processo[1];
					$credor->contexto->origemcredor	= $origemcredor;
					$credor->contexto->farein		= (int)$processo[0];
					$credor->contexto->armazenar();

					// Salvando Relcionamento na Lista
					$lista = tdClass::Criar("persistent",array(LISTA))->contexto;
					$lista->entidadepai 		= $processo[2];
					$lista->entidadefilho 		= 20;
					$lista->regpai 			= $processo[0];
					$lista->regfilho 			= $idCredor;
					$lista->id 					= $lista->getUltimo() + 1;
					//echo $processo[2] . '^' . $processo[0] . '^' . $idCredor . '^' . $lista->id;
					$lista->armazenar();
				}else{
					$linhaExiste = $queryExiste->fetch();
					if (is_numeric($linhaExiste["id"])){
						if ($linhaExiste["id"] > 0){
							$credor = tdClass::Criar("persistent",array("td_relacaocredores",$linhaExiste["id"]));
							$credor->contexto->moeda 		= $moeda;
							$credor->contexto->valor 			= $valor;
							$credor->contexto->armazenar();
						}	
					}	
				}
				echo '<tr>' .$linhaDadosCredor . '</tr>';
			}
			echo '</table>';

			if ($error > 0){
				exit;
			}
			Transacao::Fechar();
			echo '<div class="alert alert-success" role="alert"><b> Que Bom !</b> Os arquivos foram importados com sucesso</div>';
			finalizar();
		}
		exit;
	}
	$pagina = tdClass::Criar("div");
	
	// Bloco do formulario
	$form_bloco = tdClass::Criar("bloco");
	$form_bloco->class="col-md-12";	
	
	$form = tdClass::Criar("tdformulario");
	$form->legenda->add(utf8_decode("Importar Relação de Credores ( Excel ) - Assembleia"));
	
	$form->id		= 'form-importar-assembleia';
	$form->action 	= getURLProject("index.php?controller=importarRelacaoCredoresExecelAssembleia");
	$form->method 	= "POST";
	$form->enctype 	= "multipart/form-data";
	$form->target 	= "retorno";
	
	$select_processo 		= tdClass::Criar("select");
	$select_processo->class = "form-control";
	$select_processo->id 	= "processo";
	$select_processo->name 	= "processo";
	
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
	$btn_gerar->add($span_gerar," Importar");
	$btn_gerar->id = "b-gerar";
	$btn_gerar->type = "submit";

	// Baixar Modelo de Importação do Execel
	$btn_modelo = tdClass::Criar("button");
	$btn_modelo->id 		= "btn-abrir-modelo";	
	$btn_modelo->value 		= "Modelo";
	$btn_modelo->class 		= "btn btn-default";
	$span_modelo 			= tdClass::Criar("span");
	$span_modelo->class 	= "fas fa-file";
	$btn_modelo->add($span_modelo," Modelo ( Excel )");	
	$btn_modelo->style="float:right;margin-right:10px;";

	// Grupo de botões
	$grupo_botoes = tdClass::Criar("div");
	$grupo_botoes->class = "form-grupo-botao";
	$grupo_botoes->add($btn_gerar,$btn_modelo);
	
	$linha = tdClass::Criar("div");
	$linha->class = "row-fluid form_campos";
	
	$label = tdClass::Criar("label");
	$label->add("Selecione o arquivo");
	
	$coluna 				= tdClass::Criar("div");
	$coluna->class 			= "coluna";
	$coluna->data_ncolunas 	= 1;
	$coluna->add($label,$file);	

	$coluna_processo 				= tdClass::Criar("div");
	$coluna_processo->class 		= "coluna";
	$coluna_processo->data_ncolunas = 1;
	$coluna_processo->add($label_processo,$select_processo);

	$checkboxOrigemCredor = '
		<div id="opcoes-importacao">
			<div class="checkbox">
				<input type="checkbox" id="origem_credor_4" name="origem_credor_4">
				<label for="origem_credor_4">Relação de Credores da Administradora</label>
			</div>
			<div class="checkbox">
				<input type="checkbox" id="origem_credor_7" name="origem_credor_7">
				<label for="origem_credor_7">Quadro Geral de Credores</label>
			</div>
			<div class="checkbox">
				<input type="checkbox" id="origem_credor_8" name="origem_credor_8" checked>
				<label for="origem_credor_8">Habilitação Manual de Credores</label>
			</div>
		</div>
	';
	
	$label 		= tdClass::Criar("label");
	$label->id 	= 'label-opcoes-origem-credor';
	$label->add("Origem do Credor");

	$colunaOrigemCredor 				= tdClass::Criar("div");
	$colunaOrigemCredor->class 			= "coluna";
	$colunaOrigemCredor->data_ncolunas 	= 1;
	$colunaOrigemCredor->add($label,$checkboxOrigemCredor);	
	
	$linha->add($coluna_processo,$coluna,$colunaOrigemCredor);
	
	$iframe					= tdClass::Criar("iframe");
	$iframe->id 			= "retorno";
	$iframe->name 			= "retorno";
	$iframe->width 			= "100%";
	$iframe->height 		= "200px;";
	$iframe->border 		= "0";
	$iframe->frameborder 	= "0";
	$iframe->style 			= "border:0px;display:none;";
	
	// CSS
	$css = tdClass::Criar("style");
	$css->add('
		#opcoes-importacao{
			float: left;
			margin-left: 20px;
		}
		#label-opcoes-origem-credor {
			width:100%;
		}
		.radio label, .checkbox label {
			padding:0px;
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
			window.open(session.urlsystem + "projects/2/arquivos/modelo-importacao/ModelodeImportacao-RelacaoodeCredoresAssembleia.xlsx","_blank");
		});

		$("#form-importar-assembleia").submit(function(){			
			$("#progress-importar-assembleia").show();
			$("#retorno,#form-importar-assembleia").hide();
		});
	');

	$aviso = tdClass::Criar("div");
	$aviso->class = "alert alert-info";
	$aviso->add('
		<ul>
			<li>Importação de credores para a Assembleia.</li>
			<li>Serve apenas para habilitar o envio de documentos para a Assembleia.</li>
			<li>Não informar os dados do endereço.</li>
			<li>Não informar os dados de contato.</li>
		</ul>
	');

	$progress 						= tdc::Criar('div');
	$progress->class 				= 'progress';
	$progress->style 				= 'width:100%;display:none;';
	$progress->id					= 'progress-importar-assembleia';

	$progress_bar 					= tdc::Criar('div');
	$progress_bar->class 			= 'progress-bar progress-bar-info progress-bar-striped active';
	$progress_bar->role 			= 'progressbar';
	$progress_bar->aria_valuenow	= '100';
	$progress_bar->aria_valuemin	= '0';
	$progress_bar->aria_valuemax	= '100';
	$progress_bar->style 			= 'width:100%';
	$progress_bar->add('Importando credores. Aguarde ...');

	$progress->add($progress_bar);
	$form->fieldset->add($aviso,$grupo_botoes,$linha);
	$form_bloco->add($form,$progress,$iframe);
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
	function trocavazio($str){
		return trim(str_replace("#","",$str));
	}
	function msgErroValidacao($erroMSG,$td = ''){
		if ($td != ''){
			echo '<tr>' . $td . '<tr/>';
		}
		echo '<tr><td colspan="20"><div class="alert alert-danger" role="alert">'.$erroMSG.'</div></td></tr>';
	}

	function finalizar(){
		$js_finalizado = tdClass::Criar('script');
		$js_finalizado->add('
			parent.$("#progress-importar-assembleia").hide();
			parent.$("#retorno").show();
		');
		$js_finalizado->mostrar();
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