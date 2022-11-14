<?php
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe abstrata para gerenciar expressões do SQL
    * Data de Criacao: 28/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	

abstract class SqlExpressao{
	# OPeradores Lógicos
	const E_OPERADOR = "AND ";
	const OU_OPERADOR = "OR ";
	# Retorno da expressão
	abstract public function dump();
}