<?php	
	$pagina = tdClass::Criar("layout");
	
	// Bloco do formulario
	$form_bloco = tdClass::Criar("bloco");
	$form_bloco->class="col-md-12";	
	
	$form = tdClass::Criar("tdformulario");
	$form->legenda->add(utf8charset("Instrução SQL"));
	
	// Botão Executar	
	$btn_executar = tdClass::Criar("button");	
	$btn_executar->class = "btn btn-primary b-executar";
	$span_executar = tdClass::Criar("span");
	$span_executar->class = "fas fa-bolt";
	$btn_executar->add($span_executar,'Executar");	
	$btn_executar->id = "b-executar";
	
	// Grupo de botões
	$grupo_botoes = tdClass::Criar("div");
	$grupo_botoes->class = "form-grupo-botao";
	$grupo_botoes->add($btn_executar);
	
	$linha = tdClass::Criar("div");
	$linha->class = "row-fluid form_campos";
	
	// Adicionando os campos da Busca Básica
	$campoBuscaBasica = array(
		Campos::TextArea("query",'query",'",'")
	);
	
	foreach ($campoBuscaBasica as $campo){
		$coluna = tdClass::Criar("div");
		$coluna->class="col-md-12 col-sm-12";
		$coluna->data_ncolunas = 1;
		$coluna->add($campo);
		$linha->add($coluna);
	}	
		
	$script = tdClass::Criar("script");
	$script->type="text/javascript";
	$script->language="Javascript";
	$script->add('	
		$("#b-executar").click(function(){
			$.ajax({
				type:"POST",
				url:"index.php?controller=executaSQL",
				data:{
					query:$("#query").val()
				},
				dataType:"json",
				success:function(retorno){
					$("#retorno").html(retorno.msg);
					if (retorno.erro == "0"){
						$("#retorno").removeClass("alert-danger");
						$("#retorno").addClass("alert-success");
					}else{
						$("#retorno").removeClass("alert-success");
						$("#retorno").addClass("alert-danger");
					}					
				}
			});			
		});
	');
	
	// Retorno
	$div = tdClass::Criar("div");
	$div->id = "retorno";
	$div->name = "retorno";
	$div->style = "float:left;clear:left;width:97%;margin:15px -20px 15px 25px ;";
	$div->class = "alert alert-dismissible";
		
	$form->fieldset->add($grupo_botoes,$linha);
	$form_bloco->add($form,$div);
	$pagina->addCorpo($form_bloco,$script);			
	$pagina->mostrar();