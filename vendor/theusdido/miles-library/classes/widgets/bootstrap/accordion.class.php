<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe Accordion
    * Data de Criacao: 06/11/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class Accordion Extends Elemento {
    private $itens  = array();
    private $_id    = 'accordion';
    private $indice = 0;
	/*
		* Método construct 
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Cria o componente Accordion do Bootstrap
	*/
	public function __construct(){
		parent::__construct('div');
        $this->class                = 'accordion';
        $this->id                   = $this->_id;
	}

	/*  
		* Método addItem
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Adiciona um item no accordion
		@item: Litetal
        @conteudo: Object/Literal
	*/
    // public function addItem($title = '',$item = null){
    //     $control            = $this->indice++;
    //     $headingControl     = 'heading' . $control;
    //     $collapseControl    = 'collapse' . $control;

    //     $panel              = tdc::o('panel');
    //     $panel->head->role  = 'tab';
    //     $panel->head->id    = $headingControl;

	// 	$a 				    = tdc::html('a');
	// 	$a->role 		    = 'button';
    //     $a->data_toggle	    = 'collapse';
	// 	$a->data_parent	    = '#' .  $this->_id;
	// 	$a->href 		    = '#' . $collapseControl;
	// 	$a->aria_expanded	= 'false';
	// 	$a->aria_controls	= $control;
	// 	$a->add($title);

    //     $panel->title($a);
        

    //     $accordion                  = tdc::html('div');
    //     $accordion->id              = $collapseControl;
    //     $accordion->class           = 'panel-collapse collapse';
    //     $accordion->aria_labelledby = $headingControl;
    //     $accordion->role            = 'tabpanel';
    //     $accordion->add($item);
    //     $panel->body($accordion);

    //     $this->add($panel);
    // }

	/*  
		* Método addItem
	    * Data de Criacao: 29/02/2024
	    * Autor: @theusdido

		Adiciona um item no accordion
		@item: Litetal
        @conteudo: Object/Literal
	*/
    public function addItem($title = '',$content = null){
        $control            = $this->indice++;
        $item           = tdc::html('div');
        $item->class    =  'accordion-item';

        $header         = tdc::o('h',array(2));
        $header->class  = 'accordion-header';        

        $button                     = tdc::html('button');
        $button->class              = 'accordion-button';
        $button->type               = 'button';
        $button->data_bs_toggle     = 'collapse';
        $button->data_bs_target     = '#collapse-' . $control;
        $button->aria_expanded      = 'true';
        $button->aria_controls      = 'collapse-' . $control;

        $button->add($title);
        $header->add($button);

        $collapse                   = tdc::html('div');
        $collapse->id               = 'collapse-' . $control;
        $collapse->class            = 'accordion-collapse collapse show';
        $collapse->data_bs_parent   = '#accordion';

        $body                       = tdc::html('div');
        $body->class                = 'accordion-body';

        if ($content != null)
            $body->add($content);


        $collapse->add($body);

        $item->add($header);
        $item->add($collapse);

        $this->add($item);
    }

    public function mostrar(){
        parent::mostrar();
    }
}