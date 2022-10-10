<?php	
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Registro
    * Data de Criacao: 05/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
abstract class Registro {
	protected $dados;
	private $dadosarray;
	public $isAutoIncrement = true;
	private $isnew = true;

	/*
		* Método construct
	    * Data de Criacao: 05/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Instancia um objeto do tipo registro passando o ID como parametro
		@params ID
	*/
	public function __construct($id=null){
		if (!empty($id)){
			$this->isnew 	= false;
			$objeto 		= $this->carregar((int)$id);
			if ($objeto){
				$this->fromArray($objeto->toArray());
			}
		}
	}

	/*  
		* Método clone 
	    * Data de Criacao: 05/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Clona um objeto		
	*/	
	public function __clone(){
		unset($this->id); # Unset não está funcionando
		$this->id = $this->proximoID(); # Ao ser clonado adiciona o próximo ID
		$this->armazenar();
	}
	
	/*  
		* Método set 
	    * Data de Criacao: 05/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Executado quando uma propriedade for atribuida
		@parms propriedade
		@parms valor
	*/	
	public function __set($propriedade,$valor){
		if ($propriedade == "id") $this->isAutoIncrement = false; #Desabilita o auto increment se o ID for setado explicitamente
		if (method_exists($this,'set_'.$propriedade)){
			call_user_func(array($this,'set_'.$propriedade),$valor);
		}else{
			$this->dados[$propriedade] = $valor;
		}
	}

	/*  
		* Método get 
	    * Data de Criacao: 05/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Executado quando uma propriedade for requerida
		@parms propriedade		
	*/
	public function __get($propriedade){
		if (method_exists($this,'get_'.$propriedade)){
			call_user_func(array($this,'get_'.$propriedade));
		}else{
			if (isset($this->dados[$propriedade])){
				return $this->dados[$propriedade];
			}else{
				return false;
			}
		}
	}

	/*  
		* Método getEntidade 
	    * Data de Criacao: 05/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o nome da entidade ( classe ou tabela do banco de dados )
		@parms propriedade		
	*/
	public function getEntidade(){
		return get_class($this);
	}

	/*
		* Método fromArray 
	    * Data de Criacao: 05/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Preenche os dados do objeto como uma array
		@parms $dados
	*/	
	public function fromArray($dados){
		$this->dados = $dados;
	}
	/*  
		* Método toArray 
	    * Data de Criacao: 05/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna os dados do objeto como uma array		
	*/	
	public function toArray(){
		return $this->dados;
	}
	
	/*  
		* Método armazenar 
	    * Data de Criacao: 05/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Armazena os objetos na base de dados e retorna a quantidade de linhas afetas pelo SQL ( zero e um )
	*/	
	public function armazenar(){
		if ($this->isnew){			
			if ($this->isAutoIncrement){
				$this->id = $this->getUltimo() + 1;
			}else{
				$this->id = $this->dados["id"];
			}

			$sql = tdClass::Criar("sqlinserir");
			$sql->setEntidade($this->getEntidade());
			foreach($this->dados as $key => $valor){
				$sql->setLinha($key,$valor);
			}
		}else{
			$sql = tdClass::Criar("sqlatualizar");
			$sql->setEntidade($this->getEntidade());
			$criterio = tdClass::Criar("sqlcriterio");
			$criterio->add(tdClass::Criar("sqlfiltro",array("id","=",$this->id)));
			$sql->setCriterio($criterio);

			foreach($this->dados as $key => $valor){
				if($key != "id") $sql->setLinha($key,$valor);
			}
		}
		
		try{
			if ($conn = Transacao::get()){
				Transacao::log($sql->getInstrucao());
				$resultado 			= $conn->query($sql->getInstrucao());
				$status_operacao 	=  $resultado;
			}else{
				echo "Não há transação ativa: Registro Armazenar <br/>\n";
				$status_operacao =  false;
			}
		}catch(Throwable $t){
			if (IS_SHOW_ERROR_MESSAGE){
				echo $sql->getInstrucao() . "<br/>";
				echo $t->getMessage();
				Debug::console(array(
					$t->getMessage(),
					$sql->getInstrucao()
				),'Classe Registro - Método Armazenar');
			}
			$status_operacao =  false;
		}finally{
			return $status_operacao;
		}
	}
	/*  
		* Método carregar 
	    * Data de Criacao: 16/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Recupera (retorna) um objeto da base de dados através de seu ID e instancia ele na memória
	*/		
	public function carregar($id){
		try {
			$sql = tdClass::Criar("sqlselecionar");		
			$sql->setEntidade($this->getEntidade());
			$sql->addColuna("*");

			$criterio = tdClass::Criar("sqlcriterio");
			$criterio->Add(tdClass::Criar("sqlfiltro",array("id","=",$id)));
			$sql->setCriterio($criterio);
			if ($conn = Transacao::get()){
				Transacao::log($sql->getInstrucao());
				$resultado = $conn->query($sql->getInstrucao());
				if($resultado){
					$this->dados = $resultado->fetchObject($this->getEntidade());
					return $this->dados;
				}else{
					return false;
				}
			}else{
				throw new Exception("Não há transação ativa: Registro Carregar");
			}
		}catch(Throwable $t){
			return false;	
		}
	}

