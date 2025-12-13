<?php

	// Página
	$pagina = tdClass::Criar("div");

	// Bloco do formulario
	$form_bloco 		= tdClass::Criar("bloco");
	$form_bloco->class	= "col-md-12";	

	// Formulário
	$form 			= tdClass::Criar("tdformulario");
	$form->id		= 'form-importar-credores';
	$form->action 	= getURLProject("index.php?controller=importarRelacaoCredoresExecel");
	$form->method 	= "POST";
	$form->enctype 	= "multipart/form-data";
	$form->target 	= "retorno-importacao";
	$form->setOnSubmit('return true');

	// Botão Gerar	
	$btn_gerar 			= tdClass::Criar("button");
	$btn_gerar->value 	= "Importar";	
	$btn_gerar->class 	= "btn btn-primary b-gerar";
	$span_gerar 		= tdClass::Criar("span");
	$span_gerar->class 	= "fas fa-file";
	$btn_gerar->add($span_gerar," Importar");	
	$btn_gerar->id 		= "b-gerar";
	$btn_gerar->name	= "b-gerar";
	$btn_gerar->type 	= "submit";

	// Baixar Modelo de Importação do Execel
	$btn_modelo 			= tdClass::Criar("button");
	$btn_modelo->id 		= "btn-abrir-modelo";
	$btn_modelo->value 		= "Modelo";
	$btn_modelo->class 		= "btn btn-default";
	$span_modelo 			= tdClass::Criar("span");
	$span_modelo->class 	= "fas fa-file";
	$btn_modelo->add($span_modelo, " Modelo ( Excel )");
	$btn_modelo->style		=	"float:right;margin-right:10px;";

	// Grupo de botões
	$file 			= tdClass::Criar("input");
	$file->type 	= "file";
	$file->id 		= "arquivo";
	$file->name 	= "arquivo";
	$file->style	= 'display:none';
	$file->onchange	= "tdChangeInputFile(this.value);";
	
	$grupo_botoes 			= tdClass::Criar("div");
	$grupo_botoes->class 	= "form-grupo-botao";
	$grupo_botoes->add($btn_gerar,$btn_modelo);

	$linha 			= tdClass::Criar("div");
	$linha->class 	= "row-fluid form_campos";

	$label 			= tdClass::Criar("label");
	$label->add("Selecione o arquivo");

	# -- Input File Label -- #
	$input_file_label_icon			= tdc::html('i');
	$input_file_label_icon->class 	= 'fas fa-upload';

	$input_file_label_text			= tdc::html('span');
	$input_file_label_text->add('Carregar Arquivo');

	$input_file_label				= tdClass::Criar("label");
	$input_file_label->for			= "arquivo";
	$input_file_label->class		= 'td-label-input-file';
	$input_file_label->add($input_file_label_icon,$input_file_label_text);
	# -- // -- #

	$coluna 				= tdClass::Criar("div");
	$coluna->class 			= "coluna";
	$coluna->data_ncolunas 	= 1;
	$coluna->add($label,$input_file_label,$file);	

	$coluna_empresa 				= tdClass::Criar("div");
	$coluna_empresa->class 			= "coluna campo-empresa";
	$coluna_empresa->data_ncolunas 	= 1;
	$coluna_empresa->add(Empresa::Filtro());

	$label = tdClass::Criar("label");
	$label->add("");

	$checkboxEstrangeiro = '
		<ul class="list-group">
			<li class="list-group-item">
				<input type="checkbox" id="relacaoinicial" name="relacaoinicial" class="form-check-input me-1" />
				<label for="relacaoinicial" class="form-check-label stretched-link"> Relação Inicial ( Edital )</label>
			</li>		
			<li class="list-group-item">
				<input type="checkbox" id="credoresestrangeiro" name="credoresestrangeiro" class="form-check-input me-1" />
				<label for="credoresestrangeiro" class="form-check-label stretched-link"> Lote de Credores Estrangeiro</label>
			</li>
			<li class="list-group-item">
				<input type="checkbox" id="credorescomunicacao" name="credorescomunicacao" class="form-check-input me-1" />
				<label for="credorescomunicacao" class="form-check-label stretched-link">Comunicação aos Credores</label>			
			</li>
			<li class="list-group-item">
				<input type="checkbox" id="isagruparcredor" name="isagruparcredor" class="form-check-input me-1" />
				<label for="isagruparcredor" class="form-check-label stretched-link">Agrupar Credores por CPF/CNPJ</label>			
			</li>
	  	</ul>
	';

	$colunaLoteEstrangeiro 					= tdClass::Criar("div");
	$colunaLoteEstrangeiro->class 			= "coluna";
	$colunaLoteEstrangeiro->data_ncolunas 	= 1;
	$colunaLoteEstrangeiro->add($label,$checkboxEstrangeiro);

	$label = tdClass::Criar("label");
	$label->add("Número Relação");
	
	$selectNumeroRelacao 		= tdClass::Criar("select");
	$selectNumeroRelacao->class = "form-select";
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

	$colunaNumeroRelacao 					= tdClass::Criar("div");
	$colunaNumeroRelacao->class 			= "coluna";
	$colunaNumeroRelacao->data_ncolunas 	= 1;
	$colunaNumeroRelacao->add($label,$selectNumeroRelacao);

	$linha->add($coluna_empresa,$coluna,$colunaNumeroRelacao,$colunaLoteEstrangeiro);

	$iframe 				= tdClass::Criar("iframe");
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
			window.open("'.URL_CURRENT_ASSETS.'modelo-importacao/ModelodeImportacao-RelacaoodeCredores.xlsx","_blank");
		});

		$("#form-importar-credores").submit(function(){
			if ($("#termo-empresa").val() == ""){
				$("#termo-empresa").parents(".input-group").first().css("border","1px solid #FF0000");
				return false;
			}

			if ($("#arquivo").val() == ""){
				$(".td-label-input-file").css("border","1px solid #FF0000");
				return false;
			}

			$("#progress-importar-credores").show();
			$("#retorno,#form-importar-credores").hide();
		});
		
		function tdChangeInputFile(_filename)
		{
			$(".td-label-input-file").html(_filename);
			$(".td-label-input-file").css("background-color","#006600");
			$(".td-label-input-file").css("color","#FFF");
			$(".td-label-input-file").css("border","1px solid transparent");
		}

		$("#btn-pesquisar-empresa").click(function(){
			$("#termo-empresa").parents(".input-group").first().css("border","1px solid transparent");
		});
	');
	
	// Aviso
	$aviso 			= tdClass::Criar("div");
	$aviso->class 	= "alert alert-info";
	$aviso->add('
		<ul>
			<li>Importação de credores com todos os dados.</li>
			<li>Serve tanto para o edital como para comunicação aos correios.</li>
		</ul>
	');

	// Título
	$titulo = tdc::o('titulo');
	$titulo->add('Importar Relação de Credores ( Excel )');
	
	// Progress Bar
	$progress 						= tdc::Criar('div');
	$progress->class 				= 'progress';
	$progress->style 				= 'width:100%;display:none;';
	$progress->id					= 'progress-importar-credores';

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
	$pagina->add($titulo,$form_bloco,$css,$js);
	$pagina->mostrar();