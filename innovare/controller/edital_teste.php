<?php
	
	$pagina = tdClass::Criar("div");
	
	// Bloco do formulario
	$form_bloco = tdClass::Criar("bloco");
	$form_bloco->class="col-md-12";	
	
	$form = tdClass::Criar("tdformulario");
	$form->legenda->add(utf8_decode("Edital"));
	
	// Botão Gerar	
	$btn_gerar = tdClass::Criar("button");
	$btn_gerar->value = "Gerar";	
	$btn_gerar->class = "btn btn-primary b-gerar";
	$span_gerar = tdClass::Criar("span");
	$span_gerar->class = "glyphicon glyphicon-file";
	$btn_gerar->add($span_gerar,"Gerar");	
	$btn_gerar->id = "b-gerar";
	
	// Botão Conferir	
	$btn_coferir = tdClass::Criar("button");	
	$btn_coferir->class = "btn btn-primary b-gerar";
	$span_coferir = tdClass::Criar("span");
	$span_coferir->class = "glyphicon glyphicon-file";
	$btn_coferir->add($span_coferir,"Conferrir");
	$btn_coferir->id = "b-conferir";
	
	// Grupo de botões
	$grupo_botoes = tdClass::Criar("div");
	$grupo_botoes->class = "form-grupo-botao";
	$grupo_botoes->add($btn_gerar,$btn_coferir);
	
	$linha = tdClass::Criar("div");
	$linha->class = "row-fluid form_campos";
	
	$select_processo = tdClass::Criar("select");
	$select_processo->class = "form-control";
	$select_processo->id = "busca_processo";
	
	// Busca por recuperanda
	$sql = tdClass::Criar("sqlcriterio");
	$dataset = tdClass::Criar("repositorio",array("td_recuperanda"))->carregar($sql);
	foreach ($dataset as $dado){
		$processo = tdClass::Criar("persistent",array("td_processo",$dado->td_processo));
		$op = tdClass::Criar("option");
		$op->value = $processo->contexto->id . "-" . $dado->id . "-R";
		$op->add($processo->contexto->numeroprocesso . " - " . $dado->razaosocial);
		$select_processo->add($op);
	}
	
	// Busca por falida
	$sql = tdClass::Criar("sqlcriterio");
	$dataset = tdClass::Criar("repositorio",array("td_falencia"))->carregar($sql);
	foreach ($dataset as $dado){
		$processo = tdClass::Criar("persistent",array("td_processo",$dado->td_processo));
		$op = tdClass::Criar("option");
		$op->value = $processo->contexto->id . "-" . $dado->id . "-F";
		$op->add($processo->contexto->numeroprocesso . " - " . $dado->razaosocial);
		$select_processo->add($op);
	}
	
	// Busca por insolvente
	$sql = tdClass::Criar("sqlcriterio");
	$dataset = tdClass::Criar("repositorio",array("td_insolvente"))->carregar($sql);
	foreach ($dataset as $dado){
		$processo = tdClass::Criar("persistent",array("td_processo",$dado->td_processo));
		$op = tdClass::Criar("option");
		$op->value = $processo->contexto->id . "-" . $dado->id . "-I";
		$op->add($processo->contexto->numeroprocesso . " - " . $dado->nome);
		$select_processo->add($op);
	}
	
	$label = tdClass::Criar("label");
	$label->add("Selecione");
	
	$coluna = tdClass::Criar("div");
	$coluna->class = "coluna";
	$coluna->data_ncolunas = 1;
	$coluna->add($label,$select_processo);
	$linha->add($coluna);
	
	$script = tdClass::Criar("script");
	$script->type="text/javascript";
	$script->language="Javascript";
	$script->add('
		$(".alert").hide();
		$("#b-gerar").click(function(){			
			gerar();
		});
		$("#b-conferir").click(function(){			
			conferir();
		});		
		function gerar(){
			var processo = $("#busca_processo").val();
			window.open("index.php?controller=impressaoedital&processo=" + processo,"_blank");
		}
		function conferir(){
			var processo = $("#busca_processo").val();
			window.open("index.php?controller=impressaoeditalconferencia&processo=" + processo,"_blank");
		}		
	');

	$alerta = tdClass::Criar("alert",array("Utilize algum termo para sua pesquisa"));
	$alerta->type = "alert-danger";
	$alerta->alinhar = "left";	
	
	// Modal - Exibe uma tela quando a busca retornar mais de um registro
	$selecao = tdClass::Criar("modal");
	$selecao->tamanho = "modal-lg";
	$selecao->addHeader("Selecione um registro",null);
	$selecao->addBody("");
	$selecao->addFooter("<span class='text-info'>* Click no registro para retornar</span>");
	
	$grupo_botoes->add($alerta);
	$form->fieldset->add($grupo_botoes,$linha);
	$form_bloco->add($form);
	$pagina->add($form_bloco,$script,$selecao);			
	$pagina->mostrar();
?>