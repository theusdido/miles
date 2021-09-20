// Inicialização das variáveis globais
if (funcionalidade == "" || funcionalidade == null || funcionalidade == undefined){
	var funcionalidade = $("#funcionalidadetd").length==0?"cadastro":$("#funcionalidadetd").val()==""?"cadastro":$("#funcionalidadetd").val();
}
var dados = [];
var generalizacao = "";
var composicao = [];
var agregacao = [];
var inicializacao = [];
var CKEditores = [];

var EntidadePrincipalID = $("#entidadeprincipalid").val();
var EntidadePrincipalOBJ = td_entidade[EntidadePrincipalID];
if (typeof EntidadePrincipalOBJ == "undefined"){
	bootbox.alert("Não foi possível carregar Entidade");
	$(".crud-contexto").hide();
}

var contextoAdd = "#crud-contexto-add-" + EntidadePrincipalOBJ.nomecompleto;
var contextoListar = "#crud-contexto-listar-" + EntidadePrincipalOBJ.nomecompleto;
var contextoAddTempGen = [];
var contextoListarTempGen = [];
var entidades = [];
var registrounico = td_entidade[EntidadePrincipalID].registrounico;
var cmodal = " .modal-body p"; // Complemento Modal
var pModalName = "myModal-"; // PREFIXO Modal Name
var movimentacaoEntidadeDados = {};
var dadosatributodependencia = [];
var entidadesListaId = []; // Entidades que compõe o carregamento dos dados que estão na lista
var entidadesListaNome = [];
var entidadesId = []; // Entidades que compõe o carregamento dos dados
var entidadesNome = [];
var loggerDados = [];
var textcase = ""; // Aceita [CamelCase] , [UpperCase] e [LowerCase]
var dadosFiltroEnderecoTemp = {}; // Grava os dados do filtro do endereço para retornar o valores para o campo certo.
var monitorformdadospreenchido = [];

$("#div-htmlpersonalizado").load(session.folderprojectfiles + "files/cadastro/" + EntidadePrincipalID + "/" + EntidadePrincipalOBJ.nome + ".htm");
$(".loader-salvar",contextoAdd).html(getIMGLoader());
$(".loader-salvar",contextoAdd).hide();

if (session.usergroup <= 1){
	$(".titulo-pagina .nome-entidade").show();
}
$("form").submit(function(){
	if ($(this).hasClass("tdform-upload-file")){
		return true;
	}else{
		return false;
	}
});

// Seta Variavel gradesdedados
if (typeof gradesdedados === "undefined"){
	var gradesdedados = [];
}

var parmsURL = getParmsURL();
if(parmsURL["funcionalidade"] != undefined){
	if (parmsURL["funcionalidade"] == "editarformulario"){
		editarFormulario(parmsURL["entidadeid"],parmsURL["id"]);
		functionalidade = ""; // Se estiver em modo de edição via parametros na url não precisa recarregar a funcionalidade
	}
}

// Inicializa CADASTRO
if (funcionalidade == "cadastro"){
	if (parseInt(EntidadePrincipalOBJ.registrounico) == 0){
		if (gradesdedados[contextoListar] == undefined){
			// Carrega a grade de dados padrão
			var entidadePrincipalGD = new GradeDeDados(EntidadePrincipalID);
			entidadePrincipalGD.contexto=contextoListar;
			gradesdedados[contextoListar] = entidadePrincipalGD;
			gradesdedados[contextoListar].exibirpesquisa = true;
			gradesdedados[contextoListar].exibireditar 	= true;
			gradesdedados[contextoListar].exibirexcluir = true;
			gradesdedados[contextoListar].exibiremmassa = true;			
			entidadePrincipalGD.show();
		}else{
			gradesdedados[contextoListar].exibirpesquisa = true;
			gradesdedados[contextoListar].exibireditar 	= true;
			gradesdedados[contextoListar].exibirexcluir = true;
			gradesdedados[contextoListar].exibiremmassa = true;			
			gradesdedados[contextoListar].clear();
			gradesdedados[contextoListar].reload();
		}
	}
}
// Inicializa CONSULTA
if (funcionalidade == "consulta"){
	/*
	// Seta CK Editores para CONSULTA
	setaCkEditores(true);
	if (gradesdedados[contextoListar] == undefined){
		// Carrega a grade de dados padrão
		var entidadePrincipalGD = new GradeDeDados(EntidadePrincipalID);
		entidadePrincipalGD.contexto=contextoListar;
		gradesdedados[contextoListar] = entidadePrincipalGD;		
		gradesdedados[contextoListar].retornafiltro = true;
		gradesdedados[contextoListar].exibirpesquisa = false;
		gradesdedados[contextoListar].setOrder("id","DESC");
		entidadePrincipalGD.show();
	}else{
		gradesdedados[contextoListar].exibirpesquisa = false;
		gradesdedados[contextoListar].reload();
	}
	
	$("#form-consulta.tdform .form_campos .form-control").each(function(){
		if ($(this).prop("tagName") == "SELECT"){
			$(this).removeAttr("required");
			var atributo = $(this).attr("id").split(" ")[0];
			carregarListas($(this).data("entidade"),$(this).attr("id"),"");
		}
	});
	*/
}

// Inicializa RELATORIO
if (funcionalidade == "relatorio"){
	// Seta CK Editores para RELATORIO
	setaCkEditores(true);
	$("#form-relatorio.tdform .form_campos .form-control").each(function(){
		if ($(this).prop("tagName") == "SELECT"){
			$(this).removeAttr("required");
			var atributo = $(this).attr("id").split(" ")[0];
			carregarListas($(this).data("entidade"),$(this).attr("id"),"");
		}
	});
}
if (funcionalidade == "movimentacao"){
	setaCkEditores(true);
}
if (funcionalidade == "editarformulario"){
	if (typeof entidadeid == "undefined"){
		entidadeid = EntidadePrincipalID;
	}
	editarFormulario(entidadeid,id);
	funcionalidade=0;
	entidadeid =0;
	entidadeid=0;
}

// Registro Único
if (registrounico == 1){
	if (editarFormulario(EntidadePrincipalID,1) == ""){
		novoRegistroFormulario($(contextoListar).find(".b-novo").first());
	}
}

// Registros Temporários
var dados_temp = [];
var tempRegistro = 0;

// Seta entidades de Composição para vereficação de salvamento
setEntidadesComposicao(EntidadePrincipalID);
function setEntidadesComposicao(entidadeComposicao){
	for (r in td_relacionamento){
		if (td_relacionamento[r].pai == entidadeComposicao){
			if (isEntidadePai(td_relacionamento[r].filho)){
				setEntidadesComposicao(td_relacionamento[r].filho);
			}else{		
				if ((td_relacionamento[r].tipo == 2 || td_relacionamento[r].tipo == 10)){
					if (td_relacionamento[r].filho != 0 && td_relacionamento[r].filho != "" && td_relacionamento[r].filho != undefined){				
						composicao[td_entidade[td_relacionamento[r].filho].descricao] = false;
					}
				}
			}
		}	
	}
}

// Seta entidades de Agregação para salvamento
setEntidadesAgregacao(EntidadePrincipalID);
function setEntidadesAgregacao(entidadeAgregacao){
	for (r in td_relacionamento){
		if (td_relacionamento[r].pai == entidadeAgregacao){
			if (isEntidadePai(td_relacionamento[r].filho)){
				setEntidadesAgregacao(td_relacionamento[r].filho);
			}else{
				if (td_relacionamento[r].tipo == 1 ){
					if (td_relacionamento[r].filho != 0 && td_relacionamento[r].filho != "" && td_relacionamento[r].filho != undefined){
						agregacao[td_entidade[td_relacionamento[r].filho].descricao] = false;
					}
				}
			}
		}
	}
}

// Permissão dos atributos 
setPermissoesAtributos(EntidadePrincipalID,contextoAdd);

// Seta Select de generalização única
$('#select-generalizacao-unica').SumoSelect();

// Cria Generalização Multipla
setGeneralizacaoMultipla();

