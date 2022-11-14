<?php	
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://teia.tec.br

    * Classe de interface da tag TABLE
    * Data de Criacao: 07/11/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class Table Extends Elemento {
    private $thead;
    private $tbody;
	/*
		* Método construct
	    * Data de Criacao: 07/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Instância o objeto tabela
	*/
	public function __construct(){
		parent::__construct('table');
        $this->thead = tdc::o('thead');
        $this->tbody = tdc::o('tbody');
	}
    /*  
		* Método addHeadTR
	    * Data de Criacao: 07/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

        Adiciona uma TH dentro da thead com uma TR
        @content: String | Object | Array
        @return: Object
    */
    public function addHeadTR($content){
        $tr     = tdc::html('tr');
        if (func_num_args() > 1) $content = func_get_args();
        switch(gettype($content)){
            case 'array':
                foreach($content as $param){
                    $tr->add($this->addHeadTRContentTH($param));
                }
            break;
            default:
                $tr->add($this->addHeadTRContentTH($content));

        }
        $this->thead->add($tr);
        return $tr;
    }
    /*  
		* Método addHeadTRContentTH
	    * Data de Criacao: 09/12/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

        Adiciona conteúdo dentro da TH thead com uma TR
        @content: String | Object | Array
        @return: Elemento th
    */
    private function addHeadTRContentTH($content){        
        $th     = tdc::html('th');
        $th->add($content);
        return $th;
    }
    /* 
		* Método addBodyTR
	    * Data de Criacao: 07/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

        Adiciona conteúdo dentro da tbody com uma TR
        @content: String | Object
        @return: Object 
    */
    public function addBodyTR($content){
        $tr     = tdc::html('tr');
        
        if (func_num_args() > 1) $content = func_get_args();

        switch(gettype($content)){
            case 'array':
                foreach($content as $param){
                    $tr->add($this->addBodyTRContentTD($param));
                }
            break;
            default:
                $tr->add($this->addBodyTRContentTD($content));

        }
        $this->tbody->add($tr);
        return $tr;
    }
    /*  
		* Método addBodyTRContentTD
	    * Data de Criacao: 09/12/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

        Adiciona conteúdo dentro da TD thead com uma TR
        @content: String | Object | Array
        @return: Elemento td
    */
    private function addBodyTRContentTD($content){
        $td     = tdc::html('td');
        $td->add($content);
        return $td;
    }
    /*
		* mostrar
	    * Data de Criacao: 07/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

        Renderiza o elemento
        @content: String | Object
        @return: void
    */
    public function mostrar(){
        $this->add($this->thead);
        $this->add($this->tbody);
        parent::mostrar();
    }
}