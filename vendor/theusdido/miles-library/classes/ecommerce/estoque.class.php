<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link http://www.teia.tec.br

    * Classe que implementa o controle de estoque
    * Data de Criacao: 19/01/2022
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/

class Estoque 
{
    
    function iniciarEstoque(){
        global $conn;
        $sql = "SELECT * FROM td_ecommerce_tamanhoproduto;";
        $query = $conn->query($sql);
        while ($linha = $query->fetch()){
            $produto = $linha["produto"];
            $variacao = $linha["id"];
            $conn->exec("INSERT INTO td_ecommerce_posicaogeralestoque VALUES (default,0,{$produto},60,NOW(),{$variacao});");
        }
    }

    public static function produtosEsgotados()
    {
        return tdc::d('td_ecommerce_posicaogeralestoque',tdc::f('saldo','<=',0));
    }

    public static function qtdeProdutosEsgotados()
    {   
        return sizeof(self::produtosEsgotados());
    }

	public static function Baixar($produto_id,$quantidade = 1,$transacao = 1)
    {
        global $conn;
        $tipo_operacao = 2;
        // Selecione a Operação de Estoque
        $sqlTipoOperacaoEstoque         = "SELECT ifnull(operacaoestoque,{$tipo_operacao}) operacao FROM td_ecommerce_statuspedido WHERE id = " . $transacao . ";";
        $queryTipoOperacaoEstoque       = $conn->query($sqlTipoOperacaoEstoque);
        if ($queryTipoOperacaoEstoque->rowCount() > 0){
            $linhaTipoOperacaoEstoque 	= $queryTipoOperacaoEstoque->fetch();
            $tipo_operacao 		        = $linhaTipoOperacaoEstoque["operacao"];
        }

        // if ($configucao_ecommerce->usarvariacaoproduto){
        //     $_variacao_produto_id 	= $produto_id;
        //     $_produto_id 			= 0;
        // }else{
        //     $_variacao_produto_id 	= 0;
        //     $_produto_id 			= $produto_id;
        // }

        $variacao_produto_id 	= 0;
        $data = array(
            "controller" 		=> "ecommerce/posicaogeralestoque",
            "key" 				=> "k",
            "quantidade" 		=> $quantidade,
            "operacao" 			=> $tipo_operacao,
            "variacaoproduto"	=> $variacao_produto_id,
            "produto"			=> $produto_id
        );
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => URL_API,
            CURLOPT_POSTFIELDS => $data
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
    }

    public static function BaixarLote($lote,$operacao = 2){
        foreach($lote as $item){
            Estoque::Baixar($item->produto,$item->quantidade,$operacao);
        }
    }

}