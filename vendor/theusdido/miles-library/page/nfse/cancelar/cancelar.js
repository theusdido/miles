$(function(){
    var nota_final = 0;
    var nota_atual = 0;

    $("#load-pesquisar").attr("src",session.urlloading2);
    $("#pesquisar").click( ()=> {
        nota_atual = parseInt($('#rpsinicial').val());
        nota_final = parseInt($('#rpsfinal').val());
        iniciarPesquisa();
        cancelar();
    });

    function cancelar(_rpsnumero){
        $.ajax({
            url:session.urlmiles,
            data: {
                controller:'nfse/cancelar',
                op:"betha",
                nfsenumero: nota_atual,
                codigocancelamento:1
            },
            beforeSend:function(){

            },
            complete: function( ret ) {
                nota_atual++;

                const _res = JSON.parse(ret.responseText);            

                var nota        = _res.data;
                var tr          = $("<tr data-id='"+nota.nfsenumero+"'>");
                var tdNumero    = $('<td>'+nota.rpsnumero+'</td>');
                var tdSerie     = $('<td class="text-center">'+nota.rpsserie+'</td>');
                var tdTipo      = $('<td class="text-center">'+nota.rpstipo+'</td>');
                var tdTomador   = $('<td>'+nota.tomador+'</td>');
                var tdStatus    = $('<td class="text-center"></td>');

                tdStatus.append(getBadgeStatus(nota.situacao));

                tr.append(tdNumero);
                tr.append(tdSerie);
                tr.append(tdTipo);
                tr.append(tdTomador);
                tr.append(tdStatus);
                
                $("#tconsulta tbody").append(tr);

                if (nota_atual <= nota_final){
                    cancelar();
                }else{
                    finalizarPesquisa();
                }              
            }
        });
    }
});

function iniciarPesquisa(){
    $("#tconsulta tbody").html('');
    $("#load-pesquisar").show();
    $('.after-search').show();    
}

function finalizarPesquisa(){
    $("#load-pesquisar").hide();
}

function getBadgeStatus(status){
    switch(status){
        case 'G':
            badge_status = 'success';
            badge_text = 'RPS com Nota Gerada!';
        break;
        case 'C':
            badge_status = 'warning';
            badge_text = 'RPS com Nota Cancelada!';
        break;
        case 'N':
            badge_status = 'danger';
            badge_text = 'RPS Não Enviado.';
        break;
    }

    return $(`<span class="badge text-bg-${badge_status}">${badge_text}</span>`);
}