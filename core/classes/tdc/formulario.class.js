 function tdFormulario (){
	this.registro_id				= 0;
    this.entidade_id                = 0;
    this.entidade                   = null;
    this.contexto                   = '';
    this.contexto_add               = '';
    this.contexto_listar            = '';
    this.is_principal               = true;
    this.textcase                   = 0; // 0 - None | 1 - CamelCase | 2 - UpperCase | 3 - LowerCase
    this.dados                      = [];
	this.temp_registro				= 0;
    this.CKEditores                 = [];
    this.dadosatributodependencia   = [];
    this.registrounico              = false;
	this.monitorformdadospreenchido	= [];
	this.entidades_filho			= [];
	this.is_pai						= false;
	this.entidade_pai				= 0;
	this.gradesdados				= null;
	this.btn_novo					= null;
	this.btn_voltar					= null;
	this.btn_salvar					= null;
	this.composicao					= [];
	this.entidades					= []; // Usada para carregar os dados na edição
	this.cmodal						= ' .modal-body p'; // Complemento Modal

    this.construct(arguments[0], arguments[1] , arguments[2]);
}

tdFormulario.prototype.construct = function(entidade_id,registro_id = 0,entidade_pai = 0){
	if (td_entidade[entidade_id] != undefined){
		this.entidade_id    = entidade_id;
		this.entidade       = td_entidade[entidade_id];
		this.entidade_pai 	= entidade_pai;
		this.registro_id	= registro_id;
		this.init();
	}else{
		console.warn('Entidade => ' + entidade_id + ' não existe em td_entidade.');
		console.warn('*** Provavelmente não tem entidade no Menu Topo. ***');
	}
}

tdFormulario.prototype.init = function(){
	this.setContexto();
	this.setEntidadesFilho();
	this.setBotoes();
	this.emExecucao();
	this.setBuscaFiltro();
	this.addHTMLPersonalizado();
}
tdFormulario.prototype.novo = function(){
	let contextoListar 		= this.getContextoListar();
	let contextoAdd 		= this.getContextoAdd();
	let indicetemp          = 1;
	let id_init_val			= 0;

	if (this.textcase == 'uppercase'){	
		$(".form-control").val().toUpperCase();
	}

	this.setaPrimeiraAba();
	if (this.is_principal){
		$(".form-control[id=id]",contextoAdd).val(id_init_val); // Limpa todos campos id
		this.dados.splice(0,this.dados.length); // Limpa array "dados"
		this.dadosatributodependencia.splice(0,this.dadosatributodependencia.length);
		this.setCkEditores();
		$(".descricaoExibirEdicao").hide();
	}else{
		$('.form-control[id=id][data-entidade="' + this.entidade.id + '"]').val(id_init_val);
	}

	if (this.entidade != undefined){
		if (this.entidade.registrounico){
			$(contextoAdd).find(".b-voltar").first().hide();
		}
	}else{
		console.warn('Objeto entidade não encontrado');
	}

	// Permissão dos atributos
	this.setPermissoesAtributos('novo');

	let instancia = this;

	// Percorre todos os campos do cadastro, incluindo as abas de relacionamento
	// Os campos ID não podem ser alterados :not([id="id"])
	$('.form-control:not([id="id"])',contextoAdd).each(function()
	{
		let atributo 	= $(this).attr("id");
		if (atributo == ""){
			bootbox.alert('Existe um campo no formul\u00e1rio que n\u00e3o possui o atributo ID. Maiores detalhes no console do navegador');
			return false;
		}

		let entidadeAttr 	= $(this).data("entidade");
		let valor 			= "";
		let tag 			= $(this).prop("tagName");
		let atributoID 		= getIdAtributo(atributo,entidadeAttr);
		if (parseInt(atributoID) != 0 && atributoID != ""){
			let atributoOBJ = td_atributo[atributoID];
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
			let _tipo_html = td_atributo[atributoID].tipohtml;
			if (td_atributo[atributoID].chaveestrangeira != "" && (_tipo_html == "4" || _tipo_html == "5")){
				// Só carrega as listas se for acessado o botão novo do formulário principal
				if (instancia.is_principal){ 
					carregarListas(entidadeAttr,atributoID,contextoAdd,valor);
				}
			}

			let attr_selector = "#" + td_atributo[atributoID].nome + '[data-entidade="'+entidadeAttr+'"]';

			if (valor == ''){
				if ($(attr_selector,contextoAdd).hasClass("checkbox-sn")){
					$(attr_selector,contextoAdd).val(0);
				}
			}else{
				if ($(attr_selector,contextoAdd).hasClass("checkbox-sn")){
					$(attr_selector,contextoAdd).val(valor);
					setTimeout(function(){
						$(attr_selector,contextoAdd).parents('.form-group').first().find('.checkbox-'+(valor==1?'s':'n')).click();
					},1000)
				}
			}

			if ($(attr_selector,contextoAdd).hasClass("td-file-hidden")){
				$(attr_selector,contextoAdd).parents(".form-group").find("iframe").first().attr("src",getURLProject("index.php?controller=upload&atributo="+td_atributo[atributoID].id+"&valor=&id=" + indicetemp));
			}
		}

		$(".checkbox-s",contextoAdd).removeClass("active");
		$(".checkbox-n",contextoAdd).addClass("active");

		if (instancia.is_principal){
			if ($('#select-generalizacao-multipla').length > 0){
				$('#select-generalizacao-multipla').SumoSelect({placeholder: 'Selecione'});
				$('#select-generalizacao-multipla option').removeAttr("selected");
				$('#select-generalizacao-multipla')[0].sumo.reload();
				$('#select-generalizacao-multipla').change();
				$('#select-generalizacao-multipla')[0].sumo.unSelectAll();
			}	
		}
	});

	$(".form-group",contextoAdd).removeClass("has-success");
	$(".form-control-feedback").remove();

	if ($("#select-generalizacao-unica",contextoAdd))
	{
		if (instancia.is_principal){
			$("#select-generalizacao-unica",contextoAdd).removeAttr("readonly");
			$("#select-generalizacao-unica",contextoAdd).removeAttr("disabled");
		}
		instancia.setaLayoutGeneralizao();
	}
	
	this.setarformdadospreenchido();
	this.naoExibirCampos(contextoAdd);

	if (this.is_pai){
		this.entidades_filho.forEach(function(e){
			formulario[e] 				= new tdFormulario(e , 0, this.entidade.id);
			formulario[e].is_pai 		= false;
			formulario[e].is_principal	= false;
			formulario[e].newGrade();
		},this);
	}

	$(contextoAdd).show();
	$(contextoListar).hide();
	if (typeof afterNew === "function") afterNew(contextoAdd);
}

