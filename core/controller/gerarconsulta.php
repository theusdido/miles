<?php
	$id = tdc::r('id');
	if ($id == ''){
		echo 'Parametro "ID" não foi enviado.';
		exit;
	}
<<<<<<< HEAD
=======

	$cf = getCurrentConfigFile();
	$pathfileconsulta = PATH_FILES_CONSULTA . $id;
>>>>>>> d446a0d (consulta)

	$pathfileconsulta = PATH_FILES_CONSULTA . $id;
	
	// Cria diretório
	if (!file_exists($pathfileconsulta)){
		tdFile::mkdir($pathfileconsulta);
	}
	
	// Seta Cookie Diretório
	setCookie("path_files_consulta",$pathfileconsulta . "/");
	
	$consulta = tdClass::Criar("persistent",array(CONSULTA,$id))->contexto;
	$entidade = tdClass::Criar("persistent",array(ENTIDADE,$consulta->entidade))->contexto;
	
	// Campo Entidade Principal
	$entidadePrincipalID 		= tdClass::Criar("input");
	$entidadePrincipalID->id 	= "entidadeprincipalid";
	$entidadePrincipalID->name 	= "entidadeprincipalid";
	$entidadePrincipalID->type 	= "hidden";
	$entidadePrincipalID->value = $entidade->id;
	$entidadePrincipalID->mostrar();

	// Campo Entidade Principal
	$funcionalidadeTD 			= tdClass::Criar("input");
	$funcionalidadeTD->id 		= "funcionalidadetd";
	$funcionalidadeTD->name 	= "funcionalidadetd";
	$funcionalidadeTD->type 	= "hidden";
	$funcionalidadeTD->value 	= "consulta";
	$funcionalidadeTD->mostrar();
	
<<<<<<< HEAD
	// Campo Entidade Principal
	$consultaID 				= tdClass::Criar("input");
	$consultaID->id 			= "consulta_id";
	$consultaID->name 			= "consulta_id";
	$consultaID->type 			= "hidden";
	$consultaID->value 			= $id;
	$consultaID->mostrar();
=======

	// JS Formulário
	$jsFormulario 		= tdClass::Criar("script");
	$jsFormulario->src 	= Session::Get('URL_SYSTEM') . "formulario.js";
	$jsFormulario->mostrar();

	// JS Validar
	$jsValidar 		= tdClass::Criar("script");
	$jsValidar->src = Session::Get('URL_SYSTEM') . "validar.js";
	$jsValidar->mostrar();
>>>>>>> d446a0d (consulta)

	$blocoTitulo = tdClass::Criar("bloco");
	$blocoTitulo->class = "col-md-12";

	// Titulo
	$titulo = tdClass::Criar("p");
	$titulo->class = "titulo-pagina";
	$titulo->add(utf8_encode($consulta->descricao));
	$blocoTitulo->add($titulo);
	
	$linhaTitulo = tdClass::Criar("div");
	$linhaTitulo->class = "row";
	$linhaTitulo->add($blocoTitulo);
	$linhaTitulo->mostrar();
	
	// Botão PESQUISAR
	$btn_pesquisar 				= tdClass::Criar("button");
	$btn_pesquisar->class 		= "btn btn-primary b-pesquisar";
	$btn_pesquisar->id 			= "pesquisa-consulta";
	$span_pesquisar 			= tdClass::Criar("span");
	$span_pesquisar->class 		= "fas fa-search";	
	$btn_pesquisar->add($span_pesquisar," Pesquisar");

	// Seleciona os campos do FILTRO da CONSULTA
<<<<<<< HEAD
	$sql 						= tdClass::Criar("sqlcriterio");
	$sql->add(tdClass::Criar("sqlfiltro",array("consulta",'=',$consulta->id)));
	$sql->setPropriedade("order","id ASC");
	$dataset					= tdClass::Criar("repositorio",array(FILTROCONSULTA))->carregar($sql);
