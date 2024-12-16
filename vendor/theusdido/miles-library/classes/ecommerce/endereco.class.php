<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Classe Endereco
    * Data de Criacao: 03/04/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class Endereco {
	
	private $entidade = "td_ecommerce_endereco";
	/* 
		* Método addCidade 
	    * Data de Criacao: 03/04/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
		*	1 - $cidadeNome: Nome da cidade 
		*	2 - $uf: Código do Estado
		* RETORNO
		*	[ int ] - Código da Cidade

	*/
	public static function addCidade(string $cidadeNome,int $uf){
		$sql = "
			SELECT id FROM td_ecommerce_cidade 
			WHERE nome LIKE '%".$cidadeNome."%' 
			AND uf = {$uf}
			ORDER BY id DESC LIMIT 1;
		";
		$query = Transacao::Get()->query($sql);
		if ($query->rowCount() > 0){
			$linha = $query->fetch();
			return $linha["id"];
		}else{
			$cidade 		= tdc::p("td_ecommerce_cidade");
			$cidade->nome 	= $cidadeNome;
			$cidade->uf	= $uf;
			$cidade->armazenar();
			return $cidade->id;
		}
	}
	/* 
		* Método addBairro 
	    * Data de Criacao: 03/04/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
		*	1 - $bairroNome: Nome da cidade 
		*	2 - $cidade: Código da Cidade
		* RETORNO
		*	[ int ] - Código do Bairro

	*/
	public static function addBairro(string $bairroNome,int $cidade){
		$sql = "
			SELECT id FROM td_ecommerce_bairro 
			WHERE nome LIKE '%".$bairroNome."%' 
			AND cidade = {$cidade}
			ORDER BY id DESC LIMIT 1;
		";
		$query = Transacao::Get()->query($sql);
		if ($query->rowCount() > 0){
			$linha = $query->fetch();
			return $linha["id"];
		}else{
			$bairro 			= tdc::p("td_ecommerce_bairro");
			$bairro->nome 		= $bairroNome;
			$bairro->cidade	= $cidade;
			$bairro->armazenar();
			return $bairro->id;
		}
	}
	
	/* 
		* Método getEnderecoLista
	    * Data de Criacao: 28/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
		*	1 - $entidade: Nome da entidade
		*	2 - $registro: ID do registro
		* RETORNO
		*	[ Array<persistent> ] - Lista de com os endereços
	*/
	public static function getEnderecoLista
	(
		string $entidade,
		int $id
	){
		$entidadepai 	= getEntidadeId($entidade);
		$entidadefilho	= getEntidadeId($this->entidade);
		$regpai			= $id;

		return getListaRegFilhoObject($entidadepai,$entidadefilho,$regpai);
	}

	public function isExiste(){
		global $conn;
		$sql = "
			SELECT 1 FROM td_lista
			WHERE entidadepai = ".$this->entidadecliente."
			AND entidadefilho = ".$this->entidadeendereco."
			AND regpai = ".$this->cliente."
		;";
		$query = $conn->query($sql);
		if ($query->rowCount() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function getDados(){
		global $conn;
		$retorno = array();
		$sql = "
			SELECT
				a.logradouro,
				a.numero,
				a.complemento,
				a.cep,
				a.bairro bairroid,
				(SELECT d.nome FROM td_ecommerce_bairro d WHERE d.id = a.bairro LIMIT 1) bairrodesc,
				c.id cidadeid,
				c.nome cidadedesc,
				c.uf ufid,
				(SELECT nome FROM td_ecommerce_uf u WHERE c.uf = u.id LIMIT 1) ufdesc,
				(SELECT sigla FROM td_ecommerce_uf u WHERE c.uf = u.id LIMIT 1) ufsigla
			FROM td_ecommerce_endereco a 
			INNER JOIN td_ecommerce_cidade c ON a.cidade = c.id
			WHERE a.id = ".$this->getRegFilhoLista()."
			ORDER BY a.id DESC
			LIMIT 1;
		";
		$query = $conn->query($sql);
		if ($query->rowCount() > 0){
			if ($linha = $query->fetch()){
				$retorno = array(
					"logradouro" 	=> $linha["logradouro"],
					"numero"	 	=> $linha["numero"],
					"complemento" 	=> $linha["complemento"],
					"cep" 			=> $linha["cep"],
					"bairroid" 		=> $linha["bairroid"],
					"bairrodesc"	=> $linha["bairrodesc"],
					"cidadeid" 		=> $linha["cidadeid"],
					"cidadedesc"	=> $linha["cidadedesc"],
					"ufid"			=> $linha["ufid"],
					"ufdesc"		=> $linha["ufdesc"],
					"ufsigla"		=> $linha["ufsigla"]
				);
			}
		}
		return $retorno;
	}

	public function getRegFilhoLista(){
		try{
			global $conn;
			$sql = "
				SELECT regfilho FROM td_lista
				WHERE entidadepai = ".$this->entidadecliente."
				AND entidadefilho = ".$this->entidadeendereco."
				AND regpai = ".$this->cliente."
				LIMIT 1
			;";
			$query = $conn->query($sql);
			if ($query->rowCount() > 0){
				$linha = $query->fetch();
				return $linha["regfilho"];
			}else{
				return 0;
			}
		}catch(Exception $e){
			echo $e->getMessage();
			echo $sql;
		}
	}

	public function excluir(){
		/*
		global $conn;
		$conn->begintransaction();
		try{
			$sql = "
				SELECT regfilho FROM td_lista
				WHERE entidadepai = ".$this->entidadecliente."
				AND entidadefilho = ".$this->entidadeendereco."
				AND regpai = ".$this->cliente."
			;";
			$query = $this->conn->query($sql);
			if($query->rowCount() > 0){
				while ($linha = $query->fetch()){
					$this->conn->exec("DELETE FROM td_ecommerce_endereco WHERE id = " . $linha["regfilho"]);
				}
			}
			$sqlExcluirLista = "DELETE FROM td_lista WHERE entidadepai = 60 AND entidadefilho = 64 AND regpai = {$this->cliente};";
			$this->conn->exec($sqlExcluirLista);
			$this->conn->commit();
			$sucesso = true;
		}catch(Exception $e){
			$this->conn->rollback();
			$sucesso = false;
		}finally{
			return $sucesso;
		}
		*/
	}

	public function getLinha(){
		$_endereco_dados = $this->getDados();
		$linha_endereco = '';
		if (sizeof($_endereco_dados) > 0)
		{
			$logradouro 	= tdc::utf8($_endereco_dados["logradouro"]);
			$complemento	= tdc::utf8($_endereco_dados["complemento"]);
			$numero			= ($_endereco_dados["numero"]==""?"S/N":$_endereco_dados["numero"]);
			$cidade			= tdc::utf8($_endereco_dados["cidadedesc"]);
			$uf				= tdc::utf8($_endereco_dados["ufsigla"]);			
			$cep			= $_endereco_dados["cep"];
			$linha_endereco	= $logradouro . ', ' . $numero . '. '.$complemento . '. ' . $cidade . ' / '.$uf . ' | Cep.: ' . $cep;
		}
		return $linha_endereco;
	}
}