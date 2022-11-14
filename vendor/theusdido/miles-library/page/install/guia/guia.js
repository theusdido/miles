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
            let response = JSON.parse(res);
            $('#guia-base').attr('src',response.check_criarbase);
            $('#guia-instalacao').attr('src',response.check_instalacaosistema);
            $('#guia-pacote').attr('src',response.check_pacoteconfigurado);
        }
    });
}

$('#menu-guia a').click(function(event){
    event.preventDefault();
    event.stopPropagation();
    $('#conteudo-instalacao').load('?controller=page&page=install/' + $(this).data('href'));
});