// Salvando Formulário
$(".b-salvar").click(function(){
	if (typeof beforeSave === "function") beforeSave(this);
	var entidade 			= $(this).parents(".crud-contexto-add").data("entidade");
	var entidadeID 			= $(this).parents(".crud-contexto-add").data("entidadeid");
	var contextoAdd 		= "#" + $(this).parents(".crud-contexto-add").first().attr("id");
	var contextoListar 		= "#" + $(this).parents(".crud-contexto").first().find(".crud-contexto-listar").attr("id");
	var fp 					= $(this).parents(".crud-contexto-add").first().hasClass("fp");
	var entidadepai 		= $(this).parents(".crud-contexto-add").data("entidadepai");
	var btnSalvar = $(this);
	addLog("","",0,entidadeID,0,8, "");

	// Preenche os campos que tem inicialização
	for(ini in td_atributo){
		if (td_atributo[ini].entidade == entidadeID && td_atributo[ini].inicializacao != ""){
			if (td_atributo[ini].tipoinicializacao == 1){
				eval('$("#'+td_atributo[ini].nome+'[data-entidade='+entidade+']").val('+td_atributo[ini].inicializacao+')');
			}
		}
	}
	if (fp){
		btnSalvar.attr("disabled",true);
		btnSalvar.attr("readonly",true);
	}
	if (typeof entidadeID === "undefined") return false;
	// Controle pra quando for carregado vários botões salvar
	if (typeof salvamentos !== "undefined"){
		if (salvamentos[contextoAdd] != undefined){	
			liberaBotao(btnSalvar);
			return false;
		}else{
			salvamentos[contextoAdd] = 1;
		}
	}
	
	if ($("#select-generalizacao-unica").length > 0){
		var contextoMsg = " .msg-retorno-form-" + td_entidade[EntidadePrincipalID].nomecompleto;
	}else{
		var contextoMsg = contextoAdd + " .msg-retorno-form-" + td_entidade[entidadeID].nomecompleto;
	}
	var idRegistro = $("#id[data-entidade="+entidade+"]",contextoAdd).val();
	if (fp){
		if (idRegistro == ""){
			dados.splice(0,dados.length); // Limpa array "dados"
		}
	}else{
		// Pega o relacionamento
		var currentrelacionamento = getRelacionamento(entidadepai,entidadeID);
		if (!currentrelacionamento){
			console.log("Relacionamento Atual não encontrado.");
		}
	}

	var entidadepairel 			= fp ? "" : currentrelacionamento.pai;
	var tiporelacionamentopai 	= fp ? 0 : currentrelacionamento.tipo;

	if (isPaiEntidade(entidadeID)){
		for(RelEnt in td_relacionamento){
			if (td_relacionamento[RelEnt].pai == entidadeID){
				var entidadesRel = "";
				if ( td_relacionamento[RelEnt].tipo == "1" || td_relacionamento[RelEnt].tipo == "7"){
					entidadesRel = td_relacionamento[RelEnt].filho;
				}else if(td_relacionamento[RelEnt].tipo == "3"){
					if (td_relacionamento[RelEnt].filho == $("#select-generalizacao-unica").val()){
						entidadesRel = $("#select-generalizacao-unica").val();
					}
				}else if(td_relacionamento[RelEnt].tipo == "9"){
					$("#select-generalizacao-multipla option[value="+td_relacionamento[RelEnt].filho+"]:selected").each(function(){
						entidadesRel = $(this).val();
					});
				}
				if (entidadesRel != ""){			
					var hierarquiacontexto = getHierarquiaRel(RelEnt);
					$("#crud-contexto-add-" + hierarquiacontexto).find(".b-salvar").first().click();
				}
			}
			
			if (entidadepairel != ""){
				if (td_relacionamento[RelEnt].pai == entidadepairel && td_relacionamento[RelEnt].filho == entidadeID){
					tiporelacionamentopai = td_relacionamento[RelEnt].tipo;
				}
			}
		}
	}

	if (entidadepairel != "" && tiporelacionamentopai == 1){
		// Caso seja Agregação 1:1 e se o usário não digitou nada no form então não enviar o formulário
		var tdform = $(contextoAdd + " .tdform");
		if (!isalteracaoform(entidadeID,tdform)){
			tdform.find(".form-control").parent(".form-group").removeClass("has-error");
			return false;
		}
	}

	var validacaotamanho = false;
	for(a in td_atributo){
		try{
			var nomeEntidade = td_entidade[td_atributo[a].entidade].nomecompleto;
		}catch(e){
			console.log("ERRO ( Nome da entidade("+td_atributo[a].entidade+") no campo não encontrada ) => ["+a+"]" + td_atributo[a].nome);
			break;
			return false;
		}
		
		if (nomeEntidade == entidade){
			try{
				var campoAttr = $("#" + td_atributo[a].nome + "[data-entidade="+entidade+"]",contextoAdd);
				var dataToSave = campoAttr.val();
				if (td_atributo[a].tipo == "varchar" || td_atributo[a].tipo == "char"){
					if (dataToSave.length > td_atributo[a].tamanho){
						console.error("Quantidade de caracteres execido. Campo [ " + td_atributo[a].nome + " ] - Max => " + td_atributo[a].tamanho + " Len => " + dataToSave.length);
						campoAttr.parent().addClass("has-error");
						validacaotamanho = true;						
						break;
					}else{
						campoAttr.parent().removeClass("has-error")
					}
				}
			}catch(e){
				console.log("ERRO => #" + td_atributo[a].nome + "[data-entidade="+entidade+"]");
			}	
		}
	}
	// Valida o tamanho de caracteres de cada campo
	if (validacaotamanho){
		abrirAlerta("Quantidade de caracteres exedido.","alert-danger",contextoMsg);
		liberaBotao(btnSalvar);
		return false;
	}

	// Monta a array dos campos obrigatórios
	var campos_obrigatorios = {};
	$(".form-control[required][data-entidade="+entidade+"]",contextoAdd).each(function(){
		campos_obrigatorios[$(this).attr("name")] = $(this).val();
	});
	
	// Função que valida os campos obrigatórios
	if (!validar(campos_obrigatorios,contextoAdd,contextoMsg)){
		liberaBotao(btnSalvar);
		return false;
	}

	if ($(contextoAdd).find("div").hasClass("has-error")){
		abrirAlerta("Existem campos obrigat&oacute;rios n&atilde;o preenchidos","alert-danger",contextoMsg);
		liberaBotao(btnSalvar);	
		return false;
	}
	
	if (fp){
		var parar = false;
		for (c in composicao){
			if (!composicao[c]){
				abrirAlerta("<b>" + c + "</b> &eacute; obrigat&oacute;rio.","alert-danger",contextoMsg);
				parar = true;
				break;
			}
		}
		if (parar){
			liberaBotao(btnSalvar);
			return false;
		}
		
	}

	// Monta a Array com os dados a serem salvos	
	var dados_obj = [];
	var campovaziogravar = 0;
	for(a in td_atributo){
		try{
			var nomeEntidade = td_entidade[td_atributo[a].entidade].nomecompleto;
			if (nomeEntidade == entidade){
				var dataToSave = $("#" + td_atributo[a].nome + "[data-entidade="+entidade+"]",contextoAdd).val();
				if (textcase == 'uppercase'){
					dataToSave.toUpperCase();
				}
				dados_obj.push({atributo:td_atributo[a].nome,valor:dataToSave});			
			}
		}catch(e){
			console.log("ERRO => #" + td_atributo[a].nome + "[data-entidade="+entidade+"]");
		}
	}
	// Monta campo de relacionamento
	if (fp){
		var relacionamento 			= "";
		var relacionamentoTipo 		= 0;
		for(rSalvar in td_relacionamento){

			// Testa se a entidade é filho
			if (td_relacionamento[rSalvar].filho == entidadeID){
				if ($("#select-generalizacao-unica").length > 0){
					if (td_relacionamento[rSalvar].tipo == "8"){
						relacionameedinto = {entidade:td_entidade[td_relacionamento[rSalvar].pai].nome,atributo:td_atributo[td_relacionamento[rSalvar].atributo].nome};
						relacionamentoTipo = td_relacionamento[rSalvar].tipo;
					}else{
						if (td_relacionamento[rSalvar].atributo != "" && td_relacionamento[rSalvar].atributo > 0){
							relacionamento = {entidade:td_entidade[$("#select-generalizacao-unica").val()].nome,atributo:td_atributo[td_relacionamento[rSalvar].atributo].nome};
							relacionamentoTipo = td_relacionamento[rSalvar].tipo;
						}else if (td_entidade[$("#select-generalizacao-unica").val()].atributogeneralizacao != "" && td_entidade[$("#select-generalizacao-unica").val()].atributogeneralizacao > 0){
							relacionamento = {entidade:td_entidade[$("#select-generalizacao-unica").val()].nome,atributo:td_atributo[td_entidade[$("#select-generalizacao-unica").val()].atributogeneralizacao].nome};
							relacionamentoTipo = td_relacionamento[rSalvar].tipo;
						}else{
							relacionamento = {entidade:td_entidade[td_relacionamento[rSalvar].pai].nome,atributo:''};
							relacionamentoTipo = td_relacionamento[rSalvar].tipo;
						}
					}
				}else{
					if (td_relacionamento[rSalvar].atributo == "" || td_relacionamento[rSalvar].atributo == undefined || td_relacionamento[rSalvar].atributo == 0){
						relacionamento = {entidade:td_entidade[td_relacionamento[rSalvar].pai].nome,atributo:""};
					}else{
						relacionamento = {entidade:td_entidade[td_relacionamento[rSalvar].pai].nome,atributo:td_atributo[td_relacionamento[rSalvar].atributo].nome};
					}
					relacionamentoTipo = td_relacionamento[rSalvar].tipo;
				}		
			}
		}
	}else{
		if (currentrelacionamento.atributo == undefined || parseInt(currentrelacionamento.atributo) == 0){
			var atributorel		= '';
		}else{
			var atributorel		= td_atributo[currentrelacionamento.atributo].nome;
		}
		relacionamento 		= {entidade:td_entidade[currentrelacionamento.pai].nome,atributo:atributorel};
		relacionamentoTipo 	= currentrelacionamento.tipo;
	}
	addDados(entidade,dados_obj,idRegistro,relacionamento,fp,relacionamentoTipo);

	// Salvar o formulário
	if (fp){
		// Limpa os IDs pois se é "temp" é um registro novo
		for(t in dados_temp){
			dados_temp[t].id = "";
		}
		var dadospreenviar = dados.concat(dados_temp);
		// O formulário principal tem que ser enviado em primeiro
		if ($("#id",contextoAdd).val() == ""){
			var dadosenviar = dadospreenviar.reverse();
		}else{
			var primeiraarraydados = [];
			var restoarraydados = [];
			for (dp in dadospreenviar){
				if (dadospreenviar[dp].fp){
					primeiraarraydados.push(dadospreenviar[dp]);
				}else{
					restoarraydados.push(dadospreenviar[dp]);
				}
			}
			var dadosenviar = primeiraarraydados.concat(restoarraydados);
		}
		// AJAX que envia os dados a serem salvos
		$.ajax({
			type:"POST",
			url:config.urlsaveform,
			data:{
				dados:dadosenviar
			},
			dataType:"json",
			complete:function(ret){
				var retorno = JSON.parse(ret.responseText);
				if (parseInt(retorno.status) == 1){
					abrirAlerta("Salvo com Sucesso","alert-success",contextoMsg);					
					exibirDadosEdicao(retorno.entidade,retorno.id);					
					for(entID in retorno.entidadesID){
						for(dts in dados_temp){							
							if (retorno.entidadesID[entID].entidade == dados_temp[dts].entidade){
								dados_temp[dts].id = retorno.entidadesID[entID].id;
								if (dados_temp[dts].tiporel == "" || dados_temp[dts].tiporel == "1" || dados_temp[dts].tiporel == "7" || dados_temp[dts].tiporel == "3"){
									// Atualiza o ID do banco de dados no registro
									$("#id[data-entidade="+dados_temp[dts].entidade+"]").val(dados_temp[dts].id);
								}else{
									// Recarrega as grades de dados
									for(g in gradesdedados){
										// Não carrega a lista da entidade principal
										var identidadegrade = gradesdedados[g].getEntidadeNome();
										if (dados_temp[dts].entidade == identidadegrade) {
											gradesdedados[g].clear();
											gradesdedados[g].addFiltroNN(retorno.entidade, retorno.id, gradesdedados[g].entidade);
											gradesdedados[g].reload();
										}
									}
								}
							}
						}
					}
					setaPrimeiraAba(contextoAdd);
					if ($("#select-generalizacao-unica")){
						$("#select-generalizacao-unica").attr("readonly","true");
						$("#select-generalizacao-unica").attr("disabled","true");
					}
					dados_temp.splice(0,dados_temp.length); // Limpa os dados temporários após o salvamento
					setTimeout(function(){
						liberaBotao(btnSalvar);
					},5000);
				}
				if (typeof afterSave === "function") afterSave(fp,this);
				unLoaderSalvar();
			},
			error:function(ret){
				if (fp){
					btnSalvar.attr("disabled",false);
					btnSalvar.attr("readonly",false);
					unLoaderSalvar();
				}
				if (session.isproducao && session.isonline){
					enviarEmailErro(ret.responseText);
				}
				abrirAlerta("<b>Erro ao Salvar</b> favor entrar em contato com a equipe de SUPORTE.","alert-danger",contextoMsg);
			},
			beforeSend:function(){
				addLoaderSalvar(contextoAdd);
			}
		});
	}else{
		if (currentrelacionamento.cardinalidade == '1N' || currentrelacionamento.cardinalidade == 'NN'){
			var addlinhagrade = "";
			var idEdicao = "";
			if (gradesdedados[contextoListar] == undefined){
				console.log("Grade de dados não encontrada para => " + contextoListar);
				return false;
			}
			for (n in gradesdedados[contextoListar].attr_cabecalho_nome){
				var elementoDOM = "#" + gradesdedados[contextoListar].attr_cabecalho_nome[n] + "[data-entidade="+entidade+"]";
				var tagName = $(elementoDOM,contextoAdd).prop("tagName");
				switch(tagName){
					case "INPUT":
						var val = $(elementoDOM,contextoAdd).val();
					break;
					case "SELECT":					
						var val = $(elementoDOM + " option:selected",contextoAdd).html();
					break;
					case "TEXTAREA":
						var val = $(elementoDOM,contextoAdd).html();
					break;
					default:
						var val = $(elementoDOM,contextoAdd).val();
				}
				if ($(elementoDOM,contextoAdd).hasClass("termo-filtro")){
					var val = $(elementoDOM,contextoAdd).parents(".input-group").find(".descricao-filtro").val();
				}
				if (val != ""){
					addlinhagrade += (addlinhagrade!=""&&addlinhagrade!="^"?"^":"") + val;
				}else{
					addlinhagrade += "^";
				}
			}
			idEdicao = $("#id[data-entidade="+entidade+"]",contextoAdd).val();
			gradesdedados[contextoListar].addCorpo(idEdicao,addlinhagrade);	
			$(contextoAdd).hide();
			$(contextoListar).show();

			if (relacionamentoTipo == 2 || relacionamentoTipo == 10){
				composicao[td_entidade[entidadeID].descricao] = true;
			}
			if (typeof afterSave === "function") afterSave(fp,this);
		}
	}
});

