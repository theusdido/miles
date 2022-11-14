<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe TdFormulario
    * Data de Criacao: 27/12/2014
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class TdFormulario Extends Elemento {
	public $fieldset;
	public $legenda;
	protected $dados 		= array();
	public $ncolunas 		= 3;
	public $gd 				= "gd";
	public $fp				= "";
	public $linhacampos;
	public $exibirid 		= false;
	public $funcionalidade 	= "cadastro";
	public $exibirlegenda 	= true;
	public $grupo_botoes;
	private $botoes 		= array();

	/*  
		* Método construct 
	    * Data de Criacao: 27/12/2014
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Formulário padrão
	*/		
	function __construct(){
		parent::__construct('form');
		$this->fieldset 			= tdClass::Criar("fieldset");
		$this->legenda 				= tdClass::Criar("legend");		
		$this->class 				= "form-horizontal tdform";
		$this->linhacampos 			= tdClass::Criar("div");
		$this->linhacampos->class 	= "row-fluid form_campos tdform";
		$this->grupo_botoes			= tdc::html("div" , array("class" => "form-grupo-botao"));
		$this->onsubmit 			= "return false";
	}
	/*  
		* Método CamposHTML 
	    * Data de Criacao: 03/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Monta os campos em HTML de acordo com o tipo que vem da tabela "Coluna"
		@params $colunas (Instancia do Objeto Coluna)
		@params $retorno true: retorna os campos no método - false: exibe os campos
	*/			
	public function camposHTML($colunas,$retorno=false){

		$i = 0;
		$entidadeCOL = tdClass::Criar("persistent",array(ENTIDADE,$colunas[0]->entidade))->contexto->nome;
		
		// Coluna ID
		$colunaID = tdClass::Criar("div");
		if ($this->ncolunas >0){
			$colunaID->class = "coluna";
			$colunaID->data_ncolunas = $this->ncolunas;
		}
		
		// ID
		$id 				= tdClass::Criar("input");
		$id->value 			= 0;
		$id->id 			= "id";
		$id->name 			= "id";
		$id->class 			= "form-control input-sm " . $this->gd;
		$id->data_entidade 	= $entidadeCOL;

		if ($this->exibirid){
			// Form Group ID
			$formgroupID 			= tdClass::Criar("div");
			$formgroupID->class 	= "form-group";

			$labelID 			= tdClass::Criar("label");
			$labelID->for 		= "id";
			$labelID->class 	= "control-label";
			$labelID->add("ID");

			$inputGroup 		= tdClass::Criar("div");
			$inputGroup->class 	= "input-group";

			// Adicionando ID 
			$id->type 			= "text";
			$inputGroup->add($id);
			$formgroupID->add($labelID,$inputGroup);
		}else{

			// Adicionando ID 
			$id->type 			= "hidden";
			$colunaID->add($id);
		
		}
		$this->linhacampos->add($colunaID);		
		foreach($colunas as $coluna){
			$campo = tdClass::Criar("labeledit");
			$label = null;
			$atributodependencia = $coluna->atributodependencia;
			$isatributodependenciapai = isAtributoDependenciaPai($coluna->id)?"atributodependenciapai":"";
			
			if ((int)$coluna->nulo != 1){
				$asteriscoobrigatorio = tdClass::Criar("span");
				$asteriscoobrigatorio->class = "asteriscoobrigatorio";
				$asteriscoobrigatorio->add("*");
			}else{
				$asteriscoobrigatorio = null;
			}
			$initialValue = $this->initialValue($coluna);
			switch($coluna->tipohtml){
				// Campo de Texto ( Longo )
				case "3":
					$campo = Campos::TextoLongo($coluna->nome,$coluna->nome,tdc::utf8($coluna->descricao),$initialValue);
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->data_entidade = $entidadeCOL;
					if ($this->fp != "") $campo->input->class = $this->fp;
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					if ($coluna->readonly) $campo->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $campo->input->required = "true";
					$campo->input->atributo = $coluna->id;
				break;
				// Lista de Seleção Única
				case "4":
					$label = tdClass::Criar("label");
					$label->for = $coluna->nome;
					$label->class = "control-label";
					$label->add(tdc::utf8($coluna->descricao));
					$label->add($asteriscoobrigatorio);
					$select = tdClass::Criar("select");
					$select->class = "form-control input-sm " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $select->class = $this->gd;
					if ($coluna->nulo==0) $select->required = "true";
					$select->atributo = $coluna->id;
					$select->id = $coluna->nome;
					$select->name = $coluna->nome;
					$select->data_entidade = $entidadeCOL;
					if ($isatributodependenciapai!='') $select->class = $isatributodependenciapai;
					$select->data_atributodependencia = $atributodependencia;
					if ((int)$coluna->nulo == 1){
						$op = tdClass::Criar("option");
						$op->add(("-- Selecione uma opção --"));
						$op->value = "";
						$select->add($op);
					}

					$campo = tdClass::Criar("div");
					$campo->class = "form-group";
	
					$input_group = tdClass::Criar("div");
					$input_group->class = "input-group";

					$input_group_btn = tdClass::Criar("span");
					$input_group_btn->class = "input-group-btn";

					$icon_add	= tdc::o("i");
					$icon_add->class = "fas fa-plus";

					$button_add = tdc::o("button");
					$button_add->type = "button";
					$button_add->class = "btn btn-default btn-add-emexecucao";
					$button_add->add($icon_add);
					
					$input_group_btn->add($button_add);

					$input_group->add($select,$input_group_btn);
					$campo->add($label,$input_group);
						
				break;
				// Lista de Seleção Múltipla
				case "5":
					$label = tdClass::Criar("label");
					$label->for = $coluna->nome;
					$label->class = "control-label";
					$label->add(tdc::utf8($coluna->descricao));
					$label->add($asteriscoobrigatorio);
					$select = tdClass::Criar("select");
					$select->class = "form-control input-sm " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $select->class = $this->gd;
					if ($coluna->nulo==0) $select->required = "true";
					$select->atributo = $coluna->id;
					$select->id = $coluna->nome;
					$select->name = $coluna->nome;
					$select->data_entidade = $entidadeCOL;
					$select->multiple = "multiple";
					$select->size = 0;
					if ((int)$coluna->nulo == 1){
						$op = tdClass::Criar("option");
						$op->add(("-- Selecione uma opção --"));
						$op->value = "";
						$select->add($op);
					}
					$add = $JsPopover = "";
					$campo = tdClass::Criar("div");
					$campo->class = "form-group";
	
					$input_group = tdClass::Criar("div");
					$input_group->class = "input-group";

					$input_group_btn = tdClass::Criar("span");
					$input_group_btn->class = "input-group-btn";					

					$input_group->add($select,$input_group_btn);
					$campo->add($label,$input_group);
				break;				
				// Campo Senha
				case "6":
					$campo->label->add(tdc::utf8($coluna->descricao));
					$campo->label->for = $coluna->nome;
					$campo->label->class = "control-label";
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->id = $coluna->nome;
					$campo->input->name = $coluna->nome;
					$campo->input->type = "password";
					$campo->input->class = "form-control input-sm " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					if ($coluna->readonly) $campo->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $campo->input->required = "true";
					$campo->input->data_entidade = $entidadeCOL;
					$campo->input->atributo = $coluna->id;
					$campo->input->value = $initialValue;
				break;
				// Checkbox
				case "7":
					$campo->label->add(tdc::utf8($coluna->descricao));
					$campo->label->for = $coluna->nome;
					$campo->label->class = "control-label";
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->id = $coluna->nome;
					$campo->input->name = $coluna->nome;					
					$campo->input->type = "hidden";
					$campo->input->class = "form-control checkbox-sn " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					$campo->input->atributo = $coluna->id;
					$campo->input->data_entidade = $entidadeCOL;
					$br = tdClass::Criar("br");
				
					$grupo_btn = tdClass::Criar("div");
					$grupo_btn->class = "btn-group";
					$grupo_btn->data_toggle="buttons";
					
					$sim = tdClass::Criar("label");
					$sim->class="btn btn-default checkbox-s ";

					$sim->add($coluna->labelumcheckbox==""?"Sim":tdc::utf8($coluna->labelumcheckbox,7));
					$sim->onclick = "$('#{$coluna->nome}[data-entidade={$entidadeCOL}]').val(1);";
					$sim_input = tdClass::Criar("input");
					$sim_input->type="radio"; 
					$sim_input->name="check".$coluna->nome;	
					$sim_input->data_entidade = $entidadeCOL;					
					$sim_input->autocomplete="off";
					$sim_input->value = 1;
					
					$sim->add($sim_input);

					$nao = tdClass::Criar("label");
					$nao->class="btn btn-default checkbox-n ";
					$nao->add($coluna->labelzerocheckbox==""?"Sim":tdc::utf8($coluna->labelzerocheckbox,7));
					$nao->onclick = "$('#{$coluna->nome}[data-entidade={$entidadeCOL}]').val(0);";
					$nao_input = tdClass::Criar("input");
					$nao_input->type="radio"; 
					$nao_input->name="check".$coluna->nome;
					$nao_input->data_entidade = $entidadeCOL;					
					$nao_input->autocomplete="off";		
					$nao_input->value = 0;										
					$nao->add($nao_input);			
					
					if (!empty($this->dados)){
						if ($initialValue == 0) $nao->class = "active";
						else $sim->class="active";
						$campo->input->value = $initialValue;
					}else{
						$campo->input->value = 0;
						$nao->class = "active";
						$nao_input->checked="true";
						
					}		
					$grupo_btn->add($sim,$nao);
					$campo->add($br,$grupo_btn);
				break;
				// Telefone (xx) xxxx-xxxxx
				case "8":
					$campo->label->add(tdc::utf8($coluna->descricao));
					$campo->label->for = $coluna->nome;
					$campo->label->class = "control-label";
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->id = $coluna->nome;
					$campo->input->name = $coluna->nome;
					$campo->input->data_entidade = $entidadeCOL;
					$campo->input->class = "form-control input-sm formato-telefone " . ($this->fp != ""?$this->fp:"");
					$campo->input->atributo = $coluna->id;
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					if ($coluna->readonly) $campo->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $campo->input->required = "true";
					$campo->input->value = $initialValue;
				break;
				// CEP (xxxxx-xxx)
				case "9":
					$campo->label->add(tdc::utf8($coluna->descricao));
					$campo->label->for = $coluna->nome;
					$campo->label->class = "control-label";
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->id = $coluna->nome;
					$campo->input->name = $coluna->nome;
					$campo->input->data_entidade = $entidadeCOL;
					$campo->input->class = "form-control input-sm formato-cep " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					if ($coluna->readonly) $campo->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $campo->input->required = "true";
					$campo->input->value = $initialValue;
					$campo->input->atributo = $coluna->id;
				break;
				// CPF (xxx.xxx.xxx-xx)
				case "10":
					$campo = Campos::CPF($coluna->nome,$coluna->nome,tdc::utf8($coluna->descricao),$initialValue);
					$campo->input->data_entidade = $entidadeCOL;
					$campo->label->add($asteriscoobrigatorio);
					if ($this->fp != "") $campo->input->class = $this->fp;
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					if ($coluna->readonly) $campo->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $campo->input->required = "true";
					$campo->input->atributo = $coluna->id;
				break;
				// Data (dd/mm/aaaa)
				case "11":
					$campo = tdClass::Criar("div");
					$campo->class = "form-group";
					$input_group = tdClass::Criar("div");
					$input_group->class = "input-group calendar-picker-group " ;
					
					$label = tdClass::Criar("label");					
					$label->add(tdc::utf8($coluna->descricao));
					$label->add($asteriscoobrigatorio);
					$label->for = $coluna->nome;
					$label->class = "control-label";
					
					$input = tdClass::Criar("input");
					$input->type="text";
					$input->id = $coluna->nome;
					$input->name = $coluna->nome;
					$input->data_entidade = $entidadeCOL;
					if ($coluna->readonly) $input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					$input->atributo = $coluna->id;
					$input->class = "form-control input-sm formato-data formato-calendario " . ($coluna->dataretroativa==1?'data-retroativa ':'') .  ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $input->class = $this->gd;
					if ($coluna->nulo==0) $input->required = "true";
					$input->value = dateToMysqlFormat($initialValue,true);
					
					
					$input_group_btn = tdClass::Criar("span");
					$input_group_btn->class = "input-group-btn";
					
					$btn_input = tdClass::Criar("button");
					$btn_input->class = "btn btn-default";
					$icon_calendar = tdClass::Criar("span");
					$icon_calendar->class = "fas fa-calendar-alt calendar-icon";
					$icon_calendar->aria_hidden = "true";
					$btn_input->add($icon_calendar);
					
					$input_group_btn->add($btn_input);
					$input_group->add($input,$input_group_btn);
					$campo->add($label,$input_group);
				break;
				// E-Mail
				case "12":
					$campo->label->add(tdc::utf8($coluna->descricao));
					$campo->label->for = $coluna->nome;
					$campo->label->class = "control-label";
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->id = $coluna->nome;
					$campo->input->name = $coluna->nome;
					$campo->input->data_entidade = $entidadeCOL;
					$campo->input->class = "form-control input-sm formato-email " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					if ($coluna->readonly) $campo->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $campo->input->required = "true";
					$campo->input->value = $initialValue;
					$campo->input->atributo = $coluna->id;
				break;
				// Monetário R$
				case "13":
					$campo->label->add(tdc::utf8($coluna->descricao));
					$campo->label->for = $coluna->nome;
					$campo->label->class = "control-label";
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->id = $coluna->nome;
					$campo->input->name = $coluna->nome;
					$campo->input->data_entidade = $entidadeCOL;
					$campo->input->class = "form-control input-sm formato-moeda " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					if ($coluna->readonly) $campo->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $campo->input->required = "true";
					$campo->input->value = moneyToFloat($initialValue,true);
					$campo->input->atributo = $coluna->id;
				break;
				// Área de Texto
				case "14":
					$campo = Campos::TextArea($coluna->nome,$coluna->nome,tdc::utf8($coluna->descricao),$initialValue,$entidadeCOL);
					$campo->getFilhos()[0]->add($asteriscoobrigatorio); # Filho é o label
					$campo->data_entidade = $entidadeCOL;
					if ($this->fp != "") $campo->class = $this->fp;
					if ($coluna->exibirgradededados ==1) $campo->class = $this->gd;
					if ($coluna->nulo==0) $campo->required = "true";
					$campo->atributo = $coluna->id;					
				break;
				// CNPJ (xx.xxx.xxx/xxxx-xx)
				case "15":
					$campo->label->add(tdc::utf8($coluna->descricao));
					$campo->label->for = $coluna->nome;
					$campo->label->class = "control-label";
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->id = $coluna->nome;
					$campo->input->name = $coluna->nome;
					$campo->input->data_entidade = $entidadeCOL;
					$campo->input->class = "form-control input-sm formato-cnpj " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					if ($coluna->readonly) $campo->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $campo->input->required = "true";
					$campo->input->value = $initialValue;
					$campo->input->atributo = $coluna->id;
				break;
				// Oculto
				case "16":
					$campo = Campos::Oculto($coluna->nome,$coluna->nome,"");
					$campo->data_entidade = $entidadeCOL;
					$campo->class = "form-control " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados == 1) $campo->class = $this->gd;
					if ($coluna->nulo==0) $campo->required = "true";
					$campo->atributo = $coluna->id;
				break;
				// CPFJ ( Cpf e Cnpj )
				case "17":
					$campo->label->add(tdc::utf8($coluna->descricao));
					$campo->label->for = $coluna->nome;
					$campo->label->class = "control-label";
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->id = $coluna->nome;
					$campo->input->name = $coluna->nome;
					$campo->input->data_entidade = $entidadeCOL;
					$campo->input->class = "form-control input-sm formato-cpfj " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					if ($coluna->readonly) $campo->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $campo->input->required = "true";
					$campo->input->value = $initialValue;
					$campo->input->atributo = $coluna->id;
				break;
				// Número Processo Judicial
				case "18":					
					$campo = Campos::NumeroProcessoJudicial($coluna->nome,tdc::utf8($coluna->descricao),$initialValue,$entidadeCOL,$coluna->nulo);
					$campo->label->add($asteriscoobrigatorio);
					if ($this->fp != "") $campo->input->class = $this->fp;
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					$campo->input->atributo = $coluna->id;
				break;
				// Arquivo ( Caminho )
				case "19":
					
					$campo = tdClass::Criar("div");
					$campo->class = "form-group";
					
					$label = tdClass::Criar("label");
					$label->for = $coluna->nome;
					$label->class = "control-label";
					$label->add(tdc::utf8($coluna->descricao));
					$label->add($asteriscoobrigatorio);
					
					$input = Campos::Oculto($coluna->nome,$coluna->nome,'');
					$input->data_entidade = $entidadeCOL;
					$input->class = "form-control td-file-hidden " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $input->class = $this->gd;
					if ($coluna->nulo==0) $input->required = "true";					
					$input->atributo = $coluna->id;
					$iframe = tdClass::Criar("iframe");
					$iframe->data_entidade = $coluna->entidade;
					$iframe->data_atributo=$coluna->id;
					$iframe->src = tdClass::Criar("persistent",array(CONFIG,1))->contexto->urlupload . "&atributo={$coluna->id}&valor={$initialValue}&id=" . ($initialValue!=''?$initialValue:-1) . "&currentproject=" . CURRENT_PROJECT_ID;
					$campo->add($input,$label,$iframe);
				break;
				// CK Editor - Editor de Texto
				case "21":
					$instanciaEditor = 'editorCK_'.$coluna->nome.'_'.$entidadeCOL;
					$nomeCompleto = $coluna->nome . "-" . $entidadeCOL;
					$campoCKEDITOR = tdClass::Criar("div");
					$idCampo = "div-editor-" . $nomeCompleto;
					$campoCKEDITOR->id = $idCampo;
					$dadosHTML = $initialValue;
					$oculto = Campos::Oculto($coluna->nome,$coluna->nome,$dadosHTML);
					$oculto->data_entidade = $entidadeCOL;
					$oculto->data_instanciaCKEDITOR = $instanciaEditor;
					$oculto->class = "form-control " . ($this->fp != ""?$this->fp:"") . " ckeditor";
					if ($coluna->exibirgradededados ==1) $oculto->class = $this->gd;
					if ($coluna->nulo==0) $oculto->required = "true";					

					// Entidade
					$ent = tdClass::Criar("persistent",array(ENTIDADE,$entidadeCOL));
					
					// CK Editor
					$ckEditorInstancias = isset($_SESSION["ckEditorInstancias"]) ? $_SESSION["ckEditorInstancias"] : false;
					if ($ckEditorInstancias){
						$ckeditor = null;	
					}
					$campoCKEDITOR->add($oculto);
					$_SESSION["ckEditorInstancias"] = true;

					// ------
					$modalName = "myModal-ckeditor" . $nomeCompleto;
					$campo = tdClass::Criar("div");
					$campo->class = "form-group";
					$input_group = tdClass::Criar("div");
					$input_group->class = "input-group ckeditor-group " ;
					
					$label = tdClass::Criar("label");
					$label->add(tdc::utf8($coluna->descricao));
					$label->for = $coluna->nome;
					$label->class = "control-label";
					$label->add($asteriscoobrigatorio);
					
					$input = tdClass::Criar("input");
					$input->type="text";
					$input->id =  $coluna->nome;
					$input->name = $coluna->nome;
					$input->data_entidade = $entidadeCOL;
					$input->disabled = "true";
					
					if ($coluna->readonly) $input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					$input->class = "form-control input-sm formato-ckeditor " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados == 1) $input->class = $this->gd;
					if ($coluna->nulo==0) $input->required = "true";
					$input->atributo = $coluna->id;

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
					$modal->class = "ckeditor-field";
					$modal->data_nomecompleto = $nomeCompleto;					
					$modal->addHeader($coluna->descricao,null);
					$modal->addBody($campoCKEDITOR);
					$modal->addFooter("<small>* Componente externo <b>CK Editor</b>. Saiba mais em <a href='http://www.ckeditor.com' target='_blank'>www.ckeditor.com</a></small>");

					$campo->add($label,$input_group,$modal);
				break;
				// Filtro
				case "22":
					$campo = Campos::filtro($coluna->nome,tdc::utf8($coluna->descricao),$coluna->chaveestrangeira,$asteriscoobrigatorio,$entidadeCOL
					,($coluna->exibirgradededados == 1 )?$this->gd:""
					,"myModal-" . $entidadeCOL . "-" . $coluna->nome
					);
					if ($this->fp != "") $campo->class = $this->fp;
					$campo->atributo = $coluna->id;

					if ($initialValue != ""){
						$js = tdClass::Criar("script");
						$js->add('
							buscarFiltro('.$initialValue.');
						');
						$campo->add($js);
					}
				break;
				// Data e Hora
				case "23":
				
					$campo->label->add(tdc::utf8($coluna->descricao));
					$campo->label->for = $coluna->nome;
					$campo->label->class = "control-label";
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->id = $coluna->nome;
					$campo->input->name = $coluna->nome;					
					$campo->input->data_entidade = $entidadeCOL;
					if ($coluna->readonly) $campo->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					$campo->input->class = "form-control input-sm formato-datahora "  . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					if ($coluna->nulo==0) $campo->input->required = "true";
					$campo->input->atributo = $coluna->id;
					$campo->input->value = $initialValue;
				break;
				// Filtro ( Endereço Google )
				case "24":				
					$campo = Campos::filtroEnderecoFiltro($coluna->nome,tdc::utf8($coluna->descricao),$coluna->chaveestrangeira,$asteriscoobrigatorio,$entidadeCOL
						,($this->fp != "")?$this->fp:""
						,($coluna->exibirgradededados ==1 )?$this->gd:""
						,$coluna->id
						,"myModal-" . $entidadeCOL . "-" . $coluna->nome
					);
					$campo->atributo = $coluna->id;
				break;
				// Número ( Inteiro )	
				case "25":

					$campo = tdClass::Criar("div");
					$input = Campos::TextoLongo($coluna->nome,$coluna->nome,tdc::utf8($coluna->descricao),$initialValue);
					$input->label->add($asteriscoobrigatorio);
					$input->input->data_entidade = $entidadeCOL;
					$input->input->class = "formato-numerointeiro";
					if ($this->fp != "") $input->input->class = $this->fp;
					if ($coluna->exibirgradededados ==1) $input->class = $this->gd;
					if ($coluna->readonly) $input->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $input->input->required = "true";
					$input->input->atributo = $coluna->id;
					$campo->add($input);
				break;
				// Número ( Decimal )
				case "26":
					$campo = tdClass::Criar("div");
					$input = Campos::TextoLongo($coluna->nome,$coluna->nome,tdc::utf8($coluna->descricao),$initialValue);
					$input->label->add($asteriscoobrigatorio);
					$input->input->data_entidade = $entidadeCOL;
					$input->input->class = "formato-numerodecimal";
					if ($this->fp != "") $input->input->class = $this->fp;
					if ($coluna->exibirgradededados ==1) $input->input->class = $this->gd;
					if ($coluna->readonly) $input->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $input->input->required = "true";	
					$input->input->atributo = $coluna->id;
					$campo->add($input);
				break;
				// Multi Linha
				case "27":
					$modalName = "myModal-multilinha" . $coluna->nome;
					$campo = tdClass::Criar("div");
					$campo->class = "form-group";
					$input_group = tdClass::Criar("div");
					$input_group->class = "input-group multilinha-group " ;
					
					$label = tdClass::Criar("label");
					$label->add(tdc::utf8($coluna->descricao));
					$label->for = $coluna->nome;
					$label->class = "control-label";
					$label->add($asteriscoobrigatorio);
					
					$input = tdClass::Criar("input");
					$input->type="text";
					$input->id = $coluna->nome;
					$input->name = $coluna->nome;
					$input->data_entidade = $entidadeCOL;
					if ($coluna->readonly) $input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					$input->class = "form-control input-sm formato-multilinha " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados == 1) $input->class = $this->gd;
					if ($coluna->nulo==0) $input->required = "true";					
					$input->atributo = $coluna->id;
					
					$input_group_btn = tdClass::Criar("span");
					$input_group_btn->class = "input-group-btn";
					
					$btn_input = tdClass::Criar("button");
					$btn_input->class = "btn btn-default botao-multilinha";
					$btn_input->data_modalname = $modalName;
					$icon_chat = tdClass::Criar("span");
					$icon_chat->class = "fas fa-ellipsis-h icon-multilinha";
					$icon_chat->aria_hidden = "true";
					$btn_input->add($icon_chat);
					
					$input_group_btn->add($btn_input);
					$input_group->add($input,$input_group_btn);				

					$modal = tdClass::Criar("modal");
					$modal->nome = $modalName;
					
					$modal->addHeader($coluna->descricao,null);
					$modal->addBody("<textarea class='textarea-modal'></textarea>");
					$modal->addFooter("<small>* <b>Quebra de Linha</b> não considerada.</small>");

					$campo->add($label,$input_group,$modal);
				break;
				// Hora
				case "28":
					$campo = tdClass::Criar("div");
					$input = Campos::TextoLongo($coluna->nome,$coluna->nome,tdc::utf8($coluna->descricao),$initialValue);
					$input->label->add($asteriscoobrigatorio);
					$input->input->data_entidade = $entidadeCOL;
					$input->input->class = "formato-hora";
					if ($this->fp != "") $input->input->class = $this->fp;
					if ($coluna->exibirgradededados ==1) $input->input->class = $this->gd;
					if ($coluna->readonly) $input->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $input->input->required = "true";	
					$input->input->atributo = $coluna->id;
					$campo->add($input);
				break;
				// Referência ( Mês/Ano )
				case "29":
					$campo->label->add(tdc::utf8($coluna->descricao));
					$campo->label->for = $coluna->nome;
					$campo->label->class = "control-label";
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->id = $coluna->nome;
					$campo->input->name = $coluna->nome;
					$campo->input->data_entidade = $entidadeCOL;
					$campo->input->class = "form-control input-sm formato-mesano " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					if ($coluna->readonly) $campo->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $campo->input->required = "true";
					$campo->input->value = $initialValue;
					$campo->input->atributo = $coluna->id;				
				break;
				// Is Null e Is Empty
				case "30":
					$campo->label->add(tdc::utf8($coluna->descricao));
					$campo->label->for = $coluna->nome;
					$campo->label->class = "control-label";
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->id = $coluna->nome;
					$campo->input->name = $coluna->nome;					
					$campo->input->type = "hidden";
					$campo->input->class = "form-control checkbox-sn " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					$campo->input->atributo = $coluna->id;
					$campo->input->data_entidade = $entidadeCOL;
					$br = tdClass::Criar("br");
				
					$grupo_btn = tdClass::Criar("div");
					$grupo_btn->class = "btn-group";
					$grupo_btn->data_toggle="buttons";
					
					$sim = tdClass::Criar("label");
					$sim->class="btn btn-default checkbox-s ";

					$sim->add(tdc::utf8("Sim"));
					$sim->onclick = "$('#{$coluna->nome}[data-entidade={$entidadeCOL}]').val(1);";
					$sim_input = tdClass::Criar("input");
					$sim_input->type="radio"; 
					$sim_input->name="check".$coluna->nome;	
					$sim_input->data_entidade = $entidadeCOL;					
					$sim_input->autocomplete="off";
					$sim_input->value = 1;
					
					$sim->add($sim_input);

					$nao = tdClass::Criar("label");
					$nao->class="btn btn-default checkbox-n ";
					$nao->add(tdc::utf8("Não"));
					$nao->onclick = "$('#{$coluna->nome}[data-entidade={$entidadeCOL}]').val(0);";
					$nao_input = tdClass::Criar("input");
					$nao_input->type="radio"; 
					$nao_input->name="check".$coluna->nome;
					$nao_input->data_entidade = $entidadeCOL;					
					$nao_input->autocomplete="off";		
					$nao_input->value = 0;										
					$nao->add($nao_input);			
						
					$grupo_btn->add($sim,$nao);
					$campo->add($br,$grupo_btn);
				break;				
				default:
					$campo->label->add(tdc::utf8($coluna->descricao));
					$campo->label->for = $coluna->nome;
					$campo->label->class = "control-label";
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->id = $coluna->nome;
					$campo->input->name = $coluna->nome;					
					$campo->input->data_entidade = $entidadeCOL;
					if ($coluna->readonly) $campo->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					$campo->input->class = "form-control input-sm "  . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					if ($coluna->nulo==0) $campo->input->required = "true";
					$campo->input->value = $initialValue;
					$campo->input->atributo = $coluna->id;
				break;	
			}
			
			// Legenda
			if ($coluna->legenda!=""){
				$legenda = tdClass::Criar("small");
				$legenda->add(' <small>( ' . tdc::utf8($coluna->legenda) . ' )</small> ');
				if (isset($label)){
					$label->add($legenda);
				}else if(isset($campo->label)){	
					$campo->label->add($legenda);
				}else if(isset($input->label)){
					$input->label->add($legenda);
				}
			}
			$this->addCampo($campo);
		}
		if ($retorno){
			return $this->linhacampos;
		}
	}
	/*
		* Método Dados 
	    * Data de Criacao: 12/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	
		Seta o valor da propriedade $dados, utilizado para preencher as informações na hora da montagem do formulário
		@params $dados ( Objeto DataSet )
	*/			
	public function dados($dados){
		if ($dados != "" && $dados != null){
			foreach($dados as $dado){
				$this->dados[] = $dado;
			}	
		}
	}
	/*  
		* Método CamposObrigatorio 
	    * Data de Criacao: 20/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	
		Retorna os campos que são obrigatorios
		@params $dados ( Objeto DataSet )
	*/
	public function camposObrigatorio($dados){	
		$retorno = "";
		foreach($dados as $dado){
			if ((int)$dado->nulo==0){
				$retorno .= $retorno==""?"'".$dado->nome."'":",'".$dado->nome."'";
			}	
		}
		return $retorno;
	}
	
	public function arrayCampos($dados,$form,$contextoRel = ""){		
		$entidade = tdClass::Criar("persistent",array(ENTIDADE,$dados[0]->{COLUNA_ENTIDADE}));
		$retorno = 'dados_array["' . $entidade->contexto->nome . '"] = ';		
		$i = 1;
		$u = sizeof($dados);
		foreach ($dados as $d){
			$retorno .= '{' . $d->nome . ':' . '$("#'.$d->nome.'","'.$form.'").val()' . ($i!=$u?",":"");
			$i++;
			//. ($i!=$u?'+"~':'+"~id="+$("#id","'.$form.'").val() + "^" + ($("#id","'.$form.'").val()==""?"I":"U") );')
		}
		$retorno .= "}";
		$sqlRel = tdClass::Criar("sqlcriterio");
		$sqlRel->addFiltro("pai","=",$entidade->contexto->id);
		$sqlRel->addFiltro("tipo",'<>',6);
		$sqlRel->addFiltro("tipo",'<>',5);
		$sqlRel->addFiltro("tipo",'<>',2);
		$sqlRel->addFiltro("tipo",'<>',8);		
		$relacionamentos = tdClass::Criar("repositorio",array(RELACIONAMENTO))->carregar($sqlRel);
		foreach($relacionamentos as $rel){
			$entRel = tdClass::Criar("persistent",array(ENTIDADE,$rel->filho));
			//$retorno .= 'dados_array.push("' . $entRel->contexto->nome . '^';
			$i = 1;
			$sqlAttr = tdClass::Criar("sqlcriterio");
			$sqlAttr->addFiltro(COLUNAENTIDADE,"=",$entRel->contexto->id);			
			$dataset = tdClass::Criar("repositorio",array(ATRIBUTO));
			$u = $dataset->quantia($sqlAttr);
			foreach ($dataset->carregar($sqlAttr) as $d){				
				//$retorno .= $d->nome . '=' . '"+$("#'.$d->nome.'","'.$contextoRel.$entRel->contexto->nome.'").val()' . ($i!=$u?'+"~':'+"~id="+$("#id","'.$contextoRel.$entRel->contexto->nome.'").val() + "^" + ($("#id","'.$contextoRel.$entRel->contexto->nome.'").val()==""?"I":"U") );');				
				$i++;
			}
		}
		//echo "<br /><br /><br />" . "RETORNO >>> " . $retorno . " <br /><br /><br />";
		return $retorno;
	}
	public function mostrar(){
		$this->setGrupoBotoes();
		$this->fieldset->add($this->linhacampos);
		if ($this->exibirlegenda){
			if ($this->legenda->qtde_filhos>0) $this->fieldset->add($this->legenda);
		}	
		if ($this->fieldset->qtde_filhos>0) $this->add($this->fieldset);	
		parent::mostrar();		
	}
	public function sessaoRelacionados($entidade,$form){
		$retorno = "";
		$sqlRel = tdClass::Criar("sqlcriterio");
		$sqlRel->addFiltro("filho","=",$entidade);
		$relacionamentos = tdClass::Criar("repositorio",array(RELACIONAMENTO))->carregar($sqlRel);
		foreach($relacionamentos as $rel){
			$atributo = tdClass::Criar("persistent",array(ATRIBUTO,$rel->atributo));
			$retorno .= '$("#'.$atributo->contexto->nome.'").val(session.id);';
		}
	}
	public static function inicializacao($entidade){
		
		// Seleciona os dados da ENTIDADE
		$sql = tdClass::Criar("sqlcriterio");
		$sql->add(tdClass::Criar("sqlfiltro",array(ENTIDADE,"=",$entidade)));
		$entidades = tdFormulario::getEntidadesRelacionamento($entidade);
		if (sizeof($entidades) == 0) return false;
		$sql->add(new sqlFiltro(ENTIDADE,"in",$entidades),OU);
		$sql->setPropriedade("order","ordem");
		
		// Carrega os dados da ENTIDADE
		$dataset = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql);
		
		$retorno = "";
		foreach($dataset as $coluna){
			switch($coluna->tipoinicializacao){
				case 1:
					$retorno .= '$("#'.$coluna->nome.'",".crud-contexto-add-'.tdClass::Criar("persistent",array(ENTIDADE,$coluna->entidade))->contexto->nome.'").val('.$coluna->inicializacao.');';
				break;
				case 4:
					$retorno .= $coluna->inicializacao;
				break;
			}			
		}
		return $retorno;
	}
	public static function getEntidadesRelacionamento($entidade){
		$sql = tdClass::Criar("sqlcriterio");
		$sql->add(tdClass::Criar("sqlfiltro",array("pai","=",$entidade)));
		$sql->add(tdClass::Criar("sqlfiltro",array("tipo",'<>',3))); // Não seleciona relacionamento de Generalização
		
		// Carrega os dados da ENTIDADE
		$dataset = tdClass::Criar("repositorio",array(RELACIONAMENTO))->carregar($sql);
		
		$retorno = array();
		foreach($dataset as $f){			
			array_push($retorno,(int)$f->filho);
			$filhos = tdFormulario::getEntidadesRelacionamento($f->filho);
			if (sizeof($filhos) > 0){
				foreach($filhos as $filho){
					array_push($retorno,(int)$filho);
				}
			}
		}
		return $retorno;
	}
	public static function load($entidade, $id, $attr, $tpRel = 0){		
		if ($conn = Transacao::get()){
			$sql = "SELECT * FROM {$entidade} WHERE {$attr} = {$id}";			
			$query = $conn->query($sql);
			if ($query->rowcount() > 0){
				$linha = $query->fetch();
				$entidadeID = tdClass::Criar("persistent",array($entidade))->contexto->getId();
				$sqlAttr = "SELECT nome FROM " . ATRIBUTO . " WHERE " . ENTIDADE . " = " . $entidadeID;
				$queryAttr = $conn->query($sqlAttr);
				$dados_array = "";
				while ($linhaAttr = $queryAttr->fetch()){
					$dados_array[$linhaAttr["nome"]] = tdc::utf8($linha[$linhaAttr["nome"]]);
				}				
				$retorno["entidade"] = $entidadeID;
				$retorno["dados"] = $dados_array;
				return $retorno;
			}
		}		
		
		return false;
		$entidadeOBJ = tdClass::Criar("persistent",array($entidade))->contexto->getOBJ();		
		
		if ($conn = Transacao::get()){
			$sql = "SELECT b.tipohtml,b.nome FROM ".ENTIDADE." a,".ATRIBUTO." b WHERE b.".ENTIDADE."=a.id AND a.nome = '".$entidadeOBJ->contexto->nome."';";
			$query = $conn->query($sql);
			$campos = $query->fetchAll(PDO::FETCH_ASSOC);			
		}
		
		$dados = "";
		
		$sql = tdClass::Criar("sqlcriterio");
		$sql->addFiltro($attr,"=",$id);
		$sql->setPropriedade("limit",10);
		$registro = tdClass::Criar("repositorio",array($entidadeOBJ->contexto->nome))->carregar($sql);
		if ($registro){
			foreach ($registro as $dado){
				$dados .= ($dados=="")?"id=".$dado->id:"&id=".$dado->id;
				foreach($campos as $c){
					foreach($c as $key => $val){						
						if (is_string($key)){							
							if ($key=="tipohtml"){								
								$htmltipo = $val;
							}
							if ($key != "tipohtml"){
								$dados .= "&" . $val . "=" . getHTMLTipoFormato($htmltipo,$dado->{$val});
							}
						}
					}
				}
			}
			
			$sqlCamposGrade = tdClass::Criar("sqlcriterio");
			$sqlCamposGrade->addFiltro("exibirgradededados","=",1);
			$sqlCamposGrade->addFiltro(ENTIDADE,"=",$entidadeOBJ->contexto->id);
			$dataCamposGrade = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sqlCamposGrade);
			
			$campos_listados = "id";
			foreach ($dataCamposGrade as $campos){
				$campos_listados .= "," . $campos->nome;
			}
			echo utf8_encode("~" . $entidade . "^" . $dados . "^" . $campos_listados . "^" . $tpRel . "^" . $entidadeOBJ->contexto->id . "^" . $attr);
		}else{
			echo "";
		}
	}
	public static function getEntidadesRelacionamentos($entidadeID,$show=true){
		// Procura os relacionamentos para colocar a grade de dados
		$sqlRelGrade = tdClass::Criar("sqlcriterio");
		$sqlRelGrade->addFiltro("pai","=",$entidadeID);
		$datasetRelGrade = tdClass::Criar("repositorio",array(RELACIONAMENTO))->carregar($sqlRelGrade);
		$entidadesRelGrades = "";
		foreach($datasetRelGrade as $rel){
			$entidadeRel = tdClass::Criar("persistent",array(ENTIDADE,$rel->filho));
			$entidadesRelGrades .= 'addGrade("#crud-contexto-listar-'.$entidadeRel->contexto->nome.'");';
		}
		return $entidadesRelGrades;
	}
	/*
		* Método isNumberDataType
	    * Data de Criacao: 27/12/2014
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna se o atributo é do tipo número
		@params $atributo ( ID do atributo)
	*/
	public static function isNumberDataType($atributo){
		switch(getDataType($atributo)){
			case 'int': case 'smallint': case 'tinyint': case 'mediumint': case 'bigint': 
			case 'decimal': case 'float': case 'double': case 'real':
				$numberdatatype = true;
			break;
			default:
				$numberdatatype = false;
		}
		return $numberdatatype;
	}
	/*
		* Método initialValue
	    * Data de Criacao: 07/09/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o valor da inicialização do campo
		@params $coluna
	*/
	private function initialValue($coluna){
		if ($coluna->inicializacao != ''){
			return tdc::utf8($coluna->inicializacao);
		}
		if (sizeof($this->dados) > 0){
			if (isset($this->dados[0]->{$coluna->nome})){
				return tdc::utf8($this->dados[0]->{$coluna->nome});
			}
		}

		if (tdFormulario::isNumberDataType($coluna->id)){
			return 0;
		}else{
			return '';
		}
	}
	/*
		* Método addCampo
	    * Data de Criacao: 18/09/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona campo no formulário
		@params $campo Elemento HTML
	*/
	public function addCampo($campo){
		$coluna = tdClass::Criar("div");
		if ($this->ncolunas >0){
			$coluna->class = "coluna";
			$coluna->data_ncolunas = $this->ncolunas;
		}	
		$coluna->add($campo);	
		$this->linhacampos->add($coluna);
	}
	/*
		* Método addButtons
	    * Data de Criacao: 18/09/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona botões
		@params $botao: Elemento HTML
	*/
	public function addBotao($botao){
		array_push($this->botoes,$botao);
	}

	private function setGrupoBotoes(){
		$div_loader		= tdc::html("div",array("class" => "loader-salvar"));
		$loading        = tdc::html("imagem", array("class" => "loading2"));
		$loading->src   = URL_LOADING2;
		$div_loader->add($loading);

		$this->grupo_botoes->add($div_loader);
		foreach($this->botoes as $b){
			$this->grupo_botoes->add($b);
		}
		$this->add($this->grupo_botoes);
	}
}