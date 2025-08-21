<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe TD Título Página
    * Data de Criacao: 26/09/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
Class TdEditorTituloPagina extends Elemento {	

	/*  
		* Método construct 
		* Data de Criacao: 26/09/2018
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Método Construtor
	*/		
	public function __construct(){
		parent::__construct('div');
	}
	
	public static function icon(){
		$tdTitlePageBar = tdClass::Criar("div");

		// Botão Título do Formulário
		$btnTdTitlePage = tdClass::Criar("span");
		$btnTdTitlePage->class = "btn btn-default editor-element";
		$btnTdTitlePage->data_tag = "div";
		$btnTdTitlePage->data_componente = "tdEditorIconTituloPagina";
			$btnSpan = tdClass::Criar("span");
			$btnSpan->class = "fa fa-header fa-2x";
			$btnTdTitlePage->add($btnSpan);		
		
		// JS
		$jsTdTitlePage = tdClass::Criar("script");
		$jsTdTitlePage->add('
			function tdEditorIconTituloPagina(){
				var objSel = pagina.objSelected;
				var classe = $(objSel).attr("class"); 
				$(objSel).attr("class",classe + " bloco crud-contexto");
				
				var titulo = $("<span class=\'edicao-texto\'>Título</span>");
				var divtitulo = $("<div class=titulo-pagina></div>");
				pagina.addElement(titulo,divtitulo);
				pagina.addElement(divtitulo,objSel);
			}
		');
		
		$tdTitlePageBar->add($btnTdTitlePage,$jsTdTitlePage);
		return $tdTitlePageBar;
	}
}