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
	protected $dados;
	public $ncolunas = 3;
	public $fp;
	public $gd = "gd";
	public $linhacampos;
	public $exibirid = false;
	public $funcionalidade = "cadastro";
	public $exibirlegenda = true;

	/*  
		* M�todo construct 
	    * Data de Criacao: 27/12/2014
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Formul�rio padr�o
	*/		
	function __construct(){
		parent::__construct('form');
		$this->fieldset = tdClass::Criar("fieldset");
		$this->legenda = tdClass::Criar("legend");		
		$this->class = "form-horizontal tdform";
		$this->linhacampos = tdClass::Criar("div");
		$this->linhacampos->class = "row-fluid form_campos";
	}
	/*  
		* M�todo CamposHTML 
	    * Data de Criacao: 03/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Monta os campos em HTML de acordo com o tipo que vem da tabela "Coluna"
		@params $colunas (Instancia do Objeto Coluna)
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
		
		if ($this->exibirid){

			// Form Group ID
			$formgroupID = tdClass::Criar("div");
			$formgroupID->class = "form-group";

			$labelID = tdClass::Criar("label");
			$labelID->for = "id";
			$labelID->class = "control-label";
			$labelID->add("ID");

			$inputGroup = tdClass::Criar("div");
			$inputGroup->class = "input-group";

			// ID 
			$id = tdClass::Criar("input");
			$id->type = "text";
			$id->id = "id";
			$id->name = "id";
			$id->class = "form-control input-sm " . $this->gd;
			$id->data_entidade = $entidadeCOL;

			$inputGroup->add($id);
			$formgroupID->add($labelID,$inputGroup);
		}else{

			// ID 
			$id = tdClass::Criar("input");
			$id->type = "hidden";
			$id->id = "id";
			$id->name = "id";
			$id->class = "form-control " . $this->gd;
			$id->data_entidade = $entidadeCOL;
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
			switch($coluna->tipohtml){
				// Campo de Texto ( Longo )
				case "3":
					if (isset($this->dados[$i])){
						$valor = $this->dados[$i]->{$coluna->nome};
					}else if ($coluna->inicializacao != ""){
						$valor = "";
					}else{
						$valor = "";
					}
					$campo = Campos::TextoLongo($coluna->nome,$coluna->nome,utf8charset($coluna->descricao,7),$valor);
					$campo->label->add($asteriscoobrigatorio);
					$campo->input->data_entidade = $entidadeCOL;
					if ($this->fp != "") $campo->input->class = $this->fp;
					if ($coluna->exibirgradededados ==1) $campo->input->class = $this->gd;
					if ($coluna->readonly) $campo->input->readonly = "true";
					if ($coluna->desabilitar) $campo->input->disabled = "true";
					if ($coluna->nulo==0) $campo->input->required = "true";
					$campo->input->atributo = $coluna->id;
				break;
				// Lista de Sele��o �nica
				case "4":
					$label = tdClass::Criar("label");
					$label->for = $coluna->nome;
					$label->class = "control-label";
					$label->add(utf8charset($coluna->descricao,7));
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
				// Lista de Sele��o M�ltipla
				case "5":
					$label = tdClass::Criar("label");
					$label->for = $coluna->nome;
					$label->class = "control-label";
					$label->add(utf8charset($coluna->descricao,7));
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
						$op->add(("-- Selecione uma op��o --"));
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
					$campo->label->add(utf8charset($coluna->descricao,7));
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
					if (!empty($this->dados)) $campo->input->value = utf8_encode($this->dados[$i]->{$coluna->nome});
				break;
				// Checkbox
				case "7":
					$campo->label->add(utf8charset($coluna->descricao,7));
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

					$sim->add($coluna->labelumcheckbox==""?"Sim":utf8charset($coluna->labelumcheckbox,7));
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
					$nao->add($coluna->labelzerocheckbox==""?"Sim":utf8charset($coluna->labelzerocheckbox,7));
					$nao->onclick = "$('#{$coluna->nome}[data-entidade={$entidadeCOL}]').val(0);";
					$nao_input = tdClass::Criar("input");
					$nao_input->type="radio"; 
					$nao_input->name="check".$coluna->nome;
					$nao_input->data_entidade = $entidadeCOL;					
					$nao_input->autocomplete="off";		
					$nao_input->value = 0;										
					$nao->add($nao_input);			
					
					if (!empty($this->dados)){
						if ((int)$this->dados[$i]->{$coluna->nome} == 0) $nao->class = "active";
						else $sim->class="active";
						$campo->input->value = $this->dados[$i]->{$coluna->nome};						
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
					$campo->label->add(utf8charset($coluna->descricao,7));
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
					if (!empty($this->dados)) $campo->input->value = utf8_encode($this->dados[$i]->{$coluna->nome});		
				break;
				// CEP (xxxxx-xxx)
				case "9":
					$campo->label->add(utf8charset($coluna->descricao,7));
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
					if (!empty($this->dados)) $campo->input->value = utf8_encode($this->dados[$i]->{$coluna->nome});
					$campo->input->atributo = $coluna->id;
				break;
				// CPF (xxx.xxx.xxx-xx)
				case "10":
					$campo = Campos::CPF($coluna->nome,$coluna->nome,utf8charset($coluna->descricao,7),($this->dados[$i]?$this->dados[$i]->{$coluna->nome}:''));
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
					if ($this->dados){
						$valor = $this->dados[$i]->{$coluna->nome};
					}else if($coluna->inicializacao != ""){
						#eval('$valor = ' . $coluna->inicializacao . ';');
						$valor = "";
					}else{						
						$valor = "";
					}
					
					$campo = tdClass::Criar("div");
					$campo->class = "form-group";
					$input_group = tdClass::Criar("div");
					$input_group->class = "input-group calendar-picker-group " ;
					
					$label = tdClass::Criar("label");					
					$label->add(utf8charset($coluna->descricao,7));
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
					//if ($valor != "")
					$input->value = dateToMysqlFormat($valor,true);
					
					
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
					$campo->label->add(utf8charset($coluna->descricao,7));
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
					if (!empty($this->dados)) $campo->input->value = $this->dados[$i]->{$coluna->nome};
					$campo->input->atributo = $coluna->id;
				break;
				// Monet�rio R$
				case "13":
					$campo->label->add(utf8charset($coluna->descricao,7));
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
					if (!empty($this->dados)) $campo->input->value = moneyToFloat($this->dados[$i]->{$coluna->nome},true);
					$campo->input->atributo = $coluna->id;
				break;
				// �rea de Texto
				case "14":
					$campo = Campos::TextArea($coluna->nome,$coluna->nome,utf8charset($coluna->descricao,7),($this->dados[$i]?$this->dados[$i]->{$coluna->nome}:''),$entidadeCOL);
					$campo->label->add($asteriscoobrigatorio);
					$campo->data_entidade = $entidadeCOL;
					if ($this->fp != "") $campo->class = $this->fp;
					if ($coluna->exibirgradededados ==1) $campo->class = $this->gd;
					if ($coluna->nulo==0) $campo->required = "true";
					$campo->atributo = $coluna->id;					
				break;
				// CNPJ (xx.xxx.xxx/xxxx-xx)
				case "15":
					$campo->label->add(utf8charset($coluna->descricao,7));
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
					if (!empty($this->dados)) $campo->input->value = utf8_encode($this->dados[$i]->{$coluna->nome});
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
					$campo->label->add(utf8charset($coluna->descricao,7));
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
					if (!empty($this->dados)) $campo->input->value = utf8_encode($this->dados[$i]->{$coluna->nome});
					$campo->input->atributo = $coluna->id;
				break;
				// N�mero Processo Judicial
				case "18":					
					$campo = Campos::NumeroProcessoJudicial($coluna->nome,utf8charset($coluna->descricao,7),($this->dados[$i]?$this->dados[$i]->{$coluna->nome}:''),$entidadeCOL,$coluna->nulo);
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
					$label->add(utf8charset($coluna->descricao,7));
					$label->add($asteriscoobrigatorio);
					
					if ($this->dados){
						$valor = $this->dados[$i]->{$coluna->nome};
					}else if($coluna->inicializacao != ""){
						#eval('$valor = ' . $coluna->inicializacao . ';');
						$valor = "";
					}else{						
						$valor = "";
					}
					
					$input = Campos::Oculto($coluna->nome,$coluna->nome,'');
					$input->data_entidade = $entidadeCOL;
					$input->class = "form-control td-file-hidden " . ($this->fp != ""?$this->fp:"");
					if ($coluna->exibirgradededados ==1) $input->class = $this->gd;
					if ($coluna->nulo==0) $input->required = "true";					
					if ($this->dados){
						if ($valor!=""){
							//$input->value = $valor . "^" . $coluna->nome . "-" . $this->dados[$i]->id .".". getExtensao($valor);
						}
					}
					$input->atributo = $coluna->id;
					$iframe = tdClass::Criar("iframe");
					$iframe->data_entidade = $coluna->{ENTIDADE};
					$iframe->data_atributo=$coluna->id;
					$iframe->src = tdClass::Criar("persistent",array(CONFIG,1))->contexto->urlupload . "&atributo={$coluna->id}&valor={$valor}&id=" . (isset($this->dados)?$this->dados[$i]->id:-1) . "&currentproject=" . Session::Get()->projeto;
					$campo->add($input,$label,$iframe);
				break;
				// CK Editor - Editor de Texto
				case "21":
					$instanciaEditor = 'editorCK_'.$coluna->nome.'_'.$entidadeCOL;
					$nomeCompleto = $coluna->nome . "-" . $entidadeCOL;
					$campoCKEDITOR = tdClass::Criar("div");
					$idCampo = "div-editor-" . $nomeCompleto;
					$campoCKEDITOR->id = $idCampo;
					$dadosHTML = $this->dados[$i]?$this->dados[$i]->{$coluna->nome}:'';
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
					$label->add(utf8charset($coluna->descricao,7));
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
					$campo = Campos::filtro($coluna->nome,utf8charset($coluna->descricao,7),$coluna->chaveestrangeira,$asteriscoobrigatorio,$entidadeCOL
					,($coluna->exibirgradededados == 1 )?$this->gd:""
					,"myModal-" . $entidadeCOL . "-" . $coluna->nome
					);
					if ($this->fp != "") $campo->class = $this->fp;
					$campo->atributo = $coluna->id;
					if ($this->dados){
						$valor = $this->dados[$i]->{$coluna->nome};
					}else if($coluna->inicializacao != ""){
						#eval('$valor = ' . $coluna->inicializacao . ';');
						$valor = "";
					}else{						
						$valor = "";
					}
					if ($valor != ""){
						$js = tdClass::Criar("script");
						$js->add('
							buscarFiltro('.$valor.');
						');
						$campo->add($js);
					}
				break;
				// Data e Hora
				case "23":
					if ($this->dados){
						$valor = $this->dados[$i]->{$coluna->nome};
					}else if($coluna->inicializacao != ""){
						#eval('$valor = ' . $coluna->inicializacao . ';');
						$valor = "";
					}else{						
						$valor = "";
					}				
					$campo->label->add(utf8charset($coluna->descricao,7));
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
					$campo->input->value = utf8_encode($valor);					
				break;
				// Filtro ( Endere�o Google )
				case "24":				
					$campo = Campos::filtroEnderecoFiltro($coluna->nome,utf8charset($coluna->descricao,7),$coluna->chaveestrangeira,$asteriscoobrigatorio,$entidadeCOL
						,($this->fp != "")?$this->fp:""
						,($coluna->exibirgradededados ==1 )?$this->gd:""
						,$coluna->id
						,"myModal-" . $entidadeCOL . "-" . $coluna->nome
					);
					$campo->atributo = $coluna->id;
				break;
				// N�mero ( Inteiro )	
				case "25":
					if ($this->dados){
						$valor = $this->dados[$i]->{$coluna->nome};
					}else if($coluna->inicializacao != ""){
						#eval('$valor = ' . $coluna->inicializacao . ';');
						$valor = "";
					}else{						
						$valor = "";
					}
					$campo = tdClass::Criar("div");
					$input = Campos::TextoLongo($coluna->nome,$coluna->nome,utf8charset($coluna->descricao,7),$valor);
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
					if ($this->dados){
						$valor = $this->dados[$i]->{$coluna->nome};
					}else if($coluna->inicializacao != ""){
						#eval('$valor = ' . $coluna->inicializacao . ';');
						$valor = "";
					}else{						
						$valor = "";
					}
					$campo = tdClass::Criar("div");
					$input = Campos::TextoLongo($coluna->nome,$coluna->nome,utf8charset($coluna->descricao,7),$valor);
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
					$label->add(utf8charset($coluna->descricao,7));
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
					$modal->addFooter("<small>* <b>Quebra de Linha</b> n�o considerada.</small>");

					$campo->add($label,$input_group,$modal);
				break;
				// Hora
				case "28":
					if ($this->dados){
						$valor = $this->dados[$i]->{$coluna->nome};
					}else if($coluna->inicializacao != ""){
						$valor = "";
					}else{
						$valor = "";
					}
					$campo = tdClass::Criar("div");
					$input = Campos::TextoLongo($coluna->nome,$coluna->nome,utf8charset($coluna->descricao,7),$valor);
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
				// Mês-Ano ( Referencia )
				case "29":
					$campo->label->add(utf8charset($coluna->descricao,7));
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
					if (!empty($this->dados)) $campo->input->value = utf8charset($this->dados[$i]->{$coluna->nome},null,'d');
					$campo->input->atributo = $coluna->id;				
				break;
				// Is Null e Is Empty
				case "30":
					$campo->label->add(utf8charset($coluna->descricao,7));
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

					$sim->add(utf8charset("Sim"));
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
					$nao->add(utf8charset("Não"));
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
					$campo->label->add(utf8charset($coluna->descricao,7));
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
					if (!empty($this->dados)) $campo->input->value = utf8charset($this->dados[$i]->{$coluna->nome},null,'d');
					$campo->input->atributo = $coluna->id;
				break;	
			}
			
			// Legenda
			if ($coluna->legenda!=""){
				$legenda = tdClass::Criar("small");
				$legenda->add(' <small>( ' . utf8charset($coluna->legenda,7) . ' )</small> ');
				if (isset($label)){
					$label->add($legenda);
				}else if(isset($campo->label)){	
					$campo->label->add($legenda);
				}else if(isset($input->label)){
					$input->label->add($legenda);
				}
			}
			$coluna = tdClass::Criar("div");
			if ($this->ncolunas >0){
				$coluna->class = "coluna";
				$coluna->data_ncolunas = $this->ncolunas;
			}	
			$coluna->add($campo);	
			$this->linhacampos->add($coluna);
		}
		if ($retorno){
			return $this->linhacampos;
		}else{
			$this->fieldset->add($this->linhacampos);
		}
	}
	/*
		* M�todo Dados 
	    * Data de Criacao: 12/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	
		Seta o valor da propriedade $dados, utilizado para preencher as informa��es na hora da montagem do formul�rio
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
		* M�todo CamposObrigatorio 
	    * Data de Criacao: 20/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	
		Retorna os campos que s�o obrigatorios
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
			$sqlAttr->addFiltro(COLUNA_ENTIDADE,"=",$entRel->contexto->id);			
			$dataset = tdClass::Criar("repositorio",array(ATRIBUTO));
			$u = $dataset->quantia($sqlAttr);
			foreach ($dataset->carregar($sqlAttr) as $d){				
				//$retorno .= $d->nome . '=' . '"+$("#'.$d->nome.'","'.$contextoRel.$entRel->contexto->nome.'").val()' . ($i!=$u?'+"~':'+"~id="+$("#id","'.$contextoRel.$entRel->contexto->nome.'").val() + "^" + ($("#id","'.$contextoRel.$entRel->contexto->nome.'").val()==""?"I":"U") );');				
				$i++;
			}
		}
		//echo "<br /><br /><br />" . "RETORNO >>> " . $retorno . " <<<< <br /><br /><br />";
		return $retorno;
	}
	public function mostrar(){
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
			$atributo = tdClass::Criar("persistent",array(ATRIBUTO,$rel->{ATRIBUTO}));
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
					$retorno .= '$("#'.$coluna->nome.'",".crud-contexto-add-'.tdClass::Criar("persistent",array(ENTIDADE,$coluna->{ENTIDADE}))->contexto->nome.'").val('.$coluna->inicializacao.');';
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
		$sql->add(tdClass::Criar("sqlfiltro",array("tipo",'<>',3))); // N�o seleciona relacionamento de Generaliza��o
		
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
					$dados_array[$linhaAttr["nome"]] = utf8charset($linha[$linhaAttr["nome"]]);
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
				$dados .= ($dados=="")?"id=".$dado->id:"�id=".$dado->id;
				foreach($campos as $c){
					foreach($c as $key => $val){						
						if (is_string($key)){							
							if ($key=="tipohtml"){								
								$htmltipo = $val;
							}
							if ($key != "tipohtml"){
								$dados .= "�" . $val . "=" . getHTMLTipoFormato($htmltipo,$dado->{$val});
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
	public static function isNumberDataType($atributo){
		$numberdatatype = false;
		switch(getDataType($atributo)){
			case 'int': case 'smallint': case 'tinyint': case 'mediumint': case 'bigint': 
			case 'decimal': case 'float': case 'double': case 'real':
				$numberdatatype = true;
			break;
		}
		return $numberdatatype;
	}
}