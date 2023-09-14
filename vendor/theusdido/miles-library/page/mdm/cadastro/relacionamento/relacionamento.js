var _id_relacionamento = 0;
var _entidadecarregar = 0;
$(document).ready(function(){
    $('#entidadefilho').load(session.urlmiles + '?controller=mdm/cadastro/relacionamento&op=entidade-option&entidade=' + _entidade);
    listarRelacionamento();
    if (_id_relacionamento != 0){
        load();
    }else{
        disableAtributo($("#tipo").val());
    }
});
function load(){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'mdm/cadastro/relacionamento',
            id:_id_relacionamento,
            op:'load'
        },
        complete:function(_res){
            let _data = _res.responseJSON;
            console.log(_data);
            $("#tipo").val(_data.tipo);
            $("#descricao").val(_data.descricao);
            $("#entidadefilho").val(_data.filho);
            disableAtributo();
            carregarAtributos();
            listarRelacionamento();
        }
    });
}			
function disableAtributo(){
    let tipo = parseInt($('#tipo').val());
    if (tipo == 5 || tipo == 3 || tipo == 10 || tipo == 1){
        $("#atributo").val(0);
        $("#atributo").attr("disabled",true);
        entidadecarregar = 0;
    }else{					
        $("#atributo").attr("disabled",false);
        if (tipo == 6 || tipo == 2 || tipo == 8 || tipo == 9){
            entidadecarregar = $("#entidadefilho").val();
        }else{
            entidadecarregar = $("#entidade").val();
        }
    }

    if (isEmpty(entidadecarregar)){
        $("#atributo").attr("disabled",true);
    }
}

function carregarAtributos(){
    $("#atributo").load(session.urlmiles + '?controller=mdm/cadastro/relacionamento&op=atributo-option&entidade=' + $('#entidadefilho').val());
}

function isComposicao(){
    if ($("#tipo").val() == 2 || $("#tipo").val() == 7){
        return true;
    }else{
        return false;
    }
}

function validar(){

    if (isEmpty($('#descricao').val())){
        addMensagemErro('<b>Descrição</b> é obrigatório.');
        return false;
    }
    if ( isComposicao() && isEmpty($("#atributo").val()) ){
        addMensagemErro('<b>Atributo</b> é obrigatório para relacionamento do tipo <b>Composição</b>.');
        return false;
    }else{
        return true;
    }
}

function isEmpty(valor){
    switch(valor){
        case 0:
        case '0':
        case '':
        case undefined:
        case null:
            return true;
        break;
        default:
            return false;
    }
}

function addMensagemErro(msg){
    $('#error').addClass('alert-danger');
    $('#error').html(msg);
}

$("#entidadefilho").change(function(){
    disableAtributo();
    carregarAtributos();
});

$("#tipo").change(function(){
    disableAtributo();
});

$('#btn-salvar-relacionamento').click(function(){
    $.ajax({
        url:session.urlmiles,
        type:"POST",
        dataType:'json',
        data:{
            // Parametros
            controller:'mdm/cadastro/relacionamento',
            entidade:_entidade,
            op:'salvar',

            // Campos
            id:_id_relacionamento,
            entidade:_entidade,
            tipo:$('#tipo').val(),
            entidadefilho:$('#entidadefilho').val(),
            descricao:$('#descricao').val()
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

function editarRelacionamento(relacionamento_id){
    _id_relacionamento = relacionamento_id;
    load();
}

function listarRelacionamento(){
    $('#listar-relacionamentos').load(session.urlmiles + '?controller=mdm/cadastro/relacionamento&op=listar-relacionamento&entidade=' + _entidade);
}

function excluirRelacionamento(relacionamento_id){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{

            // Parametros
            controller:'mdm/cadastro/relacionamento',
            id:relacionamento_id,
            op:'excluir'
       },
        complete:function(_res){
            listarRelacionamento();
        }
    });      
}