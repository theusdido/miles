<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Classe Pedido
    * Data de Criacao: 02/06/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class Pedido {
    private $id;
    public $isVariacaoTamanho = true;
    public $isReferenciaProduto = true;
    private $itens = array();
    private $quantidadeTotalItens = 0;
    private $somaValor = 0;
    private $somaValorTotal = 0;
    private $pedido;
    private $valorfrete = 0;
    public function __construct(int $pedido){
        $this->id       = $pedido;
        $this->pedido   = tdc::p("td_ecommerce_pedido",$pedido);
        $this->valorfrete = $this->pedido->valorfrete;
        $this->itens();
    }
	/*
		* Método itens 
	    * Data de Criacao: 02/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* RETORNO
		*	[ array ] - Itens do pedido
	*/
    public function itens(){
        $ft = tdc::f("pedido","=",$this->id);
        $ft->setPropriedade("order","descricao");
        foreach(tdc::d(getEntidadeEcommercePedidoItem(),$ft) as $item){
            if ($this->isVariacaoTamanho){
                $tamanhoproduto = tdc::p("td_ecommerce_tamanhoproduto",$item->produto);
                $id             = $tamanhoproduto->id;
                $produto		= tdc::p("td_ecommerce_produto",$tamanhoproduto->produto);
                if ($this->isReferenciaProduto){
                    $referencia     = $produto->referencia != "" ? " - Ref.: " . $produto->referencia : '';
                }else{
                    $referencia     = '';
                }
                $descricao      = $produto->nome . $referencia;
                $tamanho        = $tamanhoproduto->descricao;
            }else{
                $produto		= tdc::p("td_ecommerce_produto",$item->produto);
                $id             = $produto->id;
                $descricao      = $produto->nome;
                $tamanho        = '';
            }
            $valorTotal                 = $item->qtde * $item->valor;
            $this->quantidadeTotalItens = $this->quantidadeTotalItens + $item->qtde;
            $this->somaValor            = $this->somaValor + $item->valor;
            $this->somaValorTotal       = $this->somaValorTotal + $valorTotal;            
            array_push($this->itens,array(
                "id"            => $id,
                "descricao"     => $descricao,
                "tamanho"       => $tamanho,
                "quantidade"    => $item->qtde,
                "valor"         => $item->valor,
				"produtonome"	=> $item->produtonome,
				"referencia"	=> $item->referencia,
				"tamanho"		=> $item->tamanho,
                "total"         => $valorTotal
            ));
        }
    }
	/* 
		* Método quantidadeItens 
	    * Data de Criacao: 02/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* RETORNO
		*	[ int ] - Quantidade de itens do pedido
	*/
    public function quantidadeItens(){
        return $this->quantidadeTotalItens;
    }
	/* 
		* Método somaValorItens
	    * Data de Criacao: 02/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* RETORNO
		*	[ float ] - Soma dos valores dos itens do pedido
	*/
    public function somaValorItens(){
        return $this->somaValor;
    }
	/* 
		* Método valorTotal
	    * Data de Criacao: 02/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* RETORNO
		*	[ float ] - Soma dos valores totais do pedido
	*/
    public function somaValorTotal(){
        return $this->somaValorTotal;
    }    
	/* 
		* Método getValorTotal
	    * Data de Criacao: 02/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* RETORNO
		*	[ float ] - Valor total do pedido
	*/
    public function getValorTotal(){
        return $this->somaValorTotal() - $this->getValorFrete();
    }
	/* 
		* Método getValorFrete
	    * Data de Criacao: 02/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* RETORNO
		*	[ float ] - Valor do frete
	*/
    public function getValorFrete(){
        return $this->valorfrete;
    }
	/* 
		* Método getItens
	    * Data de Criacao: 02/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* RETORNO
		*	[ array ] - Retorna os itens do pedido
	*/
    public function getItens(){
        return $this->itens;
    }
}