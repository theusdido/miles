<?php
	if (isset($_GET["op"])){
		if ($_GET["op"] == "salvarhistorico"){
			if ($conn = Transacao::Get()){
				foreach ($_GET["dadoshistorico"] as $dh){
					$obj = (object)$dh;
					$entidade = tdClass::Criar("persistent",array(ENTIDADE))->contexto->getOBJ($obj->entidade);
					$atributo = tdClass::Criar("persistent",array(ATRIBUTO,$obj->atributo))->contexto;
					$entidadepai = tdClass::Criar("persistent",array(ENTIDADE,$_GET["entidadepai"]))->contexto->nome;
					$identidadepai = $_GET["identidadepai"];
					$entidadeNameMov =  $obj->entidade;					
					$sql = "SELECT id FROM " .  $entidadeNameMov . " WHERE " . $entidadepai . "=" . $identidadepai;					
					$query = $conn->query($sql);
					if ($query->rowCount() <= 0){						
						
						$prox = getProxId(str_replace(PREFIXO . "_","",$entidadeNameMov),$conn);
						$sqlmov = "INSERT INTO " . $entidadeNameMov. " (id,{$atributo->nome},{$entidadepai}) VALUES ({$prox},'".Campos::Integridade($entidade->contexto->id,$atributo->nome,$obj->valor,"")."',{$identidadepai});";
					}else{
						$linha = $query->fetch();
						$sqlmov = "UPDATE "	. $entidadeNameMov . " SET {$atributo->nome} = '".Campos::Integridade($entidade->contexto->id,$atributo->nome,$obj->valor,"")."' WHERE ID = " . $linha["id"];
					}
					$conn->query($sqlmov);
				}
			}			
			Transacao::Fechar();
			exit;
		}
		if ($_GET["op"] == "salvaralterar"){
			if ($conn = Transacao::Get()){
				foreach ($_GET["dadosalterar"] as $dh){
					$obj = (object)$dh;
					$entidade = tdClass::Criar("persistent",array(ENTIDADE))->contexto->getOBJ($obj->entidade);
					$atributo = tdClass::Criar("persistent",array(ATRIBUTO,$obj->atributo))->contexto;
					$entidadepai = tdClass::Criar("persistent",array(ENTIDADE,$_GET["entidadepai"]))->contexto->nome;
					$identidadepai = $_GET["identidadepai"];
					$entidadeNameMov =  $obj->entidade;					
					$sql = "SELECT id,{$atributo->nome} FROM " .  $entidadeNameMov . " WHERE ID = " . $identidadepai;
					$query = $conn->query($sql);
					$linha = $query->fetch();
					$valorold = utf8_encode(getHTMLTipoFormato($atributo->tipohtml,$linha[$atributo->nome]));
					echo $entidade->contexto->id . "<= =>" . $atributo->nome;
					$valor = Campos::Integridade($entidade->contexto->id,$atributo->nome,$obj->valor,"");
					if ($valor == $valorold) continue;
					$sqlmov = "UPDATE "	. $entidadeNameMov . " SET {$atributo->nome} = '".$valor."' WHERE ID = " . $linha["id"];
					echo $sqlmov;
					$query = $conn->query($sqlmov);
					if ($query){
						$prox = getProxId(str_replace(PREFIXO . "_","","movimentacaohistoricoalteracao"),$conn);
						$empresa = Session::get()->empresa;
						$usuario = Session::get()->userid;
						$datahora = date("Y-m-d H:i:s");
						$observacao = utf8charset($_GET["observacao"]);
						$motivo = isset($_GET["motivo"])?$_GET["motivo"]:0;
						$sql = "
							INSERT INTO td_movimentacaohistoricoalteracao (id,observacao,td_atributo,td_empresa,td_entidade,td_entidademotivo,td_motivo,td_movimentacao,td_usuario,valor,valorold,td_projeto,datahora) VALUES 							
							({$prox},'{$observacao}',{$atributo->id},{$empresa},{$entidade->contexto->id},{$_GET["entidademotivo"]},{$motivo},{$_GET["movimentacao"]},{$usuario},'{$valor}','{$valorold}',1,'{$datahora}');
						";
						$conn->query($sql);
					}
				}
			}
			echo 1;
			Transacao::Fechar();
			exit;
		}
		if ($_GET["op"] == "salvarstatus"){
			if ($conn = Transacao::Get()){
				$motivo = isset($_GET["motivo"])?$_GET["motivo"]:0;
				$sql = "SELECT td_atributo,valor FROM td_movimentacaostatus WHERE td_movimentacao = " . $_GET["movimentacao"];
				$query = $conn->query($sql);
				while ($linha = $query->fetch()){
					
					$atributo = tdClass::Criar("persistent",array(ATRIBUTO,$linha["td_atributo"]))->contexto;
					$entidade = tdClass::Criar("persistent",array(ENTIDADE,$atributo->td_entidade))->contexto;
					
					$valor = Campos::Integridade($entidade->id,$atributo->nome,$linha["valor"],"");
					$valorold = tdClass::Criar("persistent",array($entidade->nome,$_GET["id"]))->contexto->{$atributo->nome};

					$sqlUpdate = "UPDATE {$entidade->nome} SET {$atributo->nome} = '".$valor."' WHERE ID = ".$_GET["id"].";";					
					$queryUpdate = $conn->query($sqlUpdate);					
					if ($queryUpdate){
						
						$prox = getProxId(str_replace(PREFIXO . "_","","movimentacaohistoricoalteracao"),$conn);
						$empresa = Session::get()->empresa;
						$usuario = Session::get()->userid;
						$datahora = date("Y-m-d H:i:s");
						$observacao = utf8charset($_GET["observacao"]);
						$sqlMHA = "
							INSERT INTO td_movimentacaohistoricoalteracao (id,observacao,td_atributo,td_empresa,td_entidade,td_entidademotivo,td_motivo,td_movimentacao,td_usuario,valor,valorold,td_projeto,datahora) VALUES 
							({$prox},'{$observacao}',{$atributo->id},{$empresa},{$entidade->id},{$_GET["entidademotivo"]},{$motivo},{$_GET["movimentacao"]},{$usuario},'{$valor}','{$valorold}',1,'{$datahora}');
						";

						$queryMHA = $conn->query($sqlMHA);
						if ($queryMHA){
							echo 1;
						}else{
							var_dump($conn->errorInfo());
						}
					}else{
						echo 'Erro ao atualizar status';
						var_dump($conn->erroInfo());
					}
				}
			}
			Transacao::Fechar();
			exit;
		}
		
		if ($_GET["op"] == "retorna_dados"){
			if ($conn = Transacao::Get()){
				$atributos = explode(",",$_GET["atributos"]);
				$a = 1;
				echo "[";
				foreach($atributos as $attr){
					$atributo = tdClass::Criar("persistent",array(ATRIBUTO,$attr))->contexto;
					$entidade = tdClass::Criar("persistent",array(ENTIDADE,$atributo->{ENTIDADE}))->contexto;
					$sql = "SELECT " . $atributo->nome . " FROM ".$entidade->nome." WHERE ID = " . $_GET["id"];
					$query = $conn->query($sql);
					$linha = $query->fetch();

					echo ($a==1?"":",").'{"atributo":"'.$attr.'","valor":"'.utf8_encode(getHTMLTipoFormato($atributo->tipohtml,$linha[$atributo->nome])).'"}';
					$a++;
				}
				echo "]";
			}
			exit;
		}
	}
	if (isset($_GET["id"])){
		$id = $_GET["id"];
	}else{
		echo 'Parametro "ID" não foi enviado.';
		exit;
	}
	
	$btnIdSalvar = $carregarMotivo = "";

	// CK Editor Instancias
	$_SESSION["ckEditorInstancias"] = false;

	

	$movimentacao = tdClass::Criar("persistent",array(MOVIMENTACAO,$id))->contexto;
	$entidade = tdClass::Criar("persistent",array(ENTIDADE,$movimentacao->{ENTIDADE}))->contexto;

	$cf = getCurrentConfigFile();
	$path_files_movimentacao = PATH_PROJECT . $cf["CURRENT_PROJECT"] . "/" . PATH_FILES_MOVIMENTACAO . $id;

	// Cria diretório
	if (!file_exists($path_files_movimentacao)){
		mkdir($path_files_movimentacao);
	}

	// Seta Cookie Diretório
	setCookie("path_files_movimentacao",$path_files_movimentacao . "/");

	// Campo Entidade Principal
	$entidadePrincipalID = tdClass::Criar("input");
	$entidadePrincipalID->id = "entidadeprincipalid";
	$entidadePrincipalID->name = "entidadeprincipalid";
	$entidadePrincipalID->type = "hidden";
	$entidadePrincipalID->value = $entidade->id;
	$entidadePrincipalID->mostrar();

	// Campo Entidade Principal
	$funcionalidadeTD = tdClass::Criar("input");
	$funcionalidadeTD->id = "funcionalidadetd";
	$funcionalidadeTD->name = "funcionalidadetd";
	$funcionalidadeTD->type = "hidden";
	$funcionalidadeTD->value = "movimentacao";
	$funcionalidadeTD->mostrar();

	// JS Funções
	$jsFuncoes = tdClass::Criar("script");
	$jsFuncoes->src = PATH_SYSTEM . "funcoes.js";
	$jsFuncoes->mostrar();

	// Arquivo JS Incorporado
	$jsIncorporado = tdClass::Criar("script");
	$jsIncorporado->src = $path_files_movimentacao . "/" . $entidade->nome . ".js";
	$jsIncorporado->mostrar();
	
	// JS Formulário
	$jsFormulario = tdClass::Criar("script");
	$jsFormulario->src = PATH_SYSTEM . "formulario.js";
	$jsFormulario->mostrar();

	
	// JS Validar
	$jsValidar = tdClass::Criar("script");
	$jsValidar->src = PATH_SYSTEM . "validar.js";
	$jsValidar->mostrar();

	$blocoTitulo = tdClass::Criar("bloco");
	$blocoTitulo->class = "col-md-12";
	
	// Titulo
	if ($movimentacao->exibirtitulo == 1){
		$titulo = tdClass::Criar("p");
		$titulo->class = "titulo-pagina";
		$titulo->add(utf8charset($movimentacao->descricao));
		$blocoTitulo->add($titulo);
		$linhaTitulo = tdClass::Criar("div");
		$linhaTitulo->class = "row";
		$linhaTitulo->add($blocoTitulo);
		$linhaTitulo->mostrar();
	}
	
	// Mensagem de Retorno
	$msgRetornoContexto = "msg-retorno-form-".$entidade->nome;
	$msgSalvar = 'abrirAlerta("Salvo com Sucesso","alert-success",".'.$msgRetornoContexto.'");';
	$retorno = tdClass::Criar("retorno");
	$retorno->class = $msgRetornoContexto;
	
	// Botão SALVAR
	$btn_salvar = tdClass::Criar("button");
	$btn_salvar->class = "btn btn-success b-salvar b-movimentacao";
	$span_salvar = tdClass::Criar("span");
	$span_salvar->class = "fas fa-check";	
	$btn_salvar->add($span_salvar," Salvar");	
		
	// Formulário Principal ( Personalizado )
	$form = tdClass::Criar("tdformulario");
	$form->id = "form-movimentacao";
	$form->exibirid = true;
	$form->funcionalidade = "movimentacao";
	$form->exibirlegenda = false;

	// ***** HISTORICO de MOVIMENTACAO *****/
	$sql = tdClass::Criar("sqlcriterio");
	$sql->add(tdClass::Criar("sqlfiltro",array(MOVIMENTACAO,'=',$movimentacao->id)));
	$dataset = tdClass::Criar("repositorio",array(HISTORICOMOVIMENTACAO))->carregar($sql);

	$arrayCamposAtributos = array();
	$atributo = "";
	$i =1;
	
	if (sizeof($dataset) > 0){
		foreach ($dataset as $ftMovimentacao){
			$atributo = tdClass::Criar("persistent",array(ATRIBUTO,(int)$ftMovimentacao->td_atributo))->contexto;
			$obj = new stdclass();
			$obj->id =  $atributo->id;
			$obj->td_entidade = $atributo->{ENTIDADE};
			$obj->nome = $atributo->nome;
			$obj->descricao = $atributo->descricao;
			$obj->tipo = $atributo->tipo;
			$obj->tamanho = $atributo->tamanho;
			$obj->nulo = $atributo->nulo;
			$obj->omissao = $atributo->omissao;
			$obj->collection = $atributo->collection;
			$obj->atributos = $atributo->atributos;
			$obj->indice = $atributo->indice;
			$obj->autoincrement = $atributo->autoincrement;
			$obj->comentario = $atributo->comentario;
			$obj->exibirgradededados = $atributo->exibirgradededados;
			$obj->chaveestrangeira = $atributo->chaveestrangeira;
			$obj->tipohtml = $atributo->tipohtml;
			$obj->dataretroativa = $atributo->dataretroativa;
			$obj->ordem = $atributo->ordem;
			$obj->inicializacao = $atributo->inicializacao;
			$obj->readonly = false;
			$obj->exibirpesquisa = $atributo->exibirpesquisa;
			$obj->tipoinicializacao = $atributo->tipoinicializacao;
			$obj->atributodependencia = $atributo->atributodependencia;
			$obj->labelzerocheckbox = $atributo->labelzerocheckbox;
			$obj->labelumcheckbox = $atributo->labelumcheckbox;
			$obj->legenda = $ftMovimentacao->legenda;

			array_push($arrayCamposAtributos,$obj);
			$i++;
		}

		$form->ncolunas = 3;
		
		if ($arrayCamposAtributos){
			$form->camposHTML($arrayCamposAtributos);
		}

		$btnIdSalvar = "salvar-movimentacao-historico";

		$js = tdClass::Criar("script");
		$js->add('
			$("#salvar-movimentacao-historico").click(function(){
				atribuiValoresCKEditor();
				var dadosHistorico = [];
				$("#form-movimentacao").find(".form-control[data-entidade]").each(function(){
					if ($(this).attr("id") != "" && $(this).attr("atributo") != undefined){
						dadosHistorico.push({
							entidade:$(this).data("entidade"),
							atributo:$(this).attr("atributo"),
							valor:$(this).val()
						});
					}
				});
				$.ajax({
					url:"index.php?controller=gerarmovimentacao",
					data:{
						op:"salvarhistorico",
						dadoshistorico:dadosHistorico,
						entidadepai:getCookie("entidademovdados"),
						identidadepai:getCookie("idmovdados")
					},
					complete:function(){
						salvarStatus();
					}
				});
			});
		');
		$js->mostrar();
	}
	// ***** HISTORICO de MOVIMENTACAO *****/
	
	// ***** ALTERAR MOVIMENTACAO *****/
	$sql = tdClass::Criar("sqlcriterio");
	$sql->add(tdClass::Criar("sqlfiltro",array(MOVIMENTACAO,'=',$movimentacao->id)));
	$dataset = tdClass::Criar("repositorio",array(ALTERARMOVIMENTACAO))->carregar($sql);

	$arrayCamposAtributos = array();
	$atributo = "";
	$atributosID = "";
	if (sizeof($dataset) > 0){
		foreach ($dataset as $ftMovimentacao){
			$atributo = tdClass::Criar("persistent",array(ATRIBUTO,(int)$ftMovimentacao->td_atributo))->contexto;
			$atributosID .= ($atributosID == ""?"":",") . $atributo->id;
			$obj = new stdclass();
			$obj->id =  $atributo->id;
			$obj->td_entidade = $atributo->{ENTIDADE};
			$obj->nome = $atributo->nome;
			$obj->descricao = $atributo->descricao;
			$obj->tipo = $atributo->tipo;
			$obj->tamanho = $atributo->tamanho;
			$obj->nulo = $atributo->nulo;
			$obj->omissao = $atributo->omissao;
			$obj->collection = $atributo->collection;
			$obj->atributos = $atributo->atributos;
			$obj->indice = $atributo->indice;
			$obj->autoincrement = $atributo->autoincrement;
			$obj->comentario = $atributo->comentario;
			$obj->exibirgradededados = $atributo->exibirgradededados;
			$obj->chaveestrangeira = $atributo->chaveestrangeira;
			$obj->tipohtml = $atributo->tipohtml;
			$obj->dataretroativa = $atributo->dataretroativa;
			$obj->ordem = $atributo->ordem;
			$obj->inicializacao = $atributo->inicializacao;
			$obj->readonly = $atributo->readonly;
			$obj->exibirpesquisa = $atributo->exibirpesquisa;
			$obj->tipoinicializacao = $atributo->tipoinicializacao;
			$obj->atributodependencia = $atributo->atributodependencia;
			$obj->labelzerocheckbox = $atributo->labelzerocheckbox;
			$obj->labelumcheckbox = $atributo->labelumcheckbox;
			$obj->legenda = "Novo";
			
			array_push($arrayCamposAtributos,$obj);

			// Campo Valor Antigo
			$obj = new stdclass();
			$obj->id =  $atributo->id;
			$obj->td_entidade = $atributo->{ENTIDADE};
			$obj->nome = $atributo->nome . "-old";
			$obj->descricao = $atributo->descricao;
			$obj->tipo = $atributo->tipo;
			$obj->tamanho = $atributo->tamanho;
			$obj->nulo = $atributo->nulo;
			$obj->omissao = $atributo->omissao;
			$obj->collection = $atributo->collection;
			$obj->atributos = $atributo->atributos;
			$obj->indice = $atributo->indice;
			$obj->autoincrement = $atributo->autoincrement;
			$obj->comentario = $atributo->comentario;
			$obj->exibirgradededados = $atributo->exibirgradededados;
			$obj->chaveestrangeira = $atributo->chaveestrangeira;
			$obj->tipohtml = $atributo->tipohtml;
			$obj->dataretroativa = $atributo->dataretroativa;
			$obj->ordem = $atributo->ordem;
			$obj->inicializacao = $atributo->inicializacao;
			$obj->readonly = 1;
			$obj->exibirpesquisa = $atributo->exibirpesquisa;
			$obj->tipoinicializacao = $atributo->tipoinicializacao;
			$obj->atributodependencia = $atributo->atributodependencia;
			$obj->labelzerocheckbox = $atributo->labelzerocheckbox;
			$obj->labelumcheckbox = $atributo->labelumcheckbox;
			$obj->legenda = "Antigo";
			
			array_push($arrayCamposAtributos,$obj);
		}
		
		$form->ncolunas = 2;
		
		if ($arrayCamposAtributos){
			$form->camposHTML($arrayCamposAtributos);
		}

		$btnIdSalvar = "salvar-movimentacao-alterar";

		$js = tdClass::Criar("script");
		$js->add('
			$("#salvar-movimentacao-historico").click(function(){
				$.ajax({
					url:"index.php?controller=gerarmovimentacao",
					data:{
						op:"salvarstatus",
						movimentacao:getCookie("movimentacaoselecionada"),
						id:getCookie("idmovdados")
					}
				});
				atribuiValoresCKEditor();
				var dadosHistorico = [];
				$("#form-movimentacao").find(".form-control[data-entidade]").each(function(){
					if ($(this).attr("id") != "" && $(this).attr("atributo") != undefined){
						dadosHistorico.push({
							entidade:$(this).data("entidade"),
							atributo:$(this).attr("atributo"),
							valor:$(this).val()
						});
					}
				});
				$.ajax({
					url:"index.php?controller=gerarmovimentacao",
					data:{
						op:"salvarhistorico",
						dadoshistorico:dadosHistorico,
						entidadepai:getCookie("entidademovdados"),
						identidadepai:getCookie("idmovdados")
					}
				});
			});

			$("#form-movimentacao .form-group .form-control").each(function(){
				if ($(this).prop("tagName") == "SELECT"){
					carregarListas($(this).attr("id"),$(this).attr("atributo"),"#form-movimentacao","");
				}
				$("#" + $(this).attr("id") + "-old").attr("disabled","disabled");
			});
			setTimeout(function(){
				$.ajax({
					url:"index.php?controller=gerarmovimentacao",
					data:{
						op:"retorna_dados",
						atributos:"'.$atributosID.'",
						id:getCookie("idmovdados")
					},
					complete:function(ret){
						var retorno = JSON.parse(ret.responseText);
						for(a in retorno){
							var atributo = td_atributo[retorno[a].atributo];
							console.log("campos => #form-movimentacao #" + atributo.nome + "-old,#form-movimentacao #" + atributo.nome);
							$("#form-movimentacao #" + atributo.nome + "-old,#form-movimentacao #" + atributo.nome).val(retorno[a].valor);
						}
					}
				});
			},500);			
			$("#td_motivo,#observacao").css("width","98%");
			$("#salvar-movimentacao-alterar").click(function(){
				var atributosID = "'.$atributosID.'";
				var atributosArrayID = atributosID.split(",");
				var dadosAlterar = [];
				var obrigatorio = true;
				for (a in atributosArrayID){
					var idatributo = td_atributo[atributosArrayID[a]].nome;
					$("#form-movimentacao #" + idatributo + "[required]").each(function(){
						if ($(this).val() == ""){
							obrigatorio = false;
							statusFormControl($(this),"error");
						}else{
							statusFormControl($(this),"success");
						}
					});
					dadosAlterar.push({
						entidade:$("#" + idatributo).data("entidade"),
						atributo:$("#" + idatributo).attr("atributo"),
						valor:$("#form-movimentacao #" + idatributo).val()
					});
				}
				if (!obrigatorio){
					return false;
				}
				$.ajax({
					url:"index.php?controller=gerarmovimentacao",
					data:{
						op:"salvaralterar",
						dadosalterar:dadosAlterar,
						entidadepai:getCookie("entidademovdados"),
						identidadepai:getCookie("idmovdados"),
						entidademotivo:"'.$movimentacao->td_motivo.'",
						motivo:$("#td_motivo").val(),
						observacao:$("#observacao").val(),
						movimentacao:'.$movimentacao->id.'
					},
					error:function(erro){
						console.log(erro);
						abrirAlerta("Erro ao Salvar","alert-danger",".'.$msgRetornoContexto.'");
					},
					complete:function(ret){
						if (parseInt(ret.responseText) == 1){
							'.$msgSalvar.'
							for (a in atributosArrayID){
								var idatributo = td_atributo[atributosArrayID[a]].nome;
								$("#form-movimentacao #" + idatributo + "-old").val($("#" + idatributo).val());
							}
						}
					}
				});
			});
			$(".coluna[data-ncolunas=2]").css("width","50%");			
		');
		$js->mostrar();
	}
	// ***** ALTERAR MOVIMENTACAO *****/
			
	// Loader Salvar
	$loader_salvar = tdClass::Criar("div");
	$loader_salvar->class = "loader-salvar";
	
	$btn_salvar->id = $btnIdSalvar;
	
	// Grupo de Botões
	$grupo_botoes = tdClass::Criar("div");
	$grupo_botoes->class = "form-grupo-botao";		
	$grupo_botoes->add($loader_salvar,$btn_salvar,$retorno);
	
	$blocoForm = tdClass::Criar("div");
	$blocoForm->class = "col-md-12";
	$blocoForm->id = "crud-contexto-add-" . $entidade->nome;
	$blocoForm->add($grupo_botoes,$form);

	$linhaForm = tdClass::Criar("div");
	$linhaForm->class = "row";
	$linhaForm->add($blocoForm);
	$linhaForm->mostrar();
	
	if ($movimentacao->td_motivo > 0){
		$motivo = tdClass::Criar("persistent",array(ENTIDADE,$movimentacao->td_motivo))->contexto;
		$campo_motivo = '<label for=\'td_motivo\' class=\'control-label\'>'.$motivo->descricao.'</label>';
		$campo_motivo .= '<select class=\'form-control input-sm\' required=\'true\' id=\'td_motivo\' name=\'td_motivo\' data-entidade=\''.$motivo->nome.'\'></select>';
		$carregarMotivo = 'carregarListaMotivo()';
		$motivoNomeEntidade = $motivo->nome	;
		$motivoid = $motivo->id;
	}else{
		$motivoNomeEntidade = "";
		$campo_motivo = "";
		$campo_observacao = "";
		$motivoid = "";
	}
	$QtdeStatus = 0;
	if ($conn = Transacao::Get()){
		$sqlQtdeStatus = "SELECT 1 FROM td_movimentacaostatus WHERE td_movimentacao = " . $movimentacao->id;
		$queryQtdeStatus = $conn->query($sqlQtdeStatus);
		$QtdeStatus = $queryQtdeStatus->rowCount();
	}	
	$campo_observacao = utf8charset('<label for=\'observacao\' class=\'control-label\'>Observação</label>');
	$campo_observacao .= '<textarea class=\'form-control input-sm\' required=\'true\' id=\'observacao\' name=\'observacao\' data-entidade=\''.$motivoNomeEntidade.'\'></textarea>';
	
	$js = tdClass::Criar("script");
	$js->add('
	
		$(".b-salvar.b-movimentacao").click(function(){		
			salvarStatus();
		});
		if ($("#form-movimentacao fieldset").length <= 0){
			var fieldset = $("<fieldset>");
			$("#form-movimentacao").append(fieldset);
		}

		var motivo = $("'.$campo_motivo.'");
		$("#form-movimentacao fieldset").append(motivo);
		var observacao = $("'.$campo_observacao.'");
		$("#form-movimentacao fieldset").append(observacao);
		$("#form-movimentacao fieldset").css("margin-bottom","15px");
		'.$carregarMotivo.'
		unLoaderGeral();

		function carregarListaMotivo(){
			$.ajax({
				url:config.urlrequisicoes,
				data:{
					op:"carregar_options",
					entidade:"'.$motivoid.'"
				},
				complete:function(ret){
					$("#td_motivo").html(ret.responseText);
				}
			});
		}

		function salvarStatus(){
			if ('.$QtdeStatus.' <= 0) return false;
			$.ajax({
				url:"index.php?controller=gerarmovimentacao",
				data:{
					op:"salvarstatus",
					movimentacao:getCookie("movimentacaoselecionada"),
					id:getCookie("idmovdados"),
					entidademotivo:"'.$movimentacao->td_motivo.'",
					motivo:$("#td_motivo").val(),
					observacao:$("#observacao").val(),
				},
				complete:function(ret){
					if (parseInt(ret.responseText) == 1){
						'.$msgSalvar.'	
					}else{
						abrirAlerta("Erro ao salvar. Entrar em contato com o administrador do sistema.","alert-danger",".'.$msgRetornoContexto.'");
						console.log("Erro ao Salvar Status");
						console.log(ret.responseText);
					}
				}
			});
		}
		$("#modal-movimentacao .modal-header .modal-title").html("'.utf8charset($movimentacao->descricao).'");
	');
	$js->mostrar();