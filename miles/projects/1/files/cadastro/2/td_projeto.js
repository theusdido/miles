// Invocado ao clicar no botão Novo
function beforeNew(){
}
// Executa após o carregamento padrão de uma novo registro
function afterNew(contextoAdd){
	if (contextoAdd === '#crud-contexto-add-td_projeto-td_connectiondatabase'){
		if ($(".btn-importar-db-from-file").length <= 0){
			var btnimportarfromfile = $("<button class='btn btn-importar-db-from-file'>Importar</button>");
			btnimportarfromfile.click(function(){
				importarConexaoFromFile();
			});
			$('#b-salvar-td_connectiondatabase').after(btnimportarfromfile);
		}
	}
}
// Invocado ao clicar no botão Salvar
function beforeSave(){
}
// Executa após o salvamento padrão de um registro
function afterSave(){
}
// Invocado ao clicar no botão Editar 
function beforeEdit(){	

}
// Executa após o carregamento padrão da edição de registro
function afterEdit(){
	var entidade = arguments[0];
	var registro = arguments[1];

	if (entidade == 39){
		verificaDataBaseInstalled();
	}	
}
// Invocado ao clicar no botão Voltar
function beforeBack(){
}
// Executa após a ação de voltar a tela anterior
function afterBack(){
}
// Invocado ao clicar no botão Deletar
function beforeDelete(){
}
// Executa após a exclusão de um registro
function afterDelete(){
}
if (typeof funcionalidade === 'undefined') var funcionalidade = 'cadastro';

function addBtnInstalar(display)
{
	if ($("#btn-instalar").length <= 0){
		var btninstalar = $("<button id='btn-instalar' class='btn btn-info formgroup-buttons-tdform'>"+display+"</button>");
		btninstalar.insertAfter("#crud-contexto-add-td_projeto-td_connectiondatabase .form-grupo-botao .b-salvar");	
		btninstalar.click(function(){			
			instalar();
		});
	}
}

var dadosconexao = {};
var novainstalacao;
function verificaDataBaseInstalled()
{
	var contextoAddTDForm = "#crud-contexto-add-td_projeto-td_connectiondatabase";
	
	setTimeout(function(){
		dadosconexao = {
			usuario:$(contextoAddTDForm + " #user").val(),
			senha:$(contextoAddTDForm + " #password").val(),
			base:$(contextoAddTDForm + " #base").val(),
			host:$(contextoAddTDForm + " #host").val(),
			tipo:"mysql",
			porta:$(contextoAddTDForm + " #port").val()
		};
	
		$.ajax({
			url:session.urlsystem + "install/criarbase.php",
			data:{
				op:"testarconexao",
				apenasstatus:true,
				usuario:dadosconexao.usuario,
				senha:dadosconexao.senha,
				base:dadosconexao.base,
				host:dadosconexao.host,
				sgbd:dadosconexao.tipo,
				porta:dadosconexao.porta
			},
			complete:function(ret){
				if (parseInt(ret.responseText) == 1){
					
					$.ajax({
						type:"POST",
						url:session.urlsystem + "install/instalacaosistema.php",
						data:{
							op:"installed",
							installsystem:1,
							usuario:dadosconexao.usuario,
							senha:dadosconexao.senha,
							base:dadosconexao.base,
							host:dadosconexao.host,
							tipo:dadosconexao.tipo,
							porta:dadosconexao.porta						
						},
						complete:function(ret){
							if (parseInt(ret.responseText) == 1){
								addBtnInstalar("Atualizar");
								novainstalacao = false;
								$("#btn-instalar").attr("data-installed",1);
							}else{
								addBtnInstalar("Instalar");
								novainstalacao = true;
								$("#btn-instalar").attr("data-installed",0);
							}
						}
					});
				}
			}
		});
	},100);
}

function instalar(){
	$.ajax({
		url:session.urlsystem + "install/criarbase.php",
		data:{
			op:"criarbanco",
			usuario:dadosconexao.usuario,
			senha:dadosconexao.senha,
			base:dadosconexao.base,
			host:dadosconexao.host,
			sgbd:dadosconexao.tipo,
			porta:dadosconexao.porta
		},
		beforeSend:function(){
			loading2Page("#crud-contexto-add-td_projeto-td_connectiondatabase");
			$("#b-salvar-td_connectiondatabase").hide("200");
			$("#btn-instalar").attr("disabled",true);
		},
		error:function(ret){
			console.log(ret);
			loading2Page("#crud-contexto-add-td_projeto-td_connectiondatabase",false);
			$("#b-salvar-td_connectiondatabase").show("200");
			$("#btn-instalar").removeAttr("disabled");
		},
		complete:function(ret){
			if (parseInt(ret.responseText) == 1){
				var paramsurliframe = "installsystem=1&usuario=" + dadosconexao.usuario + "&senha=" + dadosconexao.senha + "&base=" + dadosconexao.base + "&host=" + dadosconexao.host + "&tipo=" + dadosconexao.tipo + "&porta=" + dadosconexao.porta + "&projetoid=" + $("#crud-contexto-add-td_projeto #id").val();
				var iframeinstalacao = $("<iframe src='"+session.urlsystem+"install/instalacaosistema.php?"+paramsurliframe+"' ></iframe>");
				iframeinstalacao.hide();
				$("#crud-contexto-add-td_projeto-td_connectiondatabase").append(iframeinstalacao);
			}else{
				abrirAlerta("Erro ao instalar!","alert-danger",".msg-retorno-form-td_connectiondatabase");
				finalizacaoDisplayInstalacao();
			}
		}
	});
}

