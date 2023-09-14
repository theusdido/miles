$('#exibirlegenda').attr('checked',true);
$('#exibircabecalho').attr('checked',true);
$('#exibirmenuadministracao').attr('checked',false);
$('#registrounico').attr('checked',false);
$('#carregarlibjavascript').attr('checked',true);
$('#criarempresa').attr('checked',false);
$('#criarprojeto').attr('checked',false);
$('#criarauth').attr('checked',false);
$('#entidadeauxiliar').attr('checked',false);

var _registro_entidade = {};

$(document).ready(function(){
    $('#campodescchave,#atributogeneralizacao').load(session.urlmiles + '?controller=mdm/cadastro&_entidade=' + _entidade + '&op=listar-atributos');
    if (_entidade != 0){
        load();
    }
});


function setTipoAba(_tipoaba){
    switch(_tipoaba){
        case 'tabs':
            $("#aba-tabs").attr('checked',true);
            $("#aba-pills").attr('checked',false);
        break;
        case 'pills':
            $("#aba-tabs").attr('checked',false);
            $("#aba-pills").attr('checked',true);
        break;
        default:
            $("#aba-tabs").attr('checked',true);
            $("#aba-pills").attr('checked',false);
    }
}
$('#btn-edit-id-entidade').click(function(){
    if (is_edit_id){
        alterarIdEntidade();
    }else{
        $('#id').removeAttr('disabled');
        $('#id').removeAttr('readonly');
        $('#id').focus();
        $(this).find('.fas').removeClass('fa-pencil-alt');
        $(this).find('.fas').addClass('fa-save');
        is_edit_id = true;
    }
});

function alterarIdEntidade()
{
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/cadastro',
            op:'alterar_id',
            _entidade:_entidade,
            _entidade_new:$('#id').val(),
        },
        success:function(){
            _entidade = $('#id').val();
        }
    });
}

$('#btn-salvar-cadastro').click(function(){    
    _registro_entidade = {
        // Campos Inputs
        nome:$('#nome').val(),
        descricao:$('#descricao').val(),
        ncolunas:$('#ncolunas').val(),
        campodescchave:$('#campodescchave').val(),
        atributogeneralizacao:$('#atributogeneralizacao').val(),

        // Campos Checkbox
        exibirmenuadministracao:$('#exibirmenuadministracao').prop('checked'),
        exibirlegenda:$('#exibirlegenda').prop('checked'),
        registrounico:$('#registrounico').prop('checked'),
        carregarlibjavascript:$('#carregarlibjavascript').prop('checked'),
        exibircabecalho:$('#exibircabecalho').prop('checked'),
        entidadeauxiliar:$('#entidadeauxiliar').prop('checked'),
        criarprojeto:$('#criarprojeto').prop('checked'),
        criarempresa:$('#criarempresa').prop('checked'),
        criarauth:$('#criarauth').prop('checked'),
        tipoaba:$('input[type=radio][name=tipoaba]:checked').val()
    }

    let _opt = {
        // Parametros
        controller:'mdm/cadastro',
        entidade:_entidade,
        op:'salvar',
    }
    $.ajax({
        url:session.urlmiles,
        type:"POST",
        dataType:'json',
        data:{
            ..._opt,..._registro_entidade
        },
        complete:function(_res){
            let _ret        = _res.responseJSON;
            setEntidade(_ret.id);
            unLoaderSalvar();
            mdmToastMessage("Salvo com Sucesso");

            let _gerarhtml = new gerarHTML();
            _gerarhtml._entidade_id    = _entidade;
            _gerarhtml._conceito       = 'cadastro';
            _gerarhtml._conceito_id    = _entidade;
            _gerarhtml.conceito();

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
            controller:'mdm/cadastro',
            _entidade:_entidade,
            op:'load'
        },
        complete:function(_res){
            let _data       = _res.responseJSON;
            _entidade_obj   = _data;            
            
            $('#id').val(_data.id);
            $('#nome').val(_data.nome);
            $('#descricao').val(_data.descricao);
            $('#ncolunas').val(_data.ncolunas);
            $('#campodescchave').val(_data.campodescchave == null ? 0 : _data.campodescchave);
            $('#atributogeneralizacao').val(_data.atributogeneralizacao == null ? 0 : _data.atributogeneralizacao);

            $('#exibirmenuadministracao').attr('checked',_data.exibirmenuadministracao == 0 ? false : true);
            $('#exibirlegenda').attr('checked',_data.exibirlegenda == 0 ? false : true);
            $('#registrounico').attr('checked',_data.registrounico == 0 ? false : true);
            $('#carregarlibjavascript').attr('checked',_data.carregarlibjavascript == 0 ? false : true);
            $('#exibircabecalho').attr('checked',_data.exibircabecalho == 0 ? false : true);
            $('#entidadeauxiliar').attr('checked',_data.entidadeauxiliar == 0 ? false : true);

            setTipoAba(_data.tipoaba);
        }
    });
}

function setEntidade(_id){
    td_entidade[_id] =  {
        ...{id:_id},
        ..._registro_entidade
    }
}