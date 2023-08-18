// Pesquisar JS	

// Ano ( MultiSelect )
var ano_exercicio;
$(document).ready(function() {
    addAno();
});

$("#btn-group-especificacao.btn-group button").click(function() {
    let value = $(this).data("value");
    $(this).parents().first().attr("data-value", value);
    $(this).parents().first().find("button").removeClass("btn-dark btn-light");
    $(this).parents().first().find("button[data-value=" + value + "]").addClass("btn-dark");
    $(this).parents().first().find("button[data-value=" + value + "]").addClass("btn-light");
});
var blocos = 0;
$("#pesquisar").click(function() {
    totalpessoasenviar = [];
    pesquisar();
});

function showLoader() {
    $("#tresultado tbody").html("");
    $("#conteudo-impressao").html("");
    $("#barra-progresso").show();
    $("#retorno").hide();
}

var totalpessoasenviar = [];
var retornopesquisa;

function pesquisar() {
    $.ajax({
        url: session.urlnodejs + 'pesquisarboleto',
        crossDomain: true,
        type: "GET",
        data: {
            pagador:$('#pagador').val(),
            cpfj:$('#cpfj').val(),
            nossonumero:$('#nossonumero').val()
        },
        beforeSend: function() {
            showLoader();
        },
        complete: function(ret) {
            let retorno         = JSON.parse(ret.responseText);            
            let totalRegistro   = retorno.length;

            if (totalRegistro <= 0) {
                $("#retorno #tresultado tbody").html('<tr class="alert-warning "><td colspan="10" class="text-center"><b>Nenhum Registro Encontrato</b></td></tr>');
            } else {
                retorno.forEach(function(dado) {

                    let id              = dado._id;
                    let nosso_numero    = dado.boleto.nosso_numero;
                    let nome            = dado.pessoa.nome;
                    let vencimento      = dado.boleto.data_vencto;
                    let valor           = dado.boleto.valor_docto;
                    let token           = dado.token;
                    let email           = dado.pessoa.email;
                    email = 'edilsonbitencourt@hotmail.com';
                    let link            = 'http://webservice.locativa.com.br/boleto/p' + token;
                    let dataparams      = ' data-id="' + id + '" data-email="' + email + '" data-enviado="false" data-token="' + token + '" ';

                    // Monta linha e coluna
                    let tr              = $('<tr class="bg-info tr-registro" data-addimpressao="false" ' + dataparams + '>');
                    
                    let tdNossoNumero   = $('<td class="text-left">' + nosso_numero + '</td>');
                    let tdNome          = $('<td class="text-left"><span id="nome-pessoa-' + id + '">' + nome + '</span></td>');
                    let tdVencimento    = $('<td class="text-center">' + vencimento + '</td>');
                    let tdValor         = $('<td class="text-right">' + valor + '</td>');

                    let tdEnviar        = $('<td>');
                    let buttonEnviar    = $('<button type="button" class="btn btn-dark btn-enviar-email-individual" ' + dataparams + '><i class="fa fa-envelope"></i></button>');

                    if (email == ''){
                        buttonEnviar = null;
                    }else{
                        buttonEnviar.click(function() {
                            let instanciaBotao = this;
                            bootbox.confirm({
                                message: "Tem certeza que deseja enviar ?",
                                buttons: {
                                    confirm: {
                                        label: "Sim",
                                        className: "btn-success"
                                    },
                                    cancel: {
                                        label: "Não",
                                        className: "btn-danger"
                                    }
                                },
                                callback: function(result) {
                                    if (result) {
                                        isenvioloteativo = false;
                                        enviar($(instanciaBotao).data("email"), $(instanciaBotao).data("token"));
                                    }
                                }
                            });
                        });                            
                    }

                    let loadingEnviar   = '<img src="'+session.urlloading2+'" class="loading2" />';
                    let idTREnvar       = 'tr-envio-email-' + token;
                    trEnviar            = $('<tr class="tr-enviar" id="' + idTREnvar + '" ' + dataparams + '></tr>');
                    let tdEnviarInput   = $('<td colspan="5"><input class="alterar-email-link" onblur="alterarEmailEnvioIndividual(event)" value="' + email + '"/></td>');
                    let tdEnviarMsg     = $('<td colspan="4" id="msg-retorno-' + token + '" class="msg-retorno-envio-email">' + loadingEnviar + '</td>');

                    trEnviar.append(tdEnviarInput);
                    trEnviar.append(tdEnviarMsg);

                    let tdImprimir      = $('<td>');
                    let buttonImprimir  = $('<button type="button" class="btn btn-dark" ' + dataparams + '><i class="fa fa-print"></i></button>');

                    // Botão ícone imprimir
                    buttonImprimir.click(function() {
                        window.open(link,'_blank');
                    });

                    let idLink              = "link-" + token;
                    let idTooltipoLink      = "tooltip-" + token;
                    let tdLink              = $('<td>');
                    let buttonLink          = $('<button id="' + idLink + '"  data-tooltipoid="' + idTooltipoLink + '" type="button" class="btn btn-dark" ' + dataparams + '><i class="fa fa-link"></i></button>');
                    let tooltipLink         = $('<div id="' + idTooltipoLink + '" class="tooltip-link" role="tooltip">' +
                        '<div class="btn-toolbar mb-3" role="toolbar" aria-label="Texto com link para copiar">' +
                        '<div class="input-group tooltip-display">' +
                        '<input id="input-link-' + idTooltipoLink + '" type="text" class="form-control input-link" placeholder="Link para copiar" aria-label="Link para Copiar" aria-describedby="btnGroupAddon" value="' + link + '">' +
                        '<div class="input-group-prepend">' +
                        '<div class="input-group-text input-group-text-link" id="btnGroupAddon"><span class="badge badge-light badge-copiado" data-tooltipoid="' + idTooltipoLink + '">Copiado!</span><i class="fa fa-copy icone-copiar-link" data-tooltipoid="' + idTooltipoLink + '"></i></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );

                    $(document).on('click','.icone-copiar-link',function() {

                        $("#input-link-" + $(this).data("tooltipoid")).select();
                        document.execCommand('copy');
                        var badgecopiado = ".badge-copiado[data-tooltipoid=" + $(this).data("tooltipoid") + "]";
                        $(badgecopiado).show(200);

                        $(this).parents(".input-group-text").css("background-color", "#e0e0e0");
                        $(this).css("color", "#000");

                        var instancia = $(this);
                        setTimeout(function() {
                            $(badgecopiado).hide(300);
                        }, 3000);

                    });

                    buttonLink.click(function() {
                        
                        var tooltipoid = $(this).data("tooltipoid");

                        // Setando os TOOLTIP
                        const linkExibir = document.querySelector('#' + $(this).attr("id"));
                        const tooltip = document.querySelector('#' + tooltipoid);

                        // Pass the button, the tooltip, and some options, and Popper will do the
                        // magic positioning for you:
                        Popper.createPopper(linkExibir, tooltip, {
                            placement: 'left',
                        });

                        if ($("#" + tooltipoid).css("display") == "none") {
                            $("#" + tooltipoid).show(100);
                        } else {
                            $(".input-group-text-link").css("background-color", "#e9ecef");
                            $(".icone-copiar-link").css("color", "#495057");
                            $("#" + tooltipoid).hide(500);
                        }
                        $("#input-link-" + tooltipoid).select();

                    });

                    tdLink.append(buttonLink);
                    tdLink.append(tooltipLink);
                    tdEnviar.append(buttonEnviar);
                    tdImprimir.append(buttonImprimir);
                    
                    tr.append(tdNossoNumero);
                    tr.append(tdNome);
                    tr.append(tdValor);
                    tr.append(tdVencimento);
                    tr.append(tdLink);
                    tr.append(tdImprimir);
                    tr.append(tdEnviar);

                    $("#tresultado tbody").append(tr);
                    $("#tresultado tbody").append(trEnviar);

                });
            }
            liberarBotoesCPD();
            hideLoader();

            // Totalizadores - Rodapés
            $('#tresultado .totalizador-registros').html(totalRegistro);

            /*
            let tr                  = $("<tr>");
            let td                  = $('<td colspan="9">');
            let divTotalRegistro    = $('<div class="col-rodape">Total de Registros: <b>' +totalRegistro + '</b></div>');

            td.append(divTotalRegistro);
            tr.append(td);
            $("#tresultado tfoot").html(tr);
            */
        }
    });
}

function hideLoader() {
    $("#barra-progresso").hide(500);
    $("#rodape_individual").show();
    $("#retorno").show();
}
var isenvioloteativo = false;
function enviar(email, token) {
    let trenvioseletor      = "#tr-envio-email-" + token;
    let msgerrorseletor     = "#msg-retorno-" + token;
    let loadingseletor      = msgerrorseletor + " img";
    let inputenviarseletor  = trenvioseletor + ' input';

    $.ajax({
        type: "GET",
        url: session.urlmiles,
        data: {
            controller: "locador/boleto",
            op: "enviar",
            email: email,
            token: token
        },
        beforeSend: function() {
            $(loadingseletor).show();
        },
        complete: function(ret) {
            var retorno = parseInt(ret.responseText);

            $(trenvioseletor).removeClass('bg-success-enviar');
            $(trenvioseletor).removeClass('bg-danger-enviar');

            if (retorno == 0) {
                $(inputenviarseletor).attr('readonly',true);
                $(trenvioseletor).addClass("bg-success-enviar");
            } else {
                $(trenvioseletor).addClass("bg-danger-enviar");
            }
            $(trenvioseletor).css("color", "#FFF");
            var msgerror = '';
            switch (retorno) {
                case 0:
                    msgerror = 'Enviado com sucesso';
                    break;
                case 1:
                    msgerror = 'Não foi possível enviar e-mail';
                    break;
                case 2:
                    msgerror = 'E-Mail inválido ou não encontrado';
                    break;
                case 3:
                    msgerror = 'Extrato não encontrado';
                    break;
            }

            $(msgerrorseletor).html(" [ " + msgerror + " ] ");
            $(loadingseletor).hide();
            $(trenvioseletor).attr("data-enviado", true);
            if (isenvioloteativo) {
                EnvioLote();
            }
        }
    });
}
$("#enviarlote").click(function() {
    if ($(".tr-enviar[data-enviado=false]").length == 0) {
        bootbox.alert("Nenhum extrato habilitado para envio.");
        return false;
    }

    var qtdadeproprietarios = 0;
    for (p in totalpessoasenviar) {
        qtdadeproprietarios++;
    }

    var title               = "<h4>Enviar os Extratos</h4>";
    var totalgeral          = "Total de Extratos: <b>" + $(".tr-registro").length + "</b>";
    var totalproprietarios  = "Total de Pessoas: <b>" + qtdadeproprietarios + "</b>";
    var totalcomemail       = "Total Extratos com E-Mail: <b>" + $(".tr-enviar[data-enviado=false]").length + "</b> <br/>";
    bootbox.confirm({
        message: title + "<br/>" + totalgeral + "<br/>" + totalproprietarios + "<br/>" + totalcomemail,
        buttons: {
            confirm: {
                label: 'Enviar',
                className: 'btn-success'
            },
            cancel: {
                label: 'Não',
                className: 'btn-danger'
            }
        },
        callback: function(result) {
            if (result) {
                isenvioloteativo = true;
                EnvioLote();
            }
        }
    });
});

function EnvioLote() {
    debugger;
    let proximo = $(".tr-enviar[data-enviado=false]").first();
    if (proximo.data("email") != "" && proximo.length > 0) {
        enviar(proximo.data("email"), proximo.data("token"));
    }
}

var isindividual = false;
$("#imprimir").click(function() {
    isindividual = false;
    $("#conteudo-impressao").html("");
    $("#barra-progresso").show();
    $("#retorno").hide();
    addImpressao();
});

$("#btn-imprimir").click(function() {
    $('.conteudo-impressao-temp').remove();
    var impressao_temp = $('<div class="conteudo-impressao-temp">');
    impressao_temp.html($('#conteudo-impressao').html());
    $(document.body).append(impressao_temp);
    window.print();
    $('.conteudo-impressao-temp').remove();
});
var indiceimpressao = 0;

function addImpressao() {
    var proximo = $(".tr-registro[data-addimpressao=false]").first();
    var id_extrato = proximo.data('id');
    var token_extrato = proximo.data("token");
    if (proximo.length > 0) {
        if (is_imprimeextrato == 'S') {
            addArquivoImpressao(id_extrato, token_extrato);
        } else {
            $(".tr-registro[data-id=" + id_extrato + "][data-token=" + token_extrato + "]").attr("data-addimpressao", true);
            addImpressao();
        }
    } else {
        abrirTelaImpressao();
    }
}

function abrirTelaImpressao() {
    verificarMarcaDAgua();
    $("#retorno").hide();
    $("#header-impressao,#conteudo-impressao").show("slow");
    $("#barra-progresso").hide("fast");
}

function verificarMarcaDAgua() {
    if ($("#btn-group-conferencia").length <= 0) {
        $(".marcadaqua").show();
        return false;
    }
    if (parseInt($("#btn-group-conferencia").attr("data-value")) == 0) {
        $(".marcadaqua").hide();
    } else {
        $(".marcadaqua").show();
    }
}

function addArquivoImpressao(id, token) {
    $.ajax({
        url: session.urlmiles,
        data: {
            controller: "contabil/extrato_imposto_renda",
            op: "gethtmlfileimpressao",
            id: id,
            token: token
        },
        complete: function(ret) {
            $("#conteudo-impressao").append(ret.responseText);
            $(".tr-registro[data-id=" + id + "][data-token=" + token + "]").attr("data-addimpressao", true);
            if (isindividual) {
                abrirTelaImpressao();
            } else {
                addImpressao();
            }
        }
    });
}
$("#fechar-janela-impressao").click(function() {
    $(".tr-registro").attr("data-addimpressao", false);
    $("#conteudo-impressao").html("");
    $("#retorno").show();
    $("#header-impressao,#conteudo-impressao").hide("slow");
});

function criarArquivos() {
    //var params = "anomes=" + $("#anomes").val() + "&proprietario=" + proprietarios[enviarindice].proprietario + "&titulo=" + proprietarios[enviarindice].titulo + "&pendente=" + proprietarios[enviarindice].pendente;
    //$("#icarregararquivo").attr("src","recibo.php?"+params);
}
$(".div-rodape").each(function() {
    $(this).css("top", "1520px");
})

function addAno() {
    $("#ano").append('<option value="2021" selected>2021</option>');
    let ano = document.multiselect('#ano');

    /*
    var now 		= new Date();
    var datafinal	= new Date(now.getFullYear(),now.getMonth(),1);
    var datainicial	= new Date(2019,1,1);
    var repetir 	= true;
    var monName 	= new Array ("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho","Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");

    while (repetir){
    	var mesfinal 	= datafinal.getMonth();
    	var anofinal 	= datafinal.getFullYear();
    	var mesnatural	= mesfinal + 1;
    	$("#referencia").append('<option value="'+(String(anofinal)+(mesnatural<10?"0":"")+String(mesnatural))+'">' + monName[mesfinal] + ' / '+anofinal+'</option>');
    	if (datafinal < datainicial){ //Parar contagem
    		repetir = false;
    	}else{
    		datafinal.setMonth(datafinal.getMonth() - 1);
    	}
    }
    */
}

$("#dataliberacaoatual").click(function() {
    let data_seletor = "#dataliberacao";
    if (this.checked) {
        let currentdate = new Date();
        $(data_seletor).val(currentdate.toLocaleDateString('pt-br'));
        $(data_seletor).attr("disable", true);
        $(data_seletor).attr("readonly", true);
    } else {
        $(data_seletor).val("");
        $(data_seletor).removeAttr('disable');
        $(data_seletor).removeAttr('readonly');
        setTimeout(function() { $(data_seletor).focus(); }, 100);
    }
});

function alterarEmailEnvioIndividual(event) {
    let email = $(event.target).val();
    let token = $(event.target).parents("tr").first().attr("data-token");

    $('.tr-registro[data-token="' + token + '"]').attr("data-email", email);
    $('.btn-enviar-email-individual[data-token="' + token + '"]').attr("data-email", email);
    $(event.target).parents("tr").first().attr("data-email", email);
}

function liberarBotoesCPD() {
    if (session.usergroup <= 3) {
        $('#imprimir').show();
        $('#enviarlote').show();
    } else {
        $('#imprimir').hide();
        $('#enviarlote').hide();
    }
}

function exibirContratos(contratos)
{
    let lista = '<ul class="list-group lista-contratos">';
    contratos.forEach(function(c) {
        lista += '<li class="list-group-item">'+c.contrato.numero+'</li>';
    });
    lista += '</ul>';

    bootbox.alert({ 
        size: "large",
        title: "Contratos",
        message: lista, 
        callback: function(){ /* your callback code */ }
    });
}