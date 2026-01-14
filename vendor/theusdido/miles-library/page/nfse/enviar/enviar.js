$(function(){
    var notas_enviar = [];
    var total_notas_encontradas = 0;
    var total_notas_enviadas = 0;

    setToolTip();

    $("#load-pesquisar").attr("src",session.urlloading2);
    $("#pesquisar").click( ()=> {    
        pesquisar();
    });

    function pesquisar(){
        $.ajax({
            url:session.urlmiles,
            data: {
                controller:'nfse/consultar',
                op:"rps",
                rps: $("#rps").val(),
                data: $("#data").val(),
                situacao:$("#situacao").val()
            },
            beforeSend:function(){
                $("#tconsulta tbody").html('');
                $("#load-pesquisar").show();
                $('.after-search').hide();
            },
            complete: function( ret ) {
                notas_enviar = JSON.parse(ret.responseText);

                if (notas_enviar.length > 0){
                    for (r in notas_enviar ){

                        var nota        = notas_enviar[r];
                        var tr          = $("<tr data-id='"+nota.id+"'>");
                        var tdNumero    = $(`<td>${nota.rpsnumero} / ${nota.rpsserie} / ${nota.rpstipo}</td>`);
                        var tdDtEmissao = $('<td class="text-center">'+nota.dataemissao+'</td>');
                        var tdTomador   = $('<td>'+nota.tomador+'</td>');
                        var tdStatus    = $('<td class="text-center">'+nota.situacao+'</td>');
                        var tdRetorno   = $('<td class="msg-retorno"></td>');
                        let td_enviar   = $('<td align="center">');
                        let td_excluir  = $('<td align="center">');

                        let btn_enviar = $('<button class="btn btn-secondary btn-sm"><i class="fa fa-paper-plane"></i></button>');
                        btn_enviar.click(function(){
                            enviar(r);
                        });
                        td_enviar.append(btn_enviar);


                        let btn_excluir = $('<button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>');
                        btn_excluir.click(function(){
                            bootbox.confirm({
                                message: 'Tem certeza que deseja excluir?',
                                buttons: {
                                    confirm: {
                                        label: 'Yes',
                                        className: 'btn-success'
                                    },
                                    cancel: {
                                        label: 'No',
                                        className: 'btn-danger'
                                    }
                                },
                                callback: function (result) {
                                    if (result) {
                                        $.ajax({
                                            url: session.urlmiles,
                                            data: {
                                                controller: 'nfse/excluir',
                                                nota: nota.id
                                            },
                                            complete: function (ret) {
                                                const response = JSON.parse(ret.responseText);
                                                if (response.status == 'success') {
                                                    notas_enviar.splice(notas_enviar.indexOf(nota), 1);
                                                    $("#tconsulta tbody tr[data-id='" + nota.id + "']").remove();
                                                }else{
                                                    alert('Erro ao excluir a nota: ' + response.message);
                                                }
                                                tr.find('.msg-retorno').html('');
                                                tr.find('.msg-retorno').html(response.message || 'Nota excluída com sucesso!');
                                            }
                                        });
                                    }
                                }
                            });
                        });
                        td_excluir.append(btn_excluir);

                        tr.append(tdNumero);
                        tr.append(tdDtEmissao);
                        tr.append(tdTomador);
                        tr.append(tdStatus);
                        tr.append(tdRetorno);
                        //tr.append(td_enviar);
                        tr.append(td_excluir);
                        
                        $("#tconsulta tbody").append(tr);                
                    }
                }else{
                    notas_enviar    = [];
                    var tr          = $("<tr>");
                    var td          = $('<td colspan="8" class="bg-warning text-center">Nenhuma Nota Encontrada</td>');
                    tr.append(td);
                    $("#tconsulta tbody").append(tr);
                }

                total_notas_encontradas = notas_enviar.length;
                $("#load-pesquisar").hide();
                $('.after-search').show();
            }
        });
    }

    $("#data").mask("99/99/9999");

    function enviar(indice = 0){
        setTimeout(function(){
            $.ajax({
                url:session.urlmiles,
                data:{
                    controller:'nfse/enviar',
                    op:'enviar',
                    nota:notas_enviar[indice]
                },
                complete:function(ret){        

                    total_notas_enviadas++;
                    const response = JSON.parse(ret.responseText);
                    let badge_status = '';
                    let badge_text = '';
                    let mensagem = '';
                    if (response.status === 'success'){
                        badge_status = 'success';
                        badge_text = 'Enviado com Sucesso!';
                    }else{
                        badge_status = 'danger';
                        badge_text = 'Erro: ' + response.message.substr(0, 15) + ' ...';
                        mensagem = response.message.replace('\"',"'");
                    }

                    let badge = $(`<span class="badge text-bg-${badge_status}" data-bs-toggle="tooltip" data-bs-title="${mensagem}">${badge_text}</span>`);
                    const tr = $("#tconsulta tbody tr[data-id='" + notas_enviar[indice].id + "']");
                    tr.find('.msg-retorno').html('');
                    tr.find('.msg-retorno').append(badge);
                    if (total_notas_enviadas < total_notas_encontradas){
                        enviar(total_notas_enviadas);
                    }else{
                        setToolTip();
                        alert('Envio Encerrado!');
                    }

                    setToolTip();

                    badge.click(function(){
                        bootbox.alert($(this).data('bs-title'));
                    });
                }
            });
        },1000);
    }

    $("#btn-enviar").click( () => {
        total_notas_enviadas = 0;
        enviar(total_notas_enviadas);
    });

    function setToolTip(){
        // Habilita o Tooltip do Bootstrap 5
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    }
});