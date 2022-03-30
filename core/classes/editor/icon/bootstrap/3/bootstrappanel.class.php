<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Classe Panel do Bootstrap
    * Data de Criacao: 27/09/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
Class BootstrapPanel extends Elemento {	

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
		$tdPanel = tdClass::Criar("div");

		// Botão Título do Formulário
		$btnPanel = tdClass::Criar("span");
		$btnPanel->class = "btn btn-default editor-element";
		$btnPanel->data_tag = "div";
		$btnPanel->data_componente = "tdEditorIconLabel";
			$btnSpan = tdClass::Criar("span");
			$btnSpan->class = "fa fa-list-alt fa-2x";
			$btnPanel->add($btnSpan);		
		
		// JS
		$jsPanel = tdClass::Criar("script");
		$jsPanel->add('
			function tdEditorIconLabel(){
				var objSel = pagina.objSelected;
				var classe = $(objSel).attr("class"); 
				$(objSel).attr("class",classe + " bloco crud-contexto");

				var panel = $("<div class=\'panel panel-default\'>");
				var heading = $("<div class=\'panel-heading\'>Panel heading without title</div>");
				var body = $("<div class=\'panel-body\'>Body</div>");
				var foot = $("<div class=\'panel-footer\'>Foot</div>");
				
				pagina.addElement(heading,panel);
				pagina.addElement(body,panel);
				pagina.addElement(foot,panel);
				pagina.addElement(panel,objSel);
			}
		');
		
		$tdPanel->add($btnPanel,$jsPanel);
		return $tdPanel;
	}
}