	/*  
		* Método deletar 
	    * Data de Criacao: 16/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Exclui um objeto da base de dados através de seu ID
	*/		
	public function deletar($id = null){

		$id = $id ? $id : $this->id;
		$sql = tdClass::Criar("sqldeletar");
		$sql->setEntidade($this->getEntidade());
		$criterio = tdClass::Criar("sqlcriterio");
		$criterio->add(tdClass::Criar("sqlfiltro",array("id","=",$id)));
		$sql->setCriterio($criterio);
		if ($conn = Transacao::get()){
			Transacao::log($sql->getInstrucao());
			$resultado = $conn->exec($sql->getInstrucao());
			return $resultado;
		}else{
			throw new Exception("Não há transação ativa: Registro Deletar");
		}

	}
	
	/*  
		* Método getUltimo 
	    * Data de Criacao: 16/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o último ID
	*/	
	public function getUltimo(){				
		if ($conn = Transacao::get()){
			$sql = tdClass::Criar("sqlselecionar");
			$sql->addColuna("MAX(id) as ID");
			$sql->setEntidade($this->getEntidade());
			Transacao::log($sql->getInstrucao());
			$resultado = $conn->query($sql->getInstrucao());
			$linha = $resultado->fetch();
			return $linha[0];
		}else{
			throw new Exception("Não há transação ativa: Registro getUltimo");
		}
	}
	/*  
		* Método getID() 
	    * Data de Criacao: 20/01/2016
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		@parms $entidade [ Nome da Entidade ]
		Retorna ID da Entidade
	*/
	public function getID(){
		if ($conn = Transacao::get()){
			$sql = tdClass::Criar("sqlselecionar");
			$sql->addColuna("id");
			$sql->setEntidade(ENTIDADE);
			$criterio = tdClass::Criar("sqlcriterio");
			$criterio->addFiltro("nome","=",$this->getEntidade());
			$sql->setCriterio($criterio);
			Transacao::log($sql->getInstrucao());
			$resultado = $conn->query($sql->getInstrucao());
			$linha = $resultado->fetch();
			return $linha[0];
		}else{
			throw new Exception("Não há transação ativa: Registro getID");
		}		
	}
	/*  
		* Método getOBJ() 
	    * Data de Criacao: 23/01/2016
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna OBJ da Entidade
	*/
	public function getOBJ($entidade = ""){
		if ($entidade == ""){
			$entidadeNome = $this->getEntidade();
		}else{
			$entidadeNome = $entidade;
		}
		$entID = tdClass::Criar("persistent",array($entidadeNome))->contexto->getID();
		return tdClass::Criar("persistent",array(ENTIDADE,$entID));
	}
	/*  
		* Método proxID()
	    * Data de Criacao: 26/06/2017
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o próximo ID de registro da Entidade
	*/
	public function proximoID(){
		return $this->getUltimo() + 1;
	}

