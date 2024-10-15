var atributo_id_selected = 0;
$(document).ready(function(){
    loadColunas();
    loadAtributos();    
});

function loadColunas(){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'mdm/relatorio',
            op:'get_colunas',
            relatorio:_relatorio
        },
        complete:function(_res){
            let dados = _res.responseJSON;
            $('#lista-relatorios').html('');
            if (dados.length <= 0 ){
                $('#lista-relatorios').html('<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhuma coluna adicionada.</div>');
                return;
            }
            dados.forEach((d,i) => {
                let li              = $('<li class="list-group-item" data-id="' + d.id + '">');
                let span            = $('<span class="badge config-relatorio-icon">');                
                let label           = $('<span>');
                let icone_config    = $('<i class="fa fa-gear">');
                let icone_excluir   = $('<i class="fa fa-trash" data-id="' + d.id + '">');
                if (i == 0){
                    $('#lista-relatorios').append($('<li class="list-group-item active">Descrição</li>'));
                }
    
                icone_config.click(function(){
                    atributo_id_selected = d.atributo.id;
                    $("#modalRelatorios").modal({
                        backdrop:false
                    });
                    $('#modalRelatorios').modal('show');
                    setDados();
                });

                icone_excluir.click(function(){
                    excluirColuna($(this).data('id'));
                });

                label.html(d.atributo.descricao);
                span.append(icone_config);
                span.append('&nbsp;&nbsp;&nbsp;&nbsp;');
                span.append(icone_excluir);
                li.append(span);
                li.append(label);
                $('#lista-relatorios').append(li);
            });
        }
    });    
}

function salvarConfigRelatorio(){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'mdm/relatorio',
            op:'salvar_colunas_configuracoes',
            relatorio:_relatorio,
            atributo:atributo_id_selected,
            exibirid:$('#exibirid','#form-config-relatorio').is(':checked'),
            alinhamento:$('#alinhamento','#form-config-relatorio').val(),
            descricao:$('#descricao','#form-config-relatorio').val(),
            is_somatorio:$('#is_somatorio','#form-config-relatorio').is(':checked'),
        },
        complete:function(){
        }
    });
}

function setDados(){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'mdm/relatorio',
            op:'get_config_colunas',
            relatorio:_relatorio,
            atributo:atributo_id_selected
        },
        complete:function(_res){
            let dados = _res.responseJSON;
            $('#form-config-relatorio #exibirid').attr('checked',dados.exibirid == 1 ? true : false),
            $('#form-config-relatorio #alinhamento').val(dados.alinhamento);
            $('#form-config-relatorio #descricao').val(dados.descricao);
            $('#form-config-relatorio #is_somatorio').attr('checked',dados.is_somatorio == 1 ? true : false);
        }
    });    
}

$("#lista-relatorios").sortable({
    update: function( event, ui ) {
        var ordenacao = [];
        $("#lista-relatorios li").each(
            (e,elemento) => {
                var id = $(elemento).data("id");
                if (id != undefined){
                    ordenacao.push({
                        id:id,
                        order:e+1
                    });
                }					
            }
        );
        $.ajax({
            url:session.urlmiles,
            data:{
                op:"ordenar",
                controller:"sortable",
                entidade:"td_relatoriocoluna",
                atributo:"ordem",
                ordem:ordenacao
            }
        });
    }
});

function novaColuna(){
    $("#modalNovaColuna").modal({
        backdrop:false
    });
    $("#modalNovaColuna").modal('show');
}

function loadAtributos(){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'requisicoes',
            op:'retorna_atributos',
            _entidade:$("#entidade").val()
        },
        complete:function(_res){
            let dados               = _res.responseJSON;
            let selector_retorno    = '#form-filtro-novacoluna #atributo';
            $(selector_retorno).html('');
            dados.forEach((d,i) => {
                let li      = $('<option value="'+d.id+'">'+d.descricao+'</option>');
                $(selector_retorno).append(li);
            });
        }
    });    
}

$('#salvarFiltroNovaColuna').click(function(){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'mdm/relatorio',
            op:'add_coluna',
            relatorio:_relatorio,
            atributo:$('#form-filtro-novacoluna #atributo').val()
        },
        complete:function(_res){
            loadColunas();
            $("#modalNovaColuna").modal('hide');
        }
    });
});

function excluirColuna(coluna_id){
    bootbox.confirm("Tem certeza que deseja excluir ? ",function(result){
        if (result){
            $.ajax({
                url:session.urlmiles,
                dataType:'json',
                data:{
                    controller:'mdm/relatorio',
                    op:'del_coluna',
                    id:coluna_id
                },
                complete:function(_res){
                    loadColunas();
                }
            });
        }
    });

}