<?php
// Seleciona os dados da ENTIDADE
$sql = tdClass::Criar("sqlcriterio");
$sql->add(tdClass::Criar("sqlfiltro",array(ENTIDADE,'=',$entidade->contexto->id)));
$sql->setPropriedade("order",'ordem');

// Carrega os dados da ENTIDADE
$dataset = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql);

// Controle de Abas
$sql = tdClass::Criar("sqlcriterio");
$sql->add(tdClass::Criar("sqlfiltro",array(ENTIDADE,'=',$entidade->contexto->id)));

if ($isprincipal && isRelacionamentoPai($entidade->contexto->id) && tdc::c(ABAS,$sql) <= 0){
	$atributos = array();
	foreach(tdc::d(ATRIBUTO,$sql) as $a){
		array_push($atributos,$a->id);
	}
	criarAba($conn,$entidade->contexto->id,"Capa",implode(",",$atributos));
}

$dataset_abas = tdClass::Criar("repositorio",array(ABAS));
$abas = $dataset_abas->carregar($sql);
if ($abas){
	$tipoaba = $entidade->contexto->tipoaba == '' ? 'tabs' : $entidade->contexto->tipoaba;
	$aba_html = tdClass::Criar("aba");
	$aba_html->setTipo($tipoaba . " nav-stacked");
	$aba_html->nome = $entidade->contexto->nome;
	$aba_html->contexto = $contexto;

	// Abas Personalizadas
	foreach($abas as $aba){
		$form 		= getFormPadrao($entidade->contexto->ncolunas,$fp_form);
		$camposOBJ 	= $aba_html->camposOBJ($aba->atributos);
		$aba_html->addItem($aba->descricao,$form->camposHTML($camposOBJ,true),"",geraSenha());
	}

	// Abas dos Relacionamentos
	if (isset($abaArray)){
		if (sizeof($abaArray) > 0){
			foreach($abaArray as $abasrel){
				$aba_html->addItem($abasrel[0],$abasrel[1],$abasrel[2],geraSenha());
			}
		}
	}
	$crudAdd->add($aba_html);
}else{
	if ($dataset){		
		$form = getFormPadrao($entidade->contexto->ncolunas,$fp_form);
		$form->camposHTML($dataset);
		$crudAdd->add($form);
	}
}

// Mensagem de Salvo
$msgRetornoContexto = ".msg-retorno-form-".$entidade->contexto->nome;
$msgSalvar = 'abrirAlerta("Salvo com Sucesso","alert-success","'.$msgRetornoContexto.'");';
$excluiFormGenOculto = "";

if ($contEntGen > 0){
	$excluiFormGenOculto = '
		var entidadeGeneralizacao = $(".select-flag-generalizacao option:selected").data("entidade-nome");
		$(".div-relacionamento-generalizacao").each(function(){
			if ($(this).attr("id").replace("drv-","") != entidadeGeneralizacao){
				$(this).remove();
			}
		});
		$(".select-flag-generalizacao").attr("disabled",true);
	';
}

function getFormPadrao($ncolunas = 1,$fpForm){
	// Formulário Principal ( Personalizado )
	$form = tdClass::Criar("tdformulario");
	$form->ncolunas = $ncolunas;
	$form->fp = $fpForm;
	$form->funcionalidade = "cadastro";
	
	return $form;
}

$iframeEmExecucao = tdc::o("iframe");
$iframeEmExecucao->border = 0;
$iframeEmExecucao->frameborder = 0;
$iframeEmExecucao->width = "100%";
$iframeEmExecucao->height = "200";
$iframeEmExecucao->scrolling = "no";

$modalAddEmExecucao = tdClass::Criar("modal");
$modalAddEmExecucao->nome = "modal-add-emexecucao";
$modalAddEmExecucao->tamanho = "modal-lg";
$modalAddEmExecucao->addHeader("",null);
$modalAddEmExecucao->addBody($iframeEmExecucao);
$modalAddEmExecucao->addFooter("");
$crudAdd->add($modalAddEmExecucao);