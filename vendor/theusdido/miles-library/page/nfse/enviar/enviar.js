var notas_enviar = [];

$("#load-pesquisar").attr("src",session.urlloading2);
$("#pesquisar").click( ()=> {    
    pesquisar();
});

function pesquisar(){
    $.ajax({
        url:session.urlmiles,
        data: {
            controller:'nfse/consultar',
            op:"consultar",
            rps: $("#rps").val(),
            data: $("#data").val(),
            status:$("#status").val()
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
                    var tr          = $("<tr>");
                    var tdNumero    = $('<td class="text-center">'+nota.rpsnumero+'</td>');
                    var tdSerie     = $('<td class="text-center">'+nota.rpsserie+'</td>');
                    var tdTipo      = $('<td class="text-center">'+nota.rpstipo+'</td>');
                    var tdTomador   = $('<td>'+nota.tomador+'</td>');
                    var tdStatus    = $('<td class="text-center">'+nota.status+'</td>');
                    let td_excluir  = $('<td align="center">');

                    let btn_excluir = $('<button class="btn btn-danger"><i class="fa fa-trash"></i></button>');
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
                                console.log('This was logged in the callback: ' + result);
                            }
                        });
                    });
                    td_excluir.append(btn_excluir);

                    tr.append(tdNumero);
                    tr.append(tdSerie);
                    tr.append(tdTipo);
                    tr.append(tdTomador);
                    tr.append(tdStatus);
                    tr.append(td_excluir);
                    
                    $("#tconsulta tbody").append(tr);                
                }
            }else{
                notas_enviar    = [];
                var tr          = $("<tr>");
                var td          = $('<td colspan="6" class="bg-warning text-center">Nenhuma Nota Encontrada</td>');
                tr.append(td);
                $("#tconsulta tbody").append(tr);
            }
            $("#load-pesquisar").hide();
            $('.after-search').show();
        }
    });
}

$("#data").mask("99/99/9999");

function enviar(indice = 0){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'nfse/enviar',
            op:'enviar',
            nota:notas_enviar[indice]
        },
        complete:function(ret){

        }
    });
}

$("#btn-enviar").click( () => {
    enviar(0);
});