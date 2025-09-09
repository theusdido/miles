$('#exibirlegenda').attr('checked',true);
$('#exibircabecalho').attr('checked',true);
$('#exibirmenuadministracao').attr('checked',false);
$('#registrounico').attr('checked',false);
$('#carregarlibjavascript').attr('checked',true);
$('#criarempresa').attr('checked',false);
$('#criarprojeto').attr('checked',false);
$('#criarauth').attr('checked',false);
$('#entidadeauxiliar').attr('checked',false);
$('#controlarregistrousuario').attr('checked',false);

var _registro_entidade = {};
var nome_old = '';

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

$('#btn-edit-nome-entidade').click(function(){
    if (is_edit_nome){
        alterarNomeEntidade();
    }else{
        $('#nome').removeAttr('disabled');
        $('#nome').removeAttr('readonly');
        $('#nome').focus();
        $(this).find('.fas').removeClass('fa-pencil-alt');
        $(this).find('.fas').addClass('fa-save');
        is_edit_nome = true;
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

function alterarNomeEntidade()
{
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'mdm/cadastro',
            op:'alterar_nome',
            _entidade:nome_old,
            _entidade_new:$('#nome').val(),
        },
        success:function(){
            //_entidade = $('#id').val();
        }
    });
}



$('#btn-salvar-cadastro').click(function(){    
    _registro_entidade = {
        // Campos Inputs
        nome                            :$('#nome').val(),
        descricao                       :$('#descricao').val(),
        ncolunas                        :$('#ncolunas').val(),
        campodescchave                  :$('#campodescchave').val(),
        atributogeneralizacao           :$('#atributogeneralizacao').val(),

        // Campos Checkbox
        exibirmenuadministracao         :$('#exibirmenuadministracao').prop('checked'),
        exibirlegenda                   :$('#exibirlegenda').prop('checked'),
        registrounico                   :$('#registrounico').prop('checked'),
        carregarlibjavascript           :$('#carregarlibjavascript').prop('checked'),
        exibircabecalho                 :$('#exibircabecalho').prop('checked'),
        entidadeauxiliar                :$('#entidadeauxiliar').prop('checked'),
        criarprojeto                    :$('#criarprojeto').prop('checked'),
        criarempresa                    :$('#criarempresa').prop('checked'),
        criarauth                       :$('#criarauth').prop('checked'),
        controlarregistrousuario        :$('#controlarregistrousuario').prop('checked'),
        tipoaba                         :$('input[type=radio][name=tipoaba]:checked').val()
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
            _entidade       = _ret.id;

            setMonitorStorage('entidade',JSON.parse(_ret._data));
            unLoaderSalvar();
            mdmToastMessage("Salvo com Sucesso");

            let _gerarhtml              = new gerarHTML();
            _gerarhtml._entidade_id     = _entidade;
            _gerarhtml._conceito        = 'cadastro';
            _gerarhtml._conceito_id     = _entidade;
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
            let _data       = JSON.parse(_res.responseText);

            setMonitorStorage('entidade',_data);
            setTipoAba(_data.tipoaba);
            
            $('#id')                            .val(_data.id);
            $('#nome')                          .val(_data.nome);
            $('#descricao')                     .val(_data.descricao);
            $('#ncolunas')                      .val(_data.ncolunas);
            $('#campodescchave')                .val(_data.campodescchave == null ? 0 : _data.campodescchave);
            $('#atributogeneralizacao')         .val(_data.atributogeneralizacao == null ? 0 : _data.atributogeneralizacao);
            $('#exibirmenuadministracao')       .attr('checked',getBoolCheckedValue(_data.exibirmenuadministracao));
            $('#exibirlegenda')                 .attr('checked',getBoolCheckedValue(_data.exibirlegenda));
            $('#registrounico')                 .attr('checked',getBoolCheckedValue(_data.registrounico));
            $('#carregarlibjavascript')         .attr('checked',getBoolCheckedValue(_data.carregarlibjavascript));
            $('#exibircabecalho')               .attr('checked',getBoolCheckedValue(_data.exibircabecalho));
            $('#entidadeauxiliar')              .attr('checked',getBoolCheckedValue(_data.entidadeauxiliar));            
            $('#controlarregistrousuario')      .attr('checked',getBoolCheckedValue(_data.controlarregistrousuario));

            nome_old = _data.nome;
        }
    });
}