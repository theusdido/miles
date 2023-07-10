<?php
	
	$pagina = tdClass::Criar("div");
	
	// Bloco do formulario
	$form_bloco = tdClass::Criar("bloco");
	$form_bloco->class="col-md-12";	
	
	$form = tdClass::Criar("tdformulario");
	$form->legenda->add(utf8_decode("Impressão de Etiqueta"));
	
	// Botão Gerar	
	$btn_gerar = tdClass::Criar("button");
	$btn_gerar->value = "Gerar";	
	$btn_gerar->class = "btn btn-primary b-gerar";
	$span_gerar = tdClass::Criar("span");
	$span_gerar->class = "glyphicon glyphicon-file";
	$btn_gerar->add($span_gerar,"Gerar");	
	$btn_gerar->id = "b-gerar";
	
	// Grupo de botões
	$grupo_botoes = tdClass::Criar("div");
	$grupo_botoes->class = "form-grupo-botao";
	$grupo_botoes->add($btn_gerar);
	
	$linha = tdClass::Criar("div");
	$linha->class = "row-fluid form_campos";
	
	$select_processo = tdClass::Criar("select");
	$select_processo->class = "form-control";
	$select_processo->id = "busca_farein";
	
	// Recuperanda
	$sql = tdClass::Criar("sqlcriterio");
	$dataset = tdClass::Criar("repositorio",array("td_recuperanda"))->carregar($sql);
	foreach ($dataset as $dado){
		$op = tdClass::Criar("option");
		$op->value = $dado->id . "^" . $dado->td_processo . "^RE";
		$op->add("[ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj)." ] - " . $dado->razaosocial);
		$select_processo->add($op);
	}
	
	// Falida
	$sql = tdClass::Criar("sqlcriterio");
	$dataset = tdClass::Criar("repositorio",array("td_falencia"))->carregar($sql);
	foreach ($dataset as $dado){
		$op = tdClass::Criar("option");
		$op->value = $dado->id . "^" . $dado->td_processo . "^FA";
		$op->add("[ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj)." ] - " . $dado->razaosocial);
		$select_processo->add($op);
	}

	// Insolvente
	$sql = tdClass::Criar("sqlcriterio");
	$dataset = tdClass::Criar("repositorio",array("td_insolvente"))->carregar($sql);
	foreach ($dataset as $dado){
		$op = tdClass::Criar("option");
		$op->value = $dado->id . "^" . $dado->td_processo . "^IN";
		$op->add("[ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj)." ] - " . $dado->razaosocial);
		$select_processo->add($op);
	}
	
	$label = tdClass::Criar("label");
	$label->add("Selecione o processo");
	
	$coluna = tdClass::Criar("div");
	$coluna->class = "coluna";
	$coluna->data_ncolunas = 1;
	$coluna->add($label,$select_processo);
	
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
	
	$script = tdClass::Criar("script");
	$script->add('
		$(".alert").hide();
		$("#b-gerar").click(function(){			
			gerar();
		});
		function gerar(){
			var farein = $("#busca_farein").val();
			var codigo = $("#codigo").val();
			window.open("index.php?controller=impressaoetiqueta&farein=" + farein + "&codigo=" + codigo,"_blank");
		}
		
		$("#busca_contrato,#busca_nome,#busca_cpf").keypress(function(e) {
		  if ( e.which == 13 ) {
			gerar();
		  }
		});
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