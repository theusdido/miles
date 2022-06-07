<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Bloco
    * Data de Criacao: 22/02/2016
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Pesquisa Extends Elemento {
	public $entidade;	
	/*  
		* Método construct 
	    * Data de Criacao: 22/02/2016
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria o componente Pesquisa
	*/		
	public function __construct(){
		parent::__construct('div');
		$this->class = "form-group";		
	}	

	public function mostrar(){		
		
		$label = tdClass::Criar("label");
		$label->add("Pesquisar");
		
		$input_group = tdClass::Criar("div");
		$input_group->class = "input-group";
		
		$input_text = tdClass::Criar("input");
		$input_text->type = "text";
		$input_text->class = "form-control";
		$input_text->aria_label = "Digite um termo para pesquisar";
		$input_text->id = $this->entidade->contexto->nome . "_termo_pesquisa_gd";
		$input_text->name = "termo_pesquisa_gd";
		$input_text->placeholder = "Digite um termo para pesquisar";
		
		$input_group_btn = tdClass::Criar("div");
		$input_group_btn->class = "input-group-btn";		
		$button = tdClass::Criar("button");
		$button->class = "btn btn-default dropdown-toggle";
		$button->data_toggle = "dropdown";
		$button->aria_expanded = "false";
		$span = tdClass::Criar("span");
		$span->class = "caret";
		$span_attr_name = tdClass::Criar("span");
		$span_attr_name->id = $this->entidade->contexto->nome . "_span_atributo_pesquisa_gd";
		$span_attr_name->add("ID");
		$button->add($span_attr_name,$span);
		
		$ul = tdClass::Criar("ul");
		$ul->id = $this->entidade->contexto->nome . "_ul_atributo_pesquisa_gd";
		$ul->class = "dropdown-menu dropdown-menu-right";
		$ul->role = "menu";
		
		$sql_atributos = tdClass::Criar("sqlcriterio");
		$sql_atributos->addFiltro(ENTIDADE,"=",$this->entidade->contexto->id);
		$sql_atributos->addFiltro("exibirgradededados","=",1);
		$dataset_atributos = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql_atributos);
		
		// Adiciona o ID
		$li = tdClass::Criar("li");
		$a = tdClass::Criar("hyperlink");		
		$a->href = "#";
		$a->data_atributoname = "id";
		$a->data_tipo = "int";
		$a->add("ID");
		$li->add($a);		
		$ul->add($li);		
		foreach ($dataset_atributos as $attr){
			$li = tdClass::Criar("li");
			$a = tdClass::Criar("hyperlink");		
			$a->href = "#";
			$a->data_atributoname = "{$attr->nome}";
			$a->data_tipo = "{$attr->tipo}";
			$a->add($attr->descricao);
			$li->add($a);		
			$ul->add($li);
		}	
		
		//carregar("'.tdClass::Criar("persistent",array(CONFIG,1))->contexto->url_requicoes.'&op=paginacao&bloco=1&max_bloco='.$this->qbloco.'&entidade=" + entidade + "&total_registros=" + '.$this->qtde_registros.' + filtro_externo + "'.($this->filtro_rel_nn!=""?"&filtro_rel_nn=".trim($this->filtro_rel_nn):'').'" + filtro + "'.($this->retornaregistro!=""?"&retornaregistro=".$this->retornaregistro:"").'&contexto='.$this->contexto.'","'.($this->contexto==""?"":"#".$this->contexto).' .paginacao-bloco");					
		//carregar("'.tdClass::Criar("persistent",array(CONFIG,1))->contexto->url_requicoes.'&op=paginacao&bloco="+this.value+"&max_bloco='.$this->qbloco.'&entidade=" + entidade + "&total_registros=" + qtde_registros + filtro_externo + "'.($this->filtro_rel_nn!=""?"&filtro_rel_nn=".trim($this->filtro_rel_nn):'').'&contexto='.$this->contexto.($this->retornaregistro!=""?"&retornaregistro=".$this->retornaregistro:"").'","'.($this->contexto==""?"":"#".$this->contexto).' .paginacao-bloco");
		
		$js = tdClass::Criar("script");
		$js->add('
			var atributo_pesquisa = "id";
			var atributo_tipo = "int";
			$("#'.$this->entidade->contexto->nome.'_ul_atributo_pesquisa_gd li a").click(function(){
				atributo_pesquisa = $(this).data("atributoname");
				atributo_tipo = $(this).data("tipo");
				$("#'.$this->entidade->contexto->nome.'_span_atributo_pesquisa_gd").html($(this).html());
			});
			
			$("#'.$this->entidade->contexto->nome.'_termo_pesquisa_gd").keypress(function(e){
				 if ( e.which == 13 ) {
					 if (this.value != ""){
						var filtro = "&filtro_pesquisa=" + atributo_pesquisa + "^" + replaceAll(this.value,"","_") + "&atributo_tipo=" + atributo_tipo;
					 }else{
						var filtro = "";
					 }					
					
				 }
			});
			$(".irbloco-paginacao").keypress(function(e){
				if ( e.which == 13 ) {
					if (isNumeric(this.value)){
						if (parseInt(this.value) > 0){
							
						}
					}
				}
			});
		');
		$input_group_btn->add($button,$ul);
		$input_group->add($input_text,$input_group_btn);
		$this->add($label,$input_group);		
		parent::mostrar();
	}
}