tdFormulario.prototype.setContexto = function(){
	let hierarquia 			= getHierarquia(this.entidade_pai,this.entidade.id);
	this.contexto_add       = '#crud-contexto-add-' + hierarquia;
	this.contexto_listar    = '#crud-contexto-listar-' + hierarquia;
}

tdFormulario.prototype.getContextoAdd = function(){
	return this.contexto_add;
}

tdFormulario.prototype.getContextoListar = function(){
	return this.contexto_listar;
}


tdFormulario.prototype.setaPrimeiraAba = function(){
	// Remove a aba ativa
	$(".nav-tabs li,.tab-content div"	,this.getContextoAdd()).removeClass("active");
	//Habilita a primeira aba
	$(".nav-tabs li:first-child"		,this.getContextoAdd()).addClass("active"); 
	// Habilita a primeira div
	$(".tab-content div:first-child"	,this.getContextoAdd()).addClass("active in"); 
}

tdFormulario.prototype.setCkEditores = function(){

	let instancia = this;
	$(".ckeditor").each(function(){

		if (instancia.CKEditores[$(this).data("entidade") + "^" + $(this).attr("id")] != undefined) return false;
	
		let idCampo = "div-editor-" + $(this).attr("id") + "-" + $(this).data("entidade");
		let config  = {};
		let valor   = "";
		
		if (CKEDITOR == undefined){
			console.warn('Biblioteca CKEDITOR não encontrada.');
		}else{
			const instanciaEditor = CKEDITOR.appendTo( idCampo , config, valor );
			instancia.CKEditores[$(this).data("entidade") + "^" + $(this).attr("id")] = instanciaEditor;
		}
	});

	$(".botao-ckeditor").click(function(e){
		var modalname = $(this).data("modalname");
		$("#" + modalname).find(".modal-body").css("height","350px");
		$("#" + modalname).modal({
			backdrop:false
		});	
		var campotexto = $(this).parents(".ckeditor-group").find(".formato-ckeditor").first();
		instancia.CKEditores[campotexto.data("entidade") + "^" + campotexto.attr("id")].setData($("#" + campotexto.attr("id") + "[data-entidade="+campotexto.data("entidade")+"]").val());
		$("#" + modalname).modal("show");
		setTimeout( ()=> {
			$("#" + modalname).removeAttr("tabindex");
		},500);
	});

	$(".ckeditor-field").on('hidden.bs.modal', function (e){
		var nomecompleto = $(this).data("nomecompleto").split("-");
		$("#" + nomecompleto[0] + "[data-entidade="+nomecompleto[1]+"]").val(instancia.CKEditores[nomecompleto[1] + "^" + nomecompleto[0]].getData());
	});	
}

// Seta permissões dos campos do formulário
tdFormulario.prototype.setPermissoesAtributos = function(funcao){
	for (permissao in td_permissoes){
		if (session.userid == td_permissoes[permissao].usuario && this.entidade.id == td_permissoes[permissao].entidade){
			for (a in td_atributo){
				if (td_atributo[a].entidade == this.entidade.id){
					for (attr in td_permissoes[permissao].atributos){
						if (td_atributo[a].id == attr){
							var attrOBJ = td_permissoes[permissao].atributos[attr];
							if (funcao == "edicao"){
								if (parseInt(attrOBJ.editar) != 1){
									$(this.getContextoAdd() + ".crud-contexto-add #" + td_atributo[a].nome).first().attr("disabled",true);
								}else{
									$(this.getContextoAdd() + ".crud-contexto-add #" + td_atributo[a].nome).first().removeAttr("disabled");
								}
							}else if (funcao == "novo"){
								$(this.getContextoAdd() + ".crud-contexto-add #" + td_atributo[a].nome).first().removeAttr("disabled");
							}
							if (parseInt(attrOBJ.visualizar) != 1){
								$(this.getContextoAdd() + ".crud-contexto-add #" + td_atributo[a].nome).parents(".form-group").first().hide();
							}
						}
					}
				}
			}
		}
	}

	// Atributo dependencia	
	this.setAtributoDependencia();
}


tdFormulario.prototype.setAtributoDependencia = function(){
	let instancia = this;
	$(".form-control",this.getContextoAdd()).each(function(){
		if ($(this).attr("atributo") != undefined){
			var idatributo = $(this).attr("atributo");
			if (td_atributo[idatributo] == undefined) return false;

			if (
				td_atributo[idatributo].atributodependencia != "" && 
				td_atributo[idatributo].atributodependencia > 0 && 
				td_atributo[idatributo].atributodependencia != undefined
			){				
				let $_atributo = $("#" + td_atributo[idatributo].nome,instancia.getContextoAdd());
				$("#" + td_atributo[idatributo].nome,instancia.getContextoAdd()).attr("disabled",true);
				$("#" + td_atributo[idatributo].nome,instancia.getContextoAdd()).parents(".filtro-pesquisa").find(".descricao-filtro").val("");
				$("#" + td_atributo[idatributo].nome,instancia.getContextoAdd()).parents(".filtro-pesquisa").find(".botao-filtro").hide();
				if ($(this).prop("tagName") == "SELECT"){
					$("#" + td_atributo[idatributo].nome,instancia.getContextoAdd()).html("<option value=''>Selecione</option>");
				}
				let atributodependencia = td_atributo[td_atributo[idatributo].atributodependencia];
				$("#" + atributodependencia.nome,instancia.getContextoAdd()).change(function(){
					let _valor = $(this).val();
					let atributofiltro = "";
					if (_valor == ''){
						$_atributo.html("<option value=''>-- Selecione --</option>");
						$_atributo.attr("disabled",true);
					}else{
						for (a in td_atributo){
							if (td_atributo[a].entidade == td_atributo[idatributo].chaveestrangeira){
								if (atributodependencia.chaveestrangeira == td_atributo[a].chaveestrangeira){
									atributofiltro = td_atributo[a].nome;
									break;
								}
							}
						}

						let valordependencia = "";
						for(dap in this.dadosatributodependencia){
							let d = dadosatributodependencia[dap];
							if (td_entidade[td_atributo[idatributo].entidade].nomecompleto == d.entidade && td_atributo[idatributo].nome == d.atributo){
								valordependencia = d.valor;
							}
						}

						$("#" + td_atributo[idatributo].nome).removeAttr("disabled");
						let filtro = atributofiltro!=""?atributofiltro+ "^=^" + $(this).val():"";
						carregarListas(td_entidade[td_atributo[idatributo].entidade].nomecompleto,td_atributo[idatributo].nome,instancia.getContextoAdd(),valordependencia,filtro);
					}
				});
			}
		}
	});	
}

