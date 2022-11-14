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
			$campo->label->add(tdc::utf8($descricao));
			$campo->label->for = $id;
			$campo->label->class = "control-label";
			$campo->input->id = $id;
			$campo->input->name = $nome;
			$campo->input->class = "form-control input-sm texto-curto";
			if (!empty($valor)) $campo->input->value = tdc::utf8($valor);
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
			$campo->label->add(tdc::utf8($descricao));
			$campo->label->for = $id;
			$campo->label->class = "control-label";
			$campo->input->id = $id;
			$campo->input->name = $nome;
			$campo->input->class = "form-control input-sm texto-medio";
			if (!empty($valor)) $campo->input->value = tdc::utf8($valor);	
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
			$campo->label->add(tdc::utf8($descricao));
			$campo->label->for = $id;
			$campo->label->class = "control-label";
			$campo->input->id = $id;
			$campo->input->name = $nome;
			$campo->input->class = "form-control input-sm texto-longo";
			if (!empty($valor)) $campo->input->value = tdc::utf8($valor);
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
			$campo->label->add(tdc::utf8($descricao));
			$campo->label->for 		= $id;
			$campo->label->class 	= "control-label";
			$campo->input->id 		= $id;
			$campo->input->name 	= $nome;
			$campo->input->class 	= "form-control input-sm formato-cpf";
			if (!empty($valor)) $campo->input->value = tdc::utf8($valor);
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
			$campo 			= tdClass::Criar("div");
			$campo->class 	= "form-group";

			$textarea 					= tdClass::Criar("textarea");
			$textarea->id 				= $id;
			$textarea->name 			= $nome;
			$textarea->class 			= "form-control";
			$textarea->data_entidade 	= $coluna_entidade;

			$label 						= tdClass::Criar("label");
			$label->for 				= $id;
			$label->class 				= "control-label";
			$label->add(tdc::utf8($descricao));

			if (!empty($valor)) $textarea->add(tdc::utf8($valor));
			$campo->add($label,$textarea);
			return $campo;
		}
		/*
			* Método NumeroProcessoJudicial
			* Data de Criacao: 09/05/2015
			* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

			Campo NumeroProcessoJudicial
		*/
		public static function NumeroProcessoJudicial($id,$descricao,$valor=null,$coluna_entidade = 0,$coluna = 0){
			$campo = tdClass::Criar("labeledit");	
			$campo->label->add(tdc::utf8($descricao));
			$campo->label->for = $id;
			$campo->label->class = "control-label";
			$campo->input->id = $id;
			$campo->input->name = $id;
			$campo->input->data_entidade = $coluna_entidade;
			$campo->input->class = "form-control input-sm formato-numeroprocessojudicial";
			if (!empty($valor)) $campo->input->value = tdc::utf8($valor);
			$jsCampo = tdClass::Criar("script");
			if ($coluna==0) $campo->input->required = "true";
			return $campo;	
		}
		public static function arquivo(){
			$campo = tdClass::Criar("div");
			$label = tdClass::Criar("label");
			$label->add(tdc::utf8($descricao));
			$campo->label->for = $id;
			$campo->label->class = "control-label";
			$campo->input->id = $id;
			$campo->input->name = $nome;
			$campo->input->class = "form-control input-sm texto-longo";
			if (!empty($valor)) $campo->input->value = tdc::utf8($valor);
			return $campo;		
		}
		public static function filtro($nome,$descricao,$chaveestrangeira,$obrigatorio=null,$entidade="",$gd="",$modalName=""){

			if ($modalName == ""){
				$modalName = "modal-" . $nome;
			}
			$entidadePK = tdClass::Criar("persistent",array(ENTIDADE,$chaveestrangeira));
			if ($entidadePK->contexto == NULL){
				return self::MsgFieldError('Chave estrangeira não encontrada no campo '.$nome.' [ '.$descricao.' ] !');
			}
			
			$campo 					= tdClass::Criar("div");
			$campo->class 			= "filtro-pesquisa form-group";
			$campo->data_modalname 	= $modalName;
			
			$label 					= tdClass::Criar("label");
			$label->for 			= $nome;
			$label->class 			= "control-label";
			$label->add(tdc::utf8($descricao));

			$input_group 			= tdClass::Criar("div");
			$input_group->class 	= "input-group";

			$input_group_btn 		= tdClass::Criar("span");
			$input_group_btn->class = "input-group-btn";

			$button 					= tdClass::Criar("button");
			$button->class 				= "btn btn-default botao-filtro";
			$button->id 				= "pesquisa-" . $nome;
			$button->name 				= $nome;
			$button->data_fk 			= $chaveestrangeira;
			$button->data_entidade 		= $entidade;
			$span_icon_procura 			= tdClass::Criar("span");
			$span_icon_procura->class 	= "fas fa-search";
			$button->add($span_icon_procura);
			
			$termo 				= tdClass::Criar("input");
			$termo->type 		= "text";
			$termo->class 		= "form-control termo-filtro {$gd}";
			$termo->id 			= $nome;
			$termo->name 		= $nome;
			$termo->data_fk 	= $entidadePK->contexto->nome;
			if ($obrigatorio != null){
				$termo->required = "true";
				$label->add($obrigatorio);
			}
			$termo->data_entidade 	= $entidade;
			$termo->atributo 		= getAtributoId($entidade,$nome,Transacao::Get());

			$descricao_resultado 				= tdClass::Criar("input");
			$descricao_resultado->type 			= "text";
			$descricao_resultado->readonly 		= "true";
			$descricao_resultado->class 		= "form-control descricao-filtro";
			$descricao_resultado->id 			= "descricao-".$nome;
			$descricao_resultado->name 			= "descricao-".$nome;
			$descricao_resultado->data_entidade = $entidade;

			$icon_add				= tdc::o("i");
			$icon_add->class 		= "fas fa-plus";

			$button_add 			= tdc::o("button");
			$button_add->type 		= "button";
			$button_add->class 		= "btn btn-default btn-add-emexecucao";
			$button_add->add($icon_add);

			$input_group_btn->add($button,$button_add);
			$input_group->add($termo,$descricao_resultado,$input_group_btn);
					
			$modal 					= tdClass::Criar("modal");
			$modal->nome 			= $modalName;
			$modal->tamanho 		= "modal-lg";
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
			$label->add(tdc::utf8($descricao));
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
			$label->add(tdc::utf8($descricao));
			$select = tdClass::Criar("select");
			$select->id = $id;
			$select->name = $nome;
			$select->class = "form-control";

			switch(gettype($opcoes)){
				case  'array':
					foreach($opcoes as $op){
						$select->add($op);
					}
				break;
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
			$label->add(tdc::utf8($descricao));

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
			$campo->label->add(tdc::utf8($descricao));
			$campo->label->for = $id;
			$campo->label->class = "control-label";
			$campo->input->id = $id;
			$campo->input->class = "form-control input-sm formato-numerodecimal";
			if (!empty($valor)) $campo->input->value = tdc::utf8($valor);
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
			$campo->label->add(tdc::utf8($descricao));
			$campo->label->for = $id;
			$campo->label->class = "control-label";
			$campo->input->id = $id;
			$campo->input->class = "form-control input-sm formato-moeda";
			if (!empty($valor)) $campo->input->value = tdc::utf8($valor);
			return $campo;
		}	

		/*  
			* Método Senha
			* Data de Criacao: 10/12/2021
			* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

			Campo Senha
		*/	
		public static function Senha($id,$nome,$descricao,$valor=null){
			$campo = tdClass::Criar("labeledit");
			$campo->label->add(tdc::utf8($descricao));
			$campo->label->for = $id;
			$campo->label->class = "control-label";
			$campo->input->id = $id;
			$campo->input->name = $nome;
			$campo->input->type = 'password';
			$campo->input->class = "form-control input-sm";
			if (!empty($valor)) $campo->input->value = tdc::utf8($valor);
			return $campo;
		}
		/*  
			* Método MsgFieldError
			* Data de Criacao: 07/11/2022
			* Autor @theusdido

			Exibe uma mensagem de erro na geração dos campos
		*/	
		private static function MsgFieldError($msg){
			$alert 				= tdc::o('alert');
			$alert->alinhar 		= 'left';
			$alert->type			= 'alert-danger';
			$alert->exibirfechar 	= false;
			$alert->add($msg);
			return $alert;
		}		
	}
