<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Classe Configuracoes
    * Data de Criacao: 19/01/2022
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/

class EcommerceConfiguracoes
{
    public $is_variacao_produto			    = false;
    public $is_paginacao_produto_home	    = false;
    public $valor_total_minimo_pedido       = 0;
    public $qtdade_produto_home			    = 12;
    public $aviso_pagamento				    = '';

	/* 
		* Método __construct
	    * Data de Criacao: 12/01/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
        *   :none
		* RETORNO
		*	:void
	*/
    public function __construct()
    {
        $config = tdc::p('td_ecommerce_configuracoes',1);

		$this->setIsVariacaoProduto($config->usarvariacaoproduto);
        $this->setIsPaginacaoProdutoHome($config->ispaginacaoprodutohome);
		$this->setValorTotalMinimoPedido($config->valorminimopedido);
        $this->setQtdadeProdutoHome($config->qtdadeprodutohome);
        $this->setAvisoPagamento($config->avisopagamento);
    }

    private function setIsVariacaoProduto($is_variacao)
    {
        if (gettype($is_variacao) === 'boolean'){
            $this->is_variacao_produto = $is_variacao;
        }   
    }

    private function setIsPaginacaoProdutoHome($is_paginacao)
    {
        if (gettype($is_paginacao) === 'boolean'){
            $this->is_paginacao_produto_home = $is_paginacao;
        }
    }

    private function setValorTotalMinimoPedido($valor)
    {
        if (is_numeric($valor) && $valor > 0){
            $this->valor_total_minimo_pedido = $valor;
        }
    }
    
    private function setQtdadeProdutoHome($valor)
    {
        if (is_numeric($valor) && $valor > 0){
            $this->qtdade_produto_home = $valor;
        }
    }
    
    private function setAvisoPagamento($mensagem)
    {
        if ($mensagem != ''){
            $this->aviso_pagamento = $mensagem;
        }
    }
}