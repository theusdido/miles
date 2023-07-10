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
	
	$coluna = tdClass::Criar("div");
	$coluna->class = "coluna";
	$coluna->data_ncolunas = 1;
	$coluna->add(Processo::getLabelProcesso(),Processo::getSelectProcesso());
	$linha->add($coluna);

	$script = tdClass::Criar("script");
	$script->language="Javascript";
	$script->add('
		$(".alert").hide();
		$("#b-gerar").click(function(e){
			e.preventDefault();
			e.stopPropagation();
			gerar();
		});
		$("#b-conferir").click(function(){
			conferir();
		});
		function gerar(){
			var farein = $("#busca_farein").val();
			window.open(session.urlsystem + "?controller=impressaoedital&farein=" + farein + "&currentproject=" + session.projeto,"_blank");
		}
		function conferir(){
			var farein = $("#busca_farein").val();
			window.open(session.urlsystem + "?controller=impressaoeditalconferencia&processo=" + farein.split("^")[1] + "&currentproject=" + session.projeto,"_blank");
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
	
	$grupo_botoes->add($alerta);
	$form->fieldset->add($grupo_botoes,$linha);
	$form_bloco->add($form);
	$pagina->add($form_bloco,$script,$selecao);			
	$pagina->mostrar();
?>