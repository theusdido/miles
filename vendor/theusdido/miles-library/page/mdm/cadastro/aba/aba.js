var aba_id = 0;
$(document).ready(function(){
    $('#atributos').load(session.urlmiles + '?controller=mdm/cadastro/aba&op=lista-atributos-aba&entidade=' + _entidade);
    $('#lista-atributos-aba').load(session.urlmiles + '?controller=mdm/cadastro/aba&op=listar-aba&entidade=' + _entidade);
});
function load(){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'mdm/cadastro/aba',
            id:aba_id,
            op:'load'
        },
        complete:function(_res){
            let _data = _res.responseJSON;
            $('#descricao').val(_data.descricao);
            $('#atributos').val(_data.atributos.split(','));
        }
    });
}

$('#btn-salvar-aba').click(function(){
    $.ajax({
        url:session.urlmiles,
        type:"POST",
        dataType:'json',
        data:{

            // Parametros
            controller:'mdm/cadastro/aba',
            entidade:_entidade,
            op:'salvar',

            // Campos
            id:aba_id,
            descricao:$('#descricao').val(),
            atributos:$('#atributos').val(),
       },
        complete:function(_res){
            unLoaderSalvar();
            mdmToastMessage("Salvo com Sucesso");
        },
        beforeSend:function(){
            addLoaderSalvar();
        }
    });  
});


$(".btn-link-excluir-aba").click(function(e){
    e.stopPropagation();
    e.preventDefault();
});
function excluirAba(id_aba_excluir,entidade){
    $.ajax({
        url:session.urlmiles,
        controller:'mdm/cadastro/aba',
        dataType:'json',
        data:{
            controller:'mdm/cadastro/aba',
            id:id_aba_excluir,
            op:'excluir'
        },
        complete:function(_res){
            $('#list-item-aba-' + id_aba_excluir).remove();
        }
    });
}
function editarAba(entidade,_aba_id_edicao,atributos){
    aba_id = _aba_id_edicao
    load();
}