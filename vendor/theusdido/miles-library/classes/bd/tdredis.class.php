<?php
    /*
        * Framework MILES
        * @license : Teia Tecnologia WEB.
        * @link http://teia.tec.br

        * Classe que implementa conexão com o banco de dados Redis
        * Data de Criacao: 20/07/2025
        * Author: @theusdido
    */

    class tdRedis {
        private $schema = '';
        private $host = '127.0.0.1';
        private $port = 6379;
        private $password = '';
        private $instance = null;

        /*  
            * Método conectar 
            * Data de Criacao: 20/07/2025
            * Author: @theusdido
            
            Conecta uma instância do Redis
        */
        public function __construct($schema){
            $redis = new Redis();
            $redis->connect($this->host, $this->port);
            $this->schema = $schema;
            $this->instance = $redis;
        }

        /*
            * Método setAll 
            * Data de Criacao: 20/07/2025
            * Author: @theusdido
            
            Seta todos os registros de uma entidade no Redis
        */
        public function setAll($entidade){
            try{
                $this->instance->set($this->schema . ":" . $entidade . ":all", json_encode(tdc::da($entidade)));
            }catch(Exception $e){
                echo $e->getMessage();
            }
        }

        /*  
            * Método getAll 
            * Data de Criacao: 20/07/2025
            * Author: @theusdido
            
            Retorna todos os registros de uma entidade no Redis
        */
        public function getAll($entidade){
            try{
                return json_decode($this->instance->get($this->schema . ":" . $entidade . ":all"));
            }catch(Exception $e){
                echo $e->getMessage();
                return [];
            }
        }
    }