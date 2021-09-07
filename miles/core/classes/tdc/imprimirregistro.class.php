<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe que implementa a geração de relatório
    * Data de Criacao: 30/04/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class ImprimirRegistro {
	private $entidade;
	private $registro;
	private $campodescentidadeprincipal;
	/*  
		* Método __construct
	    * Data de Criacao: 30/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Método construtor da Classe
		@entidade: Entidade do registro.
		@id: Chave primária do registro.
	*/
	public function __construct($entidade,$registro){
		$this->entidade = tdc::e($entidade,$registro);
		$this->registro = $registro;
	}
	/*  
		* Método cabecalho
	    * Data de Criacao: 30/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Exibe o cabeçalho
	*/	
	private function cabecalho(){
		$dvEntidade = tdc::html("div","","imprimirregistro-header");
		$divID = tdc::html("div","ID: " . tdc::html("strong",$this->registro)->toString());
		$divID->id = "cabecalho-id";
		$dvEntidade->add($divID);
		$this->campodescentidadeprincipal = tdc::a(getCampoDescricaoDefault($this->entidade));
		$divCampoDesc = tdc::html("div",$this->campodescentidadeprincipal->descricao . ": " . tdc::html("strong",$this->entidade->nome)->toString());
		$divCampoDesc->id = "cabecalho-descricao";
		$dvEntidade->add($divCampoDesc);
		global $currentFile;
		$divEmpresa = tdc::html("div","Empresa: " .tdc::html("strong",$currentFile["PROJETO_DESC"])->toString());
		$divEmpresa->id = "cabecalho-empresa";
		$dvEntidade->add($divEmpresa);
		$dvEntidade->add(tdc::html("hr"));
		$dvEntidade->mostrar();
	}
	
	/*  
		* Método body
	    * Data de Criacao: 01/05/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Imprimir o corpo
	*/
	private function body(){
		$divBody = tdc::html("div","","imprimirregistro-body");
		foreach (tdc::dh(get_class($this->entidade),$this->entidade->id,[1]) as $k => $d){
			foreach(tdc::d(ATRIBUTO,array(ENTIDADE,"=",$k)) as $a){
				if (!($this->campodescentidadeprincipal->nome == $a->nome && $this->campodescentidadeprincipal->entidade == $k)){
					$label 	= tdc::html("span",$a->descricao.": ",null,"descricao-span");
					$dado	= tdc::html("span",$d->{$a->nome},null,"dados-span");
					$linha 	= tdc::html("div");
					$linha->add($label,$dado);
					$divBody->add($linha);
				}	
			}
		}
		$divBody->mostrar();
	}
	/*  
		* Método footer
	    * Data de Criacao: 01/05/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Imprimir o rodapé
	*/
	private function footer(){
		$dvRodape = tdc::html("div","","imprimirregistro-footer");
		$dvRodape->add(tdc::html("hr"));
		$divUsuario = tdc::html("div","Usuário: " . tdc::html("strong",Session::get()->username)->toString());
		$divUsuario->id = "rodape-usuario";
		$dvRodape->add($divUsuario);
		$divDatahora = tdc::html("div","Data e Hora: " . date("d/m/Y H:i:s"));
		$divDatahora->id = "rodape-datahora";
		$dvRodape->add($divDatahora);
		$dvRodape->mostrar();
	}
	
	/*  
		* Método imprimir
	    * Data de Criacao: 30/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Imprimir na Tela
	*/
	public function imprimir(){
		$this->cabecalho();
		$this->body();
		$this->footer();
	}
}