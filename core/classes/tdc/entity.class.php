<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe Entidade
    * Data de Criacao: 05/03/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

*/	
class Entity{
	private $id 						= 0;
	public $conn;
	public $nome 						= "";
	public $descricao 					= "";
	public $ncolunas 					= 3;
	public $exibirmenuadministracao 	= 0;
	public $exibircabecalho 			= 1;
	public $campodescchave 				= 0;
	public $atributogeneralizacao 		= 0;
	public $exibirlegenda 				= 1;
	public $criarprojeto 				= 1;
	public $criarempresa 				= 1;
	public $criarauth 					= 0;
	public $registrounico 				= 0;
	private $atributos					= array();
	public $modulo_nome					= '';
	public $modulo_descricao			= '';

	/* 
		* Método __construct
		* Data de Criacao: 30/09/2021
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	*/
	public function __construct($nome,$descricao){
		$this->nome 				= $nome;
		$this->descricao 			= $descricao;
		global $conn;
		$this->conn 				=  $conn;
		$this->modulo_nome			= tdc::r('modulonome');
		$this->modulo_descricao 	= tdc::r('modulodescricao');

		$this->create();
	}

	/* 
		* Método setDescriptionField()
		* Data de Criacao: 05/03/2018
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Set the description field  of entity
	*/	
	public static function setDescriptionField($conn,$entityParms,$fieldParms,$install = false){
		if ($install){
			$sql = "UPDATE td_entidade SET campodescchave = $fieldParms WHERE id = $entityParms;";
			$conn->exec($sql);
		}else{
			if (is_numeric($entityParms)){
				$entity = tdClass::Criar("persistent",array("entidade",$entiadeParms))->contexto;
			}else if(typeof($entityParms) === "string" ){
				$entityOBJ = tdClass::Criar("persistent",array(ENTIDADE))->contexto->getOBJ($entityParms)->contexto;
				$entity = tdClass::Criar("persistent",array(ENTIDADE,$entityOBJ->getID()))->contexto;
			}
			if (gettype($entity) === "object"){
				$entity->campodescchave = $fieldParms;
				$entity->armazenar();
			}else{
				echo 'Entidade não encontrada';
				return false;	
			}			
		}
	}
	/* 
		* Método criar
		* Data de Criacao: 30/09/2021
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Cria a entidade no banco de dados
	*/
	public function create(){
		$this->id = criarEntidade(
			$this->conn,
			$this->nome,
			$this->descricao,
			$this->ncolunas,
			$this->exibirmenuadministracao,
			$this->exibircabecalho,
			$this->campodescchave,
			$this->atributogeneralizacao,
			$this->exibirlegenda,
			$this->criarprojeto,
			$this->criarempresa,
			$this->criarauth,
			$this->registrounico
		);

		$this->addMenu();
		return $this->id;
	}
	/* 
		* Método addAttr
		* Data de Criacao: 30/09/2021
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona atributos na entidade
	*/
	public function addAttr($attr){

		switch(gettype($attr)){
			case 'string':
				switch($attr){
					case 'descricao':
						$a = criarAtributo($this->conn,$this->id,"descricao","Descrição","varchar","200",0,3,1,0,0,"");
					break;
				}
			break;
			case 'array':
					$tipohtml	= isset($attr['tipohtml'])?$attr['tipohtml']:3;
					$tipo 		= isset($attr['tipo'])?$attr['tipo']:'varchar';
					$tamanho	= isset($attr['tamanho'])?$attr['tamanho']:($tipo=='varchar'?200:0);
					if (gettype($tipohtml) == 'string'){
						switch($attr['tipohtml']){
							case 'numero_inteiro':
								$tipohtml	= 25;
								$tipo 		= 'int';
								$tamanho	= 0;
							break;
							case 'data':
								$tipohtml	= 11;
								$tipo 		= 'date';
								$tamanho	= 0;
							break;
							case 'monetario':
								$tipohtml	= 13;
								$tipo 		= 'float';
								$tamanho	= 0;
							break;
						}
					}
					$is_obrigatorio 		= isset($attr['is_obrigatorio']) ? $attr['is_obrigatorio'] : 1;
					$is_exibirgradedados	= isset($attr['is_exibirgradedados']) ? $attr['is_exibirgradedados'] : 0;
					$chave_estrangeira		= isset($attr['chave_estrangeira']) ? $attr['chave_estrangeira'] : 0;
					$a = criarAtributo(
						$this->conn,
						$this->id,
						$attr['nome'],
						$attr['descricao'],
						$tipo,
						$tamanho,
						$is_obrigatorio,
						$tipohtml,
						$is_exibirgradedados,
						$chave_estrangeira
					);
			break;
			default:
				$a = $attr;
		}

		array_push($this->atributos, $a);
		return $a;
	}
	/* 
		* Método addMenu
		* Data de Criacao: 30/09/2021
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Criar o menu da entidade no sistema
	*/
	private function addMenu(){

		// Criando Acesso
		$menu = addMenu($this->conn,$this->modulo_descricao,'#','',0,0,$this->modulo_nome);

		// Adicionando Menu
		addMenu($this->conn,$this->descricao,"files/cadastro/".$this->id."/".getSystemPREFIXO().$this->nome.".html",'',$menu,0,$this->modulo_nome . '-' . $this->nome);

	}
}