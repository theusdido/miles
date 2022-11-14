<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe Entity
    * Data de Criacao: 05/03/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

*/	
class Entity {
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
	public $criarprojeto 				= 0;
	public $criarempresa 				= 0;
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
			$sql = "UPDATE td_entidade SET campodescchave = {$fieldParms} WHERE id = {$entityParms};";
			$conn->exec($sql);
		}else{
			if (is_numeric($entityParms)){
				$entity = tdc::p(ENTIDADE,$entityParms);
			}else if(typeof($entityParms) === "string" ){
				$entityOBJ 	= tdClass::Criar("persistent",array(ENTIDADE))->contexto->getOBJ($entityParms)->contexto;
				$entity 	= tdClass::Criar("persistent",array(ENTIDADE,$entityOBJ->getID()))->contexto;
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
						$nome		= "descricao";
						$descricao	= "Descrição";
						$tipo		= "varchar";
						$tamanho	= 200;
						$is_null	= 0;
						$tipohtml	= 3;
						$is_listar	= 1;
					break;
					case 'email':
						$nome		= "email";
						$descricao	= "E-Mail";
						$tipo		= "varchar";
						$tamanho	= 200;
						$is_null	= 1;
						$tipohtml	= 12;
						$is_listar	= 0;
					break;
					default:
						$attr = NULL;
				}
				if ($attr !== NULL){
					$a = criarAtributo(
						$this->conn,
						$this->id,
						$nome,
						$descricao,
						$tipo,
						$tamanho,
						$is_null,
						$tipohtml,
						$is_listar
					);
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
							case 'ckeditor':
								$tipohtml 	= 21;
								$tipo		= 'text';
								$tamanho	= 0;
							break;
							case 'percentual':
								$tipohtml	= 31;
								$tipo 		= 'float';
								$tamanho	= 0;
							break;
							case 'checkbox':
								$tipohtml	= 7;
								$tipo 		= 'boolean';
								$tamanho	= 0;
							break;
							case 'email':
								$tipohtml	= 12;
								$tipo 		= 'varchar';
								$tamanho  	= 200;
							break;
							case 'datahora':
								$tipohtml	= 23;
								$tipo 		= 'datetime';
								$tamanho  	= 0;
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

	/* 
		* Método getID
		* Data de Criacao: 13/11/2021
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o ID da classe na tabela entidade
	*/
	public function getID(){
		return $this->id;
	}

	/*
		* Método isExists
	    * Data de Criacao: 19/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Verifica se a entidade existe no banco de dados e na tabela td_entidade
		@params: $entidade ( String | Integer )
	*/
	public static function isExists($entidade)
	{
		if ($entidade === '') return false;
		$type_entidade 	= gettype($entidade);
		if (is_numeric($entidade)){
			$type_entidade = 'number';
		}

		switch($type_entidade){
			case 'string':
				$where = "nome = '{$entidade}'";
			break;
			case 'number':
			default:
				$where = "id = {$entidade}";
		}
		global $conn;
		$sqlExisteEntidade = "SELECT id,nome FROM " . getSystemPREFIXO() . "entidade WHERE {$where}";
		$queryExisteEntidade = $conn->query($sqlExisteEntidade);
		if (!$queryExisteEntidade){
			if (IS_SHOW_ERROR_MESSAGE){
				echo $sqlExisteEntidade;
				var_dump($conn->errorInfo());
			}
		}
		if ($queryExisteEntidade->rowCount() <= 0) return false;
		$linhaExisteEntidade 	= $queryExisteEntidade->fetch();		
		$nomeEntidade 			= $linhaExisteEntidade['nome'];
		$sqlExisteFisicamente 	= "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE UPPER(TABLE_NAME) = UPPER('".$nomeEntidade."') AND UPPER(TABLE_SCHEMA) = UPPER('".SCHEMA."')";
		$queryExisteFisicamente = $conn->query($sqlExisteFisicamente);		
		if ($queryExisteFisicamente->rowCount() <= 0) return false;

		return true;
	}

	/*
		* Método install
	    * Data de Criacao: 04/12/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Criar a entidade a partir de um arquivo de instalação
		@params: $path ( String )
		@return: ID da entidade
	*/
	public static function install($path){
		global $conn;
		$path_explode 			= explode('_',$path);
		$entidade_nome		 	= end($path_explode);
		$path_file				= str_replace('_','/',$path);
		$pathfile 				= PATH_INSTALL . $path_file . ".php";

		if (file_exists($pathfile)){
			include_once $pathfile;
			return getEntidadeId($entidade_nome,$conn);
		}else{
			echo 'Arquivo não encotrado => ' . $pathfile . '<br/>\n';
			return 0;
		}
	}
}