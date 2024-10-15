var _atributo = 0;
$(document).ready(function(){
    listarAtributos();
    $('#entidade-display').html(_entidade_display);
    $('#entidadetransferencia').load(session.urlmiles + '?controller=mdm/cadastro&op=listar-cadastro-option');
});

function listarAtributos(){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/cadastro',
            op:'listar-campos',
            entidade:_entidade
        },
        complete:function(res){
            $('#table-lista-atributo tbody').html(res.responseText);
        }
    });
}

function salvarOrdem(id,ordem){
    if (ordem!=""){
        $.ajax({
            url:session.urlmiles,
            data:{
                controller:'mdm/cadastro',
                op:'salvar-ordem',                
                id:id,
                ordem:ordem,
                entidade:_entidade
            }
        });
    }
}
var atributoTransfId = "";
var atributoTransfNome = "";
function abrirTransferencia(atributo,nomeatributo){
    atributoTransfId = atributo;
    atributoTransfNome = nomeatributo;
    $("#transferencia").modal({
        backdrop:false
    });
    $("#transferencia").modal("show");
}
function transferir(){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/cadastro',
            op:'transferir-campo',
            transferencia:" ",
            entidade:$("#entidadetransferencia").val(),
            atributo: atributoTransfId,
            nome:atributoTransfNome
        },
        success:function(retorno){
            if (retorno == 1 ){                
                $('#linha-registro-atributo-' + atributoTransfId).remove();
            }
            $("#transferencia").modal("hide");
        }
    });
}

function reordenarCampos(){
    bootbox.confirm({
        message:"Tem certeza que deseja reorganizar os campos ?",
        buttons:{
            confirm:{
                label:"Sim",
                className:"btn-success"
            },
            cancel:{
                label:"Não",
                className:"btn-danger"
            }
        },
        callback:function(result){
            if (result){
                $.ajax({
                    url:session.urlmiles,
                    data:{
                        controller:"mdm/reorganizarcampos",
                        _entidade:_entidade
                    },
                    complete:function(retorno){
                        listarAtributos();
                    },
                    beforeSend:function(){

                    }
                });
            }
        }
    });								
}

function editarCampo(_atributo_id){
    _atributo = _atributo_id;
    loadCamposContent('form');
}

function criarCampo(){
    _atributo = 0;
    loadCamposContent('form');
}

function excluirCampo(_atributo_id){
    bootbox.confirm({
        message:"Tem certeza que deseja excluir ?",
        buttons:{
            confirm:{
                label:"Sim",
                className:"btn-success"
            },
            cancel:{
                label:"Não",
                className:"btn-danger"
            }
        },
        callback:function(result){
            if (result){
                $.ajax({
                    url:session.urlmiles,
                    data:{
                        controller:'mdm/cadastro',
                        op:'excluir-campo',
                        _atributo:_atributo_id
                    },
                    complete:function(res){
                        $('#linha-registro-atributo-' + _atributo_id).remove();
                    }
                }); 
            }
        }
    });
}