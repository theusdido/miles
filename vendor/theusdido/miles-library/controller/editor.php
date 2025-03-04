<?php
	$pagina = tdClass::Criar("pagina");
	$pagina->body->style = "background-image:none;";
	$pagina->showJSMask = $pagina->showJSMaskMoney = $pagina->showJSBootBox = $pagina->showJSGoogleMaps = $pagina->showCSSTheme = $pagina->showJSParticularidades = $pagina->showJSTaisFuncoes = false;
	
	$jsEditor = tdClass::Criar("script");
	$jsEditor->src = "system/controller/editor/editor.js";
	
	$jsUI = tdClass::Criar("script");
	$jsUI->src = "lib/jquery/ui/jquery-ui.min.js";

	$jsShortcut = tdClass::Criar("script");
	$jsShortcut->src = "lib/jquery/shortcut/shortcut.js";
	
	$editorCSS = tdClass::Criar("link");
	$editorCSS->href = 'system/controller/editor/editor.css';
	$editorCSS->rel = 'stylesheet';

	$geralCSS = tdClass::Criar("link");
	$geralCSS->href = 'system/tema/padrao/geral.css';
	$geralCSS->rel = 'stylesheet';

	$fontAwesomeCSS = tdClass::Criar("link");
	$fontAwesomeCSS->href = 'lib/font-awesome/css/font-awesome.css';
	$fontAwesomeCSS->rel = 'stylesheet';
	
	$propertiesBar	= tdClass::Criar("div");
	$propertiesBar->class = "propertiesBar";
	
	// Botão Salvar
	$btn_salvar = tdClass::Criar("button");
	$btn_salvar->class = "btn btn-default";
	$btn_salvar->id = "btn-salvar-editor";
	$btn_salvar->add("Salvar");	
	
	// Corpo ( body )
	$body = tdClass::Criar("div");
	$body->class = "editor-body";
	
	$tdiconeditor = tdClass::Criar("editor.icon.iconeditorbar");
	
	// Collapse
	$collapse = tdClass::Criar("collapse");
	$collapse->addTab("Layouts",$tdiconeditor::layouts());
	$collapse->addTab("TD Formulário",$tdiconeditor::td());
	$collapse->addTab("Bootstrap",$tdiconeditor::bootstrap());
	$collapse->addTab("Propriedades",$propertiesBar);

	// Tools Bar
	$leftBar = tdClass::Criar("div");
	$leftBar->class = "leftbar-editor";	
	
	$leftBar->add($collapse);
	
	// Biblioteca JQuery UI ( CSS )
	$smoothnessCSS = tdClass::Criar("link");
	$smoothnessCSS->href = 'lib/jquery/ui/jquery-ui.css';
	$smoothnessCSS->rel = 'stylesheet';


	$jsBootBox = tdClass::Criar("script");
	$jsBootBox->src = "lib/jquery/jquery-bootbox.js";
	
	$jsFuncoes = tdClass::Criar("script");
	$jsFuncoes->src = "system/funcoes.js";
	
	// Monta a tabela(HTML) GERAL
	$tablePropertiesGERAL = tdClass::Criar("tabela");
	$tablePropertiesGERAL->class = "table-geral-attribute";
	$captionGERAL = tdClass::Criar("caption");
	$captionGERAL->add("GERAL");
	$tablePropertiesGERAL->add($captionGERAL);
	
	// Monta a tabela(HTML) de atributos DOM
	$tablePropertiesDOM = tdClass::Criar("tabela");
	$tablePropertiesDOM->class = "table-dom-attribute";
	$captionDOM = tdClass::Criar("caption");
	$captionDOM->add("DOM");
	$tablePropertiesDOM->add($captionDOM);
	
	// Monta a tabela(HTML) de atributos CSS
	$tablePropertiesCSS = tdClass::Criar("tabela");
	$tablePropertiesCSS->class = "table-css-attribute";
	$captionCSS = tdClass::Criar("caption");
	$captionCSS->add("CSS");
	$tablePropertiesCSS->add($captionCSS);
	
	$propertiesBar->add($tablePropertiesGERAL,$tablePropertiesDOM,$tablePropertiesCSS);
	
	$pagina->head->add($editorCSS,$geralCSS,$smoothnessCSS,$fontAwesomeCSS);
	$pagina->body->add($body,$btn_salvar,$leftBar,$jsBootBox,$jsUI,$jsFuncoes,$jsShortcut,$jsEditor);
	$pagina->mostrar();
?>