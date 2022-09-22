if (typeof gradesdedados === "undefined"){
	var gradesdedados = [];
}
if (typeof dados_temp === "undefined"){
	var dados_temp = [];
}
function GradeDeDados(entidade){
	this.objectID = session.objectID++; // Importante, inicia o contador de instancias dos objetos
	this.entidade = entidade;
	this.contexto;
	this.filtros = new Array();
	this.filtrosNN = new Array();
	this.filtroPesquisa = "";	
	this.table;
	this.dadosCorpo = [];
	this.dadosReaisCorpo = [];
	this.nomeEntidade;
	this.totalRegistros = 0;
	this.qtdeMaxRegistro = 10;
	this.blocoatual = 1;
	this.totalblocos = 0;
	this.registrosExcluir = "";
	this.retornaFiltro = false;
	this.attr_cabecalho_nome = new Array("id");
	this.attr_cabecalho_descricao = new Array("ID");
	this.attr_cabecalho_tipo = new Array("int");
	this.attr_cabecalho_tipohtml = new Array("3");
	this.attr_cabecalho_chaveestrangeira = new Array("0");
	this.entidadePai = "";
	this.regpai = "";
	this.atributoRetorno = "";
	this.modalName = "";
	this.entidadeContexto = "";
	this.consulta;
	this.movimentacao = "";
	this.funcaoretorno = "";
	this.exibirpesquisa = true;
	this.totalRegistroRetorno = 0;
	this.construct(entidade);
}
GradeDeDados.prototype.construct = function(entidade){
	if (entidade > 0) this.nomeEntidade = td_entidade[entidade].nomecompleto;
}
GradeDeDados.prototype.setaTable = function(){
	// Verifica se existe uma tabela como grade de dados
	if ($(".gradededados[data-contexto='"+this.contexto+"']").length <= 0){
		this.table = $("<table class='table table-hover gradededados' data-contexto='"+this.contexto+"'>");
	}else{
		this.table = $(".gradededados[data-contexto='"+this.contexto+"']:first");
	}
}
GradeDeDados.prototype.show = function(){
	this.setaTable();
	if ($(this.contexto).find(".gradededados").length <= 0){
		$(this.contexto).append(this.table);
		this.load();
	}else{
		this.reload();
	}
}
GradeDeDados.prototype.addFiltro = function(atributo,operador,valor){
	if (atributo != "" && atributo != null && atributo != undefined){
		if (arguments.length > 3){
			var tipo = arguments[3];
		}else{
			var tipo = "int";
		}
		var filtro = new Array(atributo,operador,valor,tipo);
		var existe = false;
		for(f in this.filtros){
			var filtros = this.filtros[f];
			if (filtro[0] == filtros[0] && filtro[1] == filtros[1] && filtro[2] == filtros[2] && filtro[3] == filtros[3]){
				existe = true;
			}
		}
		if (this.consulta != "") existe = false;
		if (!existe){
			this.filtros.push(filtro);
		}
	}
}
GradeDeDados.prototype.addFiltroNN = function(entidadepai,regpai,entidadefilho){
	var filtro = new Array(entidadepai,regpai,entidadefilho);
	this.filtrosNN.push(filtro);
}
GradeDeDados.prototype.getFiltros = function(){
	var str_filtros = "";
	for(f in this.filtros){
		var filtros = this.filtros[f];
		str_filtros += (str_filtros == ""?"":"~") + filtros[0] + "^" + filtros[1] + "^" + filtros[2] + "^" + filtros[3];
	}
	return str_filtros;
}
GradeDeDados.prototype.getFiltrosNN = function(){
	var str_filtros = "";
	
	for(f in this.filtrosNN){
		var filtros = this.filtrosNN[f];
		if (str_filtros == ""){
			str_filtros = filtros[0] + "^" + filtros[1] + "^" + filtros[2];
		}else{
			str_filtros += "~" + filtros[0] + "^" + filtros[1] + "^" + filtros[2];
		}
	}
	return str_filtros;
}
GradeDeDados.prototype.load = function(){
	var instancia = this;
	var camposNome = instancia.attr_cabecalho_nome[0];
	var camposDescricao = "ID";
	var camposTipo = "int";
	var camposHTML = 3;
	var camposFK = "0";
	var camposID = "0";

	$.ajax({
		type:"POST",
		url:config.urlloadgradededados,
		data:{
			entidade:this.entidade,
			filtro:this.filtroPesquisa,
			filtros:this.getFiltros(),
			filtroNN:this.getFiltrosNN(),
			bloco:this.blocoatual,
			entidadepai:this.entidadePai,
			regpai:this.regpai,
			camposnome:camposNome,
			camposdescricao:camposDescricao,
			campostipo:camposTipo,
			camposhtml:camposHTML,
			camposfk:camposFK,
			camposid:camposID
		},
		error:function(ret){
			console.log("ERRO ao carregar a grade de dados => " + ret.responseText);
		},
		beforeSend:function(){
			instancia.totalRegistroRetorno = 0;
		},
		dataType:"json",
		complete:function(ret){

			try{
				$("table[data-contexto='"+instancia.contexto+"'] tbody").html("");
			}catch(error){
				console.log("contexto => " + instancia.contexto);
				console.log(error);
			}

			if (ret.responseText != ""){
				try {
					var retorno = JSON.parse(ret.responseText);
				}catch(err){
					console.log("Erro: ");
					console.log(ret.responseText);
    				console.log(err.message);
				}
			}else{
				var retorno = [];
			}
			dadosLoad = retorno;
			if (td_entidade[instancia.entidade].atributos.length > 0){
				for (a in td_entidade[instancia.entidade].atributos){
					if (td_entidade[instancia.entidade].atributos[a].entidade == instancia.entidade && parseInt(td_entidade[instancia.entidade].atributos[a].exibirgradededados) == 1){
						instancia.attr_cabecalho_nome.push(td_entidade[instancia.entidade].atributos[a].nome);
						instancia.attr_cabecalho_descricao.push(td_entidade[instancia.entidade].atributos[a].descricao);
						instancia.attr_cabecalho_tipo.push(td_entidade[instancia.entidade].atributos[a].tipo);
					}
				}
			}else{
				for (a in td_atributo){
					if (td_atributo[a].entidade == instancia.entidade && td_atributo[a].exibirgradededados == "1"){					
						instancia.attr_cabecalho_nome.push(td_atributo[a].nome);
						instancia.attr_cabecalho_descricao.push(td_atributo[a].descricao);
						instancia.attr_cabecalho_tipo.push(td_atributo[a].tipo);
					}
				}
			}

			if (instancia.dadosCorpo.length > 0){		
				instancia.totalRegistros = 0;
				instancia.dadosCorpo.splice(0,instancia.dadosCorpo.length);
				instancia.dadosReaisCorpo.splice(0,instancia.dadosReaisCorpo.length);
			}
			for (t in dadosLoad.dados){
				instancia.totalRegistroRetorno++;
			}
			if (dadosLoad.dados != ""){		
				if (typeof instancia.dadosCorpo === "object"){
					instancia.dadosCorpo.push(dadosLoad.dados);
					instancia.dadosReaisCorpo.push(dadosLoad.dadosreais);
					instancia.totalRegistros = dadosLoad.total;
				}else{
					instancia.totalRegistroRetorno = 0;
					instancia.totalRegistros = 0;
				}
			}else{
				instancia.totalRegistroRetorno = 0;
				instancia.totalRegistros = 0;		
			}
			instancia.nomeEntidade = td_entidade[instancia.entidade].nomecompleto;
			if (typeof composicao !== "undefined"){
				for(c in composicao){
					if (c == td_entidade[instancia.entidade].descricao){
						if (instancia.totalRegistroRetorno <=0){
							composicao[c] = false;
						}
					}
				}				
			}
			instancia.pesquisa();
			instancia.cabecalho();
			instancia.corpo();
			instancia.paginacao();
			instancia.rodape();	
		}
	});
}
GradeDeDados.prototype.editar =	function editar(id){
	var contextoAdd = "." + "crud-contexto-add-" + this.nomeEntidade;	
	this.loadDadosEdicao(id);	
	setaPrimeiraAba(contextoAdd);

	$(this.contexto).hide();	
	$(contextoAdd).show();	
}
GradeDeDados.prototype.cabecalho = function(){
	if (this.table.find("thead").length <= 0){

		var thead = $("<thead>");
		var tr = $("<tr>");
		for(c in this.attr_cabecalho_descricao){
			var th = $("<th>");
			th.append(this.attr_cabecalho_descricao[c]);
			tr.append(th);
		}		
		if (!this.retornaFiltro){
			if (this.movimentacao != ""){
				tr.append($("<th class='movimentacao-coluna-gradededados'><center>Mov.</center></th>"));
			}
			tr.append($("<th class='editar-coluna-gradededados'><center>Editar</center></th>"));
			tr.append($("<th class='excluir-coluna-gradededados'><center>Excluir</center></th>"));

			var thSelTodos = $("<th>");
			var thCenter = $("<center>")
			var buttonSelTodos = $("<button data-sel='false' aria-label='Selecionar Todos' class='btn btn-link gd-sel-todos' type='button'><span aria-hidden='true' class='fas fa-check-square'></span></button>");
			var nomeEntidade = this.nomeEntidade;
			buttonSelTodos.click(function(){
				gradesdedados[nomeEntidade].selecionarTodos();
			});
			thCenter.append(buttonSelTodos);
			thSelTodos.append(thCenter);
			tr.append(thSelTodos);
		}
		thead.append(tr);
		this.table.append(thead);
	}
	$(this.contexto).append(this.table);
	if (this.retornaFiltro){
		if ($(".editar-coluna-gradededados").length > 0){
			$(".editar-coluna-gradededados").remove();
		}
		if ($(".excluir-coluna-gradededados").length > 0){
			$(".excluir-coluna-gradededados").remove();
		}
		this.retiraSelecionarTodos();
	}
}
GradeDeDados.prototype.corpo = function(){
	if (typeof qtdeTempRegistro === "undefined"){
		var qtdeTempReg = 1;
	}else{
		var qtdeTempReg = qtdeTempRegistro(this.nomeEntidade);
	}
	if (this.table.find("tbody").length > 0 && qtdeTempReg <= 0){
		this.table.find("tbody").html("");
	}
	if (this.table.find("tbody").length <= 0){
		var tbody = $("<tbody>");
		this.table.append(tbody);
	}
	if (this.dadosCorpo.length > 0 && this.dadosCorpo[0] != undefined && this.totalRegistroRetorno > 0){
		for (ln in this.dadosCorpo){
			var linhas = this.dadosCorpo[ln];
			var linhasreais = this.dadosReaisCorpo[ln];
			var i = 0;
			for(l in linhas){
				var dadosLinha = [];
				var dadosReaisLinha = [];
				for(n in this.attr_cabecalho_nome){
					dadosLinha[this.attr_cabecalho_nome[n]] = linhas[l][this.attr_cabecalho_nome[n]];
					if (linhasreais != undefined){						
						dadosReaisLinha[this.attr_cabecalho_nome[n]] = linhasreais[l][this.attr_cabecalho_nome[n]];
					}
				}
				this.addLinha(linhas[l].id,dadosLinha,dadosReaisLinha);
			}
		}
	}else{
		if (qtdeTempReg <= 0){
			if (this.table.find("tbody tr[class=warning]").length <= 0){
				this.table.find("tbody").append('<tr class="warning"><td class="text-center" colspan="'+(this.attr_cabecalho_nome.length+3)+'">Nenhum registro encontrado.</td></tr>');	
			}
		}
	}
}
GradeDeDados.prototype.addCorpo = function(id,dadosColuna){	
	var dadosC = dadosColuna.split("^");
	var existe = isTempRegistro(this.nomeEntidade,id.replace("T",""));
	if (id != "" && id != undefined){
		this.table.find("tbody tr[idregistro="+id+"]").remove();
	}
	if (!isNumeric(id)){
		if (existe){
			var	idRegistro = id.replace("T","");
		}else{
			var idRegistro = tempRegistro;
		}
		
		var array_dados = [];		
		for (d_t in dados_temp){
			if (dados_temp[d_t].entidade == td_entidade[this.entidade].nomecompleto && dados_temp[d_t].id == idRegistro){
				var item = [];
				for(n in this.attr_cabecalho_nome){
					if (existe){
						for (d in dados_temp[d_t].dados){
							if (dados_temp[d_t].dados[d].atributo == this.attr_cabecalho_nome[n]){
								//item[dados_temp[d_t].dados[d].atributo] = dados_temp[d_t].dados[d].valor;
								item[this.attr_cabecalho_nome[n]] = dadosC[n];
							}
						}
					}else{
						item[this.attr_cabecalho_nome[n]] = dadosC[n];
					}	
				}
				array_dados.push(item);
			}
		}
		this.addLinha("T" + tempRegistro,item);
	}else{
		this.table.find("tbody tr[idregistro="+id+"]").remove();
		var item = [];
		for(n in this.attr_cabecalho_nome){			
			item[this.attr_cabecalho_nome[n]] = dadosC[n];
		}
		this.addLinha(id,item);
	}
	this.rodape();
}
GradeDeDados.prototype.paginacao = function(){
	if (this.totalRegistros > this.qtdeMaxRegistro){
		var instancia = gradesdedados[this.contexto];
		this.totalblocos = Math.ceil(this.totalRegistros / this.qtdeMaxRegistro);
		if ($("center > ul.pagination",this.contexto).length > 0){
			$("center > ul.pagination",this.contexto)[0].remove();
		}
		
		var center = $("<center>");
		var ul = $("<ul class='pagination'>");
		var primeiro = $("<li><a class='primeiro' aria-label='Primeiro' href='#'><span aria-hidden='true'>Â«</span></a></li>");
		primeiro.click(function(){
			instancia.irbloco(1);
		});
		var voltar = $("<li><a class='anterior' href='#'><span class='fas fa-angle-double-left' aria-hidden='true'></span></a></li>");
		voltar.click(function(){
			instancia.irbloco(parseInt(instancia.blocoatual)-1);
		});
		var li = $("<li>");
		var irInput = $("<input type='text' class='irbloco-paginacao'>");
		irInput.val(this.blocoatual);
		irInput.keypress(function(e){
			var tecla = e.which;
			if (tecla == 13) {
				instancia.irbloco(parseInt($(this).val()));
			}
		});
		var ir = li.append(irInput);
		var proximo = $("<li><a class='proximo' href='#'><span class='fas fa-angle-double-right' aria-hidden='true'></span></a></li>");
		proximo.click(function(){
			instancia.irbloco(parseInt(instancia.blocoatual)+1);
		});
		var ultimo = $("<li><a class='ultimo' aria-label='Ãltimo' href='#'><span aria-hidden='true'>Â»</span></a></li>");
		ultimo.click(function(){
			instancia.irbloco(instancia.totalblocos);
		});
		
		//ul.append(primeiro);
		ul.append(voltar);
		
		for (i=1;i<=10;i++){
			var bloco = $("<li><a class='pagina' data-bloco='"+i+"' href='#' onclick="+this.instancia+"GD.irbloco("+i+")>"+i+"</a></li>");
			if (5 == i){
				ul.append(ir);
			}
		}
		ul.append(proximo);
		//ul.append(ultimo);
		center.append(ul);
		var paginacaoGradededados = $("<div class='paginacao-gradededados'>").append(center);
		$(this.contexto).append(paginacaoGradededados);
		//
		
		if (this.blocoatual<=1){
			$("ul.pagination li a.anterior").parent().addClass("disabled");		
			$("ul.pagination li a.proximo").parent().removeClass("disabled");
			$("ul.pagination li a.primeiro").parent().addClass("disabled");
			$("ul.pagination li a.ultimo").parent().removeClass("disabled");			
		}else if (this.blocoatual>=this.totalblocos){
			$("ul.pagination li a.proximo").parent().addClass("disabled");
			$("ul.pagination li a.anterior").parent().removeClass("disabled");
			$("ul.pagination li a.primeiro").parent().removeClass("disabled");
			$("ul.pagination li a.ultimo").parent().addClass("disabled");		
		}else{
			$("ul.pagination li a.anterior").parent().removeClass("disabled");
			$("ul.pagination li a.proximo").parent().removeClass("disabled");
			$("ul.pagination li a.primeiro").parent().removeClass("disabled");
			$("ul.pagination li a.ultimo").parent().removeClass("disabled");
		}
		$(".pagina[data-bloco=" +this.blocoatual+"]").first().parent().addClass("active");
	}
}
GradeDeDados.prototype.irbloco = function(bloco){
	if (bloco < 1 || bloco > this.totalblocos || this.blocoatual == bloco) return false;	
	this.blocoatual = bloco;

	this.reload();
}
GradeDeDados.prototype.pesquisa = function(){
	if ($(this.contexto).find(".pesquisa-grade").length <= 0 && this.exibirpesquisa){
		var div = $("<div class='pesquisa-grade'>");
		var label = $("<label>Pesquisar</label>");
		var inputGroup = $("<div class='input-group'>");
		var input = $("<input type='text' placeholder='Digite um termo para pesquisar' name='termo_pesquisa_gd' id='"+this.nomeEntidade+"_termo_pesquisa_gd' aria-label='Digite um termo para pesquisar' class='form-control'>");
		var inputGroupBtn = $("<div class='input-group-btn'>");
		var btnSalvar = $("<button aria-expanded='false' data-toggle='dropdown' class='btn btn-default dropdown-toggle' type='button'>");
		var spanSalvar = $("<span id='ajb_pessoa_span_atributo_pesquisa_gd' data-atributopesquisa='id' data-atributotipo='int'>"+this.attr_cabecalho_descricao[0]+"</span>");
		var caret = $("<span class='caret'>");
		var ul = $("<ul role='menu' class='dropdown-menu dropdown-menu-right' id='ajb_pessoa_ul_atributo_pesquisa_gd'>");	
		for (a in this.attr_cabecalho_descricao){
			var li = $("<li>");
			var a = $("<a data-tipo='"+this.attr_cabecalho_tipo[a]+"' data-entidadenome='"+this.nomeEntidade+"' data-atributoname='"+this.attr_cabecalho_nome[a]+"' href='#'>"+this.attr_cabecalho_descricao[a]+"</a>");
			a.click(function(){
				spanSalvar.attr("data-atributopesquisa",$(this).data("atributoname"));
				spanSalvar.attr("data-atributotipo",$(this).data("tipo"));
				spanSalvar.html($(this).html());
			});
			li.append(a);
			ul.append(li);
		}
		var instancia = gradesdedados[this.contexto];
		if (instancia == undefined){
			console.log("Variavel 'gradededados' não foi setada");
		}
		var btnbuscargd = $("<button class='btn btn-default'>Pesquisar</button>");
		btnbuscargd.click(function(){
			if (input.val() != ""){				
				instancia.filtroPesquisa = spanSalvar.attr("data-atributopesquisa") + "^" + input.val() + "^" + spanSalvar.attr("data-atributotipo");
			}else{
				instancia.filtroPesquisa = '';
			}
			addLog("", "", 0, instancia.entidade,0, 6, "Pesquisado =>" +spanSalvar.attr("data-atributopesquisa") + "=" + input.val());
			instancia.show();			
		});
		input.keypress(function(e){
			if ( e.which == 13 ){			
				btnbuscargd.click();
			}
		});
		input.blur(function(){
			if (this.value == "") instancia.filtroPesquisa = '';
		});
		btnSalvar.append(spanSalvar);
		btnSalvar.append(caret);	
		inputGroupBtn.append(btnbuscargd);
		inputGroupBtn.append(btnSalvar);
		inputGroupBtn.append(ul);
		inputGroup.append(input);
		inputGroup.append(inputGroupBtn);
		div.append(label);
		div.append(inputGroup);
		$(this.contexto).append(div);
	}
	if (!this.exibirpesquisa){
		$(this.contexto).find(".pesquisa-grade").hide();
	}
}
GradeDeDados.prototype.excluir = function(){	
	if (typeof beforeDelete === "function") beforeDelete();
	var entidade = this.entidade;
	for (permissao in td_permissoes){
		if (session.userid == td_permissoes[permissao].usuario && entidade == td_permissoes[permissao].entidade){			
			if (td_permissoes[permissao].excluir != 1){
				bootbox.alert("Voc&ecirc; n&atilde;o tem permiss&atilde;o para excluir registro");
				return false;
			}
		}
	}
	var excluirRegistroUnico = (arguments.length > 0)?true:false;
	if (excluirRegistroUnico){
		var registro = arguments[0];
		var btnExcluir = arguments[1];
		var linhatable = arguments[1].parents("tr");
		var loaderExcluir = btnExcluir.parents(".excluir-coluna-gradededados").find(".loader-excluir-gd");
	}else{
		var registro = "";
	}

	var totalexcluidos = 0;
	var entidadeNome = td_entidade[this.entidade].nomecompleto;
	var irbloco = 0;
	
						
	var instancia = gradesdedados[this.contexto];
	bootbox.dialog({
	  message:'Tem certeza que deseja excluir ?',
	  title:'Aviso',
	  buttons: {
		success:{
		  label:'Sim',
		  className: 'btn-success',
		  callback: function(){
			bootbox.hideAll();
			btnExcluir.hide();
			loaderExcluir.show();
			if (excluirRegistroUnico){
				if (isNumeric(registro)){
					excluirRegistro(entidade,registro,instancia);
					linhatable.remove();
				}else{
					// Exclui o dado temporário
					for (d_t in dados_temp){
						if (dados_temp[d_t].entidade == entidadeNome && dados_temp[d_t].id == registro.replace("T","")){
							dados_temp.splice(d_t,1);
							linhatable.remove();
						}
					}
					totalexcluidos = 1;
				}
			}else{
				$(".gradededados input[type='checkbox']").each(function(){
					if ($(this).prop("checked") == true){
						registro = $(this).val();
						if (isNumeric(registro)){
							excluirRegistro(entidade,registro);
						}else{
							excluirRegistroTemp(entidadeNome,$(this).val());	
						}						
						$(this).parents("tr").remove();						
						totalexcluidos += + 1;
					}
				});
			}

			if (totalexcluidos == instancia.qtdeMaxRegistro){
				if (instancia.blocoatual == 1){
					instancia.irbloco(2);			
				}else if(instancia.blocoatual == instancia.totalblocos){
					instancia.irbloco(parseInt(instancia.blocoatual)-1);
				}else{
					instancia.irbloco(parseInt(instancia.blocoatual)+1);
				}
			}
			if (typeof afterDelete === "function") afterDelete();
			if (instancia.totalRegistros <= 0){
				instancia.nenhumRegistro();
				composicao[td_entidade[instancia.entidade].descricao] = false;
			}
		  }
		},
		danger: {
		  label: 'N&atilde;o',
		  className: 'btn-danger'
		}
	  }
	});	
}
GradeDeDados.prototype.selecionarTodos = function(){
	$(".gd-sel-todos").each(function(){		
		if ($(this).attr("data-sel")=="false"){
			$(".gradededados input[type='checkbox']").prop("checked",true);
			$(this).attr("data-sel","true");
		}else{
			$(".gradededados input[type='checkbox']").prop("checked",false);
			$(this).attr("data-sel","false");
		}
		return false; // NÃ£o sei por que mas foi preciso para nÃ£o abrir outra pagina para as telas dentro de div
	});
}
GradeDeDados.prototype.rodape = function(){
	if (!this.retornaFiltro){
		if (this.table.find("tfoot").length <= 0){
		if (typeof qtdeTempRegistro === "undefined"){
			var qtdeTempReg = 1;
		}else{
			var qtdeTempReg = qtdeTempRegistro(this.nomeEntidade);
		}
		
			if (this.dadosCorpo.length > 0 || qtdeTempReg > 0){
				var tfoot = $("<tfoot>");
				var tr = $("<tr>");			
				var td = $("<td colspan='"+(this.attr_cabecalho_nome.length+3)+"'>");
				var btnExcluirTodos = $("<input type='button' style='float:right;' value='Excluir Selecionados' class='btn btn-default'>");
				var instancia = gradesdedados[this.contexto];
				btnExcluirTodos.click(function(){
					instancia.excluir();
				});
				td.append(btnExcluirTodos);
				tr.append(td);
				tfoot.append(tr);
				this.table.append(tfoot);
			}	
		}	
	}
}
GradeDeDados.prototype.retornaid = function(termo){
	if (this.retornaFiltro && this.funcaoretorno != ""){
		eval(this.funcaoretorno + "("+termo+");");
	}else{
		var entidade = (td_entidade[this.entidade].pacote==""?"":td_entidade[this.entidade].pacote + ".") + td_entidade[this.entidade].nome;
		buscarFiltro(termo,entidade,this.atributoRetorno,this.modalName,this.entidadeContexto);
	}
}
GradeDeDados.prototype.reload = function(){

	this.attr_cabecalho_nome = null;
	this.attr_cabecalho_descricao = null;
	this.attr_cabecalho_tipo = null;

	this.attr_cabecalho_nome = new Array("id");
	this.attr_cabecalho_descricao = new Array("ID");
	this.attr_cabecalho_tipo = new Array("int");
	
	this.load();
}
$(".irbloco-paginacao").keyup(function(e){
	if ( e.which == 13 ) {
		gradesdedados[this.contexto].irbloco(this.value);
	}		
});
GradeDeDados.prototype.clear = function(){
	this.filtros.splice(0,this.filtros.length);
	this.filtrosNN.splice(0,this.filtrosNN.length);
	this.filtroPesquisa = "";
	this.blocoatual = 1;
	this.table.find("tbody").html("");
}
GradeDeDados.prototype.loadDadosEdicao = function(id){
	var entidadeGD = this.nomeEntidade;
	var contextoAdd = ".crud-contexto-add-" + entidadeGD;
	var contextoListar = ".crud-contexto-listar-" + entidadeGD;
	$.ajax({
		url:config.urlloadform,
		data:{
			id:id,
			entidade:this.nomeEntidade
		},
		error:function(ret){
			console.log("ERRO ao carregar dados para edição => " + ret.responseText);
		},
		complete:function(ret){
			var retorno = ret.responseText;
			var linha = retorno.split("~");			
			for (ln in linha){
				var linhaDado = linha[ln].trim();
				if (linhaDado == "") continue;
				
				var entidade = linhaDado.split("^")[0];
				var valores = linhaDado.split("^")[1];				
				var campos = valores.split("Â¢");
				var grade = linhaDado.split("^")[2];
				var relacionamento = linhaDado.split("^")[3];
				var entidadeREL = linhaDado.split("^")[4];
				var atributoREL = linhaDado.split("^")[5];
				var contextoAddRel = ".crud-contexto-add-" + entidade;
				var contextoListarRel = ".crud-contexto-listar-" + entidade;
				
				
				if (relacionamento == 0 || relacionamento == 1 || relacionamento == 7 || relacionamento == 3){
					var gd = "";
					for (c in campos){
						if (campos[c] != ""){	
							var atributo = campos[c].split("=")[0];
							var valor = campos[c].split("=")[1];
							$("#" + atributo,contextoAddRel).first().val(valor);
						}
					}
					// Setando valores do CK Editor
					for(c in CKEditores){
						var instanciaCK = CKEditores[c];
						var entidadeCK = c.split("^")[0];
						var atributoCK = c.split("^")[1];
						instanciaCK.setData($("#" + atributoCK + "[data-entidade="+entidadeCK+"]",contextoAddRel).val());
					}
					
					//$(".form-grupo-botao",contextoListarRel).first().hide();
					//$(".select-flag-generalizacao option:selected").val()
					
				}else if (relacionamento == 2 || relacionamento == 6 || relacionamento == 5 || relacionamento == 8){
					
					// Grade de Dados dos Entidades de Relacionamento
					gradesdedados = new GradeDeDados(entidadeREL);
					gradesdedados.contexto 	= contextoListarRel;
					gradesdedados[td_entidade[entidadeREL].nome] = gradesdedados;
					for (d in campos){
						var campo = campos[d].split("=")[0];
						var valor = campos[d].split("=")[1];
						if (campo == atributoREL){
							gradesdedados.addFiltro(atributoREL,"=",valor);
						}
					}
					gradesdedados.show();

					$(".form-grupo-botao",contextoListarRel).first().hide();
					$(".select-flag-generalizacao",contextoAdd).val(entidadeREL);
					$(".select-flag-generalizacao").attr("readonly",true);
					
					//var camposAdd = replaceAll(dados,"Â¢","~").match(/\d{5}\.\r?\n\d{5}/);
					//var camposAdd = replaceAll(dados,"Â¢","^^^");
					//eval(entidade + "_add('"+entidade+"','"+camposAdd+"');");
					indice = dados.length;
					var val = valores.split("id=");
					for(v in val){
						if (val[v] != ""){							
							dados[indice] = entidade + "^id=" + replaceAll(val[v],"Â¢","~") + "^U";
							indice++;
						}	
						//if (composicao['.$entidade->contexto->id.'] != undefined){
						//	composicao['.$entidade->contexto->id.'].qtde++;
						//}
					}
				}

				// HabilitaÃ§Ã£o das ABAS das Entidades de Relacionamentos
				if ($(".div-relacionamento-generalizacao,.generalizacaoABA",contextoAdd)){
					$(".div-relacionamento-generalizacao,.generalizacaoABA",contextoAdd).hide();
				}

				if ($(".generalizacaoABA." + entidade,contextoAdd)[0]){
					$(".generalizacaoABA." + entidade,contextoAdd)[0].style.display = "";
				}else{
					if ($(".generalizacaoABA",contextoAdd)[0]){
						$(".generalizacaoABA",contextoAdd)[0].style.display = "";	
					}
				}

				if ($(".div-relacionamento-generalizacao[id=drv-"+entidade+"]",contextoAdd)[0]){
					$(".div-relacionamento-generalizacao[id=drv-"+entidade+"]",contextoAdd)[0].style.display = "";
				}else{
					if ($(".div-relacionamento-generalizacao",contextoAdd)[0]){
						$(".div-relacionamento-generalizacao",contextoAdd)[0].style.display = "";
					}
				}
				
				var entidadesREL = "";
				$.ajax({
					url:config.url_requisicoes,
					data:{
						op:"retorno_entidades_relacionamento_nome",
						entidade:entidade
					},
					complete:function(ret){
						eval(ret.responseText);
					},
					error:function(ret){
						console.log("ERRO ao consultar os nomes das entidades de relacionamento => " + ret.responseText);
					}
				});
			}
		}
	});
}
GradeDeDados.prototype.addLinha = function(id,linha,linhareal=""){
	var i = 0;		
	var tr = $("<tr idregistro="+id+">");

	for(n in this.attr_cabecalho_nome){
		var valor =  linha[this.attr_cabecalho_nome[n]];
		var valorreal = linhareal[this.attr_cabecalho_nome[n]];
		var idAtributo = getIdAtributo(this.attr_cabecalho_nome[n],this.nomeEntidade);
		if (idAtributo != 0 && idAtributo != "" && idAtributo != undefined){
			if (td_atributo[idAtributo] != undefined){
				if (td_atributo[idAtributo].tipohtml == 7){
					if (parseInt(valor) == 0){
						valor = (td_atributo[idAtributo].labelzerocheckbox == ""?"Não":td_atributo[idAtributo].labelzerocheckbox);
					}else if(parseInt(valor) == 1){
						valor = (td_atributo[idAtributo].labelumcheckbox == ""?"Sim":td_atributo[idAtributo].labelumcheckbox);
					}
				}
			}
			if (typeof td_consulta[this.consulta] != "undefined"){
				for (f in td_consulta[this.consulta].status){
					var ft = td_consulta[this.consulta].status[f];
					if (ft.td_atributo == idAtributo){
						switch (ft.operador){
							case "=": var operador = "=="; break;
							case "!": var operador = "!="; break;
							default: var operador = "==";
						}

						var tipohtml = td_atributo[ft.td_atributo].tipohtml;
						if (valorreal != ""){
							if (parseInt(tipohtml) == 11){
								var dt = valorreal.split(" ")[0];
								if (dt != undefined && dt != null && dt != ''){
									var data = dt.split("-")[2] + "/" + dt.split("-")[1] + "/" + dt.split("-")[0];
									var data1 = new Date(dt.split("-")[2],dt.split("-")[1],dt.split("-")[0]);
									var dt2 = (ft.valor=="now()"?config.datahora.split(" ")[0]:ft.valor);
									var data2 = new Date(dt2.split("/")[0],dt2.split("/")[1],dt2.split("/")[2]);
									switch(ft.operador){
										case "=":
											if (data1 == data2) eval("tr.addClass('"+td_status[ft.status].classe+"');");
										break;
										case "!":
											if (data1 != data2) eval("tr.addClass('"+td_status[ft.status].classe+"');");
										break;
										case ">":
											if (data1 > data2) eval("tr.addClass('"+td_status[ft.status].classe+"');");
										break;
										case "<":
											if (data1 < data2) eval("tr.addClass('"+td_status[ft.status].classe+"');");
										break;								
										case ">=":
											if (data1 >= data2) eval("tr.addClass('"+td_status[ft.status].classe+"');");
										break;
										case "<=":
											if (data1 <= data2) eval("tr.addClass('"+td_status[ft.status].classe+"');");
										break;
									}
								}	
							}else if (parseInt(tipohtml) == 23){
								var dt = valorreal.split(" ")[0];
								if (dt != undefined && dt != null && dt != ''){
									var data = dt.split("-")[2] + "/" + dt.split("-")[1] + "/" + dt.split("-")[0];
									var hora = valorreal.split(" ")[1];
									var datahora = data + " " + hora;
									var data1 = new Date(datahora);
									var data2 = new Date((ft.valor=="now()"?config.datahora:ft.valor));
									switch(ft.operador){
										case "=":									
											if (data1 == data2) eval("tr.addClass('"+td_status[ft.status].classe+"');");
										break;
										case "!":
											if (data1 != data2) eval("tr.addClass('"+td_status[ft.status].classe+"');");
										break;
										case ">":
											if (data1 > data2) eval("tr.addClass('"+td_status[ft.status].classe+"');");
										break;
										case "<":
											if (data1 < data2) eval("tr.addClass('"+td_status[ft.status].classe+"');");
										break;								
										case ">=":
											if (data1 >= data2) eval("tr.addClass('"+td_status[ft.status].classe+"');");
										break;
										case "<=":
											if (data1 <= data2) eval("tr.addClass('"+td_status[ft.status].classe+"');");
										break;
									}
								}	
							}else{
								eval("if ("+valorreal+operador+ft.valor+"){ tr.addClass('"+td_status[ft.status].classe+"');}");
							}
						}	
					}
				}
			}
		}
		
		var td = $("<td><span class='grade-info'>" + (valor==undefined?"":valor) + "</span></td>");
		tr.append(td);
	}
	
	var instancia = gradesdedados[this.contexto];
	var btnOutraJanela = "<td align='center'><span onclick=outrajanela(this,38978,'index.php?controller=crud&amp;id=38978&amp;op=add&amp;t=19&amp;filtro_rel_nn=&amp;modal=true'); class='botao fas fa-external-link-alt btn btn-default'></span></td>";
	
	if (this.movimentacao != ""){
		var spanMovimentacao = $("<span reg='"+id+"' class='botao fas fa-share btn btn-default' onclick=movimentacao('"+this.entidade+"','"+id+"',"+this.movimentacao+")></span>");
	}
	var entidadeGD = this.nomeEntidade;
	var entidade = this.entidade;
	
	if (this.movimentacao != ""){
		var btnMovimentacao = $("<td align='center' class='editar-coluna-gradededados'></td>");
		btnMovimentacao.append(spanMovimentacao);
	}
	
	var spanEditar = $("<span reg='"+id+"' data-entidade='"+this.entidade+"' class='botao fas fa-edit btn btn-default' ></span>");
	spanEditar.click(function(){
		entidadeid = $(this).data("entidade");
		var id = $(this).attr("reg");
		if (typeof funcionalidade !== "undefined"){
			if (funcionalidade == "cadastro"){
				editarFormulario(entidadeid,id);
			}else if (funcionalidade == "consulta"){
				carregar(session.currentprojectregisterpath + 'files/cadastro/' + entidadeid + '/' + entidadeGD + '.html',"#conteudoprincipal");
				editarFormulario(entidadeid,id);
			}
		}else{
			console.log("Variavel 'funcionalidade' não encontrada. ");
		}
	});

	
	var entidadeGD = this.nomeEntidade;
	var entidade = this.entidade;
	
	var btnEditar = $("<td align='center' class='editar-coluna-gradededados'></td>");
	btnEditar.append(spanEditar);
	
	var tdExcluir = $("<td align='center' class='excluir-coluna-gradededados'>");
	var btnExcluir = $("<span class='botao fas fa-trash-alt btn btn-danger'></span>");	
	var loaderExcluir = $("<img class='loader-excluir-gd' style='display:none;' src='system/tema/padrao/loading2.gif' />");
	var instancia = gradesdedados[this.contexto];	
	btnExcluir.click(function(){
		instancia.excluir(id,$(this));
	});
	tdExcluir.append(btnExcluir);
	tdExcluir.append(loaderExcluir);
	var checkExcluir = "<td align='center'><span align='center' class='grade-info'><input type='checkbox' value='"+id+"'></span></td>";		
	if (!this.retornaFiltro){
		if (this.movimentacao != ""){
			tr.append(btnMovimentacao);
		}
		tr.append(btnEditar);
		tr.append(tdExcluir);
		tr.append(checkExcluir);
	}else{
		if (this.funcaoretorno != ""){
			tr.attr("style","cursor:pointer;");
			var instancia = this;
			tr.click(function(){
				instancia.retornaid(id); 
			});
		}
	}
	this.table.find("tbody tr[class=warning]").remove();
	this.table.find("tbody").append(tr);
	
	if (this.retornaFiltro){
		if ($(".editar-coluna-gradededados").length > 0){
			$(".editar-coluna-gradededados").remove();
		}
		if ($(".excluir-coluna-gradededados").length > 0){
			$(".excluir-coluna-gradededados").remove();
		}
	}
}
GradeDeDados.prototype.nenhumRegistro = function(){
	//this.dadosCorpo
	for(atr in td_atributo){
		if (td_atributo[atr].exibirgradededados == 1 && td_atributo[atr].entidade == this.entidade){
			this.attr_cabecalho_nome.push(td_atributo[atr].nome);
			this.attr_cabecalho_descricao.push(td_atributo[atr].descricao);
			this.attr_cabecalho_tipo.push(td_atributo[atr].tipo);
		}
	}

	this.dadosCorpo = "";
	this.setaTable();
	this.pesquisa();
	this.cabecalho();
	this.corpo();
	this.paginacao();
	this.rodape();	
}
GradeDeDados.prototype.barraPesquisa = function (exibir = true){
	if (exibir){
		$(this.contexto).find(".pesquisa-grade").show();
	}else{
		$(this.contexto).find(".pesquisa-grade").hide();
	}
}

GradeDeDados.prototype.retiraSelecionarTodos = function (){
	var gdseltodos = $(this.contexto).find(".gd-sel-todos");
	if (gdseltodos.length > 0){
		gdseltodos.parents("th").first().remove();
	}
}