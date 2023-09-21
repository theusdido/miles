$(document).ready(function(){
    loadCaixa();
});

function loadCaixa(){
    $.ajax({
        url:session.urlmiles,
        data:{
            op:'all',
            controller:'erp/financeiro/caixa'
        },
        dataType:"json",
        complete:function(res){
            setDados( res.responseJSON );
        }
    });
}

function setDados(dados){
    dados.forEach(function(d){

        let tr = $('<tr>');
        let td_descricao    = $('<td class="text-left">');
        let td_valor        = $('<td class="text-right">');
        let td_operacao     = $('<td class="text-center">');
        let td_data         = $('<td class="text-center">');

        td_descricao.html(d.descricao);
        td_valor.html(d.valor_formated);
        td_operacao.html(d.operacao == 1 ? 'Saída' : 'Entrada');
        td_data.html(d.datalancamento_formated);

        tr.append(td_descricao);
        tr.append(td_valor);
        tr.append(td_operacao);
        tr.append(td_data);

        $('#table-fluxo-caixa tbody').append(tr);
    });
}