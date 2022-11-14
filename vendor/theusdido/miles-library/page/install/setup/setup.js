$(document).ready(function(){
    $('#projectname').val();
    $('#projectfolder').val();
    $('#prefixo').val();
    $('#loader-instalar').attr('src',session.urlloading2);
});

var showpersonalizar    = false;
let linhas              = [];
let componentes         = [];

// System
componentes['system.entidade'] 				= 'system/entidade.php';
componentes['system.atributo'] 				= 'system/atributo.php';
componentes['system.menu'] 					= 'system/menu.php';
componentes['system.grupousuario'] 			= 'system/grupousuario.php';
componentes['system.usuario'] 				= 'system/usuario.php';
componentes['system.relacionamento']		= 'system/relacionamento.php';
componentes['system.abas'] 					= 'system/abas.php';
componentes['system.lista'] 				= 'system/lista.php';
componentes['system.pagina'] 				= 'system/pagina.php';
componentes['system.tagsattributes'] 		= 'system/tagsattributes.php';
componentes['system.tags'] 					= 'system/tags.php';
componentes['system.tipoaviso'] 			= 'system/tipoaviso.php';
componentes['system.aviso'] 				= 'system/aviso.php';
componentes['system.config'] 				= 'system/config.php';
componentes['system.entidadepermissoes']	= 'system/entidadepermissoes.php';
componentes['system.atributopermissoes'] 	= 'system/atributopermissoes.php';
componentes['system.funcao'] 				= 'system/funcao.php';
componentes['system.funcaopermissoes']		= 'system/funcaopermissoes.php';
componentes['system.menupermissoes'] 		= 'system/menupermissoes.php';
componentes['system.atributofiltro'] 		= 'system/atributofiltro.php';
componentes['system.status'] 				= 'system/status.php';
componentes['system.consulta'] 				= 'system/consulta.php';
componentes['system.relatorio'] 			= 'system/relatorio.php';
componentes['system.movimentacao'] 			= 'system/movimentacao.php';
componentes['system.log'] 					= 'system/log.php';
componentes['system.menucrud'] 				= 'system/menucrud.php';
componentes['system.typeconnectiondatabase']= 'system/typeconnectiondatabase.php';
componentes['system.database'] 				= 'system/database.php';
componentes['system.connectiondatabase'] 	= 'system/connectiondatabase.php';
componentes['system.connectionftp'] 		= 'system/connectionftp.php';
componentes['system.charset'] 				= 'system/charset.php';
componentes['system.projeto'] 				= 'system/projeto.php';
componentes['system.endereco'] 				= 'system/endereco.php';
componentes['system.empresa'] 				= 'system/empresa.php';
componentes['system.historicoatividade']	= 'system/historicoatividade.php';
componentes['system.comunicado']			= 'system/comunicado.php';
componentes['system.email']					= 'system/email.php';

// Helpdesk
componentes['helpdesk.status'] 				= 'helpdesk/status.php';
componentes['helpdesk.prioridade'] 			= 'helpdesk/prioridade.php';
componentes['helpdesk.tipo']				= 'helpdesk/tipo.php';
componentes['helpdesk.seguidores'] 			= 'helpdesk/seguidores.php';
componentes['helpdesk.ticket'] 				= 'helpdesk/ticket.php';
componentes['helpdesk.anexos'] 				= 'helpdesk/anexos.php';

// Aplicativo
componentes['aplicativo.dispositivo']		= 'aplicativo/dispositivo.php';
componentes['aplicativo.usuario'] 			= 'aplicativo/usuario.php';

// Geral
componentes['geral.mes']						= 'geral/datas/mes.php';
componentes['geral.diasemana']					= 'geral/datas/diasemana.php';
componentes['geral.feriado'] 					= 'geral/datas/feriado.php';
componentes['geral.email.emailconfiguracao']	= 'geral/email/emailconfiguracao.php';

let totalinstrucao      = 0;
let progressaoatual     = 0;

