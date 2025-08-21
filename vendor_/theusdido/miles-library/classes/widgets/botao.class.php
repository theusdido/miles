<?php 		
	requerer('classes/htmlobject.php',false);
	class Botao Extends HtmlObject {
		private $tipo;
		private $valor;
		
		// MÉTODOS GET
		public function getTipo(){
			return $this->tipo;
		}
		public function getValor(){
			return $this->valor;
		}		
		// MÉTODOS SET
		public function setTipo($pTipo){
			if (func_num_args() > 0){
				$this->tipo = $pTipo;
			}
		}		
		public function setValor($pValor){
			if (func_num_args() > 0){
				$this->valor = $pValor;
			}
		}				
		// CONSTRUTORES
		function __construct(){
		}
		
		// Retorno o HTML do Objeto (Return of the HTML OBJECT) 
		public function htmlObject(){
			return 	'<input 
					type="'.(($this->getTipo()!="")?$this->getTipo():'submit').'" 
					'.(($this->getId()!="")?'id="'.$this->getId().'"':"").' 
					'.(($this->getNome()!="")?'name="'.$this->getNome().'"':"").'
					'.(($this->getClasse()!="")?'class="'.$this->getClasse().'"':"").'
					'.(($this->getValor()!="")?'value="'.$this->getValor().'"':"Botão").'
					/>';
		}
	}
?>