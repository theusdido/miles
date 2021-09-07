<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link https://www.teia.tec.br

    * Classe Entidade
    * Data de Criacao: 19/06/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class Entidade {
	private $nome;
	private $descricao = "";
	private $ncolunas=1;
	private $exibirmenuadministracao = 0;
	private $exibircabecalho = 1;
	private $campodescchave = "";
	private $atributogeneralizacao = 0;
	private $exibirlegenda = 1;
	private $criarprojeto = 0;
	private $criarempresa = 0;
	private $criarauth = 0;
	private $registrounico = 0;
	private $carregarlibjavascript = 1;
	private $criarinativo = true;
	
	/*
		* Método isExists
	    * Data de Criacao: 19/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Verifica se a entidade existe no banco de dados e na tabela td_entidade
		@params: $entidade ( String | Integer )
	*/
	public static function isExists($entidade){
		switch(gettype($entidade)){
			case 'string':
				$where = "nome = '{$entidade}'";
			break;
			default:
				$where = "id = {$entidade}";
		}
		global $conn;
		$sqlExisteEntidade = "SELECT id,nome FROM " . getSystemPREFIXO() . "entidade WHERE {$where}";
		$queryExisteEntidade = $conn->query($sqlExisteEntidade);
		if (!$queryExisteEntidade){
			echo $sqlExisteEntidade;
			var_dump($conn->errorInfo());
		}
		if ($queryExisteEntidade->rowCount() <= 0) return false;
		$linhaExisteEntidade 	= $queryExisteEntidade->fetch();		
		$nomeEntidade 			= $linhaExisteEntidade['nome'];
		$sqlExisteFisicamente 	= "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE UPPER(TABLE_NAME) = UPPER('".$nomeEntidade."') AND UPPER(TABLE_SCHEMA) = UPPER('".SCHEMA."')";
		$queryExisteFisicamente = $conn->query($sqlExisteFisicamente);		
		if ($queryExisteFisicamente->rowCount() <= 0) return false;

		return true;
	}
}