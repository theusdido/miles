<?php
    class Transportadora {

        // Retorna lista de Transportadoras
        public static function all(){
            $ft = tdc::f();
            $ft->onlyActive();
            return tdc::da('td_ecommerce_transportadora',$ft);
        }

        public static function getLogo($transportora){
            return URL_TDECOMMERCE . "/img/beedelivery/logo.png";
        }
    }