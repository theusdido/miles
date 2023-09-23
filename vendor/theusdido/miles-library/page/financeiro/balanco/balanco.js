$(document).ready(function(){
    loadBalanco();
    addMesesReferencia();
});

function loadBalanco(){
    $.ajax({
        url:session.urlmiles,
        data:{
            op:'all',
            controller:'erp/financeiro/balanco',
            referencia:$('#referencia').val()
        },
        dataType:"json",
        complete:function(res){
            setDados( res.responseJSON );
            unloader('#loader-balanco');
            $('#table-balanco').show();
        },
        beforeSend:function(){
            loader('#loader-balanco');
            $('#table-balanco').hide();
        }
    });
}

function setDados(dados){
    let total_despesa = 0;
    let total_receita = 0;
    $('#table-balanco tbody').html('');
    $('#table-balanco tfoot').hide();
    if (dados.length <= 0){
        setNenhumRegistroTable('#table-balanco',5);
        return;
    }

    $('#table-balanco tfoot').show();
    dados.forEach(function(d){
        let tipo;
        if (d.operacao == 1){
            tipo = 'Despesa';
            total_despesa += parseFloat(d.valor);
        }else{
            tipo = 'Receita';
            total_receita += parseFloat(d.valor);
        }

        let tr              = $('<tr>');
        let td_id           = $('<td class="text-center">');
        let td_data         = $('<td class="text-left">');
        let td_descricao    = $('<td class="text-left">');
        let td_valor        = $('<td class="text-right">');
        let td_operacao     = $('<td class="text-center cell-'+tipo.toLowerCase()+'">');

        td_id.html(d.id);
        td_data.html(d.datalancamento_formated);
        td_descricao.html(d.descricao);
        td_valor.html('<strong>R$ ' + d.valor_formated + '</strong>');
        td_operacao.html(tipo);
        
        //tr.append(td_id);
        tr.append(td_data);
        tr.append(td_descricao);
        tr.append(td_valor);
        tr.append(td_operacao);

        $('#table-balanco tbody').append(tr);
    });

    let saldo = total_receita - total_despesa;
    $('#total_despesa').html(total_despesa.toLocaleString("pt-BR", { style: 'currency', currency: 'BRL' }));
    $('#total_receita').html(total_receita.toLocaleString("pt-BR", { style: 'currency', currency: 'BRL' }));
    $('#saldo').html(saldo.toLocaleString("pt-BR", { style: 'currency', currency: 'BRL' }));
}


function addMesesReferencia(){
	var now 		= new Date();
	var datafinal	= new Date(now.getFullYear(),now.getMonth(),1);
	var datainicial	= new Date(2022,10,1);
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
}

$('#btn-pesquisa-balanco').click(function(){
    loadBalanco();
});