tdFormulario.prototype.setaLayoutGeneralizao = function(){
	let contexto = this.getContextoAdd();
	// Habilitação das ABAS das Entidades de Relacionamentos
	if ($(".div-relacionamento-generalizacao,.generalizacaoABA",contexto)){
		$(".div-relacionamento-generalizacao,.generalizacaoABA",contexto).hide();
	}

	if ($(".generalizacaoABA",contexto)[0]){
		$(".generalizacaoABA",contexto)[0].style.display = "";
	}
	
	if ($(".div-relacionamento-generalizacao",contexto)[0]){
		$(".div-relacionamento-generalizacao",contexto)[0].style.display = "";		
	}
	if ($(".select-flag-generalizacao").length > 0){
		var entidadeid = parseInt($(".select-flag-generalizacao").parents(".crud-contexto-add").data("entidadeid"));
		var atributogeneralizacao = td_entidade[entidadeid].atributogeneralizacao;
		if (entidadeid < 1 || atributogeneralizacao < 1){
			bootbox.alert('Entidade/Atributo de generalização não configurados.');
			return false;
		}
		$("#" + td_atributo[atributogeneralizacao].nome, contexto).val($(".select-flag-generalizacao").val());
	}
}

tdFormulario.prototype.newGrade = function (){
	// Limpa a grade de dados
	this.getGrade().setNenhumRegistro();
}

tdFormulario.prototype.setGrade = function (){
	// Atributo uma grade de dados
	this.gradesdados 			= new GradeDeDados(this.entidade.id);
	this.gradesdados.contexto 	= this.getContextoListar();
	this.gradesdados.pesquisar	= false;
}

tdFormulario.prototype.getGrade = function(){
	if (this.gradesdados==null) this.setGrade();
	return this.gradesdados;
}

