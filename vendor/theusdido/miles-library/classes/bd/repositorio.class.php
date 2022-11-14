<?php	
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Repositório
    * Data de Criacao: 29/08/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
final class Repositorio {
	private $classe;
	private $registros = array();
	/*  
		* Método construct 
	    * Data de Criacao: 29/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Instancia um repositório de objetos
		@params classe
	*/		
	public function __construct($classe){		
		$this->classe = $classe;
		tdClass::Criar("persistent",array($classe));				
	}	
	
	/*  
		* Método carregar 
	    * Data de Criacao: 29/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Recupera um conjunto de objetos(coleção) da base de dados através de uma critério de seleção, e instanciá-los em memória
		@params criterio
	*/		
	public function carregar(){
		if (func_num_args() <= 0 || func_get_arg(0) == null){		
			$criterio = tdClass::Criar("sqlcriterio");
		}else{
			$criterio = func_get_arg(0);
			if (gettype($criterio) != "object" && get_class($criterio) != "SqlCriterio"){
				echo "Argumento inválido";
				return false;
			}
		}
		$resultados	= array();
		$sql 		= tdClass::Criar("sqlselecionar");
		$sql->setEntidade($this->classe);
		$sql->addColuna("*");
		$sql->setCriterio($criterio);
		if ($conn = Transacao::get()){
			Transacao::log($sql->getInstrucao());
			try{
				$resultado = $conn->query($sql->getInstrucao());
				if($resultado){
					while ($linha = $resultado->fetchObject($this->classe)){
						$linha->setIsNew(false); // Os registros são marcados para serem atualizados 
						$resultados[] = $linha;
					}
				}
			}catch(Exception $e){
			}
			$this->registros = $resultados;
			return $resultados;
		}else{		
			throw new Exception("Não há transação ativa: Repositorio Carregar");
		}
	}

	/*  
		* Método deletar 
	    * Data de Criacao: 29/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Excluir um conjunto de objetos (coleção) da bases de dados através de um critério de seleção
		@parms: Criterio
	*/		
	public function deletar(SqlCriterio $criterio){	
		$sql = tdClass::Criar("sqldeletar");
		$sql->setEntidade($this->classe);
		$sql->setCriterio($criterio);
		if ($conn = Transacao::get()){
			Transacao::log($sql->getInstrucao());
			$resultado = $conn->exec($sql->getInstrucao());
			return $resultado;
		}else{
			throw new Exception("Não há transação ativa: Repositorio Deletar");
		}
	}
	
	/*  
		* Método Quantia 
	    * Data de Criacao: 29/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna a quantidade de objetos da base de dados que satisfazem um determinado crit�rio de sele��o.
		@parms: Criterio
	*/	
	function quantia(SqlCriterio $criterio){	
		$sql = tdClass::Criar("sqlselecionar");
		$sql->setEntidade($this->classe);
		$sql->addColuna("count(*)");		
		$sql->setCriterio($criterio);
		if ($conn = Transacao::get()){
			Transacao::log($sql->getInstrucao());
			try{
				$resultado = $conn->query($sql->getInstrucao());
				if($resultado){
					$linha = $resultado->fetch();
				}
				$quantia = $linha[0];
			}catch(Exception $e){
				$quantia = 0;
			}
			return $quantia;
		}else{
			throw new Exception("Não há transação ativa");
		}
	}
	
	/*  
		* Método getDataArray()
	    * Data de Criacao: 26/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna se os dados em forma de array
	*/
	public function getDataArray(){	
			if (func_num_args() <= 0 || func_get_arg(0) == null){		
			$criterio = tdClass::Criar("sqlcriterio");
		}else{
			$criterio = func_get_arg(0);
			if (gettype($criterio) != "object" && get_class($criterio) != "SqlCriterio"){
				echo "Argumento inválido";
				return false;
			}
		}

		$resultados	= array();
		$sql 		= tdClass::Criar("sqlselecionar");
		$sql->setEntidade($this->classe);
		$sql->addColuna("*");
		$sql->setCriterio($criterio);
		if ($conn = Transacao::get()){
			Transacao::log($sql->getInstrucao());
			$resultado = $conn->query($sql->getInstrucao());
			if($resultado){
				while ($linha = $resultado->fetch(PDO::FETCH_ASSOC)){
					$resultados[] = addCampoFormatadoDB($linha,$this->classe);
				}
			}
			return $resultados;
		}else{			
			throw new Exception("Não há transação ativa");
		}
	}	
	/*  
		* Método hasData()
	    * Data de Criacao: 14/01/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna se existe dados no repositório
	*/
	public function hasData(){
		if (sizeof($this->registros) <= 0){
			return false;
		}else{
			return true;
		}
	}	
}	