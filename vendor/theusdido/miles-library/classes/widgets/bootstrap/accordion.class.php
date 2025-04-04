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
	    * Data de Criacao: 29/02/2024
	    * Autor: @theusdido

		Adiciona um item no accordion
		@item: Litetal
        @conteudo: Object/Literal
	*/
    public function addItem($title = '',$content = null, $extra = [])
    {
        // Extras
        $_is_show = isset($extra['is_show']) ? $extra['is_show'] ? 'show' : '' : '';

        $control        = $this->indice++;
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
        $collapse->class            = 'accordion-collapse collapse ' . $_is_show;
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