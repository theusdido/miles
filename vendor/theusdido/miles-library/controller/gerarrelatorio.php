<?php
	$id = tdc::r('id');
	if ($id == ''){
		echo 'Parametro "ID" não foi enviado.';
		exit;
	}

	$pathfilerelatorio 	= PATH_FILES_RELATORIO . $id;

	// Cria diretório
	if (!file_exists($pathfilerelatorio)){
		tdFile::mkdir($pathfilerelatorio);
	}
	
	// Seta Cookie Diretório
	setCookie("path_files_relatorio",$pathfilerelatorio . "/");
	
	$relatorio 	= tdClass::Criar("persistent",array(RELATORIO,$id))->contexto;
	$entidade 	= tdClass::Criar("persistent",array(ENTIDADE,$relatorio->entidade))->contexto;
	
	// Campo Entidade Principal
	$entidadePrincipalID 			= tdClass::Criar("input");
	$entidadePrincipalID->id 		= "entidadeprincipalid";
	$entidadePrincipalID->name 		= "entidadeprincipalid";
	$entidadePrincipalID->type 		= "hidden";
	$entidadePrincipalID->value 	= $entidade->id;
	$entidadePrincipalID->mostrar();

	// Campo Entidade Principal
	$funcionalidadeTD 				= tdClass::Criar("input");
	$funcionalidadeTD->id 			= "funcionalidadetd";
	$funcionalidadeTD->name 		= "funcionalidadetd";
	$funcionalidadeTD->type 		= "hidden";
	$funcionalidadeTD->value 		= "relatorio";
	$funcionalidadeTD->mostrar();

	// Campo ID da Relatório
	$relatorioID 				= tdClass::Criar("input");
	$relatorioID->id 			= "relatorio_id";
	$relatorioID->name 			= "relatorio_id";
	$relatorioID->type 			= "hidden";
	$relatorioID->value 		= $id;
	$relatorioID->mostrar();

	$blocoTitulo = tdClass::Criar("bloco");
	$blocoTitulo->class = "col-md-12";

	// Titulo
	$titulo 			= tdClass::Criar("p");
	$titulo->class 		= "titulo-pagina";
	$titulo->add($relatorio->descricao);
	$blocoTitulo->add($titulo);
	
	$linhaTitulo 			= tdClass::Criar("div");
	$linhaTitulo->class 	= "row";
	$linhaTitulo->add($blocoTitulo);
	$linhaTitulo->mostrar();
	
	// Botão IMPRIMIR
	$btn_imprimir 			= tdClass::Criar("button");
	$btn_imprimir->class 	= "btn btn-primary b-imprimir";
	$btn_imprimir->id 		= "imprimir-relatorio";
	$span_imprimir			= tdClass::Criar("span");
	$span_imprimir->class 	= "fas fa-print";	
	$btn_imprimir->add($span_imprimir," Imprimir");

	// Seleciona os campos do FILTRO da RELATORIO
	$sql 					= tdClass::Criar("sqlcriterio");
	$sql->add(tdClass::Criar("sqlfiltro",array('relatorio','=',$relatorio->id)));
	$sql->setPropriedade("order","ordem ASC");
	$dataset 				= tdClass::Criar("repositorio",array(FILTRORELATORIO))->carregar($sql);

	global $arrayCamposAtributos;
	$arrayCamposAtributos 	= array();
	$atributo 				= "";
	$i 						= 1;
	foreach ($dataset as $ftRelatorio){
		$atributo = tdClass::Criar("persistent",array(ATRIBUTO,(int)$ftRelatorio->atributo))->contexto;
		if ($ftRelatorio->operador == ".."){
			$objinicial 			= new stdclass();
			$objinicial->nome 		= $atributo->nome . "-inicial";
			$objinicial->legenda 	= "Inicial";
			array_push($arrayCamposAtributos,addCampoAtributo($objinicial,$atributo));

			$objfinal 				= new stdclass();
			$objfinal->nome 		= $atributo->nome . "-final";
			$objfinal->legenda 		= "Final";
			array_push($arrayCamposAtributos,addCampoAtributo($objfinal,$atributo));
		}else{
			$obj 					= new stdclass();
			$obj->legenda 			= $ftRelatorio->legenda;
			array_push($arrayCamposAtributos,addCampoAtributo($obj,$atributo));
		}
		$i++;
	}

	function addCampoAtributo($obj,$atributo){
		$obj->id 					= $atributo->id;
		$obj->entidade 				= $atributo->entidade;
		$obj->nome 					= isset($obj->nome)?$obj->nome:$atributo->nome;
		$obj->descricao 			= $atributo->descricao;
		$obj->tipo 					= $atributo->tipo;
		$obj->tamanho 				= $atributo->tamanho;
		$obj->nulo 					= $atributo->nulo;
		$obj->omissao 				= $atributo->omissao;
		$obj->collection 			= $atributo->collection;
		$obj->atributos 			= $atributo->atributos;
		$obj->indice 				= $atributo->indice;
		$obj->autoincrement 		= $atributo->autoincrement;
		$obj->comentario 			= $atributo->comentario;
		$obj->exibirgradededados 	= $atributo->exibirgradededados;
		$obj->chaveestrangeira 		= $atributo->chaveestrangeira;
		$obj->tipohtml 				= $atributo->tipohtml;
		$obj->dataretroativa 		= $atributo->dataretroativa;
		$obj->ordem 				= $atributo->ordem;
		$obj->inicializacao 		= $atributo->inicializacao;
		$obj->readonly 				= $atributo->readonly;
		$obj->exibirpesquisa 		= $atributo->exibirpesquisa;
		$obj->tipoinicializacao 	= $atributo->tipoinicializacao;
		$obj->atributodependencia 	= $atributo->atributodependencia;
		$obj->labelzerocheckbox 	= $atributo->labelzerocheckbox;
		$obj->labelumcheckbox 		= $atributo->labelumcheckbox;
		$obj->desabilitar 			= $atributo->desabilitar;

		return $obj;
	}
	
	// Formulário Principal ( Personalizado )
	$form 					= tdClass::Criar("tdformulario");
	$form->id 				= "form-relatorio";
	$form->ncolunas 		= 3;
	$form->exibirid 		= true;
	$form->funcionalidade 	= "relatorio";

	if ($arrayCamposAtributos){
		$form->camposHTML($arrayCamposAtributos);
	}
	
	$blocoForm 				= tdClass::Criar("div");
	$blocoForm->class 		= "col-md-12";
	$blocoForm->id 			= "crud-contexto-add-" . $entidade->nome;
	$blocoForm->add($btn_imprimir,$form);
	
	$linhaForm 				= tdClass::Criar("div");
	$linhaForm->class 		= "row";
	$linhaForm->add($blocoForm);
	$linhaForm->mostrar();

	$divisao = tdClass::Criar("hr");
	
	$blocoDivisao 			= tdClass::Criar("div");
	$blocoDivisao->class 	= "col-md-12";
	$blocoDivisao->add($divisao);

	$linhaDivisao 			= tdClass::Criar("div");
	$linhaDivisao->class 	= "row";
	$linhaDivisao->add($blocoDivisao);
	$linhaDivisao->mostrar();

	
	// Contexto LISTA ( Grade de Dados )
	$contextoListarID 		= "crud-contexto-listar-" . $entidade->nome;
	$contextoListar 		= tdClass::Criar("div");
	$contextoListar->id 	= $contextoListarID;
	
	$blocoGrade 			= tdClass::Criar("div");
	$blocoGrade->class 		= "col-md-12";
	$blocoGrade->add($contextoListar);

	$linhaGrade 			= tdClass::Criar("div");
	$linhaGrade->class 		= "row";
	$linhaGrade->add($blocoGrade);
	$linhaGrade->mostrar();