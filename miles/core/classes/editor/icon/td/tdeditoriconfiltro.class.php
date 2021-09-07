<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe TD Input Group ADD ON
    * Data de Criacao: 27/09/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
Class TdEditorIconFiltro extends Elemento {

	/*  
		* Método construct 
		* Data de Criacao: 27/09/2018
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Método Construtor
	*/		
	public function __construct(){
		parent::__construct('div');
	}
	
	public static function icon(){
		$tdInputGroupFiltro = tdClass::Criar("div");

		// Botão Título do Formulário
		$btnInputGroupFiltro = tdClass::Criar("span");
		$btnInputGroupFiltro->class = "btn btn-default editor-element";
		$btnInputGroupFiltro->data_tag = "div";
		$btnInputGroupFiltro->data_componente = "tdEditorIconFiltro";
			$btnSpan = tdClass::Criar("span");
			$btnSpan->class = "fa fa-search fa-2x";
			$btnInputGroupFiltro->add($btnSpan);		
		
		// JS
		$jsInputGroupFiltro = tdClass::Criar("script");
		$jsInputGroupFiltro->add('
			function tdEditorIconFiltro(){
				var objSel = pagina.objSelected;
				var classe = $(objSel).attr("class"); 
				$(objSel).attr("class",classe + " bloco crud-contexto");
				
				var label = $("<label for=\'basic-url\'>Filtro</label>");
				var inputgroup = $("<div class=\'input-group filtro-pesquisa\'></div>");
				var termofiltro = $("<input type=\'text\' class=\'form-control termo-filtro gd\' data-fk=\'\' data-entidade=\'\'>");
				var descricaofiltro = $("<input type=\'text\' readonly=\'true\' class=\'form-control descricao-filtro\' data-entidade=\'\'>");
				var inputgroupbtn = $("<span class=\'input-group-btn\'>");
				var botaofiltro = $("<button class=\'btn btn-default botao-filtro\' data-fk=\'\' data-entidade=\'\'>");
				var graphicon = $("<span class=\'fas fa-search\'></span>");

				pagina.addElement(termofiltro,inputgroup);
				pagina.addElement(descricaofiltro,inputgroup);
				pagina.addElement(inputgroupbtn,inputgroup);
				pagina.addElement(botaofiltro,inputgroupbtn);
				pagina.addElement(graphicon,botaofiltro);
				pagina.addElement(label,objSel);
				pagina.addElement(inputgroup,objSel);
			}
		');
		
		$tdInputGroupFiltro->add($btnInputGroupFiltro,$jsInputGroupFiltro);
		return $tdInputGroupFiltro;
	}
}