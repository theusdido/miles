_entidade_nome = td_entidade[_entidade].nome;
$('#filename').val(_entidade_nome + '.html');
$('#filenamejs').val(_entidade_nome + '.js');
$('#filenamecss').val(_entidade_nome + '.css');
$('#filenamehtm').val(_entidade_nome + '.htm');

$("#gerar").click(function(){
    $('#lista-arquivos-gerados').hide();
    $("#pagina-gerada").removeClass('success');
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:"gerar" + _conceito,
            entidade:_entidade,
            id:_conceito_id,
            principal:true
        },
        complete:function(retorno){
            gerarPagina(retorno.responseText);
        },
        beforeSend:function(){
            loader("#pagina-gerada");
        }
    });
});

function gerarPagina(html){
    $.ajax({
        url:session.urlmiles,
        type:"POST",
        data:{
            controller:"mdm/componente",
            op:"criar" + _conceito,
            html:html,
            filename:$("#filename").val(),
            filenamejs:$("#filenamejs").val(),
            filenamecss:$("#filenamecss").val(),
            filenamehtm:$("#filenamehtm").val(),
            entidade:_entidade,
            id:_conceito_id,
            reset:$('#exluir-arquivos-componente').prop('checked')
        },
        complete:function(){

            let url_file_cadastro = session.url_files + _conceito + '/' + _conceito_id + '/';

            $('#view-filename-html').html($('#filename').val());
            $('#view-filename-js').html($('#filenamejs').val());
            $('#view-filename-css').html($('#filenamecss').val());
            $('#view-filename-htm').html($('#filenamehtm').val());

            $('#view-filename-html').attr('href',url_file_cadastro + $('#filename').val());
            $('#view-filename-js').attr('href',url_file_cadastro + $('#filenamejs').val());
            $('#view-filename-css').attr('href',url_file_cadastro + $('#filenamecss').val());
            $('#view-filename-htm').attr('href',url_file_cadastro + $('#filenamehtml').val());

            $('#lista-arquivos-gerados').show();
            
            $("#pagina-gerada").addClass('success');
            $("#pagina-gerada").html("Arquivos gerados!");
        }
    });
}