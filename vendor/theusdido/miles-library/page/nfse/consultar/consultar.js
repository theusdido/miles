$(function(){
    var rps_inicial = 0;
    var rps_final = 0;
    var rps_atual = 0;

    $("#load-pesquisar").attr("src",session.urlloading2);
    $("#pesquisar").click( ()=> {
        rps_atual = parseInt($('#rpsinicial').val());
        rps_final = parseInt($('#rpsfinal').val());
        iniciarPesquisa();
        pesquisar();
    });

    function pesquisar(_rpsnumero){
        $.ajax({
            url:session.urlmiles,
            data: {
                controller:'nfse/consultar',
                op:"betha",
                rpsnumero: rps_atual
            },
            beforeSend:function(){

            },
            complete: function( ret ) {
                rps_atual++;

                // const _res = JSON.parse(data);

                // const link_enviar = `http://localhost/nfse/enviar.php?rpsnumero=${rpsnumero}`;
                // const reenviar_link = `<a href="${link_enviar}" target="_blank">Reenviar</a>`;

                // // Cria uma nova linha na tabela para exibir o resultado
                // const table = document.getElementById('resultTable');
                // const row = table.insertRow();
                // const cell1 = row.insertCell(0);
                // const cell2 = row.insertCell(1);
                // const cell3 = row.insertCell(2);
                // const cell4 = row.insertCell(3);
                
                // cell1.innerHTML = i++;
                // cell2.textContent = rpsnumero;
                // cell3.innerHTML = _res.is_exist_nfse ? 'OK!' : reenviar_link;
                // cell4.textContent = _res.message_error;

                const _res = JSON.parse(ret.responseText);
                
                //if (_res.status == 'success'){

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
                // }else{
                //     notas_enviar    = [];
                //     var tr          = $("<tr>");
                //     var td          = $('<td colspan="5" class="bg-warning text-center">Nenhuma Nota Encontrada</td>');
                //     tr.append(td);
                //     $("#tconsulta tbody").append(tr);
                // }

                if (rps_atual <= rps_final){
                    pesquisar();
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