<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe TD Icone Formulário
    * Data de Criacao: 25/09/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/
Class TdEditorIconFormulario extends Elemento {	

	/*  
		* Método construct 
		* Data de Criacao: 25/09/2018
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Método Construtor
	*/		
	public function __construct(){
		parent::__construct('div');
	}
	
	public static function icon(){
		$tdFormBar = tdClass::Criar("div");

		// Botão Formulário
		$btnTdForm = tdClass::Criar("span");
		$btnTdForm->class = "btn btn-default editor-element";
		$btnTdForm->data_tag = "div";
		$btnTdForm->data_componente = "tdEditorComponenteFormulario";
			$btnSpan = tdClass::Criar("span");
			$btnSpan->class = "fa fa-wpforms fa-2x";
			$btnTdForm->add($btnSpan);	

		// JS
		$jsTdForm = tdClass::Criar("script");
		$jsTdForm->add('
			function tdEditorComponenteFormulario(){
				var objSel = pagina.objSelected;
				var classe = $(objSel).attr("class"); 
				$(objSel).attr("class",classe + " bloco crud-contexto");
				
				var titulo = $("<span class=\'edicao-texto\'>Título</span>");
				var divtitulo = $("<div class=titulo-pagina></div>");
				pagina.addElement(titulo,divtitulo);
				pagina.addElement(divtitulo,objSel);
				
				/*
				var grupobotoes = $("<div class=\'form-grupo-botao\'><div class=\'loader-salvar\'></div><button type=\'button\' class=\'btn btn-success b-salvar\' id=\'b-salvar-td_cliente\' data-loading-text=\'Aguarde...\'><span class=\'fas fa-check\'></span> Salvar</button><button type=\'button\' class=\'btn btn-link b-voltar\' id=\'cliente\' value=\'\'><span class=\'fas fa-arrow-leftt\'></span>Voltar</button><div class=\'retorno msg-retorno-form-td_cliente\' style=\'display:none\'><div role=\'alert\' class=\'alert alert-dismissible alert-success\' style=\'text-align:right;font-weight:bold\'><strong></strong><button type=\'button\' class=\'close\' aria-label=\'Close\' onclick=\'fecharAlerta();\'><span aria-hidden=\'true\'>×</span></button></div></div></div>");
				pagina.addElement(grupobotoes,objSel);
				*/
				
				var grupobotoes =  $("<div class=\'form-grupo-botao\'>");
				pagina.addElement(grupobotoes,objSel);
				
				var loadersalvar = $("<div class=\'loader-salvar\'></div>");
				pagina.addElement(loadersalvar,grupobotoes);

				var salvarSpanbotao = $("<span class=\'fas fa-check\'></span>");
				var salvarBotao = $("<button type=\'button\' class=\'btn btn-success b-salvar\' id=\'b-salvar-td_cliente\' data-loading-text=\'Aguarde...\'>");
				pagina.addElement(salvarBotao,grupobotoes);
				pagina.addElement(salvarSpanbotao,salvarBotao);
				pagina.addElement($("<span> Salvar</span>"),salvarBotao);
				
				var voltarSpanBotao = $("<span class=\'fas fa-arrow-leftt\'></span>");
				var voltarBotao = $("<button type=\'button\' class=\'btn btn-link b-voltar\' id=\'td_cliente\' value=\'\'>");
				pagina.addElement(voltarBotao,grupobotoes);
				pagina.addElement(voltarSpanBotao,voltarBotao);
				pagina.addElement($("<span>Voltar</span>"),voltarSpanBotao);

				var spanCloseMSG = $("<span aria-hidden=\'true\'>×</span>");
				var buttonCloseMSG = $("<button type=\'button\' class=\'close\' aria-label=\'Close\' onclick=\'fecharAlerta();\'>")

				var divalertMSG = $("<div role=\'alert\' class=\'alert alert-dismissible alert-success\' style=\'text-align:right;font-weight:bold\'>");
				var retornoMSG = $("<div class=\'retorno msg-retorno-form-td_cliente\' style=\'display:none\'>");
				pagina.addElement(retornoMSG,grupobotoes);
				pagina.addElement(divalertMSG,retornoMSG);
				pagina.addElement(buttonCloseMSG,retornoMSG);
				pagina.addElement(spanCloseMSG,buttonCloseMSG);

				var formcampos = $("<div class=\'row-fluid form_campos\'>");
				pagina.addElement(formcampos,objSel);

				var coluna = $("<div class=\'coluna\' data-ncolunas=\'1\'>");

				pagina.addElement(coluna,objSel);
				
				var formgroup = $("<div class=\'form-group active in\'>");
				pagina.addElement(formgroup,coluna);
				
				var label = $("<label for=\'nome\' class=\'control-label edicao-texto\'>Nome</label>")
				pagina.addElement(label,formgroup);

				var campo = $("<input id=\'nome\' name=\'nome\' class=\'form-control input-sm texto-longo fp gd\' data-entidade=\'td_cliente\' required=\'true\' atributo=\'27\'>");
				pagina.addElement(campo,formgroup);
			}
		');
		$tdFormBar->add($btnTdForm,$jsTdForm);
		return $tdFormBar;
	}

}

