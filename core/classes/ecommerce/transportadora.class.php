<?php
class Transportadora {

    // Retorna lista de Transportadoras
    public static function getTransportadoras(){
        $sql = "SELECT * FROM td_ecommerce_transportadora WHERE inativo <> 1;";
        $query = Transacao::get()->query($sql);
        return $query->fetchAll();
    }

    public static function getLogo($transportora){
        return URL_TDECOMMERCE . "/img/beedelivery/logo.png";
    }
}
