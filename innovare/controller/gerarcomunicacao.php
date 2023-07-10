<?php
	if (tdClass::Read("op") == "atualizar_config"){
		$config_comunicacao 								= tdClass::Criar("persistent",array("td_comunicacaocredoresconfiguracoes",1))->contexto;
		$config_comunicacao->email 							= tdClass::Read("email");
		$config_comunicacao->indiceremessa 					= tdClass::Read("indiceremessa");
		$config_comunicacao->numerofinalmaximoetiqueta 		= tdClass::Read("numerofinalmaximoetiqueta");
		$config_comunicacao->numeroinicialmaximoetiqueta 	= tdClass::Read("numeroinicialmaximoetiqueta");
		$config_comunicacao->ultimonumeroetiqueta 			= tdClass::Read("ultimonumeroetiqueta");
		$config_comunicacao->armazenar();
		Transacao::Commit();
		echo 1;
		exit;
	}
	$pagina = tdClass::Criar("div");
	
	// Bloco do formulario
	$form_bloco = tdClass::Criar("bloco");
	$form_bloco->class="col-md-12";	
	
	$form = tdClass::Criar("tdformulario");
	$form->legenda->add(utf8_decode("Comunicação de Falência e Recuperação aos Credores"));
	
	// Botão Gerar
	$btn_gerar = tdClass::Criar("button");
	$btn_gerar->value = "Gerar";
	$btn_gerar->class = "btn btn-primary b-gerar";
	$span_gerar = tdClass::Criar("span");
	$span_gerar->class = "glyphicon glyphicon-print";
	$btn_gerar->add($span_gerar," Imprimir");
	$btn_gerar->id = "b-imprimir";
	
	// Botão Enviar
	$btn_gerarar = tdClass::Criar("button");
	$btn_gerarar->value = "Gerar AR";
	$btn_gerarar->class = "btn btn-primary b-gerar";
	$span_gerarar = tdClass::Criar("span");
	$span_gerarar->class = "glyphicon glyphicon-tag";
	$btn_gerarar->add($span_gerarar," Gerar AR");
	$btn_gerarar->id = "b-gerarar";
	
	$loaderGerarAr = tdClass::Criar("imagem");
	$loaderGerarAr->src = URL_SYSTEM_THEME . "loading2.gif";
	$loaderGerarAr->style = "float:right;display:none;";
	$loaderGerarAr->id = "loader-gerarar";
	
	// Grupo de botões
	$grupo_botoes = tdClass::Criar("div");
	$grupo_botoes->class = "form-grupo-botao";
	$grupo_botoes->add($btn_gerar,$btn_gerarar,$loaderGerarAr);
	
	$linha = tdClass::Criar("div");
	$linha->class = "row-fluid form_campos";
	
	$select_processo = tdClass::Criar("select");
	$select_processo->class = "form-control";
	$select_processo->id = "busca_farein";

	$opSelect = tdClass::Criar("option");
	$opSelect->add('SELECIONE');
	$select_processo->add($opSelect);

	$filtro = tdc::o("sqlcriterio");
	$filtro->setPropriedade("order","id DESC");
	foreach(tdc::d("td_processo",$filtro) as $processo){
		$groupProcessoRecuperacao = tdc::o("optgroup");
		$groupProcessoRecuperacao->label = "[ {$processo->id} ][ {$processo->numeroprocesso} ] {$processo->descricao}";

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

	$label = tdClass::Criar("label");
	$label->add("Recuperanda / Falida / Insolvente");
	$label->class = "control-label";
	
	$coluna = tdClass::Criar("div");
	$coluna->class = "coluna";
	$coluna->data_ncolunas = 1;
	
	$coluna->add($label,$select_processo);

	// Código
	$lcodigo = tdClass::Criar("label");
	$lcodigo->for = "codigo";
	$lcodigo->add('C&oacute;digo');
	$lcodigo->class = "control-label";
	
	$ccodigo = tdClass::Criar("input");
	$ccodigo->type = "text";
	$ccodigo->class = "form-control";
	$ccodigo->id ="codigo";
	$ccodigo->name = "codigo";
	
	$coluna_codigo = tdClass::Criar("div");
	$coluna_codigo->class = "coluna";
	$coluna_codigo->data_ncolunas = 1;
	$coluna_codigo->add($lcodigo,$ccodigo);
	
	$linha->add($coluna,$coluna_codigo);
	
	// Classificação
	$classificacao = tdClass::Criar("select");
	$classificacao->class = "form-control";
	$classificacao->id = "classificacao";
	$classificacao->name = "classificacao[]";
	$classificacao->multiple = "true";
	$classificacao->size = 5;
	
	
	$sql = tdClass::Criar("sqlcriterio");
	$dataset = tdClass::Criar("repositorio",array("td_classificacao"))->carregar($sql);
	foreach ($dataset as $dado){
		$op = tdClass::Criar("option");
		$op->value = $dado->id;
		$op->add($dado->descricao);
		$classificacao->add($op);
	}
	$lclassificacao = tdClass::Criar("label");
	$lclassificacao->for = "classificacao";
	$lclassificacao->add('Classificação ( <small class="muted">Retirar da impressão</small> )');
	$lclassificacao->class = "control-label";	
	
	$coluna_classificacao = tdClass::Criar("div");
	$coluna_classificacao->class = "coluna";
	$coluna_classificacao->data_ncolunas = 1;
	$coluna_classificacao->add($lclassificacao,$classificacao);
	$linha->add($coluna_classificacao);	
	
	// Adicionar relação de credores inicial
	$divCheckBox = tdc::o("div");
	$divCheckBox->class = "checkbox";
	
	$lIsRelacaoInicial = tdClass::Criar("label");
	$lIsRelacaoInicial->for = "isrelacaoinicial";

	$isRelacaoInicial = tdc::o("input");
	$isRelacaoInicial->type = "checkbox";
	$isRelacaoInicial->id = "isrelacaoinicial";
	$lIsRelacaoInicial->add($isRelacaoInicial);
	$lIsRelacaoInicial->add('Adicionar credores da relação inicial');

	$divCheckBox->add($lIsRelacaoInicial);
	
	$colunaRelacaoInicial = tdClass::Criar("div");
	$colunaRelacaoInicial->class = "coluna";
	$colunaRelacaoInicial->data_ncolunas = 1;	
	$colunaRelacaoInicial->add($divCheckBox);

	$linha->add($colunaRelacaoInicial);

	$script = tdClass::Criar("script");
	$script->language="Javascript";
	$script->add('
		$(".tdform").submit(function(){
			// Não retirar esse return false
			return false;
		});
		$(".alert").hide();
		$("#b-imprimir").click(function(e){			
			console.log("D1");
			gerar();
			console.log("D2");
			e.preventDefault();
			console.log("D3");
			e.stopPropagation();
			console.log("D4");
		});
		function gerar(){
			var farein = $("#busca_farein").val();
			var codigo = $("#codigo").val();
			var classificacao = $("#classificacao").val();			
			window.open(getURLProject("index.php?controller=impressaocomunicacao&farein=" + farein + "&codigo=" + codigo + "&classificacao=" + (classificacao==null?"":classificacao.join())),"_blank");
		}
		
		$("#busca_contrato,#busca_nome,#busca_cpf").keypress(function(e) {
		  if ( e.which == 13 ) {
			gerar();
		  }
		});
		$("#b-atualizar-config").click(function(){
			$("#msg-retorno-configuracoes").html("<img src=\''.URL_SYSTEM_THEME.'loading2.gif\' />");
			$.ajax({
				url:session.urlmiles,
				data:{
					controller:"gerarcomunicacao",
					op:"atualizar_config",
					numeroinicialmaximoetiqueta:$("#numeroinicialmaximoetiqueta").val(),
					numerofinalmaximoetiqueta:$("#numerofinalmaximoetiqueta").val(),
					ultimonumeroetiqueta:$("#ultimonumeroetiqueta").val(),
					email:$("#email").val(),
					indiceremessa:$("#indiceremessa").val(),
					currentproject:session.projeto
				},
				success:function(retorno){
					if (parseInt(retorno) == 1){						
						$("#msg-retorno-configuracoes").html("<img src=\''.URL_SYSTEM_THEME.'check.gif\' />");
					}
					setTimeout(function(){
						$("#msg-retorno-configuracoes").hide("100");
					},3000);
				}
			});			
		});

		$("#b-gerarar").click(function(){
			var farein 			= $("#busca_farein").val();
			var codigo 			= $("#codigo").val();
			var classificacao 	= $("#classificacao").val();
			$.ajax({
				url:session.urlmiles,
				data:{
					controller:"gerarardigital",
					farein:farein,
					codigo:codigo,
					classificacao:(classificacao==null?"":classificacao.join()),
					currentproject:'.$_SESSION["currentproject"].',
					isrelacaoinicial:$("#isrelacaoinicial").is(":checked")
				},
				beforeSend:function(){
					$("#loader-gerarar").show();
				},
				complete:function(ret){
					$("#modal-download-ar .modal-body p").html(ret.responseText);
					$("#modal-download-ar").modal("show");
					$("#loader-gerarar").hide();
				}
			});
		});
	');

	$style = tdClass::Criar("style");
	$style->type = "text/css";
	$style->add('
		#busca_farein optgroup{
			background-color:#DDD;
		}
		#busca_farein optgroup option{
			background-color:#FFF;
			margin:10px;
		}
	');

	$alerta = tdClass::Criar("alert",array("Utilize algum termo para sua pesquisa"));
	$alerta->type = "alert-danger";
	$alerta->alinhar = "left";
	$alerta->style = "display:none;";

	// Modal - Exibe uma tela quando a busca retornar mais de um registro
	$selecao = tdClass::Criar("modal");
	$selecao->tamanho = "modal-lg";
	$selecao->addHeader("Selecione um registro",null);
	$selecao->addBody("");
	$selecao->addFooter("<span class='text-info'>* Click no registro para retornar</span>");
	
	// Modal para exibir os arquivos que serão gerados para o envio para os correios
	$modalArDownload = tdClass::Criar("modal");
	$modalArDownload->nome = "modal-download-ar";
	$modalArDownload->tamanho = "modal-lg";
	$modalArDownload->addHeader("Download dos Arquivos ( AR Digital )",null);
	$modalArDownload->addBody('');
	$modalArDownload->addFooter("<span class='text-info'>Click no botão para baixar o arquivo</span>");
	
	$grupo_botoes->add($alerta);
	$form->fieldset->add($grupo_botoes,$linha);
	$form_bloco->add($form);
	
	$abas_bloco = tdClass::Criar("div");
	$abas_bloco->class="col-md-12";	
	
	$divisao = tdClass::Criar("hr");
	$divisao->class = "divider";
	
	// *** Lista de Remessa [ inicio ]
	$remessaTable = tdClass::Criar("tabela");
	$remessaTable->class = "table table-hover gradededados";
	
	$remessaTHead = tdClass::Criar("thead");
	
	$remessaTHeadId = tdClass::Criar("tabelahead");
	$remessaTHeadId->add("ID");

	$remessaTHeadFarein = tdClass::Criar("tabelahead");
	$remessaTHeadFarein->add("Recuperanda/Falida/Insolvente");

	$remessaTHeadDataHoraEnvio = tdClass::Criar("tabelahead");
	$remessaTHeadDataHoraEnvio->add("Envio<br/>(Correios)");	

	$remessaTHeadDataHoraEnvioCredores = tdClass::Criar("tabelahead");
	$remessaTHeadDataHoraEnvioCredores->add("Envio<br/>(Credores)");
	
	$remessaTHeadEnviadoCorreios = tdClass::Criar("tabelahead");
	$remessaTHeadEnviadoCorreios->add("Enviado<br/>(Correios)");

	$remessaTHeadEnviadoCredores = tdClass::Criar("tabelahead");
	$remessaTHeadEnviadoCredores->add("Enviado<br/>(Credores)");

	$remessaTHeadNumeroInicialEtiqueta = tdClass::Criar("tabelahead");
	$remessaTHeadNumeroInicialEtiqueta->add("Etiqueta<br/>Inicial");

	$remessaTHeadNumeroFinalEtiqueta = tdClass::Criar("tabelahead");
	$remessaTHeadNumeroFinalEtiqueta->add("Etiqueta<br/>Final");
	
	$remessaTHead->add($remessaTHeadId,$remessaTHeadFarein,$remessaTHeadDataHoraEnvio,$remessaTHeadDataHoraEnvioCredores,$remessaTHeadEnviadoCorreios,$remessaTHeadEnviadoCredores,$remessaTHeadNumeroInicialEtiqueta,$remessaTHeadNumeroFinalEtiqueta);
	
	
	$remessaTbody = tdClass::Criar("tbody");
	$sql = tdClass::Criar("sqlcriterio");
	$sql->setPropriedade("order","ID DESC");
	$dataset = tdClass::Criar("repositorio",array("td_comunicacaocredoresremessa"))->carregar($sql);
	foreach ($dataset as $remessa){
		$remessaTR = tdCLass::Criar("tabelalinha");
		
		$remessaTCellId = tdClass::Criar("tabelacelula");
		$remessaTCellId->add($remessa->id);
		$remessaTR->add($remessaTCellId);
		
		$farein = "";
		if ($conn = Transacao::get()){
			$sqlProcesso = "SELECT tipoprocesso FROM td_processo WHERE id = " . $remessa->processo;
			$queryProcesso = $conn->query($sqlProcesso);
			$linhaProcesso = $queryProcesso->fetch();
			switch ($linhaProcesso["tipoprocesso"]){
				case 16:					
						$sqlRecuperanda = "SELECT razaosocial FROM td_recuperanda WHERE id = " . $remessa->farein;
						$queryRecuperanda = $conn->query($sqlRecuperanda);
						$linhaRecuperanda = $queryRecuperanda->fetch();
						$farein = $linhaRecuperanda[0];
				break;
				case 18:
				break;
				case 19:
				break;
			}
		}
		$remessaTCellFarein = tdClass::Criar("tabelacelula");
		$remessaTCellFarein->add($farein);
		$remessaTR->add($remessaTCellFarein);

		$remessaTCellDataEnvioCorreios = tdClass::Criar("tabelacelula");
		$remessaTCellDataEnvioCorreios->add($remessa->datahoraenvio);
		$remessaTR->add($remessaTCellDataEnvioCorreios);
		
		$remessaTCellDataEnvioCredores = tdClass::Criar("tabelacelula");
		$remessaTCellDataEnvioCredores->add($remessa->datahoraconfirmacaoenviocredores);
		$remessaTR->add($remessaTCellDataEnvioCredores);
		
		$imgEnvioCorreios = tdClass::Criar("imagem");		
		if ($remessa->enviadocorreios == 1){
			$imgEnvioCorreios->src = URL_SYSTEM_THEME . "check.gif";
		}else{
			$imgEnvioCorreios->src = URL_SYSTEM_THEME ."erro.gif";
		}
		
		$remessaTCellEnviadoCorreios = tdClass::Criar("tabelacelula");
		$remessaTCellEnviadoCorreios->align = "center";
		$remessaTCellEnviadoCorreios->add($imgEnvioCorreios);
		$remessaTR->add($remessaTCellEnviadoCorreios);

		$imgEnvioCredores = tdClass::Criar("imagem");		
		if ($remessa->enviocredores == 1){
			$imgEnvioCredores->src = URL_SYSTEM_THEME . "check.gif";
		}else{
			$imgEnvioCredores->src = URL_SYSTEM_THEME ."erro.gif";
		}
		
		$remessaTCellEnviadoCredores = tdClass::Criar("tabelacelula");
		$remessaTCellEnviadoCredores->align = "center";
		$remessaTCellEnviadoCredores->add($imgEnvioCredores);
		$remessaTR->add($remessaTCellEnviadoCredores);
		
		$remessaTCellNumeroInicialEtiqueta = tdClass::Criar("tabelacelula");
		$remessaTCellNumeroInicialEtiqueta->add($remessa->numeroinicialetiqueta);
		$remessaTR->add($remessaTCellNumeroInicialEtiqueta);

		$remessaTCellNumeroInicialFinal = tdClass::Criar("tabelacelula");
		$remessaTCellNumeroInicialFinal->add($remessa->numerofinaletiqueta);
		$remessaTR->add($remessaTCellNumeroInicialFinal);
		
		$remessaTbody->add($remessaTR);
	}

	$remessaTable->add($remessaTHead,$remessaTbody);
	// *** Lista de Remessa [ fim ]
	
	// Configurações
	$config_comunicacao = tdClass::Criar("persistent",array("td_comunicacaocredoresconfiguracoes",1))->contexto;
	
	$form_configuracoes = tdClass::Criar("bloco");
	$form_configuracoes->class="col-md-12";	
	
	$numeroinicialmaximoetiqueta = tdClass::Criar("div");
	$numeroinicialmaximoetiqueta->class = "coluna";
	$numeroinicialmaximoetiqueta->data_ncolunas = 3;	
	$numeroinicialmaximoetiqueta->add(
		Campos::TextoLongo("numeroinicialmaximoetiqueta","numeroinicialmaximoetiqueta","Número Inicial Etiqueta",$config_comunicacao->numeroinicialmaximoetiqueta)
	);
	
	$numerofinalmaximoetiqueta = tdClass::Criar("div");
	$numerofinalmaximoetiqueta->class = "coluna";
	$numerofinalmaximoetiqueta->data_ncolunas = 3;	
	$numerofinalmaximoetiqueta->add(
		Campos::TextoLongo("numerofinalmaximoetiqueta","numerofinalmaximoetiqueta","Número Final Etiqueta",$config_comunicacao->numerofinalmaximoetiqueta)
	);
	
	$ultimonumeroetiqueta = tdClass::Criar("div");
	$ultimonumeroetiqueta->class = "coluna";
	$ultimonumeroetiqueta->data_ncolunas = 3;	
	$ultimonumeroetiqueta->add(
		Campos::TextoLongo("ultimonumeroetiqueta","ultimonumeroetiqueta","Último Número Etiqueta",$config_comunicacao->ultimonumeroetiqueta)
	);

	$email = tdClass::Criar("div");
	$email->class = "coluna";
	$email->data_ncolunas = 3;	
	$email->add(
		Campos::TextoLongo("email","email","E-Mail",$config_comunicacao->email)
	);
	
	$indiceremessa = tdClass::Criar("div");
	$indiceremessa->class = "coluna";
	$indiceremessa->data_ncolunas = 3;	
	$indiceremessa->add(
		Campos::TextoLongo("indiceremessa","indiceremessa","Índice Remessa",$config_comunicacao->indiceremessa)
	);
	
	// Botão Gerar	
	$btn_atualizar_config = tdClass::Criar("button");
	$btn_atualizar_config->value = "Gerar";	
	$btn_atualizar_config->class = "btn btn-success b-salvar";
	$span_atualizar_config = tdClass::Criar("span");
	$span_atualizar_config->class = "glyphicon glyphicon-ok";
	$btn_atualizar_config->add($span_atualizar_config,"Salvar");	
	$btn_atualizar_config->id = "b-atualizar-config";
	
	
	// Mensagem de Retorno
	$div_retorno = tdClass::Criar("div");
	$div_retorno->id = "msg-retorno-configuracoes";	
	$div_retorno->style = "float:right";
	
	// Grupo de botões
	$grupo_botoes = tdClass::Criar("div");
	$grupo_botoes->class = "form-grupo-botao";
	$grupo_botoes->add($div_retorno,$btn_atualizar_config);
	
	$form_configuracoes->add($grupo_botoes,$numeroinicialmaximoetiqueta,$numerofinalmaximoetiqueta,$ultimonumeroetiqueta,$email,$indiceremessa);
	
	$aba_html = tdClass::Criar("aba");
	$aba_html->addItem("Remessa",$remessaTable);
	$aba_html->addItem("Configurações",$form_configuracoes);
	
	$abas_bloco->add($divisao,$aba_html);
		
	$pagina->add($style,$form_bloco,$script,$selecao,$abas_bloco,$modalArDownload);
	$pagina->mostrar();
