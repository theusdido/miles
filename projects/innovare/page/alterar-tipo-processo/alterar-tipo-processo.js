$(document).ready(function () {
    load();    
    $('#btn-alterar').click(function(){
        alterar();
    });
});
function load(){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'selectprocessooption'
        },
        complete:function(_res){
            $('#processo').html(_res.responseText);
            setSumoSelect();
        }
    });
}

function alterar(){    
    let contexto_loader = '#form-alterar-tipo-processo';
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'alterartipoprocesso',
            processo:$('#processo').val(),
            tipoprocessodestino:$('#tipo').val()
        },
        complete:function(_res){
            if (_res.responseText == 1){
                toastMessage("Alterado com Sucesso!");
            }else{
                toastMessage(_res.responseText,'td-message-warning');                
            }
            unLoaderSalvar(contexto_loader);
        },
        beforeSend:function(){
            addLoaderSalvar(contexto_loader);
        }
    });
}

function setSumoSelect(){
    $('#processo').SumoSelect({
        search:true,
        placeholder: 'Selecione o Processo',
        searchText:'Pesquisar'
    });
}