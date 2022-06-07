<?php
include_once PATH_WIDGETS . 'nav.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Bloco
    * Data de Criacao: 27/02/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Paginacao extends Nav {
	public $qbloco 				= 10; 			# Quantidade de registros por bloco (página)
	protected $inicio 			= 0; 			# Controla o registro de inicio do bloco
	public $qtde_registros		= 0;
	public $filtros_rel_nn 		= array();
	public $filtros_externo 	= array();
	public $exibirnavegacao 	= true;			# Exibe o controle de navegação
	public $contexto 			= "";
	public $retornaregistro 	= "";	
	public $repositorio; 						# Conjunto de dados que será usado para montar a página
	public $entidade; 							# Usado para montar a grade de dados ( não deveria ser assim )
	protected $total_blocos; 					# Total de blocos
	public $filtro_externo;	
	private $filtro_rel_nn; 					# Filtro para relacionamento de agregação N:N
	
	/*  
		* Método construct 
		* Data de Criacao: 27/02/2015
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
	*/		
	public function __construct(){		
		parent::__construct();
		$this->class = "col-md-12";
		
	}	
	/*  
		* Método construct 
	    * Data de Criacao: 27/02/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o elemento NAV com os as páginas da navegação
	*/			
	public function navegacao(){
		$this->pesquisar(); # Adiciona a bloco de pesquisa
		#$this->setDados(); # Seta informações da páginação referente ao grupo de dados
		$ul = tdClass::Criar("ul");
		$ul->class = "pagination";
		
		$primeiro_li = tdClass::Criar("li");
		$primeiro_a = tdClass::Criar("hyperlink");
		$primeiro_a->href = "#";
		$primeiro_a->aria_label = "Previous";
		$primeiro_a->data_bloco = 1;
		$primeiro_a->class = "primeiro";
		$primeiro_span = tdClass::Criar("span");
		$primeiro_span->aria_hidden = "true";
		$primeiro_span->add("&laquo;");
		$primeiro_a->add($primeiro_span);
		$primeiro_li->add($primeiro_a);
		$ul->add($primeiro_li);
		
		$anterior_li = tdClass::Criar("li");			
		$anterior_li->class = "disabled";
		$anterior_a = tdClass::Criar("hyperlink");
		$anterior_a->href = "#";
		$anterior_a->data_bloco = ($this->qbloco + 1);
		$anterior_a->class = "anterior";
		$anterior_span = tdClass::Criar("span");
		$anterior_span->aria_hidden = "true";
		$anterior_span->class = "fas fa-angle-double-left";
		$anterior_a->add($anterior_span);
		$anterior_li->add($anterior_a);
		$ul->add($anterior_li);
		
		for ($i=1;$i<=$this->total_blocos;$i++){
			if ($i <= $this->qbloco){
				$li = tdClass::Criar("li");				
				if ($i==1) $li->class="active";
				$a = tdClass::Criar("hyperlink");
				$a->href = "#";
				$a->add(($i<10?'0':'') . $i);
				$a->data_bloco = $i;
				$a->class = "pagina";
				$li->add($a);
				$ul->add($li);
				
				if (round($this->qbloco/2) == $i){
					$irbloco = tdClass::Criar("input");
					$irbloco->type = "text";
					$irbloco->class = "irbloco-paginacao";					
					$li = tdClass::Criar("li");
					$li->add($irbloco);
					$ul->add($li);
				}
			}	
		}
		
		$proximo_li = tdClass::Criar("li");		
		if ($this->total_blocos<=$this->qbloco) $proximo_li->class = "disabled";		
		$proximo_a = tdClass::Criar("hyperlink");
		$proximo_a->href = "#";
		$proximo_a->data_bloco = ($this->qbloco + 1);
		$proximo_a->class = "proximo";
		$proximo_span = tdClass::Criar("span");
		$proximo_span->aria_hidden = "true";
		$proximo_span->class = "fas fa-angle-double-right";
		$proximo_a->add($proximo_span);
		$proximo_li->add($proximo_a);
		$ul->add($proximo_li);					
		
		$ultimo_li = tdClass::Criar("li");				
		$ultimo_a = tdClass::Criar("hyperlink");
		$ultimo_a->href = "#";
		$ultimo_a->aria_label = "Previous";
		$ultimo_a->data_bloco = $this->total_blocos;
		$ultimo_a->class = "ultimo";
		$ultimo_span = tdClass::Criar("span");
		$ultimo_span->aria_hidden = "true";
		$ultimo_span->add("&raquo;");
		$ultimo_a->add($ultimo_span);
		$ultimo_li->add($ultimo_a);
		$ul->add($ultimo_li);
						
		$center = tdClass::Criar("center");
		$center->add($ul);		
		$script = tdClass::Criar("script");
		$script->type = "text/javascript";
		$script->language = "JavaScript";		
		$script->add('
			var atual = 1;			
			var max_bloco = '.$this->qbloco.';
			var proximo = max_bloco+1;
			var anterior = 1;
			var total_bloco = '.$this->total_blocos.';
			var entidade = '.$this->entidade->contexto->id.';
			var filtro_externo = "'.($this->filtro_externo!=""?"&filtro=".trim($this->filtro_externo):'').'";
			var qtde_registros = '.$this->qtde_registros.';
			'.($this->exibirnavegacao?'				
				// Carrega o primeiro bloco de dados
				carregar("'.tdClass::Criar("persistent",array(CONFIG,1))->contexto->url_requicoes.'&op=paginacao&bloco=1&max_bloco='.$this->qbloco.'&entidade=" + entidade + "&total_registros=" + '.$this->qtde_registros.' + filtro_externo + "'.($this->filtro_rel_nn!=""?"&filtro_rel_nn=".trim($this->filtro_rel_nn):'').'&contexto='.$this->contexto.($this->retornaregistro!=""?"&retornaregistro=".$this->retornaregistro:"").'","'.($this->contexto==""?"":"#" . $this->contexto).' .paginacao-bloco");
			':'
				$(".pagination").hide();
				if (total_bloco == 0){
					carregar("'.tdClass::Criar("persistent",array(CONFIG,1))->contexto->url_requicoes.'&op=paginacao&bloco=1&max_bloco='.$this->qbloco.'&entidade=" + entidade + "&total_registros=" + '.$this->qtde_registros.' + filtro_externo + "'.($this->filtro_rel_nn!=""?"&filtro_rel_nn=".trim($this->filtro_rel_nn):'').'&contexto='.$this->contexto.'","#'.$this->contexto.' .paginacao-bloco");
				}else{
					// Carrega o cabeçalho da grade de dados
					carregar("'.tdClass::Criar("persistent",array(CONFIG,1))->contexto->url_requicoes.'&op=paginacao&dados=no&entidade=" + entidade + "&total_registros=" + filtro_externo + "'.($this->filtro_rel_nn!=""?"&filtro_rel_nn=".trim($this->filtro_rel_nn):'').'&contexto='.$this->contexto.'","#'.$this->contexto.' .paginacao-bloco");
					for(b=1;b<=total_bloco;b++){
						// Carrega o primeiro bloco de dados		
						anexar("'.tdClass::Criar("persistent",array(CONFIG,1))->contexto->url_requicoes.'&op=paginacao&dados=only&bloco="+b+"&max_bloco='.$this->qbloco.'&entidade=" + entidade + "&total_registros=" + '.$this->qtde_registros.' + filtro_externo + "'.($this->filtro_rel_nn!=""?"&filtro_rel_nn=".trim($this->filtro_rel_nn):'').'&contexto='.$this->contexto.'","#'.$this->contexto.' .paginacao-bloco .gradededados");
					}
					$("#'.$this->contexto.' .paginacao-bloco .gradededados tbody tr[class=\'warning\']").hide();
				}
			').'	
						
			$(".pagination .pagina").click(function(e){
				e.preventDefault();
				e.stopPropagation();
				$(".pagination li").removeClass("active");
				$(this).parent().addClass("active");
				//$(".paginacao-bloco").hide();
				atual = $(this).data("bloco");
				//$(".paginacao-bloco[id="+atual+"]").show();
				carregar("'.tdClass::Criar("persistent",array(CONFIG,1))->contexto->url_requicoes.'&op=paginacao&bloco="+atual+"&max_bloco='.$this->qbloco.'&entidade=" + entidade + "&total_registros=" + '.$this->qtde_registros.' + filtro_externo + "'.($this->filtro_rel_nn!=""?"&filtro_rel_nn=".trim($this->filtro_rel_nn):'').'&contexto='.$this->contexto.($this->retornaregistro!=""?"&retornaregistro=".$this->retornaregistro:"").'","'.($this->contexto==""?"":"#" . $this->contexto).' .paginacao-bloco");
				$(".irbloco-paginacao").val("");
			});						
			$(".pagination .proximo").click(function(e){
				e.preventDefault();
				e.stopPropagation();
				if(proximo>total_bloco) return false;
				if ($(this).parent().hasClass("disabled")) return false;
				$(".pagination .anterior").parent().removeClass("disabled	");
				$(".pagination .proximo").attr("data-bloco",proximo+1);
				$(".pagination li").removeClass("active");
				for(i=proximo;i>(proximo-max_bloco);i--){						
					$(".pagination li .pagina[data-bloco="+(i-1)+"]").html((i<10?"0":"")+i);
					$(".pagination li .pagina[data-bloco="+(i-1)+"]").attr("data-bloco",i);
				}
				$(".pagination li .pagina[data-bloco="+proximo+"]").parent().addClass("active");
				//$(".paginacao-bloco").hide();
				//$(".paginacao-bloco[id="+proximo+"]").show();
				if (proximo>total_bloco) $(this).parent().addClass("disabled");
				carregar("'.tdClass::Criar("persistent",array(CONFIG,1))->contexto->url_requicoes.'&op=paginacao&bloco="+proximo+"&max_bloco='.$this->qbloco.'&entidade=" + entidade + "&total_registros=" + '.$this->qtde_registros.' + filtro_externo + "'.($this->filtro_rel_nn!=""?"&filtro_rel_nn=".trim($this->filtro_rel_nn):'').'&contexto='.$this->contexto.($this->retornaregistro!=""?"&retornaregistro=".$this->retornaregistro:"").'","'.($this->contexto==""?"":"#" . $this->contexto).' .paginacao-bloco");
				anterior++;
				proximo++;
				$(".irbloco-paginacao").val("");
			});
			
			$(".pagination .anterior").click(function(e){				
				e.preventDefault();
				e.stopPropagation();
				if ($(this).parent().hasClass("disabled")) return false;
				if((proximo-max_bloco)<=0) return false;
				$(".pagination li").removeClass("active");
				for(i=proximo;i>=(proximo-max_bloco);i--){															
					$(".pagination li .pagina[data-bloco="+(i)+"]").html((i<=10?"0":"")+(i-1));
					$(".pagination li .pagina[data-bloco="+(i)+"]").attr("data-bloco",(i));
				}
				$(".pagination li .pagina").each(function(){
					$(this).attr("data-bloco",parseInt($(this).html()));
				});
				proximo--;
				anterior--;
				$(".pagination li .pagina[data-bloco="+(proximo-1)+"]").parent().addClass("active");
				//$(".paginacao-bloco").hide();
				//$(".paginacao-bloco[id="+(proximo-1)+"]").show();
				if (anterior<=1) $(this).parent().addClass("disabled");
				$(".pagination .proximo").parent().removeClass("disabled");
				carregar("'.tdClass::Criar("persistent",array(CONFIG,1))->contexto->url_requicoes.'&op=paginacao&bloco="+(proximo-1)+"&max_bloco='.$this->qbloco.'&entidade=" + entidade + "&total_registros=" + '.$this->qtde_registros.' + filtro_externo + "'.($this->filtro_rel_nn!=""?"&filtro_rel_nn=".trim($this->filtro_rel_nn):'').'&contexto='.$this->contexto.($this->retornaregistro!=""?"&retornaregistro=".$this->retornaregistro:"").'","'.($this->contexto==""?"":"#" . $this->contexto).' .paginacao-bloco");
				$(".irbloco-paginacao").val("");
			});			
			
			$(".pagination .primeiro").click(function(e){
				e.preventDefault();
				e.stopPropagation();
				$(".pagination li").removeClass("active");
				/*
				anterior = 1;
				total_bloco = '.$this->total_blocos.';				
				var resetar = max_bloco;
				for(i=proximo;i>=(proximo-max_bloco);i--){
					$(".pagination li .pagina[data-bloco="+(i-1)+"]").html((resetar<10?"0":"")+(resetar));
					resetar--;
				}
				$(".pagination li .pagina").each(function(){
					$(this).attr("data-bloco",parseInt($(this).html()));
				});
				proximo = max_bloco+1;
				
				$(".paginacao-bloco").hide();
				$(".paginacao-bloco[id=1]").show();
				$(".pagination .proximo").parent().removeClass("disabled");
				*/
				$(".pagination li .pagina[data-bloco=1]").parent().addClass("active");
				carregar("'.tdClass::Criar("persistent",array(CONFIG,1))->contexto->url_requicoes.'&op=paginacao&bloco=1&max_bloco='.$this->qbloco.'&entidade=" + entidade + "&total_registros=" + '.$this->qtde_registros.' + filtro_externo + "'.($this->filtro_rel_nn!=""?"&filtro_rel_nn=".trim($this->filtro_rel_nn):'').'&contexto='.$this->contexto.($this->retornaregistro!=""?"&retornaregistro=".$this->retornaregistro:"").'","'.($this->contexto==""?"":"#" . $this->contexto).' .paginacao-bloco");
				$(".irbloco-paginacao").val("");
			});
			
			$(".pagination .ultimo").click(function(e){
				e.preventDefault();
				e.stopPropagation();
				$(".pagination li").removeClass("active");
				/*
				total_bloco = '.$this->total_blocos.';
				
				var resetar = total_bloco;
				for(i=proximo;i>=(proximo-max_bloco);i--){
					$(".pagination li .pagina[data-bloco="+(i-1)+"]").html((resetar<10?"0":"")+(resetar));
					resetar--;
				}
				$(".pagination li .pagina").each(function(){
					$(this).attr("data-bloco",parseInt($(this).html()));
				});
				proximo = total_bloco+1;
				anterior=total_bloco-max_bloco;
				
				//$(".paginacao-bloco").hide();
				//$(".paginacao-bloco[id="+total_bloco+"]").show();
				$(".pagination .proximo").parent().addClass("disabled");
				//if ((total_bloco-max_bloco) > 1) $(".pagination .anterior").parent().removeClass("disabled");
				*/
				$(".pagination li .pagina[data-bloco="+'.$this->total_blocos.'+"]").parent().addClass("active");
				carregar("'.tdClass::Criar("persistent",array(CONFIG,1))->contexto->url_requicoes.'&op=paginacao&bloco="+'.$this->total_blocos.'+"&max_bloco='.$this->qbloco.'&entidade=" + entidade + "&total_registros=" + '.$this->qtde_registros.' + filtro_externo + "'.($this->filtro_rel_nn!=""?"&filtro_rel_nn=".trim($this->filtro_rel_nn):'').'&contexto='.$this->contexto.($this->retornaregistro!=""?"&retornaregistro=".$this->retornaregistro:"").'","'.($this->contexto==""?"":"#" . $this->contexto).' .paginacao-bloco");
				$(".irbloco-paginacao").val("");
			});
			
		');		
		if ($this->total_blocos>=1){			
			$this->add($center);
		}
		
	}	
	protected function setDados(){		
		if ($this->repositorio){
			$sql = tdClass::Criar("sqlcriterio");
			// Se tiver relacionamento N:N
			$sql->setPropriedade("limit", $this->qbloco);
			if ($this->filtros_rel_nn!=null){
				$sql_filtros_nn = tdClass::Criar("sqlcriterio");
				$sql_filtros_nn->setPropriedade("limit", $this->qbloco);
				foreach($this->filtros_rel_nn as $ft_nn){
					$sql_filtros_nn->add($ft_nn);					
				}				
				$dataset_nn = tdClass::Criar("repositorio",array(LISTA));				
				$dataset_nn->carregar($sql_filtros_nn);				
				$qtde_registros = $dataset_nn->quantia($sql_filtros_nn);
			}else{
				if($this->filtro_externo!=null){
					$filtros = explode("~",$this->filtro_externo);
					foreach ($filtros as $fts){
						$ft = explode("^",$fts);
						$sql->addFiltro($ft[0],'=',$ft[1]);
					}	
				}
				$this->repositorio->carregar($sql);
				$qtde_registros = $this->repositorio->quantia($sql);
				#$qtde_registros = sizeof($this->repositorio->carregar($sql));
			}			
			$this->qtde_registros = $qtde_registros;			
			if ($qtde_registros <= $this->qbloco){
				$this->total_blocos = 1;
			}else{
				$flutuante = $qtde_registros/$this->qbloco;				
				$inteiro = (int)round($flutuante);
				if ($flutuante > $inteiro){
					$this->total_blocos = $inteiro + 1;
				}else{
					$this->total_blocos = $inteiro;
				}
			}			
			/*
				Cria todos os blocos de dados
			*/			
			$bloco = tdClass::Criar("div");
			$bloco->class = "paginacao-bloco";
			$this->add($bloco);			
		}
	}
	/*
		* Método addFiltro 
	    * Data de Criacao: 12/05/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Adiciona filtros
	*/		
	public function addFiltro(sqlfiltro $filtro){		
		$this->filtros_externo[] = $filtro;
		$fe = explode("=",$filtro->dump());
		$this->filtro_externo .= $this->filtro_externo=="" ? trim($fe[0])."^".str_replace("'","",trim($fe[1])) : "~" . trim($fe[0])."^".str_replace("'","",trim($fe[1]));
		#$this->filtro_externo = trim($fe[0])."^".str_replace("'","",trim($fe[1]));		
	}
	/*
		* Método addFiltroRelNN 
	    * Data de Criacao: 12/05/2015
			* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
			
		Adiciona filtros para implementar o relacionamento de agrega��o N:N
	*/		
	public function addFiltroRelNN(sqlfiltro $filtro){
		$this->filtros_rel_nn[] = $filtro;
		$fe = explode("=",$filtro->dump());
		$this->filtro_rel_nn .= $this->filtro_rel_nn=="" ? trim($fe[0])."^".str_replace("'","",trim($fe[1])) : "~" . trim($fe[0])."^".str_replace("'","",trim($fe[1]));		
	}
	private function pesquisar(){
		$form_group = tdClass::Criar("div");
		$form_group->class = "form-group";
		
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
		
		$js = tdClass::Criar("script");
		$js->type = "text/javascript";
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
					carregar("'.tdClass::Criar("persistent",array(CONFIG,1))->contexto->url_requicoes.'&op=paginacao&bloco=1&max_bloco='.$this->qbloco.'&entidade=" + entidade + "&total_registros=" + '.$this->qtde_registros.' + filtro_externo + "'.($this->filtro_rel_nn!=""?"&filtro_rel_nn=".trim($this->filtro_rel_nn):'').'" + filtro + "'.($this->retornaregistro!=""?"&retornaregistro=".$this->retornaregistro:"").'&contexto='.$this->contexto.'","'.($this->contexto==""?"":"#".$this->contexto).' .paginacao-bloco");					
				 }	
			});
			$(".irbloco-paginacao").keypress(function(e){
				if ( e.which == 13 ) {
					if (isNumeric(this.value)){
						if (parseInt(this.value) > 0){
							carregar("'.tdClass::Criar("persistent",array(CONFIG,1))->contexto->url_requicoes.'&op=paginacao&bloco="+this.value+"&max_bloco='.$this->qbloco.'&entidade=" + entidade + "&total_registros=" + qtde_registros + filtro_externo + "'.($this->filtro_rel_nn!=""?"&filtro_rel_nn=".trim($this->filtro_rel_nn):'').'&contexto='.$this->contexto.($this->retornaregistro!=""?"&retornaregistro=".$this->retornaregistro:"").'","'.($this->contexto==""?"":"#".$this->contexto).' .paginacao-bloco");
						}	
					}	
				}
			});
		');
		$input_group_btn->add($button,$ul);
		$input_group->add($input_text,$input_group_btn);
		$form_group->add($label,$input_group);
		$this->add($form_group);
	}
	public function mostrar(){
		$this->navegacao();
		parent::mostrar();
	}
}