/* Adicionar um novo registro no formuário */
$(".b-novo").click(function(){
	if (typeof beforeNew === "function") beforeNew(this);
	novoRegistroFormulario(this);
});
$(".b-voltar").click(function(){
	if (typeof beforeBack === "function") beforeBack(this);
	var entidade = $(this).parents(".crud-contexto-add").first().data("entidade");
	var entidadeID = $(this).parents(".crud-contexto-add").first().data("entidadeid");
	var contextoListar = "#" + $(this).parents(".crud-contexto").first().find(".crud-contexto-listar").attr("id");
	var contextoAdd = "#" + $(this).parents(".crud-contexto").first().find(".crud-contexto-add").attr("id");
	var fp = $(this).parents(".crud-contexto-add").first().hasClass("fp");
	$(contextoAdd).hide();
	if (fp){
		$(contextoListar).hide();
		gradesdedados[contextoListar].reload();
	}else{
		$(contextoListar).show();
	}
	if (typeof afterBack === "function") afterBack(this);
});
function editarFormulario(entidade,id){
	if (typeof beforeEdit === "function") beforeEdit(entidade,id);
	entidades.splice(0,entidades.length);
	
	var entidadeNome 			= td_entidade[entidade].nomecompleto;
	entidades.push({entidade:entidadeNome,atributo:"id",valor:id,tipoRel:"",entidadepai:""});
	var atributogeneralizacao 	= td_entidade[entidade].atributogeneralizacao;
	var retornoEdicao 			= "";
	var contextoListar 			= "#crud-contexto-listar-" + entidadeNome;
	var contextoAdd 			= "#crud-contexto-add-" + entidadeNome;	
	var fp = $(contextoAdd).first().hasClass("fp");	

	if (!fp){
		var contextoListar = "#crud-contexto-listar-" + (getHierarquia(entidade) == ""?entidadeNome:getHierarquia(entidade));
		var contextoAdd = "#crud-contexto-add-" + (getHierarquia(entidade) == ""?entidadeNome:getHierarquia(entidade));
	}

	var btnSalvar = $(contextoAdd).find(".b-salvar");
	novoRegistroFormulario($(contextoListar).find(".b-novo").first()); // Limpa o formulário para edição de um novo registro

	setaAtributoGeneralizacaoLista(contextoAdd);	
	addLog("", "", "", entidade,id, 7, "");

	for (permissao in td_permissoes){
		if (session.userid == td_permissoes[permissao].usuario && entidade == td_permissoes[permissao].entidade){			
			if (td_permissoes[permissao].editar != 1){
				bootbox.alert("Voc&ecirc; n&atilde;o tem permiss&atilde;o para editar registro");
				return false;
			}
		}
	}

	if (!isNumeric(parseInt(id))){
		for (d in dados_temp){
			if (dados_temp[d].entidade == entidadeNome && dados_temp[d].id == id.replace("T","")){
				var dados = dados_temp[d].dados;
				for (dt in dados){
					var campo = $("#" + dados[dt].atributo + "[data-entidade="+entidadeNome+"]",contextoAdd);
					campo.val(dados[dt].valor);
					retornaDadoFormatadoCampo(dados[dt].atributo,entidadeNome,contextoAdd,dados[dt].valor);
				}
			}
		}
		$("#id[data-entidade="+entidadeNome+"]",contextoAdd).val(id);
		setaPrimeiraAba(contextoAdd);
		$(contextoListar).hide();
		$(contextoAdd).show();
		if (typeof afterEdit === "function") afterEdit(entidade,id);
	}else{
		for(r in td_relacionamento){
			if (td_relacionamento[r].pai == entidade){
				entidadesRelacionamentosLoad(entidade,id,td_relacionamento[r]);
			}
		}
		$.ajax({
			url:config.urlloadform,
			data:{			
				dados:entidades,
				entidadeprincipal:entidade,
				registroprincipal:id,
				entidadeslista:entidadesListaId.join(","),
				entidadeslistanome:entidadesListaNome
			},
			dataType:"json",
			beforeSend:function(){
				addLoaderGeral();
			},
			error:function(ret){
				bootbox.alert("<b>Erro ao carregar os dados.</b> Favor entrar em contato com a equipe de SUPORTE.");
				unLoaderGeral();
			},
			complete:function(ret){
				try {
    				var retorno = JSON.parse(ret.responseText);
				}catch(err){
    				console.log(err.message);
				}
				for(r in retorno){
					retornoEdicao = retorno[r].id;
					var dadosRetorno = retorno[r].dados;
					if (dadosRetorno != ""){
						var entidadeDados = td_entidade[retorno[r].entidade];			
						var tipoRelacionamento = "";
						var atributoRelacionamento = "";
						var nomeEntidadeDados = (entidadeDados.pacote==""?"":entidadeDados.pacote + "-") + entidadeDados.nome;					
						entidadesRelacionamentos(retorno[r].entidade,retorno[r].id);												
						atribuiGrade(retorno[r].entidade);
						// Verifica o tipo de relacionamento						
						for (entR in entidades){
							if (entidades[entR].tipoRel != "" && entidades[entR].entidade == nomeEntidadeDados){
								tipoRelacionamento = entidades[entR].tipoRel;
								atributoRelacionamento = entidades[entR].atributo;
								// Limpa a grade de dados
								if (gradesdedados["#crud-contexto-listar-" + getHierarquia(entidadeDados.id)] != undefined){
									gradesdedados["#crud-contexto-listar-" + getHierarquia(entidadeDados.id)].clear();
								}
							}
						}
						var entidadePai = "";
						var entidadeGenSel = ""; // Entidade de Generalização escolhida
						for(rel in td_relacionamento){

							// Configura o cadastro para a generalização simples
							if (td_relacionamento[rel].pai == entidade && (parseInt(td_relacionamento[rel].tipo) == 3 || parseInt(td_relacionamento[rel].tipo) == 8)){
								for(d in dadosRetorno){
									if (td_atributo[td_entidade[entidade].atributogeneralizacao].nome == dadosRetorno[d].atributo){
										if (dadosRetorno[d].valor  == td_relacionamento[rel].filho){

											$("#select-generalizacao-unica")[0].sumo.selectItem(td_relacionamento[rel].filho);
											$(".div-relacionamento-generalizacao,.generalizacaoABA",contextoAdd).hide();
											$("#select-generalizacao-unica").attr("readonly","true");
											$(".generalizacaoABA." + td_entidade[td_relacionamento[rel].filho].nome).show();
											$(".div-relacionamento-generalizacao#drv-" + td_entidade[td_relacionamento[rel].filho].nome,contextoAdd).show();
										}
									}
								}
							}

							// Seleciona a lista da generalização multipla
							if (parseInt(td_relacionamento[rel].tipo) == 9 && td_relacionamento[rel].pai == entidade){
								$("#select-generalizacao-multipla")[0].sumo.selectItem(String(retorno[r].entidade));
							}
						}
						if (tipoRelacionamento == "" || tipoRelacionamento == 1 || tipoRelacionamento == 7 || tipoRelacionamento == 3 || tipoRelacionamento == 9){
							$("#id[data-entidade="+nomeEntidadeDados+"]",contextoAdd).val(retorno[r].id);							
							for (d in dadosRetorno){
								var valorDados = dadosRetorno[d].valor;
								var direto = true;
								if ($("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).prop("tagName") == "SELECT"){
									if (td_atributo[getIdAtributo(dadosRetorno[d].atributo,nomeEntidadeDados)].atributodependencia <= 0){										
										carregarListas(nomeEntidadeDados,dadosRetorno[d].atributo,contextoAdd,valorDados);
										if (valorDados != "" && valorDados != undefined){
											$("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).val(valorDados);
										}else{
											if ($("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).attr("required") == "required"){
												direto = false;
												$("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).prop('selectedIndex', 0);
											}
										}
										
									}else{
										if ($("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).attr("required") == "required"){
											direto = false;
											$("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).prop('selectedIndex', 0);
										}
										dadosatributodependencia.push({
											entidade:nomeEntidadeDados,
											atributo:dadosRetorno[d].atributo,
											contexto:contextoAdd,
											valor:valorDados
										});
										var atributodependencia = td_atributo[getAtributoId(nomeEntidadeDados,dadosRetorno[d].atributo)].atributodependencia;
										var valorfiltro = 0;
										for (dr in dadosRetorno){
											if (dadosRetorno[dr].atributo == td_atributo[atributodependencia].nome){
												valorfiltro = dadosRetorno[dr].valor;
												break;
											}
										}
										carregarListas(nomeEntidadeDados,dadosRetorno[d].atributo,contextoAdd,valorDados,td_atributo[atributodependencia].nome+"^=^"+valorfiltro);
										$("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]").removeAttr("readonly");
										$("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]").removeAttr("disabled");
									}
								}
								
								if ($("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).hasClass("termo-filtro")){
									direto = false;
									var nomeEntidadeReplace = td_entidade[td_atributo[dadosRetorno[d].idatributo].chaveestrangeira].nomecompleto;
									buscarFiltro(valorDados,nomeEntidadeReplace.replace("-","."),dadosRetorno[d].atributo,pModalName + dadosRetorno[d].atributo + cmodal,nomeEntidadeDados);
								}
								
								if ($("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).hasClass("checkbox-sn")){
									if (valorDados == 1){
										$("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).parents(".form-group").find(".checkbox-s").addClass("active");
										$("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).parents(".form-group").find(".checkbox-n").removeClass("active");
									}else{
										$("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).parents(".form-group").find(".checkbox-n").addClass("active");
										$("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).parents(".form-group").find(".checkbox-s").removeClass("active");
										valorDados = 0;
									}									
								}								
								if ($("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).hasClass("td-file-hidden")){
									var dadosRetornoJSON = JSON.parse(dadosRetorno[d].valor);
									if (dadosRetornoJSON.filename != ""){
										$("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).parents(".form-group").find("iframe").first().attr("src",config.urluploadform + "&atributo="+dadosRetorno[d].idatributo+"&valor="+dadosRetorno[d].valor+"&id=" + retorno[r].id);
									}
								}
								if (tipoRelacionamento == 9){
									$("#select-generalizacao-multipla option[value="+retorno[r].entidade+"]").attr("selected","selected");
								}
								if ($("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).hasClass(".formato-moeda")){
									if (valorDados == 0) valorDados = "0,00";
								}
								if ($("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).hasClass("formato-ckeditor")){
									try{
										CKEditores[nomeEntidadeDados + "^" + dadosRetorno[d].atributo].setData(valorDados);
									}catch(e){
										console.log("Erro ao abrir CKEditor no Celular");
										console.log(e);
									}
								}
								if (direto){
									$("#" + dadosRetorno[d].atributo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).val(valorDados);
								}
							}
							setaPrimeiraAba(contextoAdd);
							$(contextoListar).hide();
							$(contextoAdd).show();
						}else if (tipoRelacionamento == 2 || tipoRelacionamento == 6 || tipoRelacionamento == 5 || tipoRelacionamento == 8 || tipoRelacionamento == 10){

							var entidadePaiContexto = getHierarquia(retorno[r].entidade);								
							var contextoListarRel = "#crud-contexto-listar-" + entidadePaiContexto;
							var contextoAddRel = "#crud-contexto-add-" + entidadePaiContexto;
							var carregargrade = false;
							// Grade de Dados dos Entidades de Relacionamento
							var gd = new GradeDeDados(retorno[r].entidade);
							gd.instancia	= gd;
							gd.contexto 	= contextoListarRel;
							gd.exibirpesquisa = false;

							// Particularidades de cada relacionamento
							switch(tipoRelacionamento){
								case "10":
									if (typeof dadosRetorno === "string"){
										gd.addFiltro("id","=",-1);
										composicao[td_entidade[retorno[r].entidade].descricao] = false;
									}else{										
										composicao[td_entidade[retorno[r].entidade].descricao] = true;
										var idsLista = "";
										var relPaiLista = getEntidadePai(retorno[r].entidade);
										var relFilhoLista = retorno[r].entidade;

										$.ajax({
											url:config.urlrequisicoes,
											async:false,
											data:{
												op:"retorna_ids_lista",
												entidadepai:relPaiLista,
												entidadefilho:relFilhoLista,
												regpai:id
											},
											complete:function(retornoLISTA){
												idsLista = retornoLISTA.responseText.split(",");
											},
											error:function(ret){
												console.log("ERRO ao carregar dados da composição Entidade = ["+relFilhoLista+"] | ID = ["+id+"] => " + ret.responseText);
											}
										});
										gd.addFiltro("id",",",idsLista);
									}
								break;
								case "8":
									if (typeof dadosRetorno === "string"){
										gd.addFiltro("id","=",-1);
									}else{
										for (d in dadosRetorno){
											if (dadosRetorno[d].atributo == atributoRelacionamento){
												gd.addFiltro(dadosRetorno[d].atributo,"=",(dadosRetorno[d].valor==undefined?-1:dadosRetorno[d].valor));
											}
										}
									}
								break;
								case "6":
								if (typeof dadosRetorno === "string"){
									gd.addFiltro("id","=",-1);
								}else{
									for (d in dadosRetorno){
										if (dadosRetorno[d].atributo == atributoRelacionamento){
											gd.addFiltro(dadosRetorno[d].atributo,"=",(dadosRetorno[d].valor==undefined?-1:dadosRetorno[d].valor));
										}
									}								
								}
								break;
								case "5":
									if (typeof dadosRetorno === "string"){
										gd.addFiltro("id","=",-1);
									}else{
										var idsLista = "";
										var relPaiLista = getEntidadePai(retorno[r].entidade);
										var relFilhoLista = retorno[r].entidade;
										$.ajax({
											url:config.urlrequisicoes,
											async:false,
											data:{
												op:"retorna_ids_lista",
												entidadepai:relPaiLista,
												entidadefilho:relFilhoLista,
												regpai:id
											},
											complete:function(retornoLISTA){
												idsLista = retornoLISTA.responseText;
											},
											error:function(ret){
												console.log("ERRO ao carregar dados da lista Entidade = ["+relFilhoLista+"] | ID = ["+id+"] => " + ret.responseText);
											}
										});
										gd.addFiltro("id",",",idsLista);
									}
								break;
								case "2":
								if (typeof dadosRetorno === "string"){
									composicao[td_entidade[retorno[r].entidade].descricao] = false;
									gd.addFiltro("id","=",-1);
								}else{
									composicao[td_entidade[retorno[r].entidade].descricao] = true;
									for (d in dadosRetorno){
										if (dadosRetorno[d].atributo == atributoRelacionamento){
											gd.addFiltro(dadosRetorno[d].atributo,"=",(dadosRetorno[d].valor==undefined?-1:dadosRetorno[d].valor));
										}
									}
								}
								break;
							}

							// Filtro Pai ( TD_Lista )
							gd.entidadePai = getEntidadePai(retorno[r].entidade);
							gd.regpai = retorno[r].id;
							
							if (gradesdedados[contextoListarRel] == undefined){
								gradesdedados[contextoListarRel] = gd;								
							}
							gd.show();
							
							// Atualiza a instancia da Grade de Dados
							gradesdedados[contextoListarRel] = gd;

							$(contextoAddRel,contextoAdd).hide();
							$(contextoListarRel,contextoAdd).show();
						}

						if ($("#select-generalizacao-unica")){
							$("#select-generalizacao-unica").attr("readonly","true");
							$("#select-generalizacao-unica").attr("disabled","true");
							setaLayoutGeneralizao(contextoAdd);
							if (fp){
								$("#select-generalizacao-unica").change();
							}
						}
						$(contextoAddRel).hide();
						$(contextoListarRel).show();
					}
				}
				if ($('#select-generalizacao-multipla')){
					$('#select-generalizacao-multipla').SumoSelect();
					if (fp){
						$("#select-generalizacao-multipla").change();
					}
				}
				if (fp){
					exibirDadosEdicao(entidade,id);
					btnSalvar.attr("disabled",false);
					btnSalvar.attr("readonly",false);					
				}

				if (typeof afterEdit === "function") afterEdit(entidade,id);
				unLoaderGeral();
			}
		});
		$(contextoListar).hide();
		if (fp){
			$(contextoAdd).show();
		}	
	}
	// Permissão dos atributos 
	setPermissoesAtributos(EntidadePrincipalID,contextoAdd,"edicao");
	return retornoEdicao;
}
$("#select-generalizacao-unica").change(function(){
	$(".div-relacionamento-generalizacao,.generalizacaoABA").hide();
	$(".generalizacaoABA")[this.selectedIndex].style.display = "";
	$(".div-relacionamento-generalizacao")[this.selectedIndex].style.display = "";
	var entidade = $(this).find("option:selected").data("entidade-nome");
	var contextoGen = "#crud-contexto-listar-" + entidade;
	setaGradeRelGeneralizacao(contextoGen);
	
	for(rFlag in td_relacionamento){
		if (td_relacionamento[rFlag].filho == $(this).val()){
			$(".form-control[id="+td_atributo[td_entidade[td_relacionamento[rFlag].pai].atributogeneralizacao].nome+"]").val($(this).val());
		}
	}
	$(".nav-tabs li").each(function(){
		if ($(this).hasClass(entidade)){
			$(this).find("a").click();
			$("#" + entidade).show();
		}
	});
	for(rFlag2 in td_relacionamento){
		if (td_relacionamento[rFlag2].pai == $(this).val()){
			if (td_relacionamento[rFlag2].tipo == "6"){
				var gdGenExtra = new GradeDeDados(td_relacionamento[rFlag2].filho);
			}
		}
	}
	
	$(this).find("option").each(function(){
		if ($(this).data("entidade-nome") != entidade){
			$(".contexto[id=crud-contexto-listar-" + $(this).data("entidade-nome") + "]").remove();
			$(".contexto[id=crud-contexto-add-" + $(this).data("entidade-nome") + "]").remove();		
		}
	});
});
$("#select-generalizacao-multipla").change(function(){
	var i = 0;
	$(this).find("option").each(function(){	
		if ($(".generalizacaoABA-M").length <= 0){
			bootbox.alert("Erro ao carrega aba de generalização. <small>Provavel ausencia de aba.</small>");
			return false;
		}

		$(".generalizacaoABA-M")[i].style.display = (this.selected?"":"none");
		$(".div-relacionamento-generalizacao-multipla")[i].style.display = (this.selected?"":"none");
		$(this).css("margin-top",(i>0?"-":"")+(i * 10) + "px"); // Corrigi o posicionamento

		if (this.selected){
			atribuiGrade(this.value);
		}
		i++;
	});
	if ($(this).find("option:selected").length <= 0){
		$(".div-relacionamento-generalizacao-multipla").hide();
	}
	return false;
});

$("#select-generalizacao-multipla").click(function(){
	if ($(this).find("option:selected").length <= 0){
		setaPrimeiraAba();
	}	
});
function addGrade(entidade,contexto){	
	if ($(contexto).find(".gradededados").length <= 0){
		var table = $("<table class='table table-hover gradededados' entidade='"+entidade+"'>");
		if (table.find("thead").length <= 0){
			var thead = $("<thead>");
			var tr = $("<tr>");
			
			// ID 
			var th = $("<th>");
			th.append("ID");
			tr.append(th);
			
			for (a in td_atributo){
				if (td_atributo[a].entidade == entidade && td_atributo[a].exibirgradededados == "1"){
					var th = $("<th>");
					th.append(td_atributo[a].descricao);
					tr.append(th);					
				}
			}
			
			tr.append($("<th><center>Editar</center></th>"));
			tr.append($("<th><center>Excluir</center></th>"));
			tr.append($("<th><center><button onclick="+this.nomeEntidade+"GD.selecionarTodos() data-sel='false' aria-label='Selecionar Todos' class='btn btn-link gd-sel-todos' type='button'><span aria-hidden='true' class='fas fa-check-square'></span></button></center></th>"));
			
			thead.append(tr);
			table.append(thead);
		}
		$(contexto).append(table);
	}
}
function addDados(entidade,dados_obj,id,relacionamento,fp,tiporelacionamento){
	if (isNumeric(id)){
		for (d in dados){
			if (dados[d].entidade == entidade && dados[d].id == id){				
				dados.splice(d,1);
			}
		}

		dados.push({
			entidade:entidade.replace("-","."),
			dados:dados_obj,
			id:id,
			relacionamento:relacionamento,
			fp:fp,
			temp:"",
			tiporel:tiporelacionamento
		});	
	}else{		
		for (d in dados_temp){
			if (dados_temp[d].entidade == entidade && dados_temp[d].id == id.replace("T","")){				
				dados_temp.splice(d,1);
			}
		}
		if (id == ""){
			tempRegistro++;	
		}
		var idRegistroTemp = (id==""?tempRegistro:id.replace("T",""));
		dados_temp.push({
			entidade:entidade,
			dados:dados_obj,
			id:idRegistroTemp,
			relacionamento:relacionamento,
			fp:fp,
			tiporel:tiporelacionamento
		});
	}
}
function isTempRegistro(entidade,id){
	var existe = false;
	for (d in dados_temp){
		if (dados_temp[d].entidade == entidade && dados_temp[d].id == id){
			existe = true;
		}
	}
	return existe;
}
function qtdeTempRegistro(entidade){
	var c = 0;
	for (d in dados_temp){
		if (dados_temp[d].entidade == entidade){
			c++;
		}
	}
	return c;
}
function carregarListas(entidade,atributo,contextoAdd,valor){ // Argumento 4 é o filtro
	
	if (!isNumeric(atributo)){
		for(a in td_atributo){
			if (td_entidade[td_atributo[a].entidade] == undefined) continue;
			if (td_atributo[a].nome == atributo && td_entidade[td_atributo[a].entidade].nomecompleto == entidade){
				atributo = td_atributo[a].id;
			}
		}
	}

	var obrigatorio = $("#" + td_atributo[atributo].nome + "[data-entidade="+entidade+"]",contextoAdd).attr("required") == undefined?0:1;
	var filtro = "";
	for (tda in td_filtroatributo){
		if (td_filtroatributo[tda].atributo == atributo){
			var ft = td_atributo[td_filtroatributo[tda].campo].nome + "^" + td_filtroatributo[tda].operador + "^" + td_filtroatributo[tda].valor;
			filtro += (filtro==""?ft:"~" + ft);
		}
	}

	if (!isNumeric(atributo) || atributo == "" || atributo == null || atributo == undefined || atributo <= 0){		
		console.log("Atributo de Chave estrangeira inesistente. Entidade => " + entidade + " Contexto => " + contextoAdd + " Valor => " + valor);
		return false;
	}	
	if (arguments.length == 5){		
		filtro += arguments[4];
		if (arguments[4].split("^")[2] == ""){
			console.log("Valor do Filtro não encontrado.");
			return false;
		}
	}

	if (td_atributo[atributo].chaveestrangeira != "" && td_atributo[atributo].chaveestrangeira != undefined && td_atributo[atributo].chaveestrangeira > 0){
		if (typeof td_entidadeauxiliar[td_atributo[atributo].chaveestrangeira] == "object"){
			$(".form-control[id=" + td_atributo[atributo].nome +"]",contextoAdd).html("");
			var entaux = td_entidadeauxiliar[td_atributo[atributo].chaveestrangeira];
			for (ea in entaux){
				var htmlOPT;
				eval("ophtmlOPTt = entaux[ea]." + td_atributo[td_entidade[td_atributo[atributo].chaveestrangeira].campodescchave].nome);
				var opt = "<option value='"+entaux[ea].id+"'>" + ophtmlOPTt + "</option>";
				$(".form-control[id=" + td_atributo[atributo].nome +"]",contextoAdd).append(opt);
			}
			if (valor != "" && valor != undefined){
				$(".form-control[id=" + td_atributo[atributo].nome + "]",contextoAdd).val(valor);
			}
		}else{
			try{
				var campochavedescricao = td_entidade[td_atributo[atributo].chaveestrangeira].campodescchave;
				if (campochavedescricao <= 0){
					console.log("Campo descrição da tabela ( ["+td_entidade[td_atributo[atributo].chaveestrangeira].id+"]"+td_entidade[td_atributo[atributo].chaveestrangeira].nomecompleto+" ) não encontrado ");
				}
			}catch(e){
				var campochavedescricao = 0;
				console.log('Chave estrangeira não encontrada e/ou atributo descrição não encontrado');
			}
			$.ajax({
				url:config.urlrequisicoes,
				type:"GET",
				data:{
					op:"carregar_options",
					entidade:td_atributo[atributo].chaveestrangeira,
					atributo:campochavedescricao,
					filtro:filtro
				},
				beforeSend:function(){
					$(".form-control[id=" + td_atributo[atributo].nome+"]",contextoAdd).html("<option value=''>Aguarde ...</option>");
				},
				complete:function(ret){
					var retorno = ret.responseText;
					if (obrigatorio == 0){
						var htmlretorno = "<option value=''>-- Selecione --</option>" + retorno;
					}else{
						var htmlretorno = retorno;
					}
					$(".form-control[id=" + td_atributo[atributo].nome+"][data-entidade="+td_entidade[td_atributo[atributo].entidade].nomecompleto+"]",contextoAdd).html(htmlretorno);
					$(".form-control[id=" + td_atributo[atributo].nome +"-old][data-entidade="+td_entidade[td_atributo[atributo].entidade].nomecompleto+"]",contextoAdd).html(htmlretorno);
					if (valor != ""){
						$(".form-control[id=" + td_atributo[atributo].nome+"][data-entidade="+td_entidade[td_atributo[atributo].entidade].nomecompleto+"]",contextoAdd).val(valor);
						$(".form-control[id=" + td_atributo[atributo].nome+"-old][data-entidade="+td_entidade[td_atributo[atributo].entidade].nomecompleto+"]",contextoAdd).val(valor);
					}
				},
				error:function(ret){
					console.log("ERRO ao carregar lista => " + ret.responseText);
				}
			});
		}	
	}
}
function excluirRegistroTemp(entidade,id){
	for (d in dados_temp){
		if (dados_temp[d].entidade == entidade && dados_temp[d].id == id.replace("T","")){
			dados_temp.splice(d,1);
		}
	}	
}

function setaGradeRelGeneralizacao(contexto){
	var entidadeGen = $("#select-generalizacao-unica").val();
	if (entidadeGen != "" && entidadeGen != undefined){
		var entidadeGenNome = td_entidade[entidadeGen].nome;
	}
}
function setaCkEditores(novo){
	// Setando valores do CK Editor
	for(c in CKEditores){
		var linha = c;
		var instanciaCK = CKEditores[c];
		if (novo){
			instanciaCK.setData("");
		}
	}
	
	$(".ckeditor").each(function(){
		if (CKEditores[$(this).data("entidade") + "^" + $(this).attr("id")] != undefined) return false;

		var idCampo = "div-editor-" + $(this).attr("id") + "-" + $(this).data("entidade");
		var instanciaEditor = "";
		if ( instanciaEditor )
			return;
		var config = {};
		var valor = "";

		instanciaEditor = CKEDITOR.appendTo( idCampo , config, valor );
		CKEditores[$(this).data("entidade") + "^" + $(this).attr("id")] = instanciaEditor;
	});	
}

function atribuiGrade(entidadeID){
	// Coloca a mensagem de nenhum registro nas entidades filho
	for(relGrade in td_relacionamento){
		if (td_relacionamento[relGrade].pai == entidadeID){
			if (parseInt(td_relacionamento[relGrade].filho)  == 0){
				bootbox.alert("Entidade <b>"+td_relacionamento[relGrade].descricao+"</b> não encontrada");
				continue;
			}
			// Grade de Dados dos Entidades de Relacionamento
			var gd = new GradeDeDados(td_relacionamento[relGrade].filho);
			gd.instancia	= gd;
			gd.contexto 	= "#crud-contexto-listar-" + getHierarquia(td_relacionamento[relGrade].filho);
			gd.addFiltro("id","=",-1);
			gradesdedados["#crud-contexto-listar-" + getHierarquia(td_relacionamento[relGrade].filho)] = gd;
			gd.exibirpesquisa = false;
			gd.nenhumRegistro();

			if (isPaiEntidade(td_relacionamento[relGrade].filho)){
				atribuiGrade(td_relacionamento[relGrade].filho);
			}
		}
	}
}
function entidadesRelacionamentos(entidade,id){	
	for(rER in td_relacionamento){		
		if (td_relacionamento[rER].pai == entidade){
			var atributogen = "";
			if (parseInt(td_relacionamento[rER].filho) != 0 && td_relacionamento[rER].filho != "" && td_relacionamento[rER].filho != undefined){
				if (td_relacionamento[rER].atributo != "" && td_relacionamento[rER].atributo != 0){
					atributogen = td_atributo[td_relacionamento[rER].atributo].nome;
				}else if(td_entidade[td_relacionamento[rER].filho].atributogeneralizacao != "" && parseInt(td_entidade[td_relacionamento[rER].filho].atributogeneralizacao) != 0){
					if (td_relacionamento[rER].filho != 0 && td_relacionamento[rER].filho != "" && td_relacionamento[rER].filho != undefined){
						atributogen = td_atributo[td_entidade[td_relacionamento[rER].filho].atributogeneralizacao].nome;
					}else{
						atributogen = "";
					}
				}else{
					atributogen = "";
				}
				if (td_relacionamento[rER].filho != 0 && td_relacionamento[rER].filho != "" && td_relacionamento[rER].filho != undefined){
					entidades.push({entidade:td_entidade[td_relacionamento[rER].filho].nomecompleto,atributo:atributogen,valor:id,tipoRel:td_relacionamento[rER].tipo,entidadepai:td_relacionamento[rER].pai});			
				}
			}	
		}
	}
}
function novoRegistroFormulario(objBotaoNovo){
	indice = dados.length;

	var entidade 			= $(objBotaoNovo).parents(".crud-contexto-listar").data("entidade");
	var entidadeID 			= $(objBotaoNovo).parents(".crud-contexto-listar").data("entidadeid");
	var contextoListar 		= "#" + $(objBotaoNovo).parents(".crud-contexto-listar").first().attr("id");
	var contextoAdd 		= "#" + $(objBotaoNovo).parents(".crud-contexto").first().find(".crud-contexto-add").attr("id");
	var fp 					= $(objBotaoNovo).parents(".crud-contexto-listar").first().hasClass("fp");
	var entidadeOBJ 		= td_entidade[entidadeID];

	if (textcase == 'uppercase'){	
		$(".form-control").val().toUpperCase();
	}	
	if (fp){
		$(".form-control[id=id]").val(""); // Limpa todos campos id
		setaPrimeiraAba();
		dados.splice(0,dados.length); // Limpa array "dados"
		dados_temp.splice(0,dados_temp.length); // Limpa array "dados_temp" responsável pelos registros temporários
		dadosatributodependencia.splice(0,dadosatributodependencia.length);
		setaCkEditores(true);
		$(".descricaoExibirEdicao").hide();
	}else{
		$(".form-control[id=id][data-entidade=" + entidade + "]").val("");		
		setaPrimeiraAba(contextoAdd);
		var indicetemp  = 1;
		for(d in dados_temp){
			++indicetemp;
		}
	}
	if (entidadeOBJ != undefined){
		if (entidadeOBJ.registrounico == 1){
			$(contextoAdd).find(".b-voltar").first().hide();
		}
	}else{
		console.warn('Objeto entidade não encontrado');
	}

	// Permissão dos atributos
	setPermissoesAtributos(EntidadePrincipalID,contextoAdd,"novo");	
	setaAtributoGeneralizacaoLista(contextoAdd); // Tem que ser depois do método setPermissoesAtributos

	// Percorre todos os campos do cadastro, incluindo as abas de relacionamento
	$(".form-control",contextoAdd).each(function(){
		var atributo = $(this).attr("id");
		if (atributo == ""){
			bootbox.alert('Existe um campo no formul\u00e1rio que n\u00e3o possui o atributo ID. Maiores detalhes no console do navegador');
			return false;
		}

		var entidadeAttr = $(this).data("entidade");
		var valor = "";
		var tag = $(this).prop("tagName");
		var atributoID = getIdAtributo(atributo,entidadeAttr);
		if (parseInt(atributoID) != 0 && atributoID != ""){
			var atributoOBJ = td_atributo[atributoID];
			if (parseInt(atributoOBJ.tipoinicializacao) == 1 && atributoOBJ.inicializacao != ""){
				eval("valor = " + atributoOBJ.inicializacao + ";");
			}
		}

		switch(tag){
			case "INPUT":
				$("#" + atributo,contextoAdd).val(valor);
			break;
			case "SELECT":
				if (valor == ""){
					if (atributoID > 0){
						if (parseInt(td_atributo[atributoID].nulo) == 0){
							$("#" + atributo + " option:first-child",contextoAdd).attr("selected",true);
						}else{
							if ($("#" + atributo,contextoAdd).find("option[value=0]").length > 0){
								$("#" + atributo,contextoAdd).val(0);
							}else{
								$("#" + atributo,contextoAdd).val("");
							}
						}
					}	
				}else{
					$("#" + atributo,contextoAdd).val(valor);
					setTimeout(function(){$("#" + atributo,contextoAdd).change();},1000);
				}
			break;
			case "TEXTAREA":
				$("#" + atributo,contextoAdd).val(valor);
			break;
			default:
				$("#" + atributo,contextoAdd).val(valor);
		}

		if (parseInt(atributoID) != 0 && atributoID != "" && atributoID != undefined){			
			var nomeEntidadeDados = td_entidade[td_atributo[atributoID].entidade].nomecompleto;
			valor = td_atributo[atributoID].inicializacao;
			obrigatorio = td_atributo[atributoID].nulo;
			if (td_atributo[atributoID].chaveestrangeira != "" && td_atributo[atributoID].tipohtml == "4"){
				if (fp){ // Só carrega as listas se for acesso o botão novo do formulário principal
					carregarListas(nomeEntidadeDados,atributoID,contextoAdd,valor);
				}
			}
			if ($("#" + td_atributo[atributoID].nome + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).hasClass("checkbox-sn")){
				$("#" + td_atributo[atributoID].nome + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).val(0);
			}
			if ($("#" + td_atributo[atributoID].nome + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).hasClass("td-file-hidden")){
				$("#" + td_atributo[atributoID].nome + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).parents(".form-group").find("iframe").first().attr("src",getURLProject("index.php?controller=upload&atributo="+td_atributo[atributoID].id+"&valor=&id=" + indicetemp));
			}
		}

		$(".checkbox-s",contextoAdd).removeClass("active");
		$(".checkbox-n",contextoAdd).addClass("active");
		
		if (fp){
			
			if ($('#select-generalizacao-multipla').length > 0){
				$('#select-generalizacao-multipla').SumoSelect({placeholder: 'Selecione'});
				$('#select-generalizacao-multipla option').removeAttr("selected");
				$('#select-generalizacao-multipla')[0].sumo.reload();
				$('#select-generalizacao-multipla').change();
				$('#select-generalizacao-multipla')[0].sumo.unSelectAll();
			}
		}else{
			
		}
	});

	$(".form-group",contextoAdd).removeClass("has-success");
	$(".form-control-feedback").remove();

	if ($("#select-generalizacao-unica",contextoAdd)){
		if (fp){
			$("#select-generalizacao-unica",contextoAdd).removeAttr("readonly");
			$("#select-generalizacao-unica",contextoAdd).removeAttr("disabled");
		}
		var entidade = $("#select-generalizacao-unica",contextoAdd).find("option:selected").data("entidade-nome");
		if (fp){
			setaGradeRelGeneralizacao("#crud-contexto-listar-" + entidade);
		}
		setaLayoutGeneralizao(contextoAdd);
	}

	atribuiGrade(entidadeID);
	setarformdadospreenchido();
	naoExibirCampos(contextoAdd);
	$(contextoAdd).show();
	$(contextoListar).hide();	
	if (typeof afterNew === "function") afterNew(contextoAdd);
}
/* 
	*********** FILTRO ************* 
	Configuração dos campos tipo filtro
*/
$(".termo-filtro").blur(function(){
	var termo = this.value;
	var entidadeNome = $(this).data("fk");
	var nome = $(this).prop("id");	
	var modalName = $(this).parents(".filtro-pesquisa").data("modalname");
	var entidadeContexto = $(this).data("entidade");
	buscarFiltro(termo,entidadeNome,nome,modalName,entidadeContexto);
});
$(".termo-filtro").keyup(function(e){
	if (e.which == 13){
		var termo = this.value;
		var entidadeNome = $(this).data("entidade");
		var nome = $(this).prop("id");
		var modalName = $(this).parents(".filtro-pesquisa").data("modalName");
		var entidadeContexto = $(this).data("entidade");
		buscarFiltro(termo,entidadeNome,nome,modalName,entidadeContexto);
	}
});
$('.botao-filtro').click(function(){
	var modalName = $(this).parents(".filtro-pesquisa").data("modalname");
	var chaveestrangeira = $(this).data("fk");
	var atributo = $(this).parents(".filtro-pesquisa").find(".termo-filtro").attr("id");

	dadosFiltroEnderecoTemp = {
		filtro:$(this).parents(".filtro-endereco").first()
	};
	// Filtro de Endereço
	if ($(this).parents(".filtro-pesquisa").hasClass("filtro-endereco")){
		$.ajax({
			url:"system/controller/enderecofiltro.html",
			data:{
				controller:"enderecogoogle",
				entidade:chaveestrangeira,
				contexto:modalName
			},
			complete:function(ret){
				$("#"+modalName+cmodal).html(ret.responseText);
			},
			error:function(ret){
				console.log("ERRO ao abrir o filtro => " + ret.responseText);
			}			
		});
	}else{
		var contextoGrade = '#'+modalName+cmodal;
		criagrade(contextoGrade,chaveestrangeira,atributo,modalName,$(this).data("entidade"));
		gradesdedados[contextoGrade].show();
	}

	$('#'+modalName+' .modal-footer').css('border','0px');
	$('#'+modalName).modal({
		backdrop:false
	});
	$('#'+modalName).modal('show');
});
function buscarFiltro(termo,entidadeNome,nome,modalName,entidadeContexto){
	if (termo=="" || termo==undefined){
		$('#descricao-' + nome + "[data-entidade="+entidadeContexto+"]").val("");
		habilitafiltro(nome,"",false);
		return false;
	}
	var filtro = "";
	if (gradesdedados["#" + modalName + cmodal]){
		filtro = gradesdedados["#" + modalName + cmodal].getFiltros();
	}
	$.ajax({
		type:'GET',
		url:config.urlrequisicoes,
		data:{
			op:'retorna_descricao_filtro',
			termo:termo,
			entidade:entidadeNome,
			filtro:filtro
		},
		success:function(retorno){
			if (retorno.trim() == ""){				
				$('#descricao-' + nome + "[data-entidade="+entidadeContexto+"]").val("");
				$('#' + nome + "[data-entidade="+entidadeContexto+"]").val("");
				habilitafiltro(nome,"",false,entidadeContexto);
			}else{
				$('#descricao-' + nome + "[data-entidade="+entidadeContexto+"]").val(retorno.trim());
				$('#' + nome + "[data-entidade="+entidadeContexto+"]").val(termo);
				habilitafiltro(nome,"",true,entidadeContexto);
			}
			$('#' + modalName).modal('hide');
		},
		error:function(ret){
			console.log("ERRO ao buscar filtro => " + ret.responseText);
		}
	});
}
function habilitafiltro(atributo,contexto,habilita,entidadeContexto){
	for (e in td_atributo){
		if (td_atributo[e].atributodependencia != "" && td_atributo[e].atributodependencia != 0){
		
			var dep = td_atributo[td_atributo[e].atributodependencia];
			var attr = td_atributo[e];
			var entidade = td_entidade[attr.entidade];
			var entidadeAtributo = td_entidade[td_atributo[e].entidade];

			if (dep.nome == atributo && entidadeAtributo.nomecompleto == entidadeContexto){
				// Limpa o campo a cada alteração
				$("#" + attr.nome).val("");
				$("#" + attr.nome).attr("disabled",true);
				$("#" + attr.nome).parents(".filtro-pesquisa").find(".descricao-filtro").val("");
				$("#" + attr.nome).parents(".filtro-pesquisa").find(".botao-filtro").hide();
				
				if (habilita){
					var campofiltro = atributo;
					var valorfiltro = $("#" + atributo).val();
					if (valorfiltro != ""){
						$("#" + attr.nome).attr("disabled",false);
						var entidadeFiltro = td_entidade[attr.entidade];
						var contextoFiltro = "#" + attr.nome;						
						var valordependencia = "";

						if ($(contextoFiltro,contexto).prop("tagName") == "SELECT"){
							for(dap in dadosatributodependencia){
								var d = dadosatributodependencia[dap];
								if (entidadeAtributo.nomecompleto == d.entidade && attr.nome == atributo){
									valordependencia = d.valor;
								}
							}
							carregarListas(entidadeFiltro.nomecompleto,attr.nome,contexto,valordependencia,campofiltro + "^=^" + $("#" + campofiltro).val());
						}else{					

							$("#" + attr.nome).parents(".filtro-pesquisa").find(".botao-filtro").show();

							var modalName = pModalName + entidade.nomecompleto + "-" + attr.nome;
							var contextoGrade = "#" + modalName + cmodal;
							
							
							for(fk in td_atributo){
								if (td_atributo[fk].entidade == attr.chaveestrangeira){
									for(relFt in td_relacionamento){
										if (td_relacionamento[relFt].pai == td_atributo[fk].chaveestrangeira && td_relacionamento[relFt].filho == td_atributo[fk].entidade){
											var campofiltro = td_atributo[td_relacionamento[relFt].atributo].nome;
											var entidadeFiltro = td_entidade[td_atributo[fk].entidade].nomecompleto;
										}
									}
								}
							}

							criagrade(contextoGrade,attr.chaveestrangeira,attr.nome,modalName,entidadeContexto);
							gradesdedados[contextoGrade].filtros.splice(0,gradesdedados[contextoGrade].filtros.length);
							gradesdedados[contextoGrade].addFiltro(campofiltro,"=",$("#" + dep.nome).val());
						}
					}	
				}
			}
		}		
	}	
}
function criagrade(contextoGrade,chaveestrangeira,atributo,modalName,entidadeContexto){
	if (gradesdedados[contextoGrade] == undefined){
		var gradeJS = new GradeDeDados(chaveestrangeira);
		gradeJS.contexto=contextoGrade;
		gradeJS.instancia=gradeJS;
		gradeJS.retornaFiltro = true;
		gradeJS.atributoRetorno = atributo;
		gradeJS.modalName = modalName;
		gradeJS.entidadeContexto = entidadeContexto;
		gradesdedados[contextoGrade] = gradeJS;
	}
}

// Seta permissões dos campos do formulário
function setPermissoesAtributos(entidade,contexto,funcao){
	for (permissao in td_permissoes){
		if (session.userid == td_permissoes[permissao].usuario && entidade == td_permissoes[permissao].entidade){
			for (a in td_atributo){
				if (td_atributo[a].entidade == entidade){
					for (attr in td_permissoes[permissao].atributos){
						if (td_atributo[a].id == attr){
							var attrOBJ = td_permissoes[permissao].atributos[attr];
							if (funcao == "edicao"){
								if (parseInt(attrOBJ.editar) != 1){
									$(contexto + ".crud-contexto-add #" + td_atributo[a].nome).first().attr("disabled",true);
								}else{
									$(contexto + ".crud-contexto-add #" + td_atributo[a].nome).first().removeAttr("disabled");
								}
							}else if (funcao == "novo"){
								$(contexto + ".crud-contexto-add #" + td_atributo[a].nome).first().removeAttr("disabled");
							}
							if (parseInt(attrOBJ.visualizar) != 1){
								$(contexto + ".crud-contexto-add #" + td_atributo[a].nome).parents(".form-group").first().hide();
							}
						}
					}
				}
			}
		}
	}
}

// Validações e Formações padrão para formulário
function camposObrigarios(campos){
	for (c in campos){		
		if ($(campos[c]).val() == "" || $(campos[c]) == null || $(campos[c]).val() != undefined){
			alert('Vazio');			
		}
	}
}
$(".botao-multilinha").click(function(){
	var modalname = $(this).data("modalname");
	$("#" + modalname).modal({
		backdrop:false
	});
	$("#" + modalname).modal("show");
});
$(".textarea-modal").keyup(function(){
	$(this).parents(".form-group").find(".formato-multilinha").val($(this).val());
});
$(".formato-multilinha").keyup(function(){	
	$(this).parents(".form-group").find(".textarea-modal").html($(this).val());
});
$(".botao-multilinha").click(function(){
	var texto = $(this).parents(".form-group").find(".formato-multilinha").val();
	$(this).parents(".form-group").find(".textarea-modal").html(texto);
});

$(".botao-ckeditor").click(function(e){
	var modalname = $(this).data("modalname");
	$("#" + modalname).find(".modal-body").css("height","350px");
	$("#" + modalname).modal({
		backdrop:false
	});	
	var campotexto = $(this).parents(".ckeditor-group").find(".formato-ckeditor").first();
	CKEditores[campotexto.data("entidade") + "^" + campotexto.attr("id")].setData($("#" + campotexto.attr("id") + "[data-entidade="+campotexto.data("entidade")+"]").val());
	$("#" + modalname).modal("show");
	setTimeout( ()=> {
		$("#" + modalname).removeAttr("tabindex");
	},500);
});
$(".checkbox-s,.checkbox-n").click(function(){
	var campo = ".form-control[data-entidade=" + $(this).find("input").data("entidade") + "][id=" + $(this).find("input").data("atributo") + "]";
	$(campo).val($(this).find("input").val());
});
function setGeneralizacaoMultipla(){
	if ($("#select-generalizacao-multipla").length > 0){
		$(".generalizacaoABA-M").hide();
		for(rm in td_relacionamento){
			if (parseInt(td_relacionamento[rm].tipo) == 9 && parseInt(td_relacionamento[rm].pai) == EntidadePrincipalID){
				var opt = $("<option value='"+td_relacionamento[rm].filho+"'>"+td_relacionamento[rm].descricao+"</option>");
				$("#select-generalizacao-multipla").append(opt);
			}
		}		
	}
}
function atribuiValoresCKEditor(){
	// Atribui valores dos campos CK Editor	
	for(c in CKEditores){
		var instanciaCK = CKEditores[c];
		var entidadeCK = c.split("^")[0];
		var atributoCK = c.split("^")[1];
		$("#" + atributoCK + "[data-entidade="+entidadeCK+"]",contextoAdd).val(instanciaCK.getData());
	}
}
function entidadesRelacionamentosLoad(entidade,id,relacionamento){	
	var atributo_rel_edicao = "";				
	if (relacionamento.atributo == undefined || relacionamento.atributo == "" || relacionamento.atributo == 0){
		if (relacionamento.filho != 0 && relacionamento.filho != "" && relacionamento.filho != undefined){
			if (relacionamento.tipo == 5 || relacionamento.tipo == 10 || relacionamento.tipo == 9){
				atributo_rel_edicao = "";
			}else{
				atributo_rel_edicao =  td_entidade[relacionamento.filho].atributogeneralizacao;
			}
		}
	}else{
		atributo_rel_edicao = relacionamento.atributo;
	}
	if (atributo_rel_edicao != "" && parseInt(atributo_rel_edicao) != 0){
		atributoRelNome = td_atributo[atributo_rel_edicao].nome;
	}else{
		atributoRelNome = "";
	}
	if (relacionamento.filho != 0 && relacionamento.filho != "" && relacionamento.filho != undefined){
		if (isEntidadePai(relacionamento.filho)){
			entidadesRelacionamentos(relacionamento.filho,id);
		}

		if (relacionamento.tipo == 5 || relacionamento.tipo == 10 || relacionamento.tipo == 9){
			entidadesListaId.push(relacionamento.filho);
			entidadesListaNome.push({
				id:relacionamento.filho,
				nome:td_entidade[relacionamento.filho].nomecompleto.replace("-","."),
				atributos:getAtributos(relacionamento.filho)
			});
		}else{
			entidadesId.push(relacionamento.filho);
			entidadesNome.push({
				id:relacionamento.filho,
				nome:td_entidade[relacionamento.filho].nomecompleto.replace("-","."),
				atributos:getAtributos(relacionamento.filho)
			});
		}		
		entidades.push({entidade:td_entidade[relacionamento.filho].nomecompleto,atributo:atributoRelNome,valor:id,tipoRel:relacionamento.tipo,entidadepai:relacionamento.pai});
		// Inicializa formatação de dados que não foram alterados
		$("#crud-contexto-add-" + (getHierarquia(relacionamento.filho) == ""?entidadeNome:getHierarquia(relacionamento.filho)) + " .form-control").each(function(){
			if ($(this).prop("tagName") == "SELECT"){
				var atributo = $(this).attr("id").split(" ")[0];
				carregarListas($(this).data("entidade"),$(this).attr("id"),"");
			}
			if ($(this).hasClass("checkbox-sn")){
				$("input[type=radio][data-atributo="+$(this).attr("id")+"]").parents(".checkbox-n").addClass("active");
				$(this).val(0);
			}
		});
	}
}
function isEntidadePai(entidade){
	var retorno = false;
	for(rep in td_relacionamento){		
		if (td_relacionamento[rep].pai == entidade){
			retorno = true;
		}
	}
	return retorno;
}
function exibirDadosEdicao(entidade,id){
	// Exibir Dados Edição
	for(a in td_entidade){
		if (td_entidade[a].id == entidade && td_entidade[a].campodescchave != "" && td_entidade[a].campodescchave != 0){
			$(".descricaoExibirEdicao .campodescricaoExibirEdicao").html($("#" + td_atributo[td_entidade[a].campodescchave].nome).val());
			$(".descricaoExibirEdicao .idExibirEdicao").html("<small>ID: </small>" + id);
			$(".descricaoExibirEdicao").show();
			
		}
	}
}
function getAtributos(entidade){
	var atributos = "";
	for (a in td_atributo){
		if (td_atributo[a].entidade == entidade){
			atributos += (atributos==""?"":",") + td_atributo[a].nome;
		}
	}
	return atributos;
}
function liberaBotao(btn){
	btn.removeAttr("disabled");
	btn.removeAttr("readonly");
}

function setaAtributoGeneralizacaoLista(contexto){
	if ($("#select-generalizacao-unica").length == 0 && $("#select-generalizacao-multipla").length == 0) return false;
	$(".form-control",contexto).each(function(){
		if ($(this).attr("atributo") != undefined){
			var idatributo = $(this).attr("atributo");
			if (td_atributo[idatributo] == undefined) return false;
			if (td_atributo[idatributo].atributodependencia != "" && td_atributo[idatributo].atributodependencia > 0 && td_atributo[idatributo].atributodependencia != undefined){
				
				$("#" + td_atributo[idatributo].nome,contexto).attr("disabled",true);
				$("#" + td_atributo[idatributo].nome,contexto).parents(".filtro-pesquisa").find(".descricao-filtro").val("");
				$("#" + td_atributo[idatributo].nome,contexto).parents(".filtro-pesquisa").find(".botao-filtro").hide();
				if ($(this).prop("tagName") == "SELECT"){
					$("#" + td_atributo[idatributo].nome,contexto).html("<option value=''>Selecione</option>");
				}
				var atributodependencia = td_atributo[td_atributo[idatributo].atributodependencia];
				$("#" + atributodependencia.nome,contexto).change(function(){
					var atributofiltro = "";
					for (a in td_atributo){
						if (td_atributo[a].entidade == td_atributo[idatributo].chaveestrangeira){
							if (atributodependencia.chaveestrangeira == td_atributo[a].chaveestrangeira){
								atributofiltro = td_atributo[a].nome;
								break;
							}
						}
					}
					var valordependencia = "";
					for(dap in dadosatributodependencia){
						var d = dadosatributodependencia[dap];
						if (td_entidade[td_atributo[idatributo].entidade].nomecompleto == d.entidade && td_atributo[idatributo].nome == d.atributo){
							valordependencia = d.valor;
						}
					}
							
					$("#" + td_atributo[idatributo].nome).removeAttr("disabled");
					var filtro = atributofiltro!=""?atributofiltro+ "^=^" + $(this).val():"";
					carregarListas(td_entidade[td_atributo[idatributo].entidade].nomecompleto,td_atributo[idatributo].nome,contexto,valordependencia,filtro);
				});
				
			}
		}
	});	
}
$(".atributodependenciapai").change(function(){
	var atributoid = $(this).attr("atributo");
	for (a in td_atributo){
		if (td_atributo[a].atributodependencia == atributoid){
			if (td_atributo[a].id == 279 && $("#id[data-entidade=" + $(this).data("entidade")).val() != "") return false; // Gambiarra para a empresa Mais Saúde e Bem Estar
			carregarListas(td_atributo[a].chaveestrangeira,td_atributo[a].id,contextoAdd,"",$(this).attr("id")+"^=^"+$(this).val());
		}
	}
});
$(".ckeditor-field").on('hidden.bs.modal', function (e){
  var nomecompleto = $(this).data("nomecompleto").split("-");
  $("#" + nomecompleto[0] + "[data-entidade="+nomecompleto[1]+"]").val(CKEditores[nomecompleto[1] + "^" + nomecompleto[0]].getData());
});

function setarformdadospreenchido(){
	$(".crud-contexto-add").each(function(){
		var campos = [];
		// Campo de texto
		$(this).find(".tdform .form-control").each(function(){
			campos.push({
				nome: $(this).attr("id"),
				valor: $(this).val()
			});
		});
		monitorformdadospreenchido[$(this).data("entidadeid")] = campos;
	});
}

function isalteracaoform(entidade,tdform){
	var f = monitorformdadospreenchido[entidade];
	var alterado = false;
	for (c in f){
		var campo = f[c];
		if (campo.valor != tdform.find(".form-control[id="+campo.nome+"]").val()){
			alterado = true;
		}
	}
	return alterado;
}

function naoExibirCampos(contexto){	
	$(".form-control",contexto).each(function(){		
		var atributo = $(this).attr("id");
		if (atributo == ""){
			alert('Existe um campo no formul\u00e1rio que n\u00e3o possui o atributo ID. Maiores detalhes no console do navegador');
			return false;
		}
		
		var entidadeAttr = $(this).data("entidade");
		var atributoID = getIdAtributo(atributo,entidadeAttr);		
		if (parseInt(atributoID) != 0){			
			var atributoOBJ = td_atributo[atributoID];
			if (parseInt(atributoOBJ.naoexibircampo) == 1){
				$(this).parents(".coluna").remove();
			}
		}
	});
}

function excluirArquivoUpload(dadosarquivos,entidade,atributo){
	$("iframe[data-entidade="+entidade+"][data-atributo="+atributo+"]").attr("src",getURLProject("index.php?controller=upload&atributo="+atributo+"&valor="));
	$("[atributo="+atributo+"]").val('{"op":"excluir","filename":"'+dadosarquivos.filename+'"}');
}
function enviarEmailErro(erro){
	/*
	$.ajax({
		url:config.urlrequisicoes,
		type:"POST",
		data:{
			op:"enviaremailerro",
			mensagemerro:erro
		}
	});
	*/
}

function loading2Page(seletor,exibir = true){
	if (exibir){
		$(seletor + " .loader-salvar").show();
		$(seletor + " .loader-salvar .loading2").show();
	}else{
		$(seletor + " .loader-salvar").hide();
		$(seletor + " .loader-salvar .loading2").hide();
	}
}