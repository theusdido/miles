<?php	    
    require 'vendor/kreait/firebase-php/src/Firebase/Factory.php';
    require 'vendor/kreait/firebase-php/src/Firebase/Database.php';

    use Kreait\Firebase\Factory;
    use Kreait\Firebase\Database;

    /*
        * Framework MILES
        * @license : Teia Tecnologia WEB.
        * @link http://teia.tec.br

        * Classe que implementa conexão com o banco de dados Firebase ( Google )
        * Data de Criacao: 04/08/2024
        * Author: @theusdido
    */
    class Firebase {
        private $database;
        /*  
            * Método construtor 
            * Data de Criacao: 04/06/2012
            * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
            
            Será marcado como private pois não existirão instancias dessa classe
        */
        public function __construct(){

            // Cria uma instância do Firebase
            $firebase = (new Factory) 
            ->withServiceAccount('firebase.json')
            ->withDatabaseUri('https://innovareadministradora-default-rtdb.firebaseio.com/');

            // Obtém uma referência ao banco de dados
            $this->database = $firebase->createDatabase();
        }

        public function insert($data, $collection = '/'){
            return $this->database->getReference($collection)->push($data);
        }

        public function add($data, $collection = '/'){
            return $this->database->getReference($collection)->set($data);
        }
    }