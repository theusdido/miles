$(document).ready(function(){
    getStatusGuia();
});

function getStatusGuia(){
    $.ajax({
        url:session.urlrequisicoes,
        data:{
            _controller:"install/guia",
            _op:"status"
        },
        success:function(res){
            try{
                let _res = JSON.parse(res);
                if (_res.installed){
                    let all_fields = '#host,#base,#porta,#usuario,#senha,#tipo';
                    $(all_fields).attr('disabled',true);
                    $(all_fields).attr('readonly',true);
                    $('#btn-criarbanco').remove();
                }
                $('#guia-base')         .attr('src',_res.check_criarbase);
                $('#guia-instalacao')   .attr('src',_res.check_instalacaosistema);
                $('#guia-pacote')       .attr('src',_res.check_pacoteconfigurado);
                $('#host')              .val(_res.database.host);
                $('#base')              .val(_res.database.base);
                $('#porta')             .val(_res.database.porta);
                $('#usuario')           .val(_res.database.usuario);
                $('#senha')             .val(_res.database.senha);
            }catch(e){
                console.log(res);
            }
        }
    });
}

$('#menu-guia a').click(function(event){
    event.preventDefault();
    event.stopPropagation();
    $('#conteudo-instalacao').load('?controller=page&page=install/' + $(this).data('href'));
});
