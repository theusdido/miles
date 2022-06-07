var EntidadePrincipalID                 = $("#entidadeprincipalid").val();
formulario[EntidadePrincipalID]         = new tdFormulario(EntidadePrincipalID);

<<<<<<< HEAD
// Funcionalidade tem que vir antes do registro único
if (typeof funcionalidade != 'undefined') formulario[EntidadePrincipalID].funcionalidade = funcionalidade;
=======
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
	debugger;
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
>>>>>>> 0017abd (status na grade de dados)

// Registro Único
is_registrounico = typeof registrounico == 'undefined' ? formulario[EntidadePrincipalID].entidade.registrounico : registrounico;
if (is_registrounico){
    formulario[EntidadePrincipalID].setRegistroUnico();
}else{
    formulario[EntidadePrincipalID].loadGrade();
}

if (funcionalidade == 'consulta'){
    formulario[EntidadePrincipalID].setConsulta($('#consulta_id').val());
}