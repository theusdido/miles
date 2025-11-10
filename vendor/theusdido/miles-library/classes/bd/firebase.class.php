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
            $project_url    = 'https://'.$project_id.'-default-rtdb.firebaseio.com/' . _ENVIRONMENT;

            // Cria uma instância do Firebase
            $firebase = (new Factory)
            ->withServiceAccount(PATH_CURRENT_FIREBASE_JSON_CONFIG)
            ->withDatabaseUri($project_url);

            // Obtém uma referência ao banco de dados
            $this->database = $firebase->createDatabase();
        }

        public function ref($collection){
            return $this->database->getReference($collection);
        }

        public function insert($data, $collection = '/'){
            return $this->ref($collection)->push($data);
        }

        public function add($data, $collection = '/'){
            $ref_ = $this->set($data, $collection);
            //$this->addRelacionamento($collection, $ref_);
            return $ref_;
        }

        public function del($collection = '/'){
            return $this->ref($collection)->remove();
        }

        public function update($data, $collection = '/'){
            return $this->ref($collection)->update($data);
        }

        public function getJSONConfig(){
            return json_decode(file_get_contents(PATH_CURRENT_FIREBASE_JSON_CONFIG),true);
        }

        public function set($data, $collection = '/'){
            $ref_ = $this->ref($collection);
            $ref_->set($data);
            return $ref_;
        }        

        public function addRelacionamento($entidade, $ref = ''){

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

                // Atualiza a td_lista
                #$ref_lista = LISTA . '/' . $dados_['id'];
                #$this->database->getReference($ref_lista)->set($dados_);

                // Atualiza o relacionamento lista dentro da coleção
                $_ref_lista = str_replace(getSystemPREFIXO(),'',tdc::e($r_)->nome).'_lista';
                $full_ref_lista = str_replace('\/\/','',$entidade . '/' . $_ref_lista);
                $this->database->getReference($full_ref_lista)->set($dados_);
            }
        }

        public function getAll($collection){
            $ref = $this->ref($collection);
            $data = $ref->getValue();

            if (!is_array($data)) return [];

            // Remove valores nulos e reindexa o array
            $data = array_values(array_filter($data, fn($item) => !is_null($item)));
            return $data;
        }

        public function getPage($collection, $limit = 10, $startKey = null){
            $ref = $this->ref($collection);
            $query = $ref->orderByKey(); // Ordena pela chave (necessário para startAt)

            // 1. Aplica o limite (tamanho da página)
            $query = $query->limitToFirst($limit); 

            // 2. Se for fornecida uma chave inicial, a consulta começará APÓS esta chave.
            // Isso é crucial para buscar a "próxima" página.
            if ($startKey !== null) {
                // startAt() inclui o ponto de partida, então para buscar a próxima página,
                // é comum usar o último item da página anterior como ponto de partida.
                $query = $query->startAt($startKey);
                
                // Se você usar startAt($startKey), e a chave for do último item da pág. anterior,
                // você precisa buscar $limit + 1 itens e descartar o primeiro (a chave de início)
                // para obter a próxima página de $limit itens. 
                // Alternativamente, se for a primeira página, não passe startKey.
            }

            $data = $query->getValue(); 

            if (!is_array($data)) return [];

            // 3. (OPCIONAL) Se você usou startAt, remova o primeiro registro, pois ele
            // é o último registro da página anterior (o seu ponto de partida).
            if ($startKey !== null) {
                array_shift($data); // Remove o primeiro elemento (a chave $startKey)
            }
            
            // Remove valores nulos e reindexa o array
            $data = array_values(array_filter($data, fn($item) => !is_null($item)));
            
            return $data;
        }        
    }