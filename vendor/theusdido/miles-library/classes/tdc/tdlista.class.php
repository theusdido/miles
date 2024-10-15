<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link http://teia.tec.br

    * Classe tdLista
    * Data de Criacao: 26/04/2024
    * Author: @theusdido
*/
class tdLista {

    /* 
		* Método add
		* Data de Criacao: 26/04/2024
		* Author: @theusdido

       Adiciona um registro na tabela td_lista
	*/
	public static function add($entidade_pai,$entidade_filho,$registro_pai,$registro_filho){
		$_lista 					= tdc::p('td_lista');
		$_lista->entidadepai 		= $entidade_pai;
		$_lista->entidadefilho		= $entidade_filho;
		$_lista->regpai				= $registro_pai;
		$_lista->regfilho			= $registro_filho;
		$_lista->armazenar();
	}
}