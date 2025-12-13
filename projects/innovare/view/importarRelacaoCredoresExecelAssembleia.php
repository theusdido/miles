<?php
	$pagina = tdClass::Criar("div");
	
	// Bloco do formulario
	$form_bloco = tdClass::Criar("bloco");
	$form_bloco->class="col-md-12";	
	
	$form 			= tdClass::Criar("tdformulario");	
	$form->id		= 'form-importar-assembleia';
	$form->action 	= getURLProject("index.php?controller=importarRelacaoCredoresExecelAssembleia");
	$form->method 	= "POST";
	$form->enctype 	= "multipart/form-data";
	$form->target 	= "retorno";
	$form->setOnSubmit('return true');
	
	$select_processo 			= tdClass::Criar("select");
	$select_processo->class 	= "form-control";
	$select_processo->id 		= "processo";
	$select_processo->name 		= "processo";
	
	// Recuperanda
	$sql 		= tdClass::Criar("sqlcriterio");
	$dataset 	= tdClass::Criar("repositorio",array("td_recuperanda"))->carregar($sql);
	foreach ($dataset as $dado){
		$op 		= tdClass::Criar("option");
		$op->value 	= $dado->id . "^" . $dado->processo . "^16";
		$op->add("[ ".completaString($dado->id,3)." ][ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj)." ] - " . $dado->razaosocial);
		$select_processo->add($op);
	}
	
	// Falida
	$sql 		= tdClass::Criar("sqlcriterio");
	$dataset 	= tdClass::Criar("repositorio",array("td_falencia"))->carregar($sql);
	foreach ($dataset as $dado){
		$op 		= tdClass::Criar("option");
		$op->value 	= $dado->id . "^" . $dado->processo . "^19";
		$op->add("[ ".completaString($dado->id,3)." ][ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj)." ] - " . $dado->razaosocial);
		$select_processo->add($op);
	}

	// Insolvente
	$sql 		= tdClass::Criar("sqlcriterio");
	$dataset 	= tdClass::Criar("repositorio",array("td_insolvente"))->carregar($sql);
	foreach ($dataset as $dado){
		$op 			= tdClass::Criar("option");
		$op->value 		= $dado->id . "^" . $dado->processo . "^18";
		$op->add("[ ".completaString($dado->id,3)." ][ ".($dado->cnpj==""?$dado->cpf:$dado->cnpj)." ] - " . $dado->razaosocial);
		$select_processo->add($op);
	}

	
	$label_processo = tdClass::Criar("label");
	$label_processo->add("Selecione o processo");
	
	// Botão Gerar	
	$btn_gerar 			= tdClass::Criar("button");
	$btn_gerar->value 	= "Importar";	
	$btn_gerar->class 	= "btn btn-primary b-gerar";
	$span_gerar 		= tdClass::Criar("span");
	$span_gerar->class 	= "fas fa-file";
	$btn_gerar->add($span_gerar," Importar");
	$btn_gerar->id 		= "b-gerar";
	$btn_gerar->type 	= "submit";

	// Baixar Modelo de Importação do Execel
	$btn_modelo 			= tdClass::Criar("button");
	$btn_modelo->id 		= "btn-abrir-modelo";	
	$btn_modelo->value 		= "Modelo";
	$btn_modelo->class 		= "btn btn-default";
	$span_modelo 			= tdClass::Criar("span");
	$span_modelo->class 	= "fas fa-file";
	$btn_modelo->add($span_modelo," Modelo ( Excel )");	
	$btn_modelo->style		= "float:right;margin-right:10px;";

	// Grupo de botões
	$grupo_botoes 			= tdClass::Criar("div");
	$grupo_botoes->class 	= "form-grupo-botao";
	$grupo_botoes->add($btn_gerar,$btn_modelo);
	
	$linha 			= tdClass::Criar("div");
	$linha->class 	= "row-fluid form_campos";
	
	$label = tdClass::Criar("label");
	$label->add("Selecione o arquivo");
	
	# -- Input File Label -- #
	$file 			= tdClass::Criar("input");
	$file->type 	= "file";
	$file->id 		= "arquivo";
	$file->name 	= "arquivo";
	$file->style	= 'display:none';
	$file->onchange	= "tdChangeInputFile(this.value);";
	
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
	$coluna_empresa->class 			= "coluna";
	$coluna_empresa->data_ncolunas 	= 1;
	$coluna_empresa->add(Empresa::Filtro());

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
	
	$linha->add($coluna_empresa,$coluna,$colunaOrigemCredor);
	
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
			window.open("'.URL_CURRENT_ASSETS.'modelo-importacao/ModelodeImportacao-RelacaoodeCredoresAssembleia.xlsx","_blank");
		});

		$("#form-importar-assembleia").submit(function(){			
			$("#progress-importar-assembleia").show();
			$("#retorno,#form-importar-assembleia").hide();
		});

		function tdChangeInputFile(_filename)
		{
			$(".td-label-input-file").html(_filename);
			$(".td-label-input-file").css("background-color","#006600");
			$(".td-label-input-file").css("color","#FFF");
		}		
	');

	$aviso 			= tdClass::Criar("div");
	$aviso->class 	= "alert alert-info";
	$aviso->add('
		<ul>
			<li>Importação de credores para a Assembleia.</li>
			<li>Serve apenas para habilitar o envio de documentos para a Assembleia.</li>
			<li>Não informar os dados do endereço.</li>
			<li>Não informar os dados de contato.</li>
		</ul>
	');

	// Título
	$titulo = tdc::o('titulo');
	$titulo->add(tdc::utf8("Importar Relação de Credores ( Excel ) - Assembleia"));
	
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
	$pagina->add($titulo,$form_bloco,$css,$js);
	$pagina->mostrar();