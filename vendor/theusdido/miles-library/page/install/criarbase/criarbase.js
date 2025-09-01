(()=>{
    let campos_obrigatorios = ['host','base','usuario'];
    $(document).ready(function(){
        getStatusGuia();
        campos_obrigatorios.forEach(
            (_element) => {
                $("#" + _element).blur( function(){
                    if ($(this).val() != ''){
                        statusFormControl('#' + _element,'default');
                    }
                });
            }
        );
    });

    $("#btn-testarconexao").click(function(){
        if (!validar()) return;
        $.ajax({
            url:session.urlmiles,
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
            complete:function(_res){
                let retorno = JSON.parse(_res.responseText);
                if (retorno.status == 1){
                    $("#retorno-criar-base").html(
                        '<div class="alert alert-success" role="alert"><b>Parabéns!</b>. Teste de conexão realizado com sucesso.</div>'
                    );
                }else{
                    $("#retorno-criar-base").html(
                        '<div class="alert alert-danger" role="alert"><b>Error: </b> Teste de conexão falhou.</div>'
                    );
                }
                $("#retorno-criar-base").show("5000");
                setTimeout(function(){
                    $("#retorno-criar-base").hide("5000");
                },3000);
            },
            error:function(_res){
                $("#retorno-criar-base").html(
                    '<div class="alert alert-danger" role="alert">'+_res.responseText+'</div>'
                );
                setTimeout(function(){
                    $("#retorno-criar-base").hide("5000");
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
            url:session.urlmiles,
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
            complete:function(_res){
                let retorno = _res.responseText;     
                $("#loader-criarbanco").hide();                
                if (retorno == 1){
                    Toastify({
                        text: 'Banco de Dados Criado com Sucesso!',
                        duration: 5000,
                        className:'td-message-success',
                        escapeMarkup:false
                    }).showToast();
                    setCurrentGuia('setup');
                    getStatusGuia();
                }else{
                    $("#retorno-criar-base").html(retorno);
                }
            }
        });
    });

    function validar(){
        let is_valid = true;
        campos_obrigatorios.forEach(
            (_element) => {
                if ($("#" + _element).val() == '' & is_valid){
                    statusFormControl('#' + _element,'error');
                    $("#" + _element).focus();
                    is_valid = false;
                    return;
                }
            }
        );
        return is_valid;
    }
})();