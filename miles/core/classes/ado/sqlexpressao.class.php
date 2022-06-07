<?php
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe abstrata para gerenciar express�es do SQL
    * Data de Criacao: 28/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	

abstract class SqlExpressao{
	# OPeradores L�gicos
	const E_OPERADOR = "AND ";
	const OU_OPERADOR = "OR ";
	# Retorno da express�o
	abstract public function dump();
}