<?php
class Transportadora {

    // Retorna lista de Transportadoras
    public static function getTransportadoras(){
        $sql = "SELECT * FROM td_ecommerce_transportadora WHERE inativo <> 1;";
        $query = Conexao::get()->query($sql);
        return $query->fetchAll();
    }
    public static function getLogo($transportora){
        #return $_SESSION["PATH_TDECOMMERCE"] . "/img/icon-entrega-motoboy.png";
        return $_SESSION["PATH_TDECOMMERCE"] . "/img/beedelivery/logo.png";
    }
}
