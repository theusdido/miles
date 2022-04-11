<?php
    class Transportadora 
    {
        // Entidade Id da tabela transportadora
        private $entidade_id    = 0;

        // Nome da Entidade
        private $entidade_name  = 'td_ecommerce_transportadora';

        // Método Construtor
        public function __construct()
        {
            $this->entidade_id = getEntidadeId($this->entidade_name);
        }

        // Retorna lista de Transportadoras
        public function all()
        {
            return tdc::dj($this->entidade_name,tdc::f()->onlyActive(),null,'decode');
        }

        // Retorna o logo da transportadora
        public function getLogo($transportora)
        {
            return URL_CURRENT_FILE . "/img/beedelivery/logo.png";
        }
    }