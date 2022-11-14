 <?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe que implementa a instação dos módulos
    * Data de Criacao: 06/11/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class Module {
    private $name;
    private $components = array();
    private $package;
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
		
		Adiciona o nome do módulo
		@name: String
	*/
    public function setName($name){
        $this->name = $name;
    }
	/*
		* Método getName
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o nome do módulo
		@return: String
	*/
    public function getName(){
        return $this->name;
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
		* Método getComponent
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna uma lista de componentes
		@return: Vetor( Array )
	*/
    public function getComponent(){
        return $this->components;
    }

	/*  
		* Método setComponent
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona um componente na lista
		@return: Void
	*/
    public function setComponent($components){
        foreach($components as $c){
            $object     = (object)$c;
            $component  = tdc::o('component');
            $component->setName($object->name);
            $component->setTitle($object->title);
            array_push($this->components,$component);
        }
    }
}