$(document).ready(function(){
    // $("#host").val("<?=$host?>");
    // $("#base").val("<?=$base?>");
    // $("#porta").val("<?=$port?>");
    // $("#usuario").val("<?=$user?>");
    // $("#senha").val("<?=$password?>");
    // $("#tipo").val("<?=$type?>");

    $("#base").blur( function(){
        if ($(this).val() != ''){
            statusFormControl('#base','default');
        }
    });
});

$("#btn-testarconexao").click(function(){
    $.ajax({
        url:session.urlrequisicoes,
        data:{
            controller:"install/database",
            op:"testar",
            host:$("#host").val(),
            base:$("#base").val(),
            porta:$("#porta").val(),
            usuario:$("#usuario").val(),
            senha:$("#senha").val(),
            tipo:$("#tipo").val()
        },
        success:function(retorno){
            var retorno = JSON.parse(retorno);
            if (retorno.status == 1){
                $("#retorno").html(
                    '<div class="alert alert-success" role="alert"><b>Parabéns!</b>. Teste de conexão realizado com sucesso.</div>'
                );
            }else{
                $("#retorno").html(
                    '<div class="alert alert-danger" role="alert"><b>Error: </b> Teste de conexão falhou.</div>'
                );
            }
            $("#retorno").show("5000");
            setTimeout(function(){
                $("#retorno").hide("5000");
            },3000);
        }
    });
});
$("#btn-criarbanco").click(function(){
    if ($("#base").val() == ''){
        statusFormControl('#base','error');
        $("#base").focus();
        return false;
    }
    $('#loader-criarbanco').attr('src',session.urlloading2);
    $("#loader-criarbanco").show();
    $.ajax({
        url:session.urlrequisicoes,
        data:{
            controller:"install/database",
            op:"criar",
            host:$("#host").val(),
            base:$("#base").val(),
            porta:$("#porta").val(),
            usuario:$("#usuario").val(),
            senha:$("#senha").val(),
            tipo:$("#tipo").val()
        },
        success:function(retorno){
            $("#retorno").show("5000");         
            $("#loader-criarbanco").hide();
            getStatusGuia();
            if (retorno == 1){
                $("#retorno").html('<div class="alert alert-success" role="alert"><b>Parabéns !</b>. Base de dados criado com sucesso.</div>');

            }else{
                $("#retorno").html(retorno);
            }
            setTimeout(function(){
                $("#retorno").hide("5000");
            },3000);
        }
    });
});