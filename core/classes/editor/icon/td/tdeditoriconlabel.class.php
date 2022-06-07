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
Class TdEditorIconLabel extends Elemento {	

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
		$tdLabel = tdClass::Criar("div");

		// Botão Título do Formulário
		$btnLabel = tdClass::Criar("span");
		$btnLabel->class = "btn btn-default editor-element";
		$btnLabel->data_tag = "div";
		$btnLabel->data_componente = "tdEditorIconLabel";
			$btnSpan = tdClass::Criar("span");
			$btnSpan->class = "fa fa-font fa-2x";
			$btnLabel->add($btnSpan);		
		
		// JS
		$jsLabel = tdClass::Criar("script");
		$jsLabel->add('
			function tdEditorIconLabel(){
				var objSel = pagina.objSelected;
				var classe = $(objSel).attr("class"); 
				$(objSel).attr("class",classe + " bloco crud-contexto");

				var label = $("<label for=\'basic-url\'>Your vanity URL</label>");
				pagina.addElement(label,objSel);
			}
		');
		
		$tdLabel->add($btnLabel,$jsLabel);
		return $tdLabel;
	}
}