=======
	$sql = tdClass::Criar("sqlcriterio");
	$sql->add(tdClass::Criar("sqlfiltro",array("consulta",'=',$consulta->id)));
	$sql->setPropriedade("order","id ASC");
	$dataset = tdClass::Criar("repositorio",array(FILTROCONSULTA))->carregar($sql);
>>>>>>> d446a0d (consulta)

	$arrayCamposAtributos = array();
	$atributo = "";
	$i =1;
	foreach ($dataset as $ftConsulta){
		$atributo = tdClass::Criar("persistent",array(ATRIBUTO,(int)$ftConsulta->atributo))->contexto;
		$obj = new stdclass();
		$obj->id 						= $atributo->id;
		$obj->entidade 					= $atributo->entidade;
		$obj->nome 						= $atributo->nome;
		$obj->descricao 				= $atributo->descricao;
		$obj->tipo 						= $atributo->tipo;
		$obj->tamanho 					= $atributo->tamanho;
		$obj->nulo						= $atributo->nulo;
		$obj->omissao 					= $atributo->omissao;
		$obj->collection 				= $atributo->collection;
		$obj->atributos 				= $atributo->atributos;
		$obj->indice 					= $atributo->indice;
		$obj->autoincrement 			= $atributo->autoincrement;
		$obj->comentario 				= $atributo->comentario;
		$obj->exibirgradededados 		= $atributo->exibirgradededados;
		$obj->chaveestrangeira 			= $atributo->chaveestrangeira;
		$obj->tipohtml 					= ($atributo->tipohtml==19?30:$atributo->tipohtml);
		$obj->dataretroativa 			= $atributo->dataretroativa;
		$obj->ordem 					= $atributo->ordem;
		$obj->inicializacao 			= $atributo->inicializacao;
		$obj->readonly 					= $atributo->readonly;
		$obj->exibirpesquisa 			= $atributo->exibirpesquisa;
		$obj->tipoinicializacao 		= $atributo->tipoinicializacao;
		$obj->atributodependencia 		= $atributo->atributodependencia;
		$obj->labelzerocheckbox 		= $atributo->labelzerocheckbox;
		$obj->labelumcheckbox 			= $atributo->labelumcheckbox;
		$obj->legenda 					= $ftConsulta->legenda;
		$obj->desabilitar 				= $atributo->desabilitar;

		array_push($arrayCamposAtributos,$obj);
		$i++;
	}
	
	// Formulário Principal ( Personalizado )
	$form 					= tdClass::Criar("tdformulario");
	$form->id 				= "form-consulta";
	$form->ncolunas 		= 3;
	$form->exibirid 		= true;
	$form->funcionalidade 	= "consulta";

	if ($arrayCamposAtributos){
		$form->camposHTML($arrayCamposAtributos);
	}
	
	$blocoForm 			= tdClass::Criar("div");
	$blocoForm->class 	= "col-md-12";
	$blocoForm->id 		= "crud-contexto-add-" . $entidade->nome;
	$blocoForm->add($btn_pesquisar,$form);
	
	$linhaForm 			= tdClass::Criar("div");
	$linhaForm->class 	= "row";
	$linhaForm->add($blocoForm);
	$linhaForm->mostrar();

	$divisao = tdClass::Criar("hr");
	
	$blocoDivisao 			= tdClass::Criar("div");
	$blocoDivisao->class	= "col-md-12";
	$blocoDivisao->add($divisao);

	$linhaDivisao 			= tdClass::Criar("div");
	$linhaDivisao->class 	= "row";
	$linhaDivisao->add($blocoDivisao);
	$linhaDivisao->mostrar();
	
	// Contexto LISTA ( Grade de Dados )
	$contextoListarID 		= "crud-contexto-listar-" . $entidade->nome;
	$contextoListar 		= tdClass::Criar("div");
	$contextoListar->id 	= $contextoListarID;
	$contextoListar->class 	= "crud-contexto-listar fp";
	
	$blocoGrade 			= tdClass::Criar("div");
	$blocoGrade->class 		= "col-md-12";
	$blocoGrade->add($contextoListar);

	$linhaGrade 			= tdClass::Criar("div");
	$linhaGrade->class		= "row";
	$linhaGrade->add($blocoGrade);
	$linhaGrade->mostrar();
