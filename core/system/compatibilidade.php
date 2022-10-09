<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Funções para garantir a compatibilidade entre versões
    * Data de Criacao: 18/05/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
function getEntidadeEcommercePedidoItem(){
	
	switch(PROJECT_ID){
		case 22: # Bixoferpa
			$entidade = 'td_ecommerce_itenspedido';
		break;
		default:
			$entidade = 'td_ecommerce_pedidoitem';
	}
	return $entidade;
}