	/*  
		* Método hasData()
	    * Data de Criacao: 04/10/2017
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna se os dados da entidade foram carregados
	*/
	public function hasData(){
		if ($this->dados == null || empty($this->dados) || $this->dados == ''){
			return false;
		}else{
			return true;
		}
	}
	
	/*  
		* Método getDataArray()
	    * Data de Criacao: 25/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna se os dados da entidade em forma de array
	*/
	public function getDataArray(){
		if ($this->hasData()){
			$sql = tdClass::Criar("sqlselecionar");		
			$sql->setEntidade($this->getEntidade());
			$sql->addColuna('*');
			$criterio = tdClass::Criar("sqlcriterio");
			$criterio->Add(tdClass::Criar("sqlfiltro",array("id","=",$this->id)));
			$sql->setCriterio($criterio);
			if ($conn = Transacao::get()){
				Transacao::log($sql->getInstrucao());
				$resultado 	= $conn->query($sql->getInstrucao());
				$resultados = array();
				if($resultado){
					if ($linha = $resultado->fetch(PDO::FETCH_ASSOC)){
						$resultados = addCampoFormatadoDB($linha,$this->getEntidade());
					}
				}
				return $resultados;
			}else{
				throw new Exception("Não há transação ativa: Registro getDataArray");
			}
		}
	}
	
	/*  
		* Método isUpdate()
	    * Data de Criacao: 16/02/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Set o registro como atualização
	*/
	public function isUpdate(){
		$this->isnew = false;
	}

	/*  
		* Método append
	    * Data de Criacao: 14/01/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona um registro caso não exista
	*/
	public function append($atributo,$operador,$valor){
		$dataset = tdc::d($this->getEntidade(),tdc::f($atributo,$operador,$valor));
		if (sizeof($dataset) <= 0 || $valor == ''){
			$entidade = tdc::p($this->getEntidade());		
			if ($valor == ''){
				$entidade->id = 0;
			}else{
				$entidade->{$atributo} = $valor;
				$entidade->armazenar();
			}
			return $entidade;
		}else{
			$dataset[0]->isUpdate();
			return $dataset[0];
		}
	}

	/*  
		* Método newNotExists
	    * Data de Criacao: 17/01/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o registro vazio caso não encontre a condição
	*/
	public function newNotExists($atributo,$operador,$valor){
		$dataset = tdc::d($this->getEntidade(),tdc::f($atributo,$operador,$valor));
		if (sizeof($dataset) <= 0 || $valor == ''){
			$entidade = tdc::p($this->getEntidade());
			return $entidade;
		}else{
			$dataset[0]->isUpdate();
			return $dataset[0];
		}
	}

	/*  
		* Método newNotExistsId
	    * Data de Criacao: 08/06/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o registro vazio caso não encontre o ID
	*/
	public function newNotExistsId($id){
		return $this->newNotExists('id','=',$id);
	}
	
	/*  
		* Método setIsNew
		* Data de Criacao: 21/02/2022
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Set se o registro é novo
	*/
	public function setIsNew($valor = true)
	{
		$this->isnew = $valor;
	}
	
	/*  
		* Método salvar
		* Data de Criacao: 08/06/2022
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona e/ou atualiza um registro
		Alias para método armazenar
	*/
	public function salvar()
	{
		if ($this->armazenar()){
			return true;
		}else{
			return false;
		}
		Transacao::Commit();
	}
	
	/*  
		* Método inativar
		* Data de Criacao: 08/06/2022
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Inativa um registro
	*/
	public function inativar()
	{	
		try{
			$this->inativo = true;
			$this->salvar();
			return true;
		}catch(Throwable $t){
			return false;
		}
	}
	/*  
		* Método isExists
		* Data de Criacao: 18/07/2022
		* @author Talles Machado Rodrigues (TallesMR)

		Verifica se um registro existe na base de dados
	*/
	public function isExists()
	{
		if ($this->carregar($this->id) == false) return false;
		else return true;
	}
}