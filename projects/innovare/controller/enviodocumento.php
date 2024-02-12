<?php		
	$pagina = tdClass::Criar("div");
	
	// Bloco do formulario
	$form_bloco = tdClass::Criar("bloco");
	$form_bloco->class="col-md-12";	
	
	$form = tdClass::Criar("tdformulario");
	#$form->legenda->add(utf8_decode("Comunicação de Falência e Recuperação aos Credores"));
	
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
	
	// Adicionando os campos da Busca Básica
	$campoBuscaBasica = array(
		Campos::TextoLongo("busca_processo","busca_processo","Processo","")
	);
	
	foreach ($campoBuscaBasica as $campo){
		$coluna = tdClass::Criar("div");
		$coluna->class = "coluna";
		$coluna->data_ncolunas = 1;
		$coluna->add($campo);
		$linha->add($coluna);
	}	
		
	$script = tdClass::Criar("script");
	$script->type="text/javascript";
	$script->language="Javascript";
	$script->add('
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
