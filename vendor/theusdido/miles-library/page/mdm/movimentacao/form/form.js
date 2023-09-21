$("#accordion_alterar,#accordion_status,#accordion_historico,#panel-colunas").hide();

$(document).ready(function(){
    $('#entidade,#motivo').load(session.urlmiles + '?controller=mdm/movimentacao&op=listar-entidade-option');
    if (_movimentacao != 0){
        load();
    }
});

$('#btn-salvar-movimentacao').click(function(){    
    $.ajax({
        type:"POST",
        url:session.urlmiles,
        dataType:"json",
        data:{
            controller:'mdm/movimentacao',
            op:"salvar",
            id:_movimentacao,
            entidade: $("#entidade").val(),
            descricao: $("#descricao").val(),
            displaybutton:$('#displaybutton').val(),
            motivo:$("#motivo").val(),
            exigirobrigatorio:$('#exigirobrigatorio').prop('checked'),
            exibirtitulo:$('#exibirtitulo').prop('checked'),
            exibirvaloresantigos:$('#exibirvaloresantigos').prop('checked')
        },
        complete:function(_res){
            atualizarListaAlterar();
            $("#modalCadastroAlterar").modal('hide');
            let _retorno = _res.responseJSON;
            if (_retorno.status == 'success'){
                unLoaderSalvar();
                mdmToastMessage("Salvo com Sucesso");
                _movimentacao = _retorno.id;
                load();
            }            
        },
        beforeSend:function(){
            addLoaderSalvar();
        }        
    });
});

function load(){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'mdm/movimentacao',
            id:_movimentacao,
            op:'load'
        },
        complete:function(_res){
            let _data = _res.responseJSON;

            // Campos Inputs
            $("#descricao").val(_data.descricao);
            $('#displaybutton').val(_data.displaybutton);
            $("#entidade").val(_data.entidade);
            $("#motivo").val(_data.motivo);

            // Campos Checkbox
            $('#exigirobrigatorio').attr('checked',_data.exigirobrigatorio == 0 ? false : true);
            $('#exibirtitulo').attr('checked',_data.exibirtitulo == 0 ? false : true);
            $('#exibirvaloresantigos').attr('checked',_data.exibirvaloresantigos == 0 ? false : true);

            $("#accordion_alterar,#accordion_status,#accordion_historico,#panel-colunas").show();
            $('select[id="atributo"]').load(session.urlmiles + '?controller=mdm/movimentacao&_entidade=' + _data.entidade + '&op=listar-atributos');

            atualizarListaAlterar();
            atualizarListaStatus();
            atualizarListaHistorico();
        }
    });
}

