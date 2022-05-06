/* Funções Padrão do Sistema */
function carregar(arquivo,elemento_retorno = "",callback_function = null){
	var url = arquivo.replace(" ","");
	session.urlloaded = url;
	$.ajax({
		type:"GET",
		url:getURLProject(url),
		crossDomain: true,
		beforeSend:function(){
			if (elemento_retorno == ""){
				addLoaderGeral();
			}else{
				loader(elemento_retorno);
			}
		},		
		complete:function(retorno){
			$(elemento_retorno).html(retorno.responseText);
			if (elemento_retorno == "") unLoaderGeral();
			if (typeof callback_function == "function") callback_function();
		},
		error:function(ret){
			if (elemento_retorno == ""){
				console.log("ERRO ao carregar página => " + ret.responseText);
			}else{
				$(elemento_retorno).html("ERRO ao carregar página => " + ret.responseText);
			}
		}
	});
}
function anexar(arquivo,elemento_retorno){
	var url = arquivo.replace(" ","");
	session.urlloaded = url;
	$.ajax({
		type:"GET",
		url:getURLProject(url),
		crossDomain: true,
		beforeSend:function(){
			loader(elemento_retorno);
		},
		complete:function(retorno){
			$(elemento_retorno).append(retorno.responseText);
		},
		error:function(ret){
			console.log("ERRO ao anexar página => " + ret.responseText);
		}
	});
}
function loader(elemento_retorno){
	$(elemento_retorno).html("");
	$(elemento_retorno).html(
		'<div style="width:100%;margin:75px auto">' +
			'<center>' +
				'<img width="32" align="middle" src="'+session.urlloading+'">' +
				'<p class="text-muted">Aguarde</p>' +
			'</center>' +	
		'</div>'
	);
}
function isNumeric(str){
  var er = /^[0-9]+$/;
  return (er.test(str));
}
// Funções para substituir todos os caracteres de uma string
function replaceAll(str, de, para){
	var retorno = str;
	for (i=0;i<de.length;i++){
		retorno = retirar(retorno,de.substr(i,1),para);		
	}
	return retorno;
	function retirar(str, de, para){
		var pos = str.indexOf(de);
		while (pos > -1){
			str = str.replace(de, para);
			pos = str.indexOf(de);
		}
		return (str);
	}	
}
// Script das mensagens da class retorno
function abrirAlerta(){
	if (arguments[1] == 'alert-success' ){
		$(arguments[2] + ' .alert').removeClass('alert-danger');
	}else if(arguments[1] == 'alert-danger'){
		$(arguments[2] + ' .alert').removeClass('alert-sucess');
	}
	$(arguments[2] + ' .alert').addClass(arguments[1]);
	$(arguments[2] + ' .alert .retorno-msg').remove();
	
	$(arguments[2] + ' .alert').append('<span class=\'retorno-msg\'>' + arguments[0] + '</span>');
	$(arguments[2]).show('1000');
	fecharAlerta(arguments[2]);
}
function fecharAlerta(classe){
	setTimeout(function(){
			$(classe).hide('200');
	},3000);
}
if( typeof parent.retorno == 'function'){
	//parent.retorno();
}
function inicializaEndereco(){		
		var bairroPadrao = "Centro";
		var cidadePadrao = "Crici\u00fama";
		var estadoPadrao = "SC";		
		
		$.ajaxSetup({
				scriptCharset: "utf-8" , 
				contentType: "application/x-www-form-urlencoded;charset=UTF-8"
		});
		
		// Carrega UF (Estado)
		$.ajax({
			type:"GET",
			url:"system/controller/enderecofiltro.csp",
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			data:{
				op:"retorna_lista_uf",
				selecionado:estadoPadrao
			},
			complete:function(ret){
				var estados = ret.responseText;
				$("#estado").html(estados);
				
				// Carrega Localidade ( Cidade )
				$.ajax({
					type:"GET",
					url:"system/controller/enderecofiltro.csp",
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					data:{
						op:"retorna_lista_localidade",
						uf:estadoPadrao,
						selecionado:cidadePadrao
					},
					complete:function(ret){
						var cidades = ret.responseText;
						$("#cidade").html(cidades);
						
						// Carrega Bairro
						$.ajax({
							type:"GET",
							url:"system/controller/enderecofiltro.csp",
							contentType: "application/x-www-form-urlencoded;charset=UTF-8",
							data:{
								op:"retorna_lista_bairro",
								uf:estadoPadrao,
								localidade:cidadePadrao,
								selecionado:bairroPadrao
							},
							complete:function(ret){
								var bairros = ret.responseText;
								$("#bairro").html('<option value="-1">Escolha um bairro ...</option><option value="0">Adicionar Manualmente ...</option>');
								$("#bairro").append(bairros);
							}
						});
						
					},
					error:function(ret){
						console.log("ERRO ao carregar lista de cidade [Endereço] => " + ret.responseText);
					}
				});

			}
		});
		$("#logradouro,#cep").val('');
		$("#cep").mask("99999-999");
		
		$("#tabela-resultado-busca-endereco tbody").html('<tr class="warning"><td class="text-center" colspan="2">Nenhum registro encontrado.</td></tr>');
		$("#input-endereco-busca").val("");
}
var conteudoloader = "";
function loader(){
	if (arguments.length <= 0){
		$(".loader-pagina").html(
			'<div style="width:100%;margin:75px auto" id="loader-geral">' +
				'<center>' +
					'<img width="32" align="middle" src="'+session.urlloading+'">' +
					'<p class="text-muted">Aguarde</p>' +
				'</center>' +	
			'</div>'
		);
		$(".loader-pagina").show();
	}else{
		$(arguments[0]).html(
			'<div style="width:100%;margin:75px auto" id="loader-geral">' +
				'<center>' +
					'<img width="32" align="middle" src="'+session.urlloading+'">' +
					'<p class="text-muted">Aguarde</p>' +
				'</center>' +	
			'</div>'
		);
		$(arguments[0]).show();
	}
}
function unloader(){
	if (arguments.length <= 0){
		$(".loader-pagina").html("");
		$(".loader-pagina").hide();
		if ($("#loader-geral")){
			$("#loader-geral").remove();
		}
	}else{
		$(arguments[0]).html("");
		$(arguments[0]).hide();
	}	
}
function retornaFiltroDescricao(entidade,id){
	var r = "";	
	$.ajax({
		type:"GET",
		url:config.urlrequisicoes,
		data:{
			op:"retorna_descricao_filtro",
			termo:id,
			entidade:entidade.replace("_",".")
		},
		complete:function(retorno){
			r = retorno.responseText;
		},
		error:function(ret){
			console.log("ERRO ao retornar o filtro da descrição => " + ret.responseText);
		}
	});
	return r;
}
function statusFormControl(campo,tipo){
	$('.status-'+$(campo).attr("id")).remove();
	switch (tipo){
		case 'success':
			if (!$(campo).parent().hasClass("calendar-picker-group")){
				$(campo).parent().addClass('has-success has-feedback');
				$(campo).parent().removeClass('has-error');
				$(campo).parent().append(
					'<span class=\"fas fa-check form-control-feedback status-'+$(campo).attr("id")+'\" aria-hidden=\"true\"></span>' +
					'<span class=\"sr-only status-'+$(campo).attr("id")+'\">(success)</span>'
				);							
			}else{
				$(campo).parent().parent().addClass('has-success has-feedback');
				$(campo).parent().parent().removeClass('has-error');
			}		
		break;
		case 'error':
			if (!$(campo).parent().hasClass("calendar-picker-group")){
				$(campo).parent().addClass('has-error has-feedback');
				$(campo).parent().removeClass('has-success');
				$(campo).parent().append(
					'<span class=\"fas fa-times form-control-feedback status-'+$(campo).attr("id")+'\" aria-hidden=\"true\"></span>' +
					'<span class=\"sr-only status-'+$(campo).attr("id")+'\">(error)</span>'
				);
			}else{
				$(campo).parent().parent().addClass('has-error has-feedback');
				$(campo).parent().parent().removeClass('has-success');	
			}
		break;
		case 'default':
		default:
			if (!$(campo).parent().hasClass("calendar-picker-group")){
				$(campo).parent().removeClass('has-success');
				$(campo).parent().removeClass('has-error');
			}else{
				$(campo).parent().parent().removeClass('has-success');
				$(campo).parent().parent().removeClass('has-error');				
			}	
		break;	
	}
}
function getHierarquia(pai = 0,filho = 0){
	if ((filho == 0 && pai != 0) || (pai == filho)){
		// Se tiver apenas a entidade pai pode retornar somente o nome da entidade
		return td_entidade[pai].nomecompleto;
	}else if(pai == 0 && filho != 0){
		return td_entidade[filho].nomecompleto;
	}else{
		return getHierarquiaRel(getRelacionamento(pai,filho).id);
	}
}
function getHierarquiaRel(rel){
	var relacionamento 	= td_relacionamento[rel];
	var pai 			= td_entidade[relacionamento.pai];
	var filho 			= td_entidade[relacionamento.filho];
	return pai.nomecompleto + "-" + filho.nomecompleto;
}
function getEntidadePai(entidade){
	var retorno = "";
	var c = 0;
	for (relH in td_relacionamento){
		if (td_relacionamento[relH].filho == entidade){
			retorno = td_relacionamento[relH].pai;
			c++;
		}
	}
	if (c > 1){
		retorno = $(".select-flag-generalizacao").val();
	}	
	return retorno;
}
function addLoaderGeral(){	
	$(".loadergeral").show();
	$("#conteudoprincipal").hide();
	$('.conteudo-home').hide();
}
function unLoaderGeral(){	
	$(".loadergeral").hide();
	$("#conteudoprincipal").show();
	$('.conteudo-home').hide();
}
function addLoaderSalvar(contexto){
	$(".loader-salvar",contexto).html(getIMGLoader());
	$(".loader-salvar",contexto).find('.loading2').show();
	$(".loader-salvar",contexto).show();
}
function unLoaderSalvar(contexto){
	$(".loader-salvar",contexto).hide();
	$(".loader-salvar",contexto).find('.loading2').hide();
}
function unLoaderPadrao(elemento){	
	/*$(".loaderpadrao").hide();*/
}
function addLoaderPagina(){	
	if (arguments.length > 0){
		$(arguments[0]).parents(".crud-contexto").first().find(".loader-pagina").show();
	}else{
		$(".loader-pagina").show();
	}
}
function unLoaderPagina(){
	if (arguments.length > 0){
		$(arguments[0]).parents(".crud-contexto").first().find(".loader-pagina").hide();
	}else{
		$(".loader-pagina").hide();
	}
}
function menuLateral(target){	
	var listgroup = $('<div class="list-group">');
	listgroup.append('<a href="#" class="list-group-item disabled">Menu</a>');
	for (m in menuprincipal.dados){
		var menum = menuprincipal.dados[m];
		if (menum.pai == menuprincipalselecionado){
			var item = $('<a href="'+menu.link+'" class="list-group-item">'+menum.descricao+'</a>');
			listgroup.append(item);			
		}
	}	
	$(target).html(listgroup);
}
function setCookie(name, value, duration) {
        var cookie = name + "=" + escape(value) +
        ((duration) ? "; duration=" + duration.toGMTString() : "");
        document.cookie = cookie;
}
function getCookie(name) {
    var cookies = document.cookie;
    var prefix = name + "=";
    var begin = cookies.indexOf("; " + prefix);
    if (begin == -1) {
        begin = cookies.indexOf(prefix);
        if (begin != 0) {
            return null;
        }
    } else {
        begin += 2;
    }
    var end = cookies.indexOf(";", begin);
    if (end == -1) {
        end = cookies.length;                        
    }
    return unescape(cookies.substring(begin + prefix.length, end));
}
function deleteCookie(name) {
   if (getCookie(name)) {
		  document.cookie = name + "=" + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
   }
}
function getIdAtributo(atributonome,entidadenome){
	var entidade = 0;
	for (e in td_entidade){
		if (td_entidade[e].nomecompleto == entidadenome){
			entidade = td_entidade[e].id;
		}
	}
	var idRetorno = 0;
	for (a in td_atributo){
		if (td_atributo[a].nome == atributonome && td_atributo[a].entidade == entidade){
			idRetorno = td_atributo[a].id;
		}
	}
	return idRetorno;
}
function getParmsURL(){
	var retorno = [];
	if (session.urlloaded == "" || session.urlloaded == undefined) return retorno;
	var parms = session.urlloaded.split("?")[1];
	if (parms == undefined) return retorno;	
	if (parms.indexOf("&") <= 0){
		retorno[parms.split("=")[0]] = parms.split("=")[1];
	}else{
		var rparms = parms.split("&");
		for(p in rparms){
			retorno[rparms[p].split("=")[0]] = rparms[p].split("=")[1];
		}
	}
	return retorno;
}
function retornaPermissao(funcao){
	var retorno = false;
	for (fp in td_funcaopermissao){
		if (td_funcaopermissao[fp].funcao == funcao && td_funcaopermissao[fp].usuario == session.userid && td_funcaopermissao[fp].permissao == 1){
			retorno = true;
		}
	}
	return retorno;
}
function obrigatoriedade(campo){
	var obrigar = true;
	if (arguments.length > 1){
		if (!arguments[1]){
			obrigar = false;
		}
	}
	
	if (obrigar){
		campo.attr("required","true");
		var label = campo.parents(".form-group").find(".control-label");
		campo.parents(".form-group").find(".control-label .marcaobrigatorio-asterisco").remove();
		var htmllabel = label.html() + '<span class="marcaobrigatorio-asterisco">*</span>';
		label.html(htmllabel);
	}else{
		campo.removeAttr("required");
		campo.parents(".form-group").find(".control-label .marcaobrigatorio-asterisco").remove();
	}	
}
function addLog(valornew, valorold, atributo, entidade,valorid, acao, registro){
	$.ajax({
		url:config.urlrequisicoes,
		crossDomain: true,
		data:{
			op:"addlog",
			valornew:valornew,
			valorold:valorold,
			atributo:atributo,
			entidade:entidade,
			valorid:valorid,
			acao:acao,
			registro:registro
		},
		error:function(ret){
			console.log("ERRO ao registro LOG => " + ret.responseText);
		}
	});
}
function getEntidadeId(nomeEntidade){
	var id = 0;
	for (e in td_entidade){
		if (td_entidade[e].nomecompleto == nomeEntidade) id = td_entidade[e].id;
	}
	return id;
}
function retornaDadoFormatadoCampo(campo,nomeEntidadeDados,contextoAdd,valorDados){
	if ($("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).prop("tagName") == "SELECT"){
		carregarListas(nomeEntidadeDados,campo,contextoAdd,valorDados);
		if (valorDados != "" && valorDados != undefined){
			$("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).val(valorDados);
		}else{
			if ($("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).attr("required") == "required"){
				direto = false;
				$("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).prop('selectedIndex', 0);
			}
		}

		// Gambiarra pra liberar o código expedidor que vem em forma de texto do caché
		if (campo == "orgaoexpedidor"){
			direto = false;
			$("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).prop('selectedIndex', 0);
		}
	}
	if ($("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).hasClass("termo-filtro")){
		direto = false;
		var nomeEntidadeReplace = td_entidade[td_atributo[getIdAtributo(campo,nomeEntidadeDados)].chaveestrangeira].nomecompleto;
		//buscarFiltro(valorDados,nomeEntidadeReplace.replace("-","."),campo,pModalName + campo + cmodal,nomeEntidadeDados);
	}
	if ($("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).hasClass("checkbox-sn")){
		if (valorDados == 1){
			$("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).parents(".form-group").find(".checkbox-s").addClass("active");
			$("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).parents(".form-group").find(".checkbox-n").removeClass("active");
		}else{
			$("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).parents(".form-group").find(".checkbox-n").addClass("active");
			$("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).parents(".form-group").find(".checkbox-s").removeClass("active");
			valorDados = 0;
		}
	}
	if ($("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).hasClass("td-file-hidden")){
		if (valor != ""){
			direto = false;
			var idgeral = $("#id[data-entidade="+nomeEntidadeDados+"]",contextoAdd).val();
			$("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).parents(".form-group").find("iframe").first().attr("src",getURLProject("index.php?controller=upload&atributo="+campo+"&valor="+valor+"&id=" + idgeral));
		}
	}
	if ($("#" + campo + "[data-entidade="+nomeEntidadeDados+"]",contextoAdd).hasClass(".formato-moeda")){
		if (valorDados == 0) valorDados = "0,00";
	}
}

function retirarAcentos(palavra) {

var com_acento = "áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇ";
var sem_acento = "aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC";
var nova = "";

for(i=0;i<palavra.length;i++) {
   if (com_acento.search(palavra.substr(i,1))>=0)
       nova += sem_acento.substr(com_acento.search(palavra.substr(i,1)),1);
   else 
       nova += palavra.substr(i,1);
}
return nova;
}

function movimentacao(entidade,id,movimentacao){
	setCookie("entidademovdados",entidade,"");
	setCookie("idmovdados",id,"");
	setCookie("movimentacaoselecionada",movimentacao,"");
	$("#modal-movimentacao .modal-body p").load(session.folderprojectfiles + "files/movimentacao/"+movimentacao+"/"+td_entidade[entidade].nomecompleto+".html");
	$("#modal-movimentacao").modal("show");
}
function limpaArraysFormularioDados(){
	dados.splice(0,dados.length);
	dados_temp.splice(0,dados_temp.length);	
}
function isAtributoDependencia(entidade,atributo){
	if (td_atributo[getIdAtributo(atributo,entidade)].atributodependencia > 1){
		return true;
	}else{
		return false;
	}
}
function completaString(str,length){
	var caracter = String(arguments.length > 2 ? arguments[2] : "0");
	var direction = arguments.length > 3 ? arguments[3] : "left";
	
	var qcaracter = "";
	for (i=1;i<=(length-String(str).length);i++){
		qcaracter += caracter;
	}
	
	if (direction == "left"){
		return qcaracter + str;
	}else if (direction="right") {
		return str + qcaracter;
	}
}
/*  
	* carregarOptions
	* Data de Criacao: Desconhecido
	* Ultima atualização: 06/11/2020
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* Retorna as tags option de um elemento HTML
	* PARAMETROS
	*	@params: String elemento:"Seletor do elemento"
	*	@params: int entidade:"ID da Entidade"
	*	@params: int atributo:"ID do Atributo"
	*	@params: String filtro:"Filtro separado por ^"
	*	@params: Boolean obrigatorio:"Cria um Option extra caso não seja obrigatório"
	* RETORNO
	*	@return String lista de options
*/
function carregarOptions(elemento,entidade,atributo = 0,filtro = "",obrigatorio = false){	
	$.ajax({
		url:config.urlrequisicoes,
		type:"GET",
		data:{
			op:"carregar_options",
			entidade:entidade,
			atributo:atributo,
			filtro:filtro
		},
		beforeSend:function(){
			$(elemento).html("<option value=''>Aguarde ...</option>");
		},
		complete:function(ret){
			var retorno = ret.responseText;
			$(elemento).html("");
			if (obrigatorio == false){
				$(elemento).append('<option value="">-- Todos --</option>');
				$(elemento).append(retorno);
			}else{
				$(elemento).html(retorno);
			}
			
		},
		error:function(ret){
			console.log("ERRO ao carregar options => " + ret.responseText);
		}
	});
}
function getAtributoId(entidade,atributo){
	if (!isNumeric(entidade)){
		entidade = getEntidadeId(entidade);
	}
	var retornoID = 0;
	for(a in td_atributo){
		if (td_entidade[td_atributo[a].entidade] == undefined) continue;
		if (td_atributo[a].nome == atributo && td_atributo[a].entidade == entidade){
			retornoID = td_atributo[a].id;
			break;
		}
	}
	return retornoID;
}
function carregaAtributoDependente(atributo){

}
function isPaiEntidade(entidade){
	var ispai = false;	
	for(relGrade in td_relacionamento){
		if (td_relacionamento[relGrade].pai == entidade){
			ispai = true;
			break;
		}
	}
	return ispai;
}

function formatarCPF(cpf){
	return cpf.replace(/^(\d{3})(\d{3})(\d{3})/, "$1.$2.$3-");
}

function formatarCNPJ(cnpj){
	return cnpj.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})/, "$1.$2.$3/$4-");	
}

function getExtensao(filename) {
  return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
}
function getTipoExtensao(arquivo){
	var extensao = getExtensao(arquivo).toLowerCase();
	var tipo = '';
	switch(extensao){
		case 'jpg': tipo = 'imagem';
		case 'jpeg': tipo = 'imagem';
		case 'png': tipo = 'imagem';
		case 'gif': tipo = 'imagem';
		case 'bmp': tipo = 'imagem'; break;
		default: tipo = extensao;
	}
	return tipo;
}
function moneyToFloat(valor){
	var sempontos = replaceAll(valor, ".", "");
	return parseFloat(sempontos.replace(",","."));
}
function editarTDFormulario(entidade,id){
	
	carregar(session.folderprojectfiles + "files/cadastro/"+entidade+"/"+td_entidade[entidade].nomecompleto+".html",'#conteudoprincipal',function(){
		
		carregarScriptCRUD('editarformulario',entidade,id);
	});
}
function getRelacionamento(entidadepai,entidadefilho){
	let retorno = null;
	for(r in td_relacionamento){
		if (td_relacionamento[r].pai == entidadepai && td_relacionamento[r].filho == entidadefilho){
			retorno = td_relacionamento[r];
			break;
		}
	}
	return retorno;
}
function getURLProject(parametro = null){
	let nocache = new Date().getTime();
	if (parametro.indexOf("?") < 0 && typeof parametro == "string"){
		if (parametro.indexOf(".html") > -1 || parametro.indexOf(".htm") > -1){
			//parametro = parametro + "?nocahe=" + nocache
		}
		return parametro;
	}
	var urlproject 		= session.urlmiles.replace("index.php","") + "index.php";
	var parmsProject 	= [];
	parmsProject.push(getParamsOBJ("currentproject",session.projeto));
	//parmsProject.push(getParamsOBJ("nocache",nocache));
	var tipoparms = typeof parametro;
	switch(typeof parametro){
		case 'string':
			var pa = getURLParamsArray(parametro);
			paramsArray = pa.concat(parmsProject);
		break;
		case 'object':
		case 'array':
			paramsArray = parametro.concat(parmsProject);
		break;
	}
	var params = "";
	paramsArray.forEach(function(p){
		params += (params==""?"?":"&") + p.key + "=" + p.value;
	});
	var url = urlproject + params;
	return url;
}
function getURLParamsArray(url){
	var arrayParams = [];
	paramsURL 			= url.split("?");
	if (paramsURL[1] != undefined){
		var params 		= paramsURL[1].split("&");
		params.forEach(function(param){
			var p 		= param.split("=");
			var key 	= p[0];
			var value	= p[1];
			arrayParams.push(getParamsOBJ(key,value));
		});
	}
	return arrayParams;
}
function getParamsOBJ(key,value){
	return {
		key:key,
		value:value
	}
}
function getSRCLoader(){
	var src = session.urlloading2;
	return src;
}
function getIMGLoader(){
	return '<img src="'+getSRCLoader()+'" class="loading2" />';
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
			var ft = td_atributo[td_filtroatributo[tda].td_campo].nome + "^" + td_filtroatributo[tda].operador + "^" + td_filtroatributo[tda].valor;
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
			if (valor != "" && valor != undefined && valor != 0){
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

function excluirArquivoUpload(dadosarquivos,entidade,atributo){
	$("iframe[data-entidade="+entidade+"][data-atributo="+atributo+"]").attr("src",getURLProject("index.php?controller=upload&atributo="+atributo+"&valor="));
	$("[atributo="+atributo+"]").val('{"op":"excluir","filename":"'+dadosarquivos.filename+'"}');
}

function carregarScriptCRUD(tipo,entidade,registro_id = 0){
	
	formulario[entidade]				 	= new tdFormulario(entidade);
	formulario[entidade].funcionalidade 	= tipo;
	switch(tipo){
		case 'cadastro':
			// Registro Único
			let is_registrounico = typeof registrounico == 'undefined' ? formulario[entidade].entidade.registrounico : registrounico;
			if (is_registrounico){
				formulario[entidade].setRegistroUnico();
			}else{
				formulario[entidade].loadGrade();
			}

			// Monta os formulários das entidades que compoem o relacionamento
			formulario[entidade].entidades_filho.forEach((entidade_id)=>{
				formulario[entidade_id] = new tdFormulario(entidade_id);
				formulario[entidade_id].loadGrade();    
			});
		break;
		case 'editarformulario':
			formulario[entidade].registro_id 	= registro_id;
			formulario[entidade].editar();
		break;
		case 'consulta':
			formulario[entidade].setConsulta($('#consulta_id').val());
		break;	
	}
}