<?php
/*
    * Framework MILES
    * @license: Teia Online.
    * @link http://www.teia.online

    * Classe Field
    * Data de Criacao: 05/03/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

*/	
class Field {
    private $id                     = 0;
    public $conn;
    public $nome;
    public $descricao               = "";
    public $ncolunas                =1;
    public $exibirmenuadministracao = 0;
    public $exibircabecalho         = 1;
    public $campodescchave          = "";
    public $atributogeneralizacao   = 0;
    public $exibirlegenda           = 1;
    public $criarprojeto            = 0;
    public $criarempresa            = 0;
    public $criarauth               = 0;
    public $registrounico           = 0;
    public $carregarlibjavascript   = 1;
    public $criarinativo            = true;

	/* 
		* Método __construct
		* Data de Criacao: 25/01/2024
		* Autor @theusdido
	*/
	public function __construct($nome,$descricao){
		
		$this->nome 				= $nome;
		$this->descricao 			= $descricao;
		global $conn;
		$this->conn 				=  $conn;

	}    


	/*
		* Método getJSON
	    * Data de Criacao: 25/01/2024
	    * Autor @theusdido

		Retorna o atributo no formato JSON
		@params: ID do Atributo
		@return: string
	*/
	public static function getJSON($_atributo_id){
		$_atributo 		= tdc::pa(ATRIBUTO,$_atributo_id);

		return json_encode(array(
            'id'                                => $_atributo['id'],
            'entidade'                          => $_atributo['entidade'],
            'nome'                              => $_atributo['nome'],
            'descricao'                         => $_atributo['descricao'],
            'tipo'                              => $_atributo['tipo'],
            'tamanho'                           => $_atributo['tamanho'],
            'omissao'                           => $_atributo['omissao'],
            'collection'                        => $_atributo['collection'],
            'atributos'                         => $_atributo['atributos'],
            'nulo'                              => $_atributo['nulo'],
            'indice'                            => $_atributo['indice'],
            'autoincrement'                     => $_atributo['autoincrement'],
            'comentario'                        => $_atributo['comentario'],
            'exibirgradededados'                => $_atributo['exibirgradededados'],
            'chaveestrangeira'                  => $_atributo['chaveestrangeira'],
            'tipohtml'                          => $_atributo['tipohtml'],
            'dataretroativa'                    => $_atributo['dataretroativa'],
            'ordem'                             => $_atributo['ordem'],
            'readonly'                          => $_atributo['readonly'],
            'inicializacao'                     => $_atributo['inicializacao'],
            'exibirpesquisa'                    => $_atributo['exibirpesquisa'],
            'tipoinicializacao'                 => $_atributo['tipoinicializacao'],
            'atributodependencia'               => $_atributo['atributodependencia'],
            'labelzerocheckbox'                 => $_atributo['labelzerocheckbox'],
            'labelumcheckbox'                   => $_atributo['labelumcheckbox'],
            'criarsomatoriogradededados'        => $_atributo['criarsomatoriogradededados'],
            'naoexibircampo'                    => $_atributo['naoexibircampo'],
            'is_unique_key'                     => $_atributo['is_unique_key']
		));
	}	    
}