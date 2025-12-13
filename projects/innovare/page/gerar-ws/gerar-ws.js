$(document).ready(function () {    
    $('#btn-gerar').click(function(){
        gerarWS();
    });
});
function gerarWS(){
    $.ajax({
        url:session.urlmiles,
        data:{
            controller:'gerarws',
            op:$('#site').val()
        },
        complete:function(_res){
            alert('Feito!');
        }
    });
}