<<<<<<< HEAD
=======
	
	// Consulta Filtro Inicial
	$gdFiltroInicial = "";
	$sqlCI = tdClass::Criar("sqlcriterio");
	$sqlCI->addFiltro("consulta","=",$id);
	$dsCI = tdClass::Criar("repositorio",array("td_consultafiltroinicial"))->carregar($sqlCI);
	foreach ($dsCI as $d){
		$atributoCI = tdClass::Criar("persistent",array("atributo",$d->atributo))->contexto->nome;
		$gdFiltroInicial .= 'gd.addFiltro("'.$atributoCI.'","'.$d->operador.'","'.$d->valor.'");';
	}

	// JS 
	$js = tdClass::Criar("script");
	$js->add('
		debugger;
		// Seta CK Editores para CONSULTA
		setaCkEditores(true);
		if (gradesdedados[contextoListar] == undefined){
			// Carrega a grade de dados padrão
			var gd 	= new GradeDeDados('.$consulta->entidade.');
			gd.contexto="#'.$contextoListarID.'";
		}else{
			var gd 	= gradesdedados["#'.$contextoListarID.'"];
		}
		gd.consulta 		= '.$id.';
		gd.movimentacao 	= '.$consulta->movimentacao.';
		gd.exibireditar		= '.($consulta->exibireditar?'true':'false').';
		gd.exibirexcluir	= '.($consulta->exibirexcluir?'true':'false').';
		gd.exibiremmassa	= '.($consulta->exibiremmassa?'true':'false').';		
		gd.exibirpesquisa 	= false;
		gd.setOrder("id","DESC");		
		gd.show();
		gd.clear();

		$("#form-consulta.tdform .form_campos .form-control").each(function(){
			if ($(this).prop("tagName") == "SELECT"){
				$(this).removeAttr("required");
				var atributo = $(this).attr("id").split(" ")[0];
				carregarListas($(this).data("entidade"),$(this).attr("id"),"");
			}
		});
		$(".asteriscoobrigatorio").hide();

		'.$gdFiltroInicial.'
		$("#pesquisa-consulta").click(function(){
			gd.clear();
			'.$gdFiltroInicial.'
			atribuiValoresCKEditor();
			$("#form-consulta.tdform .form_campos .form-control").each(function(){
				if ($(this).hasClass("input-sm") || $(this).hasClass("termo-filtro") || $(this).hasClass("checkbox-sn")){
					if ($(this).val() != "" && $(this).val() != undefined && $(this).val() != null){
						var operador 	= $(this).data("operador");
						var tipo 		= $(this).data("tipo");
						var atributo 	= $(this).attr("id");
						gd.addFiltro(atributo,(operador == undefined?"=":operador),$(this).val(),(tipo == undefined?"int":tipo));
					}
				}
			});
			gd.qtdeMaxRegistro = 500;
			gd.reload();
		});
		var i = 1;
		for (f in td_consulta['.$id.'].filtros){
			var ft = td_consulta['.$id.'].filtros[f];
			$("#form-consulta .form-control[atributo="+ft.atributo+"]").attr("data-operador",ft.operador);
			$("#form-consulta .form-control[atributo="+ft.atributo+"]").attr("data-tipo",td_atributo[ft.atributo].tipo);
			i++;
		}
		
		$(document).ready(function(){
			$(".checkbox-s,.checkbox-n").removeClass("active");
			$(".checkbox-s,.checkbox-n").parents(".form-group").find("input").val("");
		});
	');
	$js->mostrar();
>>>>>>> d446a0d (consulta)

	// Modal Movimentação
	$modalName 		= "modal-movimentacao";
	$modal 			= tdClass::Criar("modal");
	$modal->nome 	= $modalName;
	$modal->tamanho = "modal-lg";
	$modal->addHeader("Movimentação",null);
	$modal->addBody('');
	$modal->mostrar();