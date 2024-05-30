var atributo_id_selected = 0;
$.ajax({
    url:session.urlmiles,
    dataType:'json',
    data:{
        controller:'requisicoes',
        op:'retorna_atributos',
        _entidade:$("#entidade").val()
    },
    complete:function(_res){
        let dados = _res.responseJSON;
        dados.forEach((d,i) => {
            let li      = $('<li class="list-group-item">');
            let span    = $('<span class="badge config-coluna-icon">');
            let icone   = $('<i class="fa fa-gear">');
            let label   = $('<span>');
            
            if (i == 0){
                $('#lista-colunas').append($('<li class="list-group-item active">Descrição</li>'));
            }
            span.click(function(){
                atributo_id_selected = d.id;
                setDados();
            });
            label.html(d.descricao);
            span.append(icone);
            li.append(span); 
            li.append(label);
            $('#lista-colunas').append(li);
        });
    }
});

function salvarConfigColuna(){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'mdm/consulta',
            op:'salvar_colunas_configuracoes',
            consulta:_consulta,
            atributo:atributo_id_selected,
            exibirid:$('#exibirid').is(':checked'),
            alinhamento:$('#alinhamento').val()
        },
        complete:function(_res){
            let dados = _res.responseJSON;
        }
    });
}

function setDados(){
    $.ajax({
        url:session.urlmiles,
        dataType:'json',
        data:{
            controller:'mdm/consulta',
            op:'get_config_colunas',
            consulta:_consulta,
            atributo:atributo_id_selected
        },
        complete:function(_res){
            let dados = _res.responseJSON;
            if (dados != undefined){
                $('#exibirid').attr('checked',dados.exibirid == 1 ? true : false);
                $('#alinhamento').val(dados.alinhamento);
            }
            $('#modalColunas').modal('show');
        }
    });    
}