tdFormulario.prototype.setarformdadospreenchido = function(){
	let instancia = this;
	$(".crud-contexto-add").each(function(){
		let campos = [];
		// Campo de texto
		$(this).find(".tdform .form-control").each(function(){
			campos.push({
				nome: $(this).attr("id"),
				valor: $(this).val()
			});
		});
		instancia.monitorformdadospreenchido[instancia.entidade.id] = campos;
	});
}
tdFormulario.prototype.naoExibirCampos = function(){
	$(".form-control",this.getContextoAdd()).each(function(){
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

tdFormulario.prototype.setEntidadesFilho = function(){
	td_relacionamento.forEach(function(e){
		if (e.pai == this.entidade.id){
			this.entidades_filho.push(e.filho);
			this.is_pai = true;
		}
	},this);
}

tdFormulario.prototype.setBotoes = function(){
	this.btn_novo 	= $(".b-novo"	, this.getContextoListar()).first();
	this.btn_voltar	= $(".b-voltar"	, this.getContextoAdd()).first();
	this.btn_salvar	= $(".b-salvar"	, this.getContextoAdd()).first();

	this.btn_novo.click(this,function(handler){
		if (typeof beforeNew === "function") beforeNew(this);
		handler.data.novo();
	});

	this.btn_voltar.click(this,function(handler){
		if (typeof beforeBack === "function") beforeBack(this);
		handler.data.voltar();
		if (typeof afterBack === "function") afterBack(this);
	});


	this.btn_salvar.click(this,function(handler){
		if (typeof beforeSave === "function") beforeSave(this);
		handler.data.salvar();

	});
}

tdFormulario.prototype.loadGrade = function(){
	this.getGrade().show();
}
tdFormulario.prototype.voltar = function(){
	this.getGrade().show();
	$(this.getContextoAdd()).hide();
	$(this.getContextoListar()).show();
}
tdFormulario.prototype.salvar = function(){
	addLog("","",0,this.entidade_id,0,8, "");

	if (this.is_principal){
		this.btn_salvar.attr("disabled",true);
		this.btn_salvar.attr("readonly",true);
	}

	let contextoMsg = '';
	if ($("#select-generalizacao-unica").length > 0){
		contextoMsg = " .msg-retorno-form-" + td_entidade[EntidadePrincipalID].nomecompleto;
	}else{
		contextoMsg = this.getContextoAdd() + " .msg-retorno-form-" + this.entidade.nomecompleto;
	}
	let idRegistro 				= $('#id[data-entidade="'+this.entidade.nomecompleto+'"]',this.getContextoAdd()).val();
	let currentrelacionamento 	= null;
	if (this.is_principal){
		if (idRegistro == 0){
			this.dados.splice(0,this.dados.length); // Limpa array "dados"
		}
	}else{
		// Pega o relacionamento
		currentrelacionamento = getRelacionamento(this.entidade_pai,this.entidade_id);
		if (!currentrelacionamento){
			console.log("Relacionamento Atual não encontrado.");
		}
	}

	let entidadepairel 			= this.is_principal ? "" : currentrelacionamento.pai;
	let tiporelacionamentopai 	= this.is_principal ? 0 : currentrelacionamento.tipo;

	if (isPaiEntidade(this.entidade_id)){
		for(RelEnt in td_relacionamento){
			if (td_relacionamento[RelEnt].pai == this.entidade_id){
				let entidadesRel = "";
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
					let hierarquiacontexto = getHierarquiaRel(RelEnt);
					$("#crud-contexto-add-" + hierarquiacontexto).find(".b-salvar").first().click();
				}
			}
			
			if (entidadepairel != ""){
				if (td_relacionamento[RelEnt].pai == entidadepairel && td_relacionamento[RelEnt].filho == this.entidade_id){
					tiporelacionamentopai = td_relacionamento[RelEnt].tipo;
				}
			}
		}
	}

	if (entidadepairel != "" && tiporelacionamentopai == 1){
		// Caso seja Agregação 1:1 e se o usário não digitou nada no form então não enviar o formulário
		let tdform = $(this.getContextoAdd() + " .tdform");
		if (!this.isalteracaoform(this.entidade_id,tdform)){
			tdform.find(".form-control").parent(".form-group").removeClass("has-error");
			return false;
		}
	}

	let validacaotamanho = false;
	for(a in td_atributo){
		let nomeEntidade = '';
		try{
			nomeEntidade = td_entidade[td_atributo[a].entidade].nomecompleto;
		}catch(e){
			console.log("ERRO ( Nome da entidade("+td_atributo[a].entidade+") no campo não encontrada ) => ["+a+"]" + td_atributo[a].nome);
			break;
			return false;
		}

		if (nomeEntidade == this.entidade.nomecompleto){
			try{
				let campoAttr 	= $('#' + td_atributo[a].nome + '[data-entidade="'+this.entidade.nomecompleto+'"]',this.getContextoAdd());
				let dataToSave 	= campoAttr.val();
				if ((td_atributo[a].tipo == "varchar" || td_atributo[a].tipo == "char") && td_atributo[a].tipohtml != 19){
					if (dataToSave.length > td_atributo[a].tamanho){
						console.log(dataToSave);
						console.error("Quantidade de caracteres execido. Campo [ " + td_atributo[a].nome + " ] - Max => " + td_atributo[a].tamanho + " Len => " + dataToSave.length);
						campoAttr.parent().addClass("has-error");
						validacaotamanho = true;						
						break;
					}else{
						campoAttr.parent().removeClass("has-error")
					}
				}
			}catch(e){
				console.log("ERRO => " + '#' + td_atributo[a].nome + '[data-entidade="'+this.entidade.nomecompleto+'"]');
			}	
		}
	}

	// Valida o tamanho de caracteres de cada campo
	if (validacaotamanho){
		abrirAlerta("Quantidade de caracteres exedido.","alert-danger",contextoMsg);
		this.liberaBotaoSalvar();
		return false;
	}

	// Monta a array dos campos obrigatórios
	let campos_obrigatorios = [];
	$('.form-control[required][data-entidade="'+this.entidade.nomecompleto+'"]',this.getContextoAdd()).each(function(){
		campos_obrigatorios[$(this).attr("name")] = $(this).val();
	});

	// Função que valida os campos obrigatórios
	if (!validar(campos_obrigatorios,this.getContextoAdd(),contextoMsg)){
		this.liberaBotaoSalvar();
		return false;
	}

	if ($(this.getContextoAdd()).find("div").hasClass("has-error")){
		abrirAlerta("Existem campos obrigat&oacute;rios n&atilde;o preenchidos","alert-danger",contextoMsg);
		this.liberaBotaoSalvar();
		return false;
	}
	
	if (this.is_principal){
		let parar = false;
		for (c in this.composicao){
			if (!this.composicao[c]){
				abrirAlerta("<b>" + c + "</b> &eacute; obrigat&oacute;rio.","alert-danger",contextoMsg);
				parar = true;
				break;
			}
		}
		if (parar){
			this.liberaBotaoSalvar();
			return false;
		}
		
	}

	// Monta a Array com os dados a serem salvos	
	let dados_obj = [];
	this.entidade.atributos.forEach(function(e){
		try{
			var dataToSave = $('#' + e.nome + '[data-entidade="'+this.entidade.nomecompleto+'"]',this.getContextoAdd()).val();
			if (this.textcase == 'uppercase'){
				dataToSave.toUpperCase();
			}
			dados_obj.push({atributo:e.nome,valor:dataToSave});
		}catch(error){
			console.warn(error.getMessage() + " | ERRO => " + '#' + e.nome + '[data-entidade="'+this.entidade.nomecompleto+'"]');
		}
	},this);

	// Monta campo de relacionamento
	if (this.is_principal){
		relacionamento 		=  {entidade:this.entidade.nomecompleto,atributo:'id'};
		relacionamentoTipo 	= 0;
		for(rSalvar in td_relacionamento){
			// Testa se a entidade é filho
			if (td_relacionamento[rSalvar].filho == this.entidade_id){
				if ($("#select-generalizacao-unica").length > 0){
					if (td_relacionamento[rSalvar].tipo == "8"){
						relacionamento 	= {entidade:td_entidade[td_relacionamento[rSalvar].pai].nome,atributo:td_atributo[td_relacionamento[rSalvar].atributo].nome};
						relacionamentoTipo 	= td_relacionamento[rSalvar].tipo;
					}else{
						if (td_relacionamento[rSalvar].atributo != "" && td_relacionamento[rSalvar].atributo > 0){
							relacionamento 		= {entidade:td_entidade[$("#select-generalizacao-unica").val()].nome,atributo:td_atributo[td_relacionamento[rSalvar].atributo].nome};
							relacionamentoTipo 	= td_relacionamento[rSalvar].tipo;
						}else if (td_entidade[$("#select-generalizacao-unica").val()].atributogeneralizacao != "" && td_entidade[$("#select-generalizacao-unica").val()].atributogeneralizacao > 0){
							relacionamento 		= {entidade:td_entidade[$("#select-generalizacao-unica").val()].nome,atributo:td_atributo[td_entidade[$("#select-generalizacao-unica").val()].atributogeneralizacao].nome};
							relacionamentoTipo 	= td_relacionamento[rSalvar].tipo;
						}else{
							relacionamento 		= {entidade:td_entidade[td_relacionamento[rSalvar].pai].nome,atributo:''};
							relacionamentoTipo 	= td_relacionamento[rSalvar].tipo;
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
		let atributorel		= 0;
		if (currentrelacionamento.atributo != undefined && parseInt(currentrelacionamento.atributo) != 0){
			atributorel		= td_atributo[currentrelacionamento.atributo].nome;
		}
		relacionamento 		= {entidade:td_entidade[currentrelacionamento.pai].nome,atributo:atributorel};
		relacionamentoTipo 	= currentrelacionamento.tipo;
	}

	// Adiciona dados para ser enviado
	this.addDados(dados_obj,idRegistro,relacionamento,this.is_principal,relacionamentoTipo);
	
	// Salvar o formulário
	if (this.is_principal){
		let dadosenviar = [];

		formulario.forEach(function(f){
			f.dados.forEach(function(d){
				dadosenviar.push(d);
			});
		});

		// AJAX que envia os dados a serem salvos
		$.ajax({
			type:"POST",
			url:config.urlsaveform,
			data:{
				dados:dadosenviar
			},
			instancia:this,
			dataType:"json",
			complete:function(req,req_status){
				if (req_status == 'error' || req_status == 'parsererror') return false;
				let retorno = JSON.parse(req.responseText);
				if (parseInt(retorno.status) == 1){
					abrirAlerta("Salvo com Sucesso","alert-success",contextoMsg);
					if (this.instancia.registro_id <= 0){
						this.instancia.registro_id = retorno.id;
						this.instancia.exibirDadosEdicao();
						retorno.entidadesID.forEach(function(entidades_retorno){
							switch(entidades_retorno.tipo_relacionamento){
								case '':
								case 0: 
								case 1:
								case 7:
								case 3:
									// Atualiza o ID do banco de dados no registro
									formulario[getEntidadeId(entidades_retorno.entidade)].id = entidades_retorno.id;
									$('#id[data-entidade="'+entidades_retorno.entidade+'"]').val(entidades_retorno.id);
								break;
								default:
								// Recarrega as grades de dados
								formulario[getEntidadeId(entidades_retorno.entidade)].gradesdados.clear();
								formulario[getEntidadeId(entidades_retorno.entidade)].gradesdados.addFiltroNN(retorno.entidade, retorno.id, getEntidadeId(entidades_retorno.entidade));
								formulario[getEntidadeId(entidades_retorno.entidade)].gradesdados.reload();							
							}
							formulario[getEntidadeId(entidades_retorno.entidade)].dados = [];
						});
						this.instancia.setaPrimeiraAba();
						if ($("#select-generalizacao-unica")){
							$("#select-generalizacao-unica").attr("readonly","true");
							$("#select-generalizacao-unica").attr("disabled","true");
						}
					}
					setTimeout((function(){
						this.liberaBotaoSalvar();
					}).bind(this.instancia)(),3000);
				}
				if (typeof afterSave === "function") afterSave(this.instancia.is_principal,this);
				unLoaderSalvar();

				// Retorna para o formulário que chamou o adição de registro
				if (this.instancia.funcionalidade == 'add-emexecucao'){
					parent.$(".modal[id='modal-add-emexecucao'] iframe[fk_entidade='"+this.instancia.entidade.nome+"']").attr("em_execucao_id",this.instancia.registro_id);
					parent.$(".modal[id='modal-add-emexecucao']").modal('hide');
				}
			},
			error:function(ret){
				if (this.instancia.is_principal){
					this.instancia.btn_salvar.attr("disabled",false);
					this.instancia.btn_salvar.attr("readonly",false);
					unLoaderSalvar();
				}
				if (session.isproducao && session.isonline){
					enviarEmailErro(ret.responseText);
				}
				abrirAlerta("<b>Erro ao Salvar</b> favor entrar em contato com a equipe de SUPORTE.","alert-danger",contextoMsg);
			},
			beforeSend:function(){
				addLoaderSalvar(this.instancia.getContextoAdd());
			}
		});
	}else{
		if (currentrelacionamento != null){
			if (currentrelacionamento.cardinalidade == '1N' || currentrelacionamento.cardinalidade == 'NN'){
				let linha_grade 	= [];
				this.getGrade().attr_cabecalho_nome.forEach(function(atributo){
					const elementoDOM = '#' + atributo + '[data-entidade="'+this.entidade.nomecompleto+'"]';
					let tagName 	= $(elementoDOM,this.getContextoAdd()).prop("tagName");
					let val			= '';
					switch(tagName){
						case "INPUT":
							val = $(elementoDOM,this.getContextoAdd()).val();
						break;
						case "SELECT":					
							val = $(elementoDOM + " option:selected",this.getContextoAdd()).html();
						break;
						case "TEXTAREA":
							val = $(elementoDOM,this.getContextoAdd()).html();
						break;
						default:
							val = $(elementoDOM,this.getContextoAdd()).val();
					}
					if ($(elementoDOM,this.getContextoAdd()).hasClass("termo-filtro")){
						val = $(elementoDOM,this.getContextoAdd()).parents(".input-group").find(".descricao-filtro").val();
					}
					linha_grade[atributo] = val;
				},this);

				const idEdicao = $('#id[data-entidade="'+this.entidade.nomecompleto+'"]',this.getContextoAdd()).val();
				this.getGrade().addLinha(idEdicao,linha_grade);
				$(this.getContextoAdd()).hide();
				$(this.getContextoListar()).show();
				if (relacionamentoTipo == 2 || relacionamentoTipo == 10){
					this.composicao[this.entidade_id] = true;
				}
				if (typeof afterSave === "function") afterSave(this.is_principal,this);
			}
		}		
	}
}

tdFormulario.prototype.addDados = function(dados_obj,id,relacionamento,fp,tiporelacionamento){
	this.dados.push({
		entidade:this.entidade.nomecompleto,
		dados:dados_obj,
		id:id,
		relacionamento:relacionamento,
		fp:fp,
		tiporel:tiporelacionamento
	});
}

tdFormulario.prototype.liberaBotaoSalvar = function(){
	this.btn_salvar.removeAttr("disabled");
	this.btn_salvar.removeAttr("readonly");	
}

tdFormulario.prototype.exibirDadosEdicao =  function(){
	let campodescchave = this.entidade.campodescchave;
	if (campodescchave != "" && campodescchave != 0){
		$(".descricaoExibirEdicao .campodescricaoExibirEdicao").html($("#" + td_atributo[campodescchave].nome,this.getContextoAdd()).val());
		$(".descricaoExibirEdicao .idExibirEdicao").html("<small>ID: </small>" + this.registro_id);
		$(".descricaoExibirEdicao").show();
	}
}

tdFormulario.prototype.editar = function(){	
	if (typeof beforeEdit === "function") beforeEdit(this.entidade.id,this.registro_id);
	//Limpa o formulário para edição de um novo registro
	this.novo();
	this.setAtributoDependencia();
	
	addLog("", "", "", this.entidade.id,this.registro_id, 7, "");
	
	// Verifica se o usuário tem permissão para editar
	for (permissao in td_permissoes){
		if (session.userid == td_permissoes[permissao].usuario && this.entidade.id == td_permissoes[permissao].entidade){
			if (td_permissoes[permissao].editar != 1){
				bootbox.alert("Voc&ecirc; n&atilde;o tem permiss&atilde;o para editar registro");
				return false;
			}
		}
	}

	$.ajax({
		url:config.urlloadform,
		data:{
			entidadeprincipal:this.entidade.id,
			registroprincipal:this.registro_id,
			rastrearrelacionamentos:true
		},
		instancia:this,
		dataType:"json",
		beforeSend:function(){
			addLoaderGeral();
		},
		error:function(ret){
			bootbox.alert("<b>Erro ao carregar os dados.</b> Favor entrar em contato com a equipe de SUPORTE.");
			unLoaderGeral();
		},
		complete:function(ret){
			let retorno = [];

			try {
				retorno = JSON.parse(ret.responseText);
			}catch(err){
				console.log(err.message);
				return false;
			}

			retorno.forEach(function(r){
				let dadosRetorno 			= r.dados;
				let entidadeDados 			= td_entidade[r.entidade];
				let tipoRelacionamento 		= "";
				let atributoRelacionamento 	= "";
				let nomeEntidadeDados 		= entidadeDados.nomecompleto;
				
				if (!r.fp){
					// Verifica o tipo de relacionamento
					this.entidade.relacionamentos.forEach(function(relacionamento){
						const tipo 		= relacionamento.tipo;
						const atributo 	= relacionamento.atributo;
						const pai 		= relacionamento.pai;
						const filho 	= relacionamento.filho;
						const entidade 	= r.entidade;

						if ((tipo != "" || tipo != 0) && entidade == filho){
							tipoRelacionamento 		= tipo;
							atributoRelacionamento	= atributo;
						}

						// Configura o cadastro para a generalização simples
						if (pai == entidade && (tipo == 3 || tipo == 8)){
							for(d in dadosRetorno){
								if (td_atributo[td_entidade[entidade].atributogeneralizacao].nome == dadosRetorno[d].atributo){
									if (dadosRetorno[d].valor  == td_relacionamento[rel].filho){

										$("#select-generalizacao-unica")[0].sumo.selectItem(td_relacionamento[rel].filho);
										$(".div-relacionamento-generalizacao,.generalizacaoABA",this.getContextoAdd()).hide();
										$("#select-generalizacao-unica").attr("readonly","true");
										$(".generalizacaoABA." + td_entidade[td_relacionamento[rel].filho].nome).show();
										$(".div-relacionamento-generalizacao#drv-" + td_entidade[td_relacionamento[rel].filho].nome,this.getContextoAdd()).show();
									}
								}
							}
						}

						// Seleciona a lista da generalização multipla
						if (tipo == 9 && pai == entidade){
							$("#select-generalizacao-multipla")[0].sumo.selectItem(String(entidade));
						}
					},this);
				}

				// Abre o formulário das entidades de relacionamento
				if (tipoRelacionamento == "" || tipoRelacionamento == 1 || tipoRelacionamento == 7 || tipoRelacionamento == 3 || tipoRelacionamento == 9){
					this.setDados(r);
					this.setaPrimeiraAba();
					$(this.getContextoListar()).hide();
					$(this.getContextoAdd()).show();

				// Seta a grade de dados para as entidades de relacionamento
				}else if (tipoRelacionamento == 2 || tipoRelacionamento == 6 || tipoRelacionamento == 5 || tipoRelacionamento == 8 || tipoRelacionamento == 10){
					this.setGradeRelacionamento(r.entidade,r.id,r.dados);
					$(formulario[r.entidade].getContextoAdd(),this.getContextoAdd()).hide();
					$(formulario[r.entidade].getContextoListar(),this.getContextoAdd()).show();
				}

				if ($("#select-generalizacao-unica")){
					$("#select-generalizacao-unica").attr("readonly","true");
					$("#select-generalizacao-unica").attr("disabled","true");
					this.setaLayoutGeneralizao();
					if (r.fp){
						$("#select-generalizacao-unica").change();
					}
				}

				if ($('#select-generalizacao-multipla')){
					$('#select-generalizacao-multipla').SumoSelect();
					if (r.fp){
						$("#select-generalizacao-multipla").change();
					}
				}
				if (r.fp){
					this.exibirDadosEdicao();
					this.liberaBotaoSalvar()
				}
			},this.instancia);
			


			// Permissão dos atributos
			this.instancia.setPermissoesAtributos('edicao');
			if (typeof afterEdit === "function") afterEdit(this.instancia.entidade.id,this.instancia.registro_id);
			unLoaderGeral();
		}
	});
	
}

tdFormulario.prototype.setDados = function(dados){
	let form	 		= formulario[dados.entidade];
	let entidade_nome 	= form.entidade.nome;
	let contextoAdd		= form.contexto_add;
	let id				= dados.id;
	let atributos		= dados.dados;

	// id do Formulário
	$('#id[data-entidade='+entidade_nome+']',contextoAdd).val(id);
	formulario[dados.entidade].registro_id = id;

	// Não prosseguir sem dados atributos
	if (atributos == '' || atributos == undefined) return false;

	// Percorre os atributos do formulário
	atributos.forEach(function(dado){
		let valorDados 		= dado.valor;
		let direto 			= true;
		
		// Apenas os campos do tipo SELECT <select>
		if ($('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).prop("tagName") == "SELECT"){

			let atributodependencia_id 	= td_atributo[getIdAtributo(dado.atributo,entidade_nome)].atributodependencia;
			atributodependencia_id		= atributodependencia_id == '' ? 0 : atributodependencia_id;
			// Trata-se de um atributo dependente
			if (atributodependencia_id > 0){
				/*
				if ($('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).attr("required") == "required"){
					direto = false;
					$('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).prop('selectedIndex', 0);
				}
				*/

				// Adiciona na lista de atributos dependentes
				this.dadosatributodependencia.push({
					entidade:entidade_nome,
					atributo:dado.atributo,
					contexto:contextoAdd,
					valor:valorDados
				});

				/*
				const atributodependencia	= td_atributo[getAtributoId(entidade_nome,dado.atributo)].atributodependencia;
				const atributodependente	= td_atributo[atributodependencia];
				const valorfiltro 			= 0;
				if (atributodependencia != '' && dado.atributo != ''){
					if (dado.atributo == atributodependente.nome){
						valorfiltro = dado[dr].valor;
						carregarListas(entidade_nome,dado.atributo,contextoAdd,valorDados,atributodependente.nome+"^=^"+valorfiltro);
					}
				}
				*/
			}else{
				//carregarListas(entidade_nome,dado.atributo,contextoAdd,valorDados);
				if (valorDados != "" && valorDados != undefined){
					$('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).val(valorDados);

				}else{
					if ($('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).attr("required") == "required"){
						direto = false;
						$('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).prop('selectedIndex', 0);
					}
				}
			}
			/*
			if (dado.nulo == 1){
				$('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]').removeAttr("readonly");
				$('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]').removeAttr("disabled");
			}
			*/			
		}

		if ($('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).hasClass("termo-filtro")){
			try{
				direto = false;
				const nomeEntidadeReplace = td_entidade[td_atributo[dado.idatributo].chaveestrangeira].nomecompleto;
				this.buscarFiltro(valorDados,nomeEntidadeReplace.replace("-","."),dado.atributo,"myModal-" + dado.atributo + " .modal-body p ",entidade_nome);				
			}catch(e){
				console.warn('ID => atributo => ' + dado.idatributo);
			}

		}

		if ($('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).hasClass("checkbox-sn")){
			if (valorDados == 1){
				$('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).parents(".form-group").find(".checkbox-s").addClass("active");
				$('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).parents(".form-group").find(".checkbox-n").removeClass("active");
			}else{
				$('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).parents(".form-group").find(".checkbox-n").addClass("active");
				$('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).parents(".form-group").find(".checkbox-s").removeClass("active");
				valorDados = 0;
			}
		}

		if ($('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).hasClass("td-file-hidden")){
			const dadosRetornoJSON = JSON.parse(dado.valor);
			if (dadosRetornoJSON.filename != ""){
				$('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).parents(".form-group").find("iframe").first().attr("src",config.urluploadform + "&atributo="+dado.idatributo+"&valor="+dado.valor+"&id=" + id);
			}
		}

		if ($("#select-generalizacao-multipla").length > 0){
			$("#select-generalizacao-multipla option[value="+retorno[r].entidade+"]").attr("selected","selected");
		}

		if ($('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).hasClass(".formato-moeda")){
			if (valorDados == 0) valorDados = "0,00";
		}

		if ($('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).hasClass("formato-ckeditor")){
			try{
				this.CKEditores[entidade_nome + "^" + dado.atributo].setData(valorDados);
			}catch(e){
				console.log("Erro ao abrir CKEditor no Celular");
				console.log(e);
			}
		}

		if (direto){
			$('#' + dado.atributo + '[data-entidade="'+entidade_nome+'"]',contextoAdd).val(valorDados);
		}
	},this);
}

tdFormulario.prototype.setGradeRelacionamento = function(entidade,id,dados){
	let linha = [];
	dados.forEach(function(d){
		linha[d.atributo] = d.valor;
	});
	formulario[entidade].gradesdados.addLinha(id,linha);
}

tdFormulario.prototype.buscarFiltro = function(termo,entidadeNome,nome,modalName,entidadeContexto){
	if (termo == "" || termo == undefined){
		$('#descricao-' + nome + '[data-entidade="'+entidadeContexto+'"]').val("");
		this.habilitafiltro(nome,"",false);
		return false;
	}
	$.ajax({
		type:'GET',
		url:config.urlrequisicoes,
		data:{
			op:'retorna_descricao_filtro',
			termo:termo,
			entidade:entidadeNome
		},
		instancia:this,
		success:function(retorno){
			if (retorno.trim() == ""){				
				$('#descricao-' + nome + '[data-entidade="'+entidadeContexto+'"]').val("");
				$('#' + nome + '[data-entidade="'+entidadeContexto+'"]').val("");
				this.instancia.habilitafiltro(nome,"",false,entidadeContexto);
			}else{
				$('#descricao-' + nome + '[data-entidade="'+entidadeContexto+'"]').val(retorno.trim());
				$('#' + nome + '[data-entidade="'+entidadeContexto+'"]').val(termo);
				this.instancia.habilitafiltro(nome,"",true,entidadeContexto);
			}
			$('#' + modalName).modal('hide');
		},
		error:function(ret){
			console.log("ERRO ao buscar filtro => " + ret.responseText);
		}
	});
}
tdFormulario.prototype.habilitafiltro = function(atributo,contexto,habilita,entidadeContexto){
	for (e in td_atributo){
		if (td_atributo[e].atributodependencia != "" && td_atributo[e].atributodependencia != 0){

			var dep 				= td_atributo[td_atributo[e].atributodependencia];
			var attr 				= td_atributo[e];
			var entidade 			= td_entidade[attr.entidade];
			var entidadeAtributo 	= td_entidade[td_atributo[e].entidade];

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
							//carregarListas(entidadeFiltro.nomecompleto,attr.nome,contexto,valordependencia,campofiltro + "^=^" + $("#" + campofiltro).val());
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

tdFormulario.prototype.emExecucao = function(){
	let instancia = this;
	$('.btn-add-emexecucao',this.getContextoAdd()).click(function(){

		let contextoAdd	= instancia.getContextoAdd();
		let modal 		= $("#modal-add-emexecucao",contextoAdd);
		let campo 		= $(this).parents(".input-group").first().find(".form-control");
		let atributo 	= campo.attr("atributo");
		let fk			= td_atributo[atributo].chaveestrangeira;
		let entidade	= campo.data("entidade");
		let iframe		= $('#modal-add-emexecucao iframe[data-contexto="'+contextoAdd+'"]');
		iframe.attr("em_execucao_id",0);
		iframe.attr("fk_entidade",td_entidade[fk].nome);
		iframe.attr("src",session.urlmiles + '?controller=htmlpage&entidade='+fk+'&op=cadastro');		

		modal.on('hide.bs.modal', function (e) {
			carregarListas(entidade,atributo,contextoAdd,iframe[0].attributes.em_execucao_id.value);
		});
		modal.modal('show');
	});
}

tdFormulario.prototype.setRegistroUnico = function(){
	this.registrounico = true;

	$(".b-voltar",this.getContextoAdd()).hide();
	if (this.funcionalidade == "add-emexecucao"){
		this.novo();
	}else{
		this.registro_id = 1;
		this.editar();
	}
}

tdFormulario.prototype.setConsulta = function(id_consulta){

	$("#form-consulta.tdform .form_campos .form-control").each(function(){
		if ($(this).prop("tagName") == "SELECT"){
			$(this).removeAttr("required");
			carregarListas($(this).data("entidade"),$(this).attr("id"),"");
		}
	});

	// Alinha os campos para consulta
	$(".checkbox-s,.checkbox-n").removeClass("active");
	$(".checkbox-s,.checkbox-n").parents(".form-group").find("input").val("");
	$(".btn-add-emexecucao").parents(".input-group-btn").remove();
	$(".asteriscoobrigatorio").hide();
	this.setCkEditores();

	let consulta = td_consulta[id_consulta];

	// Monta os filtros
	for (f in consulta.filtros){
		var ft = td_consulta[id_consulta].filtros[f];
		$("#form-consulta .form-control[atributo="+ft.atributo+"]").attr("data-operador",ft.operador);
		$("#form-consulta .form-control[atributo="+ft.atributo+"]").attr("data-tipo",td_atributo[ft.atributo].tipo);
	}

	let instancia = this;
	$("#pesquisa-consulta").click(function(){
		instancia.getGrade().clear();
		$("#form-consulta.tdform .form_campos .form-control").each(function(){
			if ($(this).hasClass("input-sm") || $(this).hasClass("termo-filtro") || $(this).hasClass("checkbox-sn")){
				if ($(this).val() != "" && $(this).val() != undefined && $(this).val() != null){
					var operador 	= $(this).data("operador");
					var tipo 		= $(this).data("tipo");
					var atributo 	= $(this).attr("id");
					instancia.getGrade().addFiltro(atributo,(operador == undefined?"=":operador),$(this).val(),(tipo == undefined?"int":tipo));
				}
			}
		});
		instancia.getGrade().qtdeMaxRegistro = 500;
		instancia.getGrade().reload();
	});

	// Filtros Iniciais
	consulta.filtros_iniciais.forEach(function(ft){
		this.addFiltro(ft.atributo,ft.operador,ft.valor);
	},this.getGrade());
	
	this.getGrade().consulta 		= id_consulta;
	this.getGrade().funcionalidade	= 'consulta';
	this.getGrade().movimentacao 	= consulta.movimentacao;
	this.getGrade().exibireditar	= consulta.exibireditar;
	this.getGrade().exibirexcluir	= consulta.exibirexcluir;
	this.getGrade().exibiremmassa	= consulta.exibiremmassa;		
	this.getGrade().exibirpesquisa 	= false;
	this.getGrade().setOrder("id","DESC");
	this.getGrade().show();
}
/* 
	*********** FILTRO ************* 
	Configuração dos campos tipo filtro
*/
tdFormulario.prototype.setBuscaFiltro = function()
{
	let instancia = this;
	td_entidade[this.entidade_id].atributos.forEach(
		(atributo) =>{
			if (atributo.tipohtml == 22){

				$('.termo-filtro[atributo='+atributo.id+']',this.getContextoAdd()).each(function(){

					$(this).blur(function(){
						let termo 				= this.value;
						let entidadeNome 		= $(this).data("fk");
						let nome 				= $(this).prop("id");	
						let modalName 			= $(this).parents(".filtro-pesquisa").data("modalname");
						let entidadeContexto 	= $(this).data("entidade");
						instancia.buscarFiltro(termo,entidadeNome,nome,modalName,entidadeContexto);
					});

					$(this).parent('.input-group').find('.botao-filtro').click(function(){
						let modalName 			= $(this).parents(".filtro-pesquisa").data("modalname");
						let chaveestrangeira 	= $(this).data("fk");
						let atributo 			= $(this).parents(".filtro-pesquisa").find(".termo-filtro").attr("id");
						let contextoGrade 		= '#' + modalName + instancia.cmodal;

						// Atributo uma grade de dados
						let gd_filtro 					= new GradeDeDados(chaveestrangeira);
						gd_filtro.contexto 				= contextoGrade;
						gd_filtro.pesquisar				= true;
						gd_filtro.retornaFiltro 		= true;
						gd_filtro.atributoRetorno 		= atributo;
						gd_filtro.modalName 			= modalName;
						gd_filtro.entidadeContexto 		= $(this).data("entidade");
						gd_filtro.entidade_contexto_id 	= instancia.entidade_id;

						gd_filtro.show();

						$('#'+modalName+' .modal-footer').css('border','0px');
						$('#'+modalName).modal({
							backdrop:false
						});
						$('#'+modalName).modal('show');

					});

				});

			}
		}
	);
}


tdFormulario.prototype.addHTMLPersonalizado = function()
{
	$("#div-htmlpersonalizado").load(
		session.folderprojectfiles + 
		"files/cadastro/" + 
		this.entidade_id + 
		"/" + 
		this.entidade.nome + 
		".htm"
	);
}

tdFormulario.prototype.isalteracaoform = function(){
	return true;
}