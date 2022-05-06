<?php	
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Elemento
    * Data de Criacao: 30/08/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Elemento {
	private $tag;
	private $propriedades = Array();
	private $filhos = Array();
	public $qtde_filhos = 0;
	private $tag_sem_fechamento = array ("link","input","meta","img","br");
	private $sessionid;
	public $istostring = false;
	private $classes = Array();

	/*
		* Método construct 
	    * Data de Criacao: 30/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Instancia uma classe HTML
		@params nome da tag
	*/		
	public function __construct($tag){
		$this->tag = $tag;		
	}
	
	/*  
		* Método __set
	    * Data de Criacao: 30/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Intercepta as atribuições de atributos
		@params nome [ nome da propriedade ]
		@params valor [ valor da propriedade ]
	*/
	public function __set($nome,$valor){
		$nome = str_replace("_","-",$nome);
		if ($nome == "href" || $nome == "src"){
			$extensao = getExtensao($valor);
			if ($extensao == "css" || $extensao == "js"){
				
				$valor .= (strpos($valor,"?") === false ?"?":"&") . getnocacheparams();
			}
		}
		$this->propriedades[$nome] = isset($this->propriedades[$nome])?$this->propriedades[$nome]." ".$valor:$valor;
	}

	/*  
		* Método add
	    * Data de Criacao: 30/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Adiciona um elemento filho na Tag
		@parms: filho
	*/		
	public function add(){	
		if (func_num_args() > 0){
			foreach (func_get_args() as $filho){
				// Tratamento para quando for enviada um array. by @theusdido 23/10/2021 17:43h
				if (gettype($filho) == 'array'){
					foreach($filho as $f){
						$this->add($f);
					}
				}else{
					$this->filhos[] = $filho;
					$this->qtde_filhos++;
				}
			}
			$this->setJSONSession();
		}	
	}
	
	/*  
		* Método abrir 
	    * Data de Criacao: 30/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Exibe a tag de abertura na tela
	*/	
	function abrir(){
		$iniciotag = "<{$this->tag}";
		if ($this->propriedades){
			foreach($this->propriedades as $nome=>$valor){
				$iniciotag .= " {$nome}=\"{$valor}\"";
			}		
		}
		
		if(in_array($this->tag, $this->tag_sem_fechamento)) $iniciotag .= " /";
		
		$iniciotag .=  ">";
		if ($this->istostring){
			return $iniciotag;
		}else{
			echo $iniciotag;
		}	
	}
	/*  
		* Método mostrar 
	    * Data de Criacao: 30/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Exibe uma tag na tela, juntamento com seu conteúdo
	*/	
	function mostrar(){
		$this->abrir();
		if ($this->filhos){
			foreach($this->filhos as $filho){
				if (is_object($filho)){
					$filho->mostrar();
				}else if((is_string($filho)) or (is_numeric($filho))){
					tdClass::Write($filho);
				}
			}			
		}
		if(!in_array($this->tag, $this->tag_sem_fechamento))
			$this->fechar();
	}

	/*  
		* Método innerHTML 
	    * Data de Criacao: 01/05/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o conteúdo da tag
	*/		
	function innerHTML(){
		$innerhtml = "";
		if ($this->filhos){
			foreach($this->filhos as $filho){
				if (is_object($filho)){
					$filho->istostring = true;
					$innerhtml .= $filho->toString();
				}else if((is_string($filho)) or (is_numeric($filho))){
					$innerhtml .= $filho;
				}
			}
		}
		return $innerhtml;
	}
	
	/*  
		* Método fechar 
	    * Data de Criacao: 30/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Fecha uma tag HTML
	*/	
	function fechar(){
		$fechamentotag = "</{$this->tag}>";
		if ($this->istostring){
			return $fechamentotag;
		}else{
			echo $fechamentotag;
		}	
	}
	
	/*
		* toString
	    * Data de Criacao: 30/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Returna o elemento como String
	*/
	function toString(){
		$this->istostring = true;		
		$string = $this->abrir();
		$string .= $this->innerHTML();
		if(!in_array($this->tag, $this->tag_sem_fechamento))
			$string .= $this->fechar();
		
		return $string;
	}
	
	/*  
		* setSessionID
	    * Data de Criacao: 30/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Seta o ID do objeto na Sessão
	*/		
	function setSessionID(){
		$this->sessionid = (int)Session::get("IDOBJECTHTML") + 1;
		Session::append("IDOBJECTHTML",$this->sessionid);
	}
	/*  
		* getSessionID
	    * Data de Criacao: 30/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o ID do objeto na Sessão
	*/		
	function getSessionID(){
		return $this->sessionid;
	}

	function setJSONSession(){
		$obj["tag"] = $this->tag;
		if (getPHPVersion() >= 7){
			$filhos = array();
			foreach($this->filhos as $f){
				$filhos[] = is_object($f) ? "##object(".$f->getSessionID().")#" : $f;
			}
		}else{
			$filhos = array();
			foreach($this->filhos as $f){
				array_push($filhos,is_object($f) ? "##object(".$f->getSessionID().")#" : $f);
			}
		}
		$obj["filhos"] = $filhos;
		Session::append("JSONOBJECT[{$this->sessionid}]",$obj);
	}

	public function getFilhos()
	{
		return $this->filhos;
	}
}