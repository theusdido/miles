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
	
}