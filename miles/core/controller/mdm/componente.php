<?php

	$entidade 	= $_POST["entidade"];
	$path 		= PATH_FILES_CADASTRO . $entidade . "/";

	// Documentação
	$datacriacaodoc = "* @Data de Criacao: ".date("d/m/Y H:i:s");
	$authordoc = "* @Criado por: ".$_SESSION["username"].", @id: ".$_SESSION["userid"];
	$paginadoc = "* @Página: {$entidade} - {$_POST["descricaoentidade"]} [{$_POST["nomeentidade"]}]";

	// Cria o arquivo HTML
	$fp = fopen($path . $_POST["filename"] ,'w+');
	fwrite($fp,htmlespecialcaracteres($_POST["html"],1));
	fclose($fp);

	/*
	// Arquivo HTML do Componente Angular
	$fp = fopen("../../../../miles/angularjs/aplicacao/src/app/pages/crm/pessoa/pessoa.component.html" ,'w');
	fwrite($fp,htmlespecialcaracteres(
	'
		<div class="main-content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header card-header-danger">
								<h4 class="card-title">PESSOA</h4>
								<p class="card-category">Cadastro Único de Locador, Locatário, Fiador.</p>
							</div>
							<div class="card-body">'.$_POST["html"].'</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	'
	,1));
	fclose($fp);
	*/

	// Cria o arquivo HTML Embutido Dinâmico
	$dhtmlFile = $path . $_POST["nomeentidade"] . ".htm";
	if (!file_exists($dhtmlFile)){
		$fp = fopen($dhtmlFile,'w');
		fwrite($fp,"<!--\n * HTML Personalizado \n {$datacriacaodoc} \n {$authordoc} \n {$paginadoc} \n\n Escreve seu código HTML personalizado aqui! \n-->\n");
		fclose($fp);
	}	
	
	// Cria o arquivo CSS
	$cssFile = $path . $_POST["filenamecss"];
	if (!file_exists($cssFile)){
		$fp = fopen($cssFile ,'w');
		fwrite($fp,"/*\n * CSS Personalizado \n {$datacriacaodoc} \n {$authordoc} \n {$paginadoc} \n\n Escreve seu código CSS personalizado aqui! \n*/\n");
		fclose($fp);
	}
	
	// Cria o arquivo JS
	$jsFile = $path . $_POST["filenamejs"];
	if (!file_exists($jsFile)){
		$fp = fopen($jsFile ,'w');
		fwrite($fp,"/*\n * JS Personalizado \n {$datacriacaodoc} \n {$authordoc} \n {$paginadoc} \n */\n\n");
		fwrite($fp,"// Invocado ao clicar no botão Novo");
		fwrite($fp,"\n");
		fwrite($fp,"function beforeNew(){");
		fwrite($fp,"\n\t var btnnew = arguments[0];");
		fwrite($fp,"\n");
		fwrite($fp,"}");
		fwrite($fp,"\n");
		fwrite($fp,"// Executa após o carregamento padrão de uma novo registro");
		fwrite($fp,"\n");
		fwrite($fp,"function afterNew(){");					
		fwrite($fp,"\n\t var contexto = arguments[0];");
		fwrite($fp,"\n");					
		fwrite($fp,"}");
		fwrite($fp,"\n");
		fwrite($fp,"// Invocado ao clicar no botão Salvar");
		fwrite($fp,"\n");
		fwrite($fp,"function beforeSave(){");
		fwrite($fp,"\n\t var btnsave = arguments[0];");
		fwrite($fp,"\n");
		fwrite($fp,"}");
		fwrite($fp,"\n");
		fwrite($fp,"// Executa após o salvamento padrão de um registro");
		fwrite($fp,"\n");
		fwrite($fp,"function afterSave(){");
		fwrite($fp,"\n\t var fp = arguments[0];");
		fwrite($fp,"\n\t var btnsave = arguments[1];");
		fwrite($fp,"\n");
		fwrite($fp,"}");
		fwrite($fp,"\n");
		fwrite($fp,"// Invocado ao clicar no botão Editar ");
		fwrite($fp,"\n");
		fwrite($fp,"function beforeEdit(){");
		fwrite($fp,"\n\t var entidade = arguments[0];");
		fwrite($fp,"\n\t var registro = arguments[1];");
		fwrite($fp,"\n");
		fwrite($fp,"}");
		fwrite($fp,"\n");
		fwrite($fp,"// Executa após o carregamento padrão da edição de registro");
		fwrite($fp,"\n");
		fwrite($fp,"function afterEdit(){");
		fwrite($fp,"\n\t var entidade = arguments[0];");
		fwrite($fp,"\n\t var registro = arguments[1];");					
		fwrite($fp,"\n");
		fwrite($fp,"}");
		fwrite($fp,"\n");
		fwrite($fp,"// Invocado ao clicar no botão Voltar");
		fwrite($fp,"\n");
		fwrite($fp,"function beforeBack(){");
		fwrite($fp,"\n\t var btnback = arguments[0];");
		fwrite($fp,"\n");
		fwrite($fp,"}");
		fwrite($fp,"\n");
		fwrite($fp,"// Executa após a ação de voltar a tela anterior");
		fwrite($fp,"\n");
		fwrite($fp,"function afterBack(){");
		fwrite($fp,"\n\t var btnback = arguments[0];");
		fwrite($fp,"\n");
		fwrite($fp,"}");
		fwrite($fp,"\n");
		fwrite($fp,"// Invocado ao clicar no botão Deletar");
		fwrite($fp,"\n");
		fwrite($fp,"function beforeDelete(){");
		fwrite($fp,"\n");
		fwrite($fp,"}");
		fwrite($fp,"\n");
		fwrite($fp,"// Executa após a exclusão de um registro");
		fwrite($fp,"\n");
		fwrite($fp,"function afterDelete(){");
		fwrite($fp,"\n");
		fwrite($fp,"}");
		fwrite($fp,"\n");
		fwrite($fp,"if (typeof funcionalidade === 'undefined') var funcionalidade = 'cadastro';");
		fwrite($fp,"\n\n/* \n ### Escreva seu código JavaScript abaixo dessa linha ou dentro das funções acima ### \n*/\n");
		fclose($fp);
	}