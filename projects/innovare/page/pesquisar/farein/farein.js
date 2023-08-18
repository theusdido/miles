var is_farein_selected = false;
$(document).ready(function(){
    initPesquisarFarein();
});
function showModalPesquisaFarein(){
    if (is_farein_selected){        
        limparFareinCampo();
        return;
    }    
    initPesquisarFarein();
    $("#modal-pesquisar-farein").modal("show"); 
}
$('#botao-pesquisar-farein').click(function(){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'farein',
            op:'pesquisar',
            termo:$('#termo-pesquisar-farein').val(),
            tipo:$('#tipo-processo').val()
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
                                $('#termo-farein').val(_data.id);
                                $('#resultado-descricao-farein').val(_data.razaosocial);
                                $('#retorno_farein').val(_data.id + '^' + _data.processo + '^' + tipo);
                                $("#modal-pesquisar-farein").modal("hide");
                                $('#btn-pesquisar-farein span').removeClass('fa-search');
                                $('#btn-pesquisar-farein span').addClass('fa-trash');
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

                    $('#resultado-farein tbody').append(tr);
                });
            }else{
                setNenhumRegistro();
            }
            
        },
        beforeSend:function(){
            $('#resultado-farein tbody').html('');
        }
    });
});

function setNenhumRegistro(){
    $('#resultado-farein tbody').html('<tr class="warning"><td colspan="4" class="text-center">Nenhum Registro Encontrado</td></tr>');
}

function initPesquisarFarein(){
    $('#tipo-processo,#termo-pesquisar-farein').val('');
    $('#resultado-farein tbody').html('');
    setNenhumRegistro();
}
function limparFareinCampo(){
    $('#termo-farein,#resultado-descricao-farein,#retorno_farein').val('');
    is_farein_selected = false;
    $('#btn-pesquisar-farein span').addClass('fa-search');
    $('#btn-pesquisar-farein span').removeClass('fa-trash');    
}