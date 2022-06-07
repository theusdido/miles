<?php 
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Campos
    * Data de Criacao: 12/03/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Campos {
	/*  
		* Método TextoCurto
	    * Data de Criacao: 12/03/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Campo de texto curto
	*/		
	public static function TextoCurto($id,$nome,$descricao,$valor=null){		
		$campo = tdClass::Criar("labeledit");
		$campo->label->add(utf8charset($descricao));
		$campo->label->for = $id;
		$campo->label->class = "control-label";
		$campo->input->id = $id;
		$campo->input->name = $nome;
		$campo->input->class = "form-control input-sm texto-curto";
		if (!empty($valor)) $campo->input->value = utf8charset($valor,4);	
		return $campo;
	}
	/*  
		* Método TextoMedio
	    * Data de Criacao: 12/03/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Campo de texto médio
	*/	
	public static function TextoMedio($id,$nome,$descricao,$valor=null){		
		$campo = tdClass::Criar("labeledit");
		$campo->label->add(utf8charset($descricao));
		$campo->label->for = $id;
		$campo->label->class = "control-label";
		$campo->input->id = $id;
		$campo->input->name = $nome;
		$campo->input->class = "form-control input-sm texto-medio";
		if (!empty($valor)) $campo->input->value = utf8charset($valor,4);	
		return $campo;
	}
	/*  
		* Método TextoLongo
	    * Data de Criacao: 12/03/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Campo de texto Longo
	*/	
	public static function TextoLongo($id,$nome,$descricao,$valor=null){		
		$campo = tdClass::Criar("labeledit");
		$campo->label->add(utf8charset($descricao));
		$campo->label->for = $id;
		$campo->label->class = "control-label";
		$campo->input->id = $id;
		$campo->input->name = $nome;
		$campo->input->class = "form-control input-sm texto-longo";
		if (!empty($valor)) $campo->input->value = utf8charset($valor,4);	
		return $campo;
	}	

	/*  
		* Método CPF
	    * Data de Criacao: 12/03/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Campo de texto formatado como CPF
	*/	
	public static function CPF($id,$nome,$descricao,$valor=null){
		$campo = tdClass::Criar("labeledit");	
		$campo->label->add(utf8charset($descricao));
		$campo->label->for = $id;
		$campo->label->class = "control-label";
		$campo->input->id = $id;
		$campo->input->name = $nome;
		$campo->input->class = "form-control input-sm formato-cpf";
		if (!empty($valor)) $campo->input->value = utf8charset($valor,4);		
		return $campo;
	}
	/*  
		* Método OCULTO
	    * Data de Criacao: 25/03/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Campo Oculto
	*/	
	public static function Oculto($id,$nome,$valor=null){
		$campo = tdClass::Criar("input");	
		$campo->id = $id;
		$campo->name = $nome;
		$campo->type = "hidden";
		if ($valor != null){
			$campo->value = $valor;
		}
		return $campo;	
	}
	/*  
		* Método Texarea
	    * Data de Criacao: 25/03/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Campo Texarea
	*/	
	public static function TextArea($id,$nome,$descricao,$valor=null,$coluna_entidade=""){
		$campo = tdClass::Criar("div");
		$campo->class = "form-group";
		
		$textarea = tdClass::Criar("textarea");
		$textarea->id = $id;
		$textarea->name = $nome;
		$textarea->class = "form-control";
		$textarea->data_entidade = $coluna_entidade;
		
		$label = tdClass::Criar("label");
		$label->add(utf8charset($descricao));
		$label->for = $id;
		$label->class = "control-label";		
		
		
		if (!empty($valor)) $textarea->add(utf8charset($valor),4);
		$campo->add($label,$textarea);
		return $campo;
	}
	/*
		* Método NumeroProcessoJudicial
	    * Data de Criacao: 09/05/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Campo NumeroProcessoJudicial
	*/
	public static function NumeroProcessoJudicial($id,$descricao,$valor=null,$coluna_entidade,$coluna){
		$campo = tdClass::Criar("labeledit");	
		$campo->label->add(utf8charset($descricao));
		$campo->label->for = $id;
		$campo->label->class = "control-label";
		$campo->input->id = $id;
		$campo->input->name = $id;
		$campo->input->data_entidade = $coluna_entidade;
		$campo->input->class = "form-control input-sm formato-numeroprocessojudicial";
		if (!empty($valor)) $campo->input->value = utf8charset($valor,4);		
		$jsCampo = tdClass::Criar("script");
		if ($coluna==0) $campo->input->required = "true";
		return $campo;	
	}
	/*  
		* integridade
		* Data de Criacao: desconhecido
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* Retorna um padrão de dados e processa o salvamento de dados padrão
		* PARAMETROS
		*	@params: int entidade:"ID da Entidade"
		*	@params: String atributo:"Nome do Atributo"
		*	@params: any valor:"Valor a ser salvo"
		*	@params: int id:"ID do registro"
		* RETORNO
		*	@return any: Valor formatado para salvamento
	*/		
	public static function integridade($entidade,$atributo,$valor,$id){

		$sql = tdClass::Criar("sqlcriterio");
		$sql->addFiltro("nome","=",$atributo);
		$sql->addFiltro("entidade","=",$entidade);
		$dataset = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql);
		if (sizeof($dataset) <= 0) return $valor;
		switch((int)$dataset[0]->tipohtml){
			case 1: case 2: case 3:
				$retorno = utf8charset($valor,4);
			break;
			case 6: # Senha
				$retorno = strlen($valor) == 32 ? $valor : md5($valor);
			break;
			case 7:				
				$retorno = $valor == "" ? 0 : $valor;
			break;
			case 10:
				$retorno = $valor;
			break;
			case 11: # Data
				if ($valor != ""){
					$separador = (strpos($valor,"/") > 0)?"/":"-";
					$dt = explode($separador,$valor);
					return $dt[2] . "-" . $dt[1] . "-" . $dt[0];
				}else{
					return null;
				}
			break;
			case 13:
				$retorno = (double)moneyToFloat($valor);
			break;
			case 19: # Upload
				if ($valor == ""){
					$retorno = $valor;
				}else{
					$val 				= json_decode($valor,true);
					$op             	= isset($val["op"])?$val["op"]:'';
                    $filename 			= isset($val["filename"])?$val["filename"]: (isset($val[1])?$val[1]:'');
					$tipo 				= isset($val["tipo"])?$val["tipo"]:'';
					$src 				= isset($val["src"])?$val["src"]:'';
					$legenda			= isset($val["legenda"])?$val["legenda"]:'';					
					$isexcluirtemp		= isset($val["isexcluirtemp"])?(bool)$val["isexcluirtemp"]:true;
					$filenamefixed		= $atributo . "-" . $entidade . "-". $id. "." . getExtensao($filename);
					$filenametemp		= $src;
                    $pathfile       	= Session::Get("PATH_CURRENT_FILE") . $filenamefixed;
					$pathexternalfile	= '/public_html/sistema/projects/'.Session::Get()->projeto.'/arquivos/' . $filenamefixed;

                    // Em modo de exclusão
                    if ($op == "excluir"){
                        if (file_exists($pathfile)){

							// Exclui o arquivo no FTP
							$ftp = new FTP();
							$ftp->delete($pathexternalfile);

							// Exclui o arquivo
                            unlink($pathfile);
                        }
                        $filename = '';
                    }

					if (sizeof($val) > 0 && $filename != ''){
						if (file_exists($filenametemp)){

							// Envia arquivo para o FTP Externo do Projeto
							$ftp = new FTP();
							$ftp->put($filenametemp,$pathexternalfile);

							// Move o arquivo da pasta temporária para permanente
							copy($filenametemp,$pathfile);	

							// Exclui o arquivo temporário
							if ($isexcluirtemp) unlink($filenametemp);
						}
					}

					$retorno = $filename;					
				}
			break;
			case 22: # Filtro
				if ($valor == "" || $valor == null){
					return 0;
				}else{
					return $valor;
				}
			break;
			case 23: # Data e Hora
				if ($valor != ""){
					$separador = (strpos($valor,"/") > 0)?"/":"-";
					$data = explode(" ",$valor);
					$hora = explode(" ",$valor);
					$dt = explode($separador,$data[0]);
					return $dt[2] . "-" . $dt[1] . "-" . $dt[0] . (isset($data[1])?" " . $data[1]:"");
				}else{
					return null;
				}	
			break;
			case 24: # Filtro (Endereço)
				if ($valor == "" || $valor == null){
					return 0;
				}else{
					return $valor;
				}
			break;
			default:
				$retorno = $valor;
		}
		return $retorno;
	}
	public static function arquivo(){
		$campo = tdClass::Criar("div");
		$label = tdClass::Criar("label");
		$label->add(utf8charset($descricao));
		$campo->label->for = $id;
		$campo->label->class = "control-label";
		$campo->input->id = $id;
		$campo->input->name = $nome;
		$campo->input->class = "form-control input-sm texto-longo";
		if (!empty($valor)) $campo->input->value = utf8charset($valor,4);	
		return $campo;		
	}
	public static function filtro($nome,$descricao,$chaveestrangeira,$obrigatorio=null,$entidade="",$gd="",$modalName=""){

		if ($modalName == ""){
			$modalName = "modal-" . $nome;
		}
		$entidadePK = tdClass::Criar("persistent",array(ENTIDADE,$chaveestrangeira));
		
		$campo = tdClass::Criar("div");
		$campo->class = "filtro-pesquisa form-group";
		$campo->data_modalname = $modalName;
		
		$label = tdClass::Criar("label");
		$label->add(utf8charset($descricao));
		$label->for = $nome;
		$label->class = "control-label";				
		
		$input_group = tdClass::Criar("div");
		$input_group->class = "input-group";
		
		$input_group_btn = tdClass::Criar("span");
		$input_group_btn->class = "input-group-btn";
						
		$button = tdClass::Criar("button");
		$button->class = "btn btn-default botao-filtro";
		$button->id = "pesquisa-" . $nome;
		$button->name = $nome;
		$button->data_fk = $chaveestrangeira;
		$button->data_entidade = $entidade;
		$span_icon_procura = tdClass::Criar("span");
		$span_icon_procura->class = "fas fa-search";
		
		$button->add($span_icon_procura);		
		
		$termo = tdClass::Criar("input");
		$termo->type = "text";
		$termo->class = "form-control termo-filtro {$gd}";
		$termo->id = $nome;
		$termo->name = $nome;
		$termo->data_fk = $entidadePK->contexto->nome;
		if ($obrigatorio != null){
			$termo->required = "true";
			$label->add($obrigatorio);
		}
		$termo->data_entidade = $entidade;
		$termo->atributo = getAtributoId($entidade,$nome,Transacao::Get());
		
		$descricao_resultado = tdClass::Criar("input");
		$descricao_resultado->type = "text";
		$descricao_resultado->readonly = "true";
		$descricao_resultado->class = "form-control descricao-filtro";
		$descricao_resultado->id = "descricao-".$nome;
		$descricao_resultado->name = "descricao-".$nome;
		$descricao_resultado->data_entidade = $entidade;
		
		$input_group_btn->add($button);
		$input_group->add($termo,$descricao_resultado,$input_group_btn);
				
		$modal = tdClass::Criar("modal");
		$modal->nome = $modalName;
		$modal->tamanho = "modal-lg";
		$modal->addHeader("Pesquisa de " . $entidadePK->contexto->descricao,null);
		$modal->addBody("");
		$modal->addFooter("");
	
		$campo->add($label,$input_group,$modal);
		return $campo;
	}
	public static function filtroEnderecoFiltro($nome,$descricao,$chaveestrangeira,$obrigatorio,$entidadeCOL,$fp,$gd,$entidade,$modalName){

		$entidadePK = tdClass::Criar("persistent",array(ENTIDADE,$chaveestrangeira));
		
		$campo = tdClass::Criar("div");
		$campo->class = "filtro-pesquisa form-group filtro-endereco";
		$campo->data_modalname = $modalName;
		
		$label = tdClass::Criar("label");
		$label->add(utf8charset($descricao));
		$label->for = $nome;
		$label->class = "control-label";				
		
		$input_group = tdClass::Criar("div");
		$input_group->class = "input-group";
		
		$input_group_btn = tdClass::Criar("span");
		$input_group_btn->class = "input-group-btn";
						
		$button = tdClass::Criar("button");
		$button->class = "btn btn-default botao-filtro";
		$button->id = "pesquisa-" . $nome;
		$button->data_fk = $chaveestrangeira;
		$button->data_entidade = $entidadeCOL;
		$span_icon_procura = tdClass::Criar("span");
		$span_icon_procura->class = "fas fa-search";
		
		$button->add($span_icon_procura);		
		
		$termo = tdClass::Criar("input");
		$termo->type = "text";
		$termo->class = "form-control termo-filtro " . ($fp!=""?$fp:"") . " " . ($gd!=""?$gd:"");
		$termo->id = $nome;
		$termo->name = $nome;
		if ($obrigatorio != null){
			$termo->required = "true";
			$label->add($obrigatorio);
		}
		$termo->data_entidade = $entidadeCOL;
		$termo->data_fk = $entidadePK->contexto->nome;
		
		$descricao_resultado = tdClass::Criar("input");
		$descricao_resultado->type = "text";
		$descricao_resultado->readonly = "true";		
		$descricao_resultado->class = "form-control descricao-filtro";
		$descricao_resultado->id = "descricao-".$nome;
		$descricao_resultado->name = "descricao-".$nome;
		$descricao_resultado->data_entidade = $entidadeCOL;
		
		$input_group_btn->add($button);
		$input_group->add($termo,$descricao_resultado,$input_group_btn);
		
		$modal = tdClass::Criar("modal");
		$modal->nome = $modalName;
		$modal->tamanho = "modal-lg";
		$modal->addHeader("Pesquisa de " . $entidadePK->contexto->descricao,null);
		$modal->addBody("");
		$modal->addFooter("");
	
		$campo->add($label,$input_group,$modal);
		return $campo;
	}
	public static function CKEditor($id,$nome,$titulo,$valor="",$entidadeCOL="",$fp="",$gd="",$nulo=0,$readonly=false){
		$instanciaEditor = 'editorCK_'.$id.'_'.$entidadeCOL;
		$nomeCompleto = $id . "-" . $entidadeCOL;
		$campoCKEDITOR = tdClass::Criar("div");
		$idCampo = "div-editor-" . $nomeCompleto;
		$campoCKEDITOR->id = $idCampo;

		$oculto = Campos::Oculto($nome,$nome,$valor);
		$oculto->data_entidade = $entidadeCOL;
		$oculto->data_instanciaCKEDITOR = $instanciaEditor;
		$oculto->class = "form-control " . ($fp != ""?$fp:"") . " ckeditor";
		$oculto->class = $gd;
		if ($nulo==0) $oculto->required = "true";

		// CK Editor
		if ($_SESSION["ckEditorInstancias"]){
			$ckeditor = null;
		}
		$campoCKEDITOR->add($oculto);
		$_SESSION["ckEditorInstancias"] = true;

		$modalName = "myModal-ckeditor" . $nomeCompleto;
		$campo = tdClass::Criar("div");
		$campo->class = "form-group";
		$input_group = tdClass::Criar("div");
		$input_group->class = "input-group ckeditor-group ";
		
		$label = tdClass::Criar("label");
		$label->add(($titulo));
		$label->for = $id;
		$label->class = "control-label";
		
		$input = tdClass::Criar("input");
		$input->type="text";
		$input->id =  $id;
		$input->name = $nome;
		$input->data_entidade = $entidadeCOL;
		$input->disabled = "true";
		
		if ($readonly) $input->readonly = "true";		
		$input->class = "form-control input-sm formato-ckeditor " . ($fp != ""?$fp:"");
		if ($nulo==0) $input->required = "true";
		$input->atributo = $nome;

		$input_group_btn = tdClass::Criar("span");
		$input_group_btn->class = "input-group-btn";
		
		$btn_input = tdClass::Criar("button");
		$btn_input->class = "btn btn-default botao-ckeditor";
		$btn_input->data_modalname = $modalName;
		$icon_chat = tdClass::Criar("span");
		$icon_chat->class = "fas fa-ellipsis-h icon-ckeditor";
		$icon_chat->aria_hidden = "true";
		$btn_input->add($icon_chat);
		
		$input_group_btn->add($btn_input);
		$input_group->add($input,$input_group_btn);				

		$modal = tdClass::Criar("modal");
		$modal->nome = $modalName;
		$modal->tamanho = "modal-lg";
		$modal->addHeader($titulo,null);
		$modal->addBody($campoCKEDITOR);
		$modal->addFooter("<small>* Componente externo <b>CK Editor</b>. Saiba mais em <a href='http://www.ckeditor.com' target='_blank'>www.ckeditor.com</a></small>");

		$campo->add($label,$input_group,$modal);
		return $campo;
	}

	/*
		* Método Lista
	    * Data de Criacao: 20/08/2019
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Campo de lista única
	*/
	public static function Lista($id, $nome, $descricao, $opcoes = null){
		$label = tdClass::Criar("label");
		$label->for = $id;
		$label->class = "control-label";
		$label->add(utf8charset($descricao));
		$select = tdClass::Criar("select");
		$select->id = $id;
		$select->name = $nome;
		$select->class = "form-control";
		
		if (gettype($opcoes) == "array"){
			foreach($opcoes as $op){
				$select->add($op);
			}
		}

		$campo = tdClass::Criar("div");
		$campo->class = "form-group";
		$campo->add($label,$select);
		return $campo;
	}
	
	/*
		* Método InteiroBotao
	    * Data de Criacao: 20/08/2019
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Campo de número inteiro com botões
	*/
	public static function InteiroBotao($id,$descricao){
		$formgroup = tdClass::Criar("div");
		$formgroup->class = "form-group";
		
		$label = tdClass::Criar("label");
		$label->class = "control-label";
		$label->for = $id;
		$label->add(utf8charset($descricao));
		
		$inputgroup = tdClass::Criar("div");
		$inputgroup->class = "input-group componente-incdec";
		
		$inputgroupbtnDec = tdClass::Criar("span");
		$inputgroupbtnDec->class = "input-group-btn";
		
		$buttondec = tdClass::Criar("button");
		$buttondec->class = "btn btn-primary dec";
		$buttondec->add(" - ");
		$inputgroupbtnDec->add($buttondec);
		
		$inputval = tdClass::Criar("input");
		$inputval->class = "form-control text-center val";
		$inputval->id = $id;
		
		$inputgroupbtnInc = tdClass::Criar("span");
		$inputgroupbtnInc->class = "input-group-btn";
		
		$buttoninc = tdClass::Criar("button");
		$buttoninc->class = "btn btn-primary dec";
		$buttoninc->add(" + ");
		$inputgroupbtnInc->add($buttoninc);
		
		$inputgroup->add($inputgroupbtnDec,$inputval,$inputgroupbtnInc);
		$formgroup->add($label,$inputgroup);
		return $formgroup;
	}
	
	/*
		* Método NumeroDecimal
	    * Data de Criacao: 20/08/2019
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Campo de número decimal
	*/
	public static function NumeroDecimal($id,$descricao,$valor=null){
		$campo = tdClass::Criar("labeledit");
		$campo->label->add(utf8charset($descricao));
		$campo->label->for = $id;
		$campo->label->class = "control-label";
		$campo->input->id = $id;
		$campo->input->class = "form-control input-sm formato-numerodecimal";
		if (!empty($valor)) $campo->input->value = utf8charset($valor,4);	
		return $campo;
	}
	
	/*
		* Método Monetário
	    * Data de Criacao: 20/08/2019
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Campo de valor monetário
	*/
	public static function Monetario($id,$descricao,$valor=null){
		$campo = tdClass::Criar("labeledit");
		$campo->label->add(utf8charset($descricao));
		$campo->label->for = $id;
		$campo->label->class = "control-label";
		$campo->input->id = $id;
		$campo->input->class = "form-control input-sm formato-moeda";
		if (!empty($valor)) $campo->input->value = utf8charset($valor,4);
		return $campo;
	}	
}