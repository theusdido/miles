var is_edit_id          = false;

$(document).ready(function(){
    listarEntidades();
    $('#entidade-termo').focus();
    setTituloPagina('Cadastros');
});

function excluirEntidade(_entidade_excluir){
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
                        op:'excluir-entidade',
                        entidade:_entidade_excluir
                    },
                    complete:function(res){
                        $('#linha-registro-entidade-' + _entidade_excluir).remove();
                    }
                }); 
            }
        }
    });
}

function gerarCadastro(entidade_id,entidade_display_){
    _conceito       = 'cadastro';
    _conceito_id    = entidade_id;
    _entidade       = entidade_id;
    setTituloPagina('Gerar',entidade_display_);
    loadCadastroContent('pagina');
}

function listarEntidades(){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/cadastro',
            op:'listar-entidades',
            termo:termo_buscar_cadastro
        },
        complete:function(res){
            $('#table-lista-entidade tbody').html(res.responseText);
            $('#entidade-termo').val(termo_buscar_cadastro);
        }
    });

}

$('#btn-pesquisar-cadastro').click(function(){
    termo_buscar_cadastro = $('#entidade-termo').val();
    listarEntidades();
});

$('#entidade-termo').keyup(function(e){
    termo_buscar_cadastro = $('#entidade-termo').val();
    listarEntidades();
});

function editarCadastro(_entidade_id,entidade_display_){
    _entidade = _entidade_id;
    setTituloPagina('Editar',entidade_display_);
    goCadastro();
}

function criar(){
    _entidade = 0;
    setTituloPagina('Novo','Cadastro');
    goCadastro();
}

function listarCampos(_entidade_id,entidade_display_){
    _entidade           = _entidade_id;
    setTituloPagina('Campos',entidade_display_);
    loadCadastroContent('campos');
}

function goCadastro(){
    loadCadastroContent('form');
}

function goAbasCadastro(_entidade_id,entidade_display_){
    _entidade           = _entidade_id;
    setTituloPagina('Aba',entidade_display_);
    loadCadastroContent('aba');
}

function goRelacionamentos(_entidade_id,entidade_display_){
    _entidade           = _entidade_id;
    setTituloPagina('Relacionamento',entidade_display_);
    loadCadastroContent('relacionamento');
}