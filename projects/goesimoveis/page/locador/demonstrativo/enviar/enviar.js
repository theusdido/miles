$(function(){
    var demonstrativo_enviar = [];
    var total_demonstrativo_encontradas = 0;
    var total_demonstrativo_enviadas = 0;
    var enviar_todos = false;

    $("#load-pesquisar").attr("src",session.urlloading2);
    $("#pesquisar").click( ()=> {    
        pesquisar();
    });

    function pesquisar(){
        $.ajax({
            url:session.urlmiles,
            data: {
                controller:'locador/demonstrativo/consultar',
                proprietario: $("#proprietario").val(),
                data: $("#data").val(),
                situacao:$("#situacao").val()
            },
            beforeSend:function(){
                $("#tconsulta tbody").html('');
                $("#load-pesquisar").show();
                $('.after-search').hide();
            },
            complete: function( ret ) {
                demonstrativo_enviar = JSON.parse(ret.responseText);

                if (demonstrativo_enviar.length > 0){
                    for (r in demonstrativo_enviar ){

                        var demonstrativo   = demonstrativo_enviar[r];
                        var tr              = $("<tr data-id='"+demonstrativo.id+"'>");
                        var tdDtEmissao     = $('<td class="text-center">'+demonstrativo.dataemissao+'</td>');
                        var tdLocador       = $('<td>'+demonstrativo.locador+'</td>');
                        var tdStatus        = $('<td class="col-status">'+demonstrativo.enviada+'</td>');
                        var tdRetorno       = $('<td class="msg-retorno"></td>');
                        let td_visualizar   = $('<td align="center">');
                        let td_enviar       = $('<td align="center">');
                        let td_excluir      = $('<td align="center">');

                        let link = demonstrativo_enviar[r].link;
                        let btn_visualizar = $('<button class="btn btn-secondary btn-sm"><i class="fa fa-eye"></i></button>');
                        btn_visualizar.click(function(){
                            visualizar(link);
                        });
                        td_visualizar.append(btn_visualizar);

                        let btn_enviar = $('<button class="btn btn-secondary linha-btn-enviar btn-sm" data-indice="'+r+'"><i class="fa fa-paper-plane"></i></button>');
                        
                        btn_enviar.click(function() {
                            var indice = $(this).data('indice');
                            bootbox.confirm({
                                title: "Confirmação de Envio",
                                message: "Tem certeza que deseja enviar este demonstrativo individualmente?",
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
                                callback: function(result){
                                    if (result){
                                        enviar_todos = false;
                                        enviar(indice);
                                    }
                                }
                            });
                        });
                        td_enviar.append(btn_enviar);


                        let btn_excluir = $('<button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>');
                        btn_excluir.click(function(){
                            bootbox.confirm({
                                title: "Confirmação de Exclusão",
                                message: "Tem certeza que deseja excluir este demonstrativo? Esta ação é irreversível.",
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
                                    if (result) {
                                        $.ajax({
                                            url: session.urlmiles,
                                            data: {
                                                controller: 'nfse/excluir',
                                                demonstrativo: demonstrativo.id
                                            },
                                            complete: function (ret) {
                                                const response = JSON.parse(ret.responseText);
                                                if (response.status == 'success') {
                                                    demonstrativo_enviar.splice(demonstrativo_enviar.indexOf(demonstrativo), 1);
                                                    $("#tconsulta tbody tr[data-id='" + demonstrativo.id + "']").remove();
                                                }else{
                                                    alert('Erro ao excluir a demonstrativo: ' + response.message);
                                                }
                                                tr.find('.msg-retorno').html('');
                                                tr.find('.msg-retorno').html(response.message || 'Demonstrativo excluída com sucesso!');
                                            }
                                        });
                                    }
                                }
                            });
                        });
                        td_excluir.append(btn_excluir);

                        tr.append(tdDtEmissao);
                        tr.append(tdLocador);
                        tr.append(tdStatus);
                        tr.append(tdRetorno);
                        tr.append(td_visualizar);
                        tr.append(td_enviar);
                        tr.append(td_excluir);
                        
                        $("#tconsulta tbody").append(tr);                
                    }
                }else{
                    demonstrativo_enviar    = [];
                    var tr          = $("<tr>");
                    var td          = $('<td colspan="8" class="bg-warning text-center">Nenhum Demonstrativo Encontrada</td>');
                    tr.append(td);
                    $("#tconsulta tbody").append(tr);
                }

                total_demonstrativo_encontradas = demonstrativo_enviar.length;
                $("#load-pesquisar").hide();
                $('.after-search').show();
            }
        });
    }

    $("#data").mask("99/99/9999");

        function enviar(indice = 0, callback){    
            $.ajax({
                url:session.urlmiles,
                data:{
                    controller:'locador/demonstrativo/enviar',
                    demonstrativo:demonstrativo_enviar[indice]
                },
                complete:function(ret){
    
                    const response = JSON.parse(ret.responseText);
                    let badge_status = response.status;
                    let badge_text = response.message;
                    let badge_text_part = badge_text.substr(0, 15) + (badge_status == 'danger' ? '...' : '');
                    let badge = $(`<span class="badge text-bg-${badge_status}" data-bs-toggle="tooltip" data-bs-title="${badge_text}">${badge_text_part}</span>`);
    
                    const tr = $("#tconsulta tbody tr[data-id='" + demonstrativo_enviar[indice].id + "']");
                    tr.find('.msg-retorno').html('');
                    tr.find('.msg-retorno').append(badge);
                    tr.find('.col-status').html(badge_status == 'success' ? 'Enviada' : 'Não Enviada');
    
                    // Habilita o Tooltip do Bootstrap 5
                    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    
                    if (callback){
                        callback();
                    }
                }
            });
        }
    function visualizar(link){
        window.open('http://teiasrv/miles/demonstrativo/' + link);
    }

    $("#btn-enviar").click( () => {
        enviarTodos();
    });

    function enviarTodos(){

        bootbox.confirm({
            title: "Enviar Todos os Demonstrativos",
            message: "Tem certeza que deseja enviar todos os demonstrativos pendentes? Este processo será sequencial.",
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
            callback: function(result){
                if (result){
                    var linhasParaEnviar = $("#tconsulta tbody tr").filter(function() {
                        return $(this).find(".col-status").text().trim() !== 'Enviada';
                    });

                    if (linhasParaEnviar.length === 0) {
                        bootbox.alert("Todos os demonstrativos já foram enviados.");
                        return;
                    }

                    var indiceAtual = 0;

                    function processarProximo() {
                        if (indiceAtual >= linhasParaEnviar.length) {
                            bootbox.alert("Envio de todos os demonstrativos concluído com sucesso!");
                            return;
                        }

                        var linha = $(linhasParaEnviar[indiceAtual]);
                        var indiceDemonstrativo = linha.find("button.btn-secondary").data('indice');

                        enviar(indiceDemonstrativo, function() {
                            indiceAtual++;
                            processarProximo();
                        });
                    }

                    processarProximo();
                }
            }
        });
    }
});