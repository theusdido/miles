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
	$sql->add(tdClass::Criar("sqlfiltro",array(RELATORIO,'=',$relatorio->id)));
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
	
	// JS 
	$js = tdClass::Criar("script");
	$js->add('
		var entidadeID 	= '.$entidade->id.';
		var campos 		= "";
		
		$("#imprimir-relatorio").click(function(){
			var filtros 			= "";
			var urlpersonalizada 	= "'.$relatorio->urlpersonalizada.'";

			if ($("#form-relatorio.tdform .form_campos .form-control").length >0){
				$("#form-relatorio.tdform .form_campos .form-control").each(function(){
					if ($(this).hasClass("input-sm") || $(this).hasClass("termo-filtro") || $(this).hasClass("checkbox-sn")){
						if ($(this).val() != "" && $(this).val() != undefined && $(this).val() != null){
							var atributo = $(this).attr("id");
							if ($(this).data("operador") == ".."){
								var operador = atributo.split("-")[1] == "inicial"?">=":"<=";
							}else{
								var operador = $(this).data("operador");
							}
							var tipo = $(this).data("tipo");
							
							var filtro = atributo+"^"+operador+"^"+$(this).val()+"^"+tipo;
							filtros += (filtros == ""?"":"~") + filtro;
						}
					}
				});
				$.ajax({
					url:config.urlrelatorio,
					data:{
						entidade:entidadeID,
						filtros:filtros
					},
					complete:function(){
						
						var parametros = "&entidade=" + entidadeID + "&currentproject=" + session.projeto
						+ "&filtros=" + filtros
						+ "&campos=" + campos;
						
						if (urlpersonalizada == ""){
							window.open(config.urlrelatorio +  parametros,"_blank");
						}else{
							window.open(urlpersonalizada + (urlpersonalizada.indexOf("?") >= 0?"&":"?") + "filtros=" + filtros,"_blank");
						}
					}
				});				
			}else{
				$.ajax({
					url:config.urlrelatorio,
					data:{
						entidade:entidadeID
					},
					complete:function(){
						var parametros = "&entidade=" + entidadeID + "&currentproject=" + session.projeto
						+ "&campos=" + campos;
						window.open(config.urlrelatorio +  parametros,"_blank");
					}
				});
			}
		});
		var i = 1;
		for (f in td_relatorio['.$id.'].filtros){
			var ft = td_relatorio['.$id.'].filtros[f];			
			$("#form-relatorio .form-control[atributo="+ft.atributo+"]").attr("data-operador",ft.operador);
			$("#form-relatorio .form-control[atributo="+ft.atributo+"]").attr("data-tipo",td_atributo[ft.atributo].tipo);
			i++;
		}
		for (c in td_atributo){
			if (td_atributo[c].entidade == entidadeID && td_atributo[c].exibirgradededados == 1){
				if (parseInt(td_atributo[c].chaveestrangeira) > 0){
					var fk = td_entidade[td_atributo[c].chaveestrangeira].nome;
				}else{
					var fk = "";
				}
				campos += (campos==""?"":",") + td_atributo[c].nome + "^" + td_atributo[c].descricao + "^" + fk;
			}
		}
	');
	$js->mostrar();