function validar(){
    if ($("#entidade").val() == "" || $("#entidade").val() == null){
        alert('Entidade não pode ser vazio');
        return false;
    }
    return true;
}
function editarAlterar(id){
    $("#form-alterar #movimentacao").val(id);
    $("#form-alterar #atributo").val($("#lista-alterar #atributo-editar-" + id).data("atributo"));
    $("#form-alterar #legenda").val($("#lista-alterar #atributo-editar-" + id).data("legenda"));
    $("#form-alterar #idalterar").val($("#lista-alterar #atributo-editar-" + id).data("idalterar"));
    $("#modalCadastroAlterar").modal('show');
}
function editarStatus(id){
    carregarValoresAtributo($("#form-status #atributo"),id);
    setTimeout(function(){
        $("#form-status #movimentacao").val(id);
        $("#form-status #atributo").val($("#lista-status #atributo-editar-" + id).data("atributo"));
        $("#form-status #operador").val($("#lista-status #atributo-editar-" + id).data("operador"));
        $("#form-status #valor").val($("#lista-status #atributo-editar-" + id).data("valor"));
        $("#form-status #idstatus").val($("#lista-status #atributo-editar-" + id).data("idstatus"));
        $("#modalCadastroStatus").modal('show');					
    },500);
}
function editarHistorico(id){
    $("#form-historico #entidade").val($("#lista-historico #atributo-editar-" + id).data("entidade"));
    carregarAtributosHistorico();
    setTimeout(function(){
        $("#form-historico #movimentacao").val(id);
        $("#form-historico #atributo").val($("#lista-historico #atributo-editar-" + id).data("atributo"));
        $("#form-historico #legenda").val($("#lista-historico #atributo-editar-" + id).data("legenda"));
        $("#form-historico #idstatus").val($("#lista-historico #atributo-editar-" + id).data("idstatus"));
        $("#modalCadastroHistorico").modal('show');
    },500);
}
$(document).ready(function(){
    $("#salvarAlterar").click(function(){
        $.ajax({
            type:"POST",
            url:session.urlmiles,
            data:{
                controller:'mdm/movimentacao',
                op:"salvaralterar",
                atributo: $("#form-alterar #atributo").val(),
                movimentacao:_movimentacao,
                id:$("#form-alterar #idalterar").val(),
                legenda:$("#form-alterar #legenda").val()
            },
            complete:function(r){
                atualizarListaAlterar();
                $("#modalCadastroAlterar").modal('hide');
            }
        });
    });
    $("#salvarStatus").click(function(){
        $.ajax({
            type:"POST",
            url:session.urlmiles,
            data:{
                controller:'mdm/movimentacao',
                op:"salvarstatus",
                operador: $("#form-status #operador").val(),
                valor: $("#form-status #valor").val(),
                atributo: $("#form-status #atributo").val(),
                movimentacao:_movimentacao,
                id:$("#form-status #idstatus").val()
            },
            complete:function(r){
                atualizarListaStatus();
                $("#modalCadastroStatus").modal('hide');
            }
        });
    });
    $("#salvarHistorico").click(function(){
        $.ajax({
            type:"POST",
            url:session.urlmiles,
            data:{
                controller:'mdm/movimentacao',
                op:"salvarhistorico",
                legenda: $("#form-historico #legenda").val(),
                atributo: $("#form-historico #atributo").val(),
                movimentacao:_movimentacao,
                id:$("#form-historico #idhistorico").val()
            },
            complete:function(r){
                atualizarListaHistorico();
                $("#modalCadastroHistorico").modal('hide');
            }
        });
    });
    $("#form-status #atributo").change(function(){
        carregarValoresAtributo($(this));
    });
    carregarValoresAtributo($("#form-status #atributo"));
    $("#form-historico #entidade").change(function(){
        console.log("TESTE");
        carregarAtributosHistorico();
    });				
});
function novoAlterar(){
    $("#modalCadastroAlterar").modal({
        backdrop:false
    });
    $("#modalCadastroAlterar").modal('show');
    $("#form-alterar #movimentacao,#form-alterar #idalterar,#form-alterar #legenda").val("");
    $("#form-alterar #atributo").val($("#form-alterar #atributo option:first").val());
}
function novoStatus(){
    $("#modalCadastroStatus").modal({
        backdrop:false
    });
    $("#modalCadastroStatus").modal('show');				
    $("#form-status #movim,#form-status #valor,#form-status #idstatus").val("");
    $("#form-status #operador").val("=");
    $("#form-status #atributo").val($("#form-status #atributo option:first").val());
    $("#form-status #status").val($("#form-status #status option:first").val());
}
function novoHistorico(){
    $("#form-historico #entidade").val($("#form-historico #entidade option:first").val());
    carregarAtributosHistorico();
    $("#modalCadastroHistorico").modal({
        backdrop:false
    });
    $("#form-historico #legenda,#form-historico #idhistorico").val("");
    $("#form-historico #atributo").val($("#form-historico #atributo option:first").val());
    $("#modalCadastroHistorico").modal('show');
}
function atualizarListaAlterar(){
    $("#lista-alterar").load(session.urlmiles + "?controller=mdm/movimentacao&op=listarmovimentacao&movimentacao=" + _movimentacao);
}
function atualizarListaStatus(){
    $("#lista-status").load(session.urlmiles + "?controller=mdm/movimentacao&op=listarstatus&movimentacao=" + _movimentacao);
}
function atualizarListaHistorico(){
    $("#lista-historico").load(session.urlmiles + "?controller=mdm/movimentacao&op=listarhistorico&movimentacao=" + _movimentacao);
}
function excluirAlterar(id){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/movimentacao',
            op:"excluiralterar",
            id:id
        },
        complete:function(){
            atualizarListaAlterar();
        }
    });
}
function excluirStatus(id){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/movimentacao',
            op:"excluirstatus",
            id:id
        },
        complete:function(){
            atualizarListaStatus();
        }
    });
}
function excluirHistorico(id){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/movimentacao',
            op:"excluirhistorico",
            id:id
        },
        complete:function(){
            atualizarListaHistorico();
        }
    });				
}
function carregarValoresAtributo(obj){
    if (arguments.length > 1){
        var valor = arguments[1];
    }else{
        var valor = "";
    }
    var fk = obj.find("option:selected").data("chaveestrangeira");
    if (fk > 0 && fk != "" && fk != undefined){
        $(".form-control[data-tipoatributo=lista]").attr("id","valor");
        $(".form-control[data-tipoatributo=lista]").attr("name","valor");
        $(".form-control[data-tipoatributo=lista]").show();
        $(".form-control[data-tipoatributo=input]").attr("id","");
        $(".form-control[data-tipoatributo=input]").attr("name","");
        $(".form-control[data-tipoatributo=input]").hide();

        $.ajax({
            url:session.urlrequisicoes,
            type:"GET",
            data:{
                op:"carregar_options",
                entidade:fk,
                atributo:"",
                valor:valor
            },
            complete:function(ret){
                $("#form-status #valor").html(ret.responseText);
            }
        });
    }else{
        var checkbox = obj.find("option:selected").data("checkbox");
        if (checkbox > 0 && checkbox != "" && checkbox != undefined){
            $.ajax({
                url:session.urlrequisicoes,
                type:"GET",
                data:{
                    op:"carregar_options_checkbox",
                    atributo:obj.find("option:selected").val()
                },
                complete:function(ret){
                    $(".form-control[data-tipoatributo=input]").hide();
                    $(".form-control[data-tipoatributo=input]").attr("id","");
                    $(".form-control[data-tipoatributo=input]").attr("name","");
                    $(".form-control[data-tipoatributo=lista]").attr("id","valor");
                    $(".form-control[data-tipoatributo=lista]").attr("name","valor");
                    $(".form-control[data-tipoatributo=lista]").show();
                    $("#form-status #valor").html(ret.responseText);
                }
            });
        }else{
            $(".form-control[data-tipoatributo=input]").attr("id","valor");
            $(".form-control[data-tipoatributo=input]").attr("name","valor");
            $(".form-control[data-tipoatributo=input]").show();
            $(".form-control[data-tipoatributo=lista]").attr("id","");
            $(".form-control[data-tipoatributo=lista]").attr("name","");
            $(".form-control[data-tipoatributo=lista]").hide();
        }
    }
}
function carregarAtributosHistorico(){
    $("#form-historico #atributo").load(session.urlmiles + "?controller=mdm/movimentacao&op=lista_atributos&entidade=" + $("#form-historico #entidade").val());
}