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

            $config         = $this->getJSONConfig();
            $project_id     = $config['project_id'];
            $project_url    = 'https://'.$project_id.'-default-rtdb.firebaseio.com/';            

            // Cria uma instância do Firebase
            $firebase = (new Factory)
            ->withServiceAccount(PATH_CURRENT_FIREBASE_JSON_CONFIG)
            ->withDatabaseUri($project_url);

            // Obtém uma referência ao banco de dados
            $this->database = $firebase->createDatabase();

            return $this->database;
        }

        public function insert($data, $collection = '/'){
            return $this->database->getReference($collection)->push($data);
        }

        public function add($data, $collection = '/'){
            $ref_ = $this->database->getReference($collection);
            $ref_->set($data);
            $this->addRelacionamento($collection,$ref_);
            return $ref_;
        }

        public function del($collection = '/'){
            return $this->database->getReference($collection)->remove();
        }

        public function update($data, $collection = '/'){
            return $this->database->getReference($collection)->update($data);
        }

        public function getJSONConfig(){
            return json_decode(file_get_contents(PATH_CURRENT_FIREBASE_JSON_CONFIG),true);
        }

        private function addRelacionamento($entidade,$ref){

            // Dados da Entidade
            $ent            = explode('/',$entidade);
            $entidade_id    = getEntidadeId($ent[0]);
            $id_            = $ent[1];

            // Entidades de Relacionamento
            $criterio = tdc::f();
            $criterio->addFiltro('pai','=',$entidade_id);
            $criterio->addFiltro('tipo','in',[2, 6, 8, 11]);

            // Mapea os relacionamentos
            $relacionamentos        = tdc::da(RELACIONAMENTO, $criterio);
            $relacionamentos_id     = array();
            foreach($relacionamentos as $rel){
                array_push($relacionamentos_id, $rel['filho']);
            }
            
            // Percorre os relacionamentos
            foreach($relacionamentos_id as $r_){

                // Percorre a entidade td_lista
                $dados_ = getListaRegFilhoArray(
                    $entidade_id,
                    $r_,
                    $id_
                );
                $_ref_lista = str_replace(getSystemPREFIXO(),'',tdc::e($r_)->nome).'_lista';
                $this->database->getReference($entidade . $_ref_lista)->set($dados_);
            }
        }
    }