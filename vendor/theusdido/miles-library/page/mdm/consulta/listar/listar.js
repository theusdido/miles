$(document).ready(function(){
    listarConsulta();
    $('#entidade-termo').focus();
});

function listarConsulta(){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/consulta',
            op:'listar-consulta'
        },
        complete:function(res){
            $('#table-lista-consulta tbody').html(res.responseText);
        }
    });
}

function criarConsulta(){
    _consulta = 0;
    loadConsultaContent('form');
}

function editarConsulta(_consulta_id){
    _consulta = _consulta_id;
    loadConsultaContent('form');
}

function excluirConsulta(_consulta_id){
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
                        controller:'mdm/consulta',
                        op:'excluir-consulta',
                        id:_consulta_id
                    },
                    complete:function(res){
                        $('#linha-registro-consulta-' + _consulta_id).remove();
                        if ($('#table-lista-consulta tbody tr').length <= 0){
                            $('#table-lista-consulta tbody').html('<tr><td colspan="6" class="warning text-center">Nenhum Registro Encontrado</td></tr>');
                        }
                    }
                });
            }
        }
    });
}

function gerarConsulta(consulta_id,entidade_id){
    setGlobais(consulta_id,entidade_id);
    setTituloPagina('Gerar');
    loadConsultaContent('pagina');
}

function setGlobais(consulta_id,entidade_id){
    _conceito           = 'consulta';
    _conceito_id        = consulta_id;
    _entidade           = entidade_id;
    _entidade_display   = td_entidade[entidade_id].descricao;    
}