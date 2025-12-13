var is_farein_selected  = false;
var empresa_id_selected = 0;
$(document).ready(function(){
    initPesquisarFarein();

    $('#termo-empresa').blur(function(){
        buscarEmpresaFiltro(this.value);
    });
});
function showModalPesquisaFarein(){
    if (is_farein_selected){        
        limparFareinCampo();
        return;
    }    
    initPesquisarFarein();
    $("#modal-pesquisar-empresa").modal("show"); 
}
$('#botao-pesquisar-empresa').click(function(){    
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'farein',
            op:'pesquisar',
            termo:$('#modal-pesquisar-empresa #termo-pesquisar-empresa').val(),
            tipo:$('#modal-pesquisar-empresa #tipo-processo').val()
        },
        complete:function(_res){
            let _data = _res.responseJSON;
            if (_data.length > 0){
                _data.forEach( _linha => {
                    let tr              = $('<tr data-id="'+ _linha.id +'" data-farein="'+ _linha.farein_tabela +'" >');
                    let td_id           = $('<td class="text-center">');
                    let td_nome         = $('<td class="text-left">');
                    let td_documento    = $('<td class="text-left">');
                    let td_tipo         = $('<td class="text-left">');
                    
                    tr.click(function(){
                        let tipo            = _linha.tipo;
                        $.ajax({
                            url:session.urlmiles,
                            dataType:'json',
                            data:{
                                controller:'farein',
                                op:'get',
                                id:_linha.id,
                                farein:_linha.farein_tabela
                            },
                            complete:function(_res){
                                let _data = _res.responseJSON;
                                empresa_id_selected = _data.id;
                                $('#termo-empresa').val(_data.id);
                                $('#resultado-descricao-empresa').val(_data.razaosocial);
                                $('#retorno_empresa').val(_data.id + '^' + _data.processo + '^' + tipo);
                                $("#modal-pesquisar-empresa").modal("hide");

                                is_farein_selected = true;
                            }
                        });
                    });

                    td_id.html(_linha.id);
                    td_nome.html(_linha.nome);
                    td_documento.html(_linha.documento);
                    td_tipo.html(_linha.tipo_descricao);

                    tr.append(td_id);
                    tr.append(td_nome);
                    tr.append(td_documento);
                    tr.append(td_tipo);

                    $('#resultado-empresa tbody').append(tr);
                });
                unLoading();
            }else{
                setNenhumRegistro();                
            }
            
        },
        beforeSend:function(){
            $('#resultado-empresa tbody').html('');
            addLoading();
        }
    });
});

function setNenhumRegistro(){
    $('#resultado-empresa tbody').html('<tr class="warning"><td colspan="4" class="text-center">Nenhum Registro Encontrado</td></tr>');
}

function initPesquisarFarein(){
    $('#tipo-processo,#termo-pesquisar-empresa').val('');
    $('#resultado-empresa tbody').html('');
    setNenhumRegistro();
}
function limparFareinCampo(){
    $('#termo-empresa,#resultado-descricao-empresa,#retorno_empresa').val('');
    is_farein_selected = false;
    $('#btn-pesquisar-empresa span').addClass('fa-search');
    $('#btn-pesquisar-empresa span').removeClass('fa-trash');
}

function buscarEmpresaFiltro(_termo_id_empresa){
    if (_termo_id_empresa == '' || empresa_id_selected != _termo_id_empresa){
        $('#termo-empresa').val('');
        $('#resultado-descricao-empresa').val('');
        empresa_id_selected = 0;
    }
}

function addLoading(){
    $('#resultado-empresa tbody').html('<tr><td id="linha-loader-pesquisa-empresa" colspan="4" class="text-center"></td></tr>');
    loader('#linha-loader-pesquisa-empresa');
}

function unLoading(){
    $('#linha-loader-pesquisa-empresa').parent('tr').first().remove();
}