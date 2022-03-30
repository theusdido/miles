 <?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe que implementa a instação dos componentes
    * Data de Criacao: 06/11/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class Component {
    private $name;
	/*  
		* Método __construct
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Inicializada a classe
		@name: String
	*/
    public function __construct($name = null){
        if ($name != null) $this->setName($name);
    }
	/*  
		* Método setName
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Adiciona o nome do componentes
		@name: String
	*/
    public function setName($name){
        $this->name = $name;
    }
	/*  
		* Método setTitle
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Adiciona titulo
		@name: String
	*/
    public function setTitle($title){
        $this->title = $title;
    }
	/*
		* Método getTitle
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna title
		@return: String
	*/
    public function getTitle(){
        return $this->title;
    }
	/*  
		* Método getName
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o nome do componentes
		@return: String
	*/
    public function getName(){
        return $this->name;
    }    

}