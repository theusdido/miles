<?php
/*
	1  - Associação - Agregação (1:1)
	6  - Associação - Agregação (1:N)
	5  - Associação - Agregação (N:N)
	7  - Associação - Composição (1:1)
	2  - Associação - Composição (1:N)
	10 - Associação - Composição (N:N)
	3  - Generalização/Especialização (1:1)
	8  - Generalização/Especialização (1:N)
	9  - Generalização/Especialização Múltipla (1:1)
	4  - Dependência (1:1)							
*/

$panel 						= ""; # Controla a Flag de Generalização/Especialização
$panelM 					= ""; # Controla a Flag de Generalização/Especialização Múltipla
$abaArray 					= array(); # Arrays com as abas que fazem relacionamento
$contEntGen 				= 0; # Conta a quantidade de entidades de generalização
$contEntGenM 				= 0; # Conta a quantidade de entidades de generalização multipla
$atributo_relacionamento 	= ""; # Atributo que implementa o relacionamento
$entidade_pai_relacionamento= ""; # Entidade Pai do Relacionamento
$fp_form					= "fp"; # Se o campo faz parte do formulário principal
$composicao					= ""; # Adiciona uma composição no CRUD JS
$gradededadosGeneralizacao 	= ""; # Cria a grade de dados nas entidades de fazem relacionamento
$atributoGeneralizacaoPai   = ""; # Atributo que indica qual campo faz relacionamento na entidade pai da generalização
$conteudo					= "";

// Abre Relacionamentos
$sqlRel 			= tdClass::Criar("sqlcriterio");
$relacionamentos 	= tdClass::Criar("repositorio",array(RELACIONAMENTO))->carregar($sqlRel);
if ($relacionamentos){
	foreach($relacionamentos as $rel){
		if ($rel->pai == $entidade->contexto->id){
			switch($rel->tipo){
				case 1:			
					include 'agregacao11.php';
				break;
				case 2:
					include 'composicao1N.php';
				break;
				case 3:
					include 'generalizacao11.php';
				break;
				case 5:
					include 'agregacaoNN.php';
				break;
				case 6:
					include 'agregacao1N.php';
				break;
				case 7:
					include 'composicao11.php';
				break;
				case 8:
					include 'generalizacao1N.php';
				break;
				case 9:
					include 'generalizacaoMultipla11.php';
				break;
				case 10:
					include 'composicaoNN.php';
				break;
			}
		}else if($rel->filho == $entidade->contexto->id && !$isprincipal && $rel->pai == tdc::r('entidadepai')){
			$crudAdd->data_entidadepai		= $rel->pai;
			switch($rel->tipo){
				case 1:				
				break;
				case 2:
					$btnNovoType 					= "btn-default";
					$btnNovoLabel 					= " Adicionar";
					$btnSalvarType 					= "btn-default";
					$atributo_relacionamento 		= tdClass::Criar("persistent",array(ATRIBUTO,$rel->atributo))->contexto->nome;
					$entidade_pai_relacionamento	= tdClass::Criar("persistent",array(ENTIDADE,$rel->pai))->contexto->nome;
					$fp_form 						= "";
					$composicao 					=  'composicao['.$rel->filho.'] = {descricao:"'.$rel->descricao.'",qtde:0};';
				break;
				case 3:
					$attr_pai_gen = tdClass::Criar("persistent",array(ENTIDADE,$rel->pai))->contexto->atributogeneralizacao;
					if ($attr_pai_gen == null && $attr_pai_gen <= 0){
						$msg = "Atributo de generaliza&ccedil;&atilde;o n&atilde;o encontrado na entidade";
						$alerta = tdClass::Criar("alert",array($msg));
						$alerta->type = "alert-danger";
						$bloco->add($alerta);
					}else{
						$atributo_relacionamento = tdClass::Criar("persistent",array(ATRIBUTO,$attr_pai_gen))->contexto->nome;
						$contEntGen++;
					}
				break;
				case 5:
					$btnNovoType 	= "btn-default";
					$btnNovoLabel 	= " Adicionar";
					$btnSalvarType 	= "btn-default";
					$entidade_pai_relacionamento= tdClass::Criar("persistent",array(ENTIDADE,$rel->pai))->contexto->id;
					$fp_form = "";
				break;
				case 6:
					$btnNovoType 					= "btn-default";
					$btnNovoLabel 					= " Adicionar";
					$btnSalvarType 					= "btn-default";
					$atributo_relacionamento 		= tdClass::Criar("persistent",array(ATRIBUTO,$rel->atributo))->contexto->nome;
					$entidade_pai_relacionamento	= tdClass::Criar("persistent",array(ENTIDADE,$rel->pai))->contexto->nome;
					$fp_form 						= "";
				break;
				case 7:

				break;
				case 8:
					$btnNovoType 	= "btn-default";
					$btnNovoLabel 	= " Adicionar";
					$btnSalvarType 	= "btn-default";
					$fp_form = "";
					
					$entidade_pai_relacionamento = tdClass::Criar("persistent",array(ENTIDADE,$rel->pai))->contexto->nome;
					
					$attr_pai_gen = tdClass::Criar("persistent",array(ENTIDADE,$rel->pai))->contexto->atributogeneralizacao;
					$atributoGeneralizacaoPai = tdClass::Criar("persistent",array(ATRIBUTO,$attr_pai_gen))->contexto->nome;
					$attr_filho_gen = tdClass::Criar("persistent",array(ENTIDADE,$rel->filho))->contexto->atributogeneralizacao;
					$atributo_relacionamento = tdClass::Criar("persistent",array(ATRIBUTO,$attr_filho_gen))->contexto->nome;
					$contEntGen++;
				break;
				case 9:
					$attr_pai_gen = tdClass::Criar("persistent",array(ENTIDADE,$rel->pai))->contexto->atributogeneralizacao;				
					$atributo_relacionamento = tdClass::Criar("persistent",array(ATRIBUTO,$attr_pai_gen))->contexto->nome;
					$contEntGen++;			
				break;
				case 10:
					$btnNovoType 	= "btn-default";
					$btnNovoLabel 	= " Adicionar";
					$btnSalvarType 	= "btn-default";
					$fp_form = "";
					$entidade_pai_relacionamento = tdClass::Criar("persistent",array(ENTIDADE,$rel->pai))->contexto->nome;			
				break;
			}
		}
	}
}

$divPainelRelacionamento = tdc::o("div");
$divPainelRelacionamento->class= "row-fluid div-painel-relacionamento";

if ($panel != ""){
	// Adiciona flag generalização
	$panel_body->add($select);
	$panel->add($panel_head,$panel_body);
	$panel->class = "col-md-6";
	$divPainelRelacionamento->add($panel);
}
if ($panelM != ""){
	// Adiciona flag generalização
	$panelM_body->add($selectM);
	$panelM->add($panelM_head,$panelM_body);
	$panelM->class = "col-md-6";
	$divPainelRelacionamento->add($panelM);
}

if (($panel != "") || ($panelM != ""))
	$crudAdd->add($divPainelRelacionamento);