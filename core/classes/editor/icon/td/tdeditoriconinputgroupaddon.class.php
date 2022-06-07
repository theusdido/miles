<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe TD Input Group ADD ON
    * Data de Criacao: 26/09/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
Class TdEditorIconInputGroupAddOn extends Elemento {	

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
		$tdInputGroupAddOn = tdClass::Criar("div");

		// Botão Título do Formulário
		$btnInputGroupAddOn = tdClass::Criar("span");
		$btnInputGroupAddOn->class = "btn btn-default editor-element";
		$btnInputGroupAddOn->data_tag = "div";
		$btnInputGroupAddOn->data_componente = "tdEditorIconInputGroupAddOn";
			$btnSpan = tdClass::Criar("span");
			$btnSpan->class = "fa fa-cc fa-2x";
			$btnInputGroupAddOn->add($btnSpan);		
		
		// JS
		$jsInputGroupAddOn = tdClass::Criar("script");
		$jsInputGroupAddOn->add('
			function tdEditorIconInputGroupAddOn(){
				var objSel = pagina.objSelected;
				var classe = $(objSel).attr("class"); 
				$(objSel).attr("class",classe + " bloco crud-contexto");
				
				var label = $("<label for=\'basic-url\'>Your vanity URL</label>");
				var inputgroup = $("<div class=\'input-group\'></div>");
				var span = $("<span class=\'input-group-addon\'>https://example.com/users/</span>");
				var input = $("<input type=\'text\' class=\'form-control\' aria-describedby=\'basic-addon3\'>");
				
				pagina.addElement(span,inputgroup);
				pagina.addElement(input,inputgroup);
				pagina.addElement(label,objSel);
				pagina.addElement(inputgroup,objSel);
			}
		');
		
		$tdInputGroupAddOn->add($btnInputGroupAddOn,$jsInputGroupAddOn);
		return $tdInputGroupAddOn;
	}
}