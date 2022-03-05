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
            $produto = $linha["td_produto"];
            $variacao = $linha["id"];
            $conn->exec("INSERT INTO td_erp_material_posicaogeralestoque VALUES (default,0,{$produto},60,NOW(),{$variacao});");
        }
    }
    
}