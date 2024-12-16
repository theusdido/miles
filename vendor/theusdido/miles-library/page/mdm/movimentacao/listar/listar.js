var _movimentacao = 0;
$(document).ready(function(){
    listarMovimentacao();
});

function listarMovimentacao(){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/movimentacao',
            op:'listar-movimentacao'
        },
        complete:function(res){
            $('#table-lista-movimentacao tbody').html(res.responseText);
        }
    });
}

function criarMovimentacao(){
    _movimentacao = 0;
    loadMovimentacaoContent('form');
}

function editarMovimentacao(_movimentacao_id){
    _movimentacao = _movimentacao_id;
    loadMovimentacaoContent('form');
}

function excluirMovimentacao(_movimentacao_id){
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
                        controller:'mdm/movimentacao',
                        op:'excluir-movimentacao',
                        id:_movimentacao_id
                    },
                    complete:function(res){
                        $('#linha-registro-movimentacao-' + _movimentacao_id).remove();
                        if ($('#table-lista-movimentacao tbody tr').length <= 0){
                            $('#table-lista-movimentacao tbody').html('<tr><td colspan="6" class="warning text-center">Nenhum Registro Encontrado</td></tr>');
                        }
                    }
                });
            }
        }
    });
}

function gerarMovimentacao(movimentacao_id,entidade_id){
    setGlobais(movimentacao_id,entidade_id);
    setTituloPagina('Gerar');
    loadMovimentacaoContent('pagina');
}

function setGlobais(movimentacao_id,entidade_id){
    _conceito           = 'movimentacao';
    _conceito_id        = movimentacao_id;
    _entidade           = entidade_id;
    _entidade_display   = td_entidade[entidade_id].descricao;    
}