var _relatorio = 0;
$(document).ready(function(){
    listarRelatorio();
});

function listarRelatorio(){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/relatorio',
            op:'listar-relatorio'
        },
        complete:function(res){
            $('#table-lista-relatorio tbody').html(res.responseText);
        }
    });
}

function criarRelatorio(){
    _relatorio = 0;
    loadRelatorioContent('form');
}

function editarRelatorio(_relatorio_id){
    _relatorio = _relatorio_id;
    loadRelatorioContent('form');
}

function excluirRelatorio(_relatorio_id){
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
                        controller:'mdm/relatorio',
                        op:'excluir-relatorio',
                        id:_relatorio_id
                    },
                    complete:function(res){
                        $('#linha-registro-relatorio-' + _relatorio_id).remove();
                        if ($('#table-lista-relatorio tbody tr').length <= 0){
                            $('#table-lista-relatorio tbody').html('<tr><td colspan="6" class="warning text-center">Nenhum Registro Encontrado</td></tr>');
                        }
                    }
                });
            }
        }
    });
}

function gerarRelatorio(relatorio_id,entidade_id){
    setGlobais(relatorio_id,entidade_id);
    setTituloPagina('Gerar');
    loadRelatorioContent('pagina');
}

function setGlobais(relatorio_id,entidade_id){
    _conceito           = 'relatorio';
    _conceito_id        = relatorio_id;
    _entidade           = entidade_id;
    _entidade_display   = td_entidade[entidade_id].descricao;    
}