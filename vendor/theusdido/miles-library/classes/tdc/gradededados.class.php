<?php
include_once PATH_WIDGETS . 'tabela.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe GradedeDados
    * Data de Criacao: 03/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/
class GradedeDados extends Tabela{
	public $entidade;
	protected $colunas;
	protected $colspan = 5;
	public $qtde_registros;
	public $dataset; 
	public $exibireditar = true;
	public $exibirexcluir = true;
	public $exibirselecionar = false;
	public $retornaregistro = false;
	public $funcaoretornaregistro;
	public $retornoCarregar;
	public $filtro_rel_nn=""; #colacar o filtro de relacionamento quando for N:N em agregação
	public $filtro="";
	public $exibircorpo = true; # Corpo com os dados
	public $exibircabecalho = true;
	public $exibirrodape = true;
	public $exibiroutrajanela = false;
	public $contexto;
	private $qtde_caracteres_colunas = 25;
	public $exibirId = true;
	/*  
		* Método construct 
		* Data de Criacao: 03/01/2015
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria uma tabela em HTML baseado na tabela do banco de dados
		@parms $Tabela 
	*/					
	function __construct(){
		parent::__construct();
		$this->class = "table table-hover gradededados";		
	}
	/*  
		* Método thead 
		* Data de Criacao: 03/01/2015
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Criar o cabelhaço da grade de dados
	*/						
	public function thead(){		
		$thead = tdClass::Criar("thead");
		$tr = tdClass::Criar("tabelalinha");
		
		if ($this->exibirId){
			$th = tdClass::Criar("tabelahead");
			$span =tdClass::Criar("span");
			$span->add("ID");		
			$span->class = "text-center";
			$th->width = "10%";
			$th->add($span);
			$tr->add($th);
		}
		
		if (sizeof($this->colunas) > 0){
			$width = (int)(70/sizeof($this->colunas));
		}else{
			$width = 0;
		}	
		
		// Monta as colunas de acordo com os parametros que vem do banco de dados
		foreach($this->colunas as $coluna){
			$c = tdClass::Criar("tabelahead");
			//$c->add(($coluna->descricao));
			if ($coluna->nome == "codigo"){
				$c->width = "5%";
			}else{
				$c->width = $width . "%";	
			}			
			$tr->add($c);
		}
		
		if ($this->exibiroutrajanela){
			$outrajanela = tdClass::Criar("tabelahead");
			$outrajanela->add("Janela");
			$outrajanela->width = "5%";
			$outrajanela->class = "text-center";
			$tr->add($outrajanela);
		}	
		
		if ($this->exibireditar){
			$editar = tdClass::Criar("tabelahead");
			$editar->add("Editar");
			$editar->width = "5%";
			$editar->class = "text-center";
			$tr->add($editar);
		}	
		if ($this->exibirexcluir){
			$excluir = tdClass::Criar("tabelahead");
			$excluir->add("Excluir");
			$excluir->width = "5%";
			$excluir->class = "text-center";
			$tr->add($excluir);
		}	
		
		// [inicio] - Icone para selecionar todos os registros
		$button = tdClass::Criar("button");
		$button->class = "btn btn-link gd-sel-todos";
		$button->type="button";
		$button->aria_label = "Selecionar Todos";
		$button->data_sel = "true";
		
		$icone = tdClass::Criar("span");
		$icone->class = "fas fa-check-square";
		$icone->aria_hidden = "true";
		
		$button->add($icone);
		// [fim]	- Icone para selecionar todos os registros
		
		if ($this->exibirselecionar){
			$th = tdClass::Criar("tabelahead");
			$span = tdClass::Criar("span");
			$span->add($button);				
			$th->width = "5%";
			$th->style = "text-align:center !important;";
			$th->add($span);
			$tr->add($th);
		}	
		$thead->add($tr);
		$this->add($thead);
	}	
	/*  
		* Método tbody 
		* Data de Criacao: 03/01/2015
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria o corpo da grade de dados
	*/						
	public function tbody(){
		$tbody = tdClass::Criar("tbody");
		$this->add($tbody);
	}
	/*  
		* Método tfooter 
		* Data de Criacao: 20/02/2015
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria o rodapé da grade de dados		
	*/
	public function tfoot(){
		$tfooter = tdClass::Criar("tfoot");
		$this->add($tfooter);
	}
	/*
		* Método mostrar 
		* Data de Criacao: 03/01/2015
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Exibi a grade de dados
		Sobreescreve o método pai
	*/
	function mostrar(){
		$this->colunas();
		if ($this->exibircabecalho) $this->thead();
		if ($this->exibircorpo) $this->tbody();
		if ($this->exibirrodape) $this->tfoot();		
		
		parent::mostrar();
	}
	/*
		* Método colunas
		* Data de Criacao: 03/01/2015
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna as colunas da grade em forma de array
		@return $colunas
	*/
	protected function colunas(){
		$sql = tdClass::Criar("sqlcriterio");
		$sql->add(tdClass::Criar("sqlfiltro",array("exibirgradededados","=",1)));
		$sql->add(tdClass::Criar("sqlfiltro",array(ENTIDADE,'=',$this->entidade->contexto->id)));
		
		$dataset = tdClass::Criar("repositorio",array(ATRIBUTO));
		$this->colunas = $dataset->carregar($sql);
	}
}