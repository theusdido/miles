var notas_enviar = [];

$("#load-pesquisar").attr("src",session.urlloading2);
$("#pesquisar").click( ()=> {    
    pesquisar();
});

function pesquisar(){
    $.ajax({
        url:session.urlmiles + "?page=nfse/consultar",
        data: {
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
                    
                    tr.append(tdNumero);
                    tr.append(tdSerie);
                    tr.append(tdTipo);
                    tr.append(tdTomador);
                    tr.append(tdStatus);
                    
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
            /*
            var r = JSON.parse(retorno.responseText);
            total = r.length;
            notasSelecionadas = r;
            if (total > 0) {
                consultar();
            }else{
                progressbar(100);
                $("#retorno").html('<div class="alert alert-danger" role="alert">Nenhuma nota encontrada com esses parâmetros.</div>');
                $("#btn_enviar_pendentes").hide();
            }
            */
        }
    });
}

$("#data").mask("99/99/9999");

function enviar(indice = 0){
    $.ajax({
        url:session.urlmiles + "?page=nfse/enviar",
        data:{
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