$("#btn-instalar").click(function(){
    for (c in componentes){
        linhas.push(componentes[c]);
    }
    totalinstrucao = linhas.length;

    $.ajax({
        type:"POST",
        url:session.urlrequisicoes,
        data:{
            controller:"install/instalar",
            op:"instalar",
            projectfolder:$("#projectfolder").val(),
            projectname:$("#projectname").val(),
            prefixo:$("#prefixo").val()
        },
        beforeSend:function(){
            $('#loader-instalar').show();
        },
        success:function(retorno){
            if (retorno == 1 || retorno == "1"){
                $("#barradeprogresso-instalacao .progress-bar").css("width","0%");
                $("#barradeprogresso-instalacao .progress-bar").html("0%");
                $("#barradeprogresso-instalacao").show();
                executa(linhas[progressaoatual]);
            }else{
                $("#retorno").html('<div class="alert alert-danger" role="alert">Erro ao instalar o sistema. Motivo: ' +retorno+ '</div>');
                $("#retorno").show();				
            }
        },
        complete:function(){
            $('#loader-instalar').hide();
        }
    });
});
$("#btn-personalizar").click(function(){
    if (showpersonalizar){						
        $("#linha-formulario").show();
        $("#linha-personalizacao").hide();
        showpersonalizar = false;
    }else{						
        $("#linha-formulario").hide();
        $("#linha-personalizacao").show();
        showpersonalizar = true;
        addComponentesPersonalizados();
    }
});
$("#btn-atualizar-personalizado").click(function(){
    // Pega os itens personalizados
    $("#formulario-personalizado .form-group input[type=checkbox]:checked").each(function(){
        instrucao = componentes[$(this).data("componente")];
        $.ajax({
            url:session.urlrequisicoes,
            beforeSend:function(){
                $("#loading-atualizar-personalizado").show();
            },
            data:{
                controller:"install/instalar",
                op:"instrucao",
                instrucao:instrucao
            },
            complete:function(retorno){
                $("#loading-atualizar-personalizado").hide();
                if (parseInt(retorno.responseText) == 1){
                    td.CallBackMenssage('Atualizado com Sucessos','success');
                }else{
                    td.CallBackMenssage('Erro ao atualizar','danger');
                }
            }
        });	
    });
});

function executa(instrucao){
    $.ajax({
        type:"POST",
        url:session.urlrequisicoes,
        data:{
            controller:"install/instalar",
            op:"instrucao",
            instrucao:instrucao
        },
        success:function(retorno){
            if (retorno == 1 || retorno == "1"){
                progressaoatual++;
                let percentual = parseInt((progressaoatual*100)/totalinstrucao);
                $("#barradeprogresso-instalacao .progress-bar").css("width",percentual + "%");
                $("#barradeprogresso-instalacao .progress-bar").html(percentual + "%");
                
                if (progressaoatual < totalinstrucao){
                    executa(linhas[progressaoatual]);
                }else{
                    $("#barradeprogresso-instalacao .progress-bar").removeClass("progress-bar-info");
                    $("#barradeprogresso-instalacao .progress-bar").addClass("progress-bar-success");
                    $("#barradeprogresso-instalacao .progress-bar").html("Sistema Instalado com Sucesso!");
                    //$("#guia-instalacao").attr("src","<?=URL_SYSTEM_THEME?>check.gif");
                    getStatusGuia();
                    setTimeout(function(){
                        $("#barradeprogresso-instalacao").hide("5000");
                    },5000);
                    $.ajax({
                        type:"POST",
                        url:session.urlrequisicoes,
                        data:{
                            controller:"install/instalar",
                            op:"projeto",
                            nome:$("#projectname").val()
                        }
                    });
                    $.ajax({
                        type:"POST",
                        url:session.urlrequisicoes,
                        data:{
                            controller:"install/instalar",
                            op:"inserirregistros"
                        },
                        complete:function(){
                            // Setando Permissões
                            $.ajax({
                                url:session.urlrequisicoes,
                                data:{
                                    controller:"requisicoes",
                                    op:"setar_todas_permissoes",
                                    auth:1,
                                    permissao:1
                                }
                            });
                        }
                    });
                    $.ajax({
                        type:"POST",
                        url:session.urlrequisicoes,
                        data:{
                            controller:'install/instalar',
                            op:"arquivos",
                            nome:$("#projectname").val()
                        }
                    });
                    $.ajax({
                        type:"POST",
                        url:session.urlrequisicoes,
                        data:{
                            controller:'install/instalar',
                            op:"versao"
                        }
                    });									
                }
            }else{
                $("#retorno").html('<div class="alert alert-danger" role="alert">Erro ao instalar o sistema. Motivo: ' +retorno+ '</div>');
                $("#retorno").show();
            }
        }
    });					
}

function addComponentesPersonalizados(){
    for(c in componentes){
        let formgroup 	= $('<div class="form-group">');
        let label 		= $('<label for="'+c+'">'+c+'</label>');
        let checkbox	= $('<input id="'+c+'" type="checkbox" data-componente="'+c+'">');

        formgroup.append(checkbox,label);
        $("#formulario-personalizado").append(formgroup);
    }
}