function finalizacaoDisplayInstalacao(){
	$("#crud-contexto-add-td_projeto-td_connectiondatabase .btn-instalar").hide("200");
	$("#b-salvar-td_connectiondatabase").show("200");
	loading2Page("#crud-contexto-add-td_projeto-td_connectiondatabase",false);
	abrirAlerta("Instalado com Sucesso!","alert-success",".msg-retorno-form-td_connectiondatabase");
	$("#btn-instalar").removeAttr("disabled");

	if (novainstalacao || parseInt($("#btn-instalar").data("installed")) == 0){
		$.ajax({
			type:"POST",
			url:session.urlsystem + "install/instalacaosistema.php",
			data:{
				op:"inserirregistros",
				installsystem:1,
				novainstalacao:1,
				usuario:dadosconexao.usuario,
				senha:dadosconexao.senha,
				base:dadosconexao.base,
				host:dadosconexao.host,
				tipo:dadosconexao.tipo,
				porta:dadosconexao.porta
			},
			complete:function(ret){
				if (parseInt(ret.responseText) == 1){
					addBtnInstalar("Atualizar");
					novainstalacao = false;
				}else{
					addBtnInstalar("Instalar");
					novainstalacao = true;
				}
			}
		});
	}
	
	var dadosconexaodescricao = '<p><ul class="list-group">' + 
		'<li class="list-group-item">Tipo: <b>'+$("#crud-contexto-add-td_projeto-td_connectiondatabase #td_type option:selected").html()+'</b></li>' + 
		'<li class="list-group-item">SGBD: <b>'+$("#crud-contexto-add-td_projeto-td_connectiondatabase #td_sgdb option:selected").html()+'</b></li>' + 
		'<li class="list-group-item">Host: <b>'+$("#crud-contexto-add-td_projeto-td_connectiondatabase #host").val()+'</b></li>' + 
		'<li class="list-group-item">Base: <b>'+$("#crud-contexto-add-td_projeto-td_connectiondatabase #base").val()+'</b></li>' + 
		'<li class="list-group-item">Usuário: <b>'+$("#crud-contexto-add-td_projeto-td_connectiondatabase #user").val()+'</b></li>' +
		'<li class="list-group-item">Porta: <b>'+$("#crud-contexto-add-td_projeto-td_connectiondatabase #port").val()+'</b></li>' + 
	'</ul></p>';
	bootbox.confirm({
		message: "Deseja acessar o projeto com essa base de dados abaixo: " + dadosconexaodescricao,
		buttons: {
			confirm: {
				label: 'Sim',
				className: 'btn-success'
			},
			cancel: {
				label: 'Não',
				className: 'btn-danger'
			}
		},
		callback: function (result) {
			if (result){
				var project = $("#crud-contexto-add-td_projeto #id").val();
				var tipo = $("#crud-contexto-add-td_projeto-td_connectiondatabase #td_type").val();
				var databaseid = $("#crud-contexto-add-td_projeto-td_connectiondatabase #id").val();
				criarArquivosConfiguracao();
				alterProject(project,tipo,databaseid);
			}
		}
	});
}
function criarArquivosConfiguracao(){
	$.ajax({
		type:"POST",
		url:session.urlsystem + "install/instalacaosistema.php",
		data:{
			op:"criararquivosconfiguracao",
			projetoid:$("#crud-contexto-add-td_projeto #id").val(),
			projetodesc: $("#crud-contexto-add-td_projeto #nome").val(),
			databasepadrao:$("#crud-contexto-add-td_projeto-td_connectiondatabase #td_type").val(),
		},
		complete:function(ret){
		}
	});	
}

function importarConexaoFromFile(){
	switch(parseInt($("#td_type").val())){
		case 1: var tipoconexao = 'desenv'; break;
		case 2: var tipoconexao = 'teste'; break;
		case 3: var tipoconexao = 'homolog'; break;
		case 4: var tipoconexao = 'producao'; break;
	}
	var sgbd = $("#td_sgdb option:selected").html();
	$.ajax({
		url:session.urlsystem + "projects/"+$("#projectdiretorio").val()+"/config/"+tipoconexao+"_"+sgbd+".ini",
		complete:function(ret){
			var retorno = ret.responseText;
			var linhas 	= retorno.split("\n");
			for (l in linhas){
				var linha = linhas[l].split("=");
				var campo = linha[0];
				var valor = linha[1];
				switch(campo){
					case 'usuario': campo = 'user'; break;
					case 'senha': campo = 'password'; break;
					case 'porta': campo = 'port'; break;
				}
				$("#" + campo).val(valor);
				console.log("#" + campo + "=>" + valor);
			}
		}
	});
}