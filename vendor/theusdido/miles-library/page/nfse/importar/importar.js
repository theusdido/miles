$(function(){
    const URL_IMPORTAR = '?controller=nfse/importar';
    var quantidade_notas = 0;

    // Popula o campo de referência
    popularReferencias();

    var meuDropzone = new Dropzone("#formulario" , {
        url:session.urlmiles + URL_IMPORTAR,
        paramName: "arquivo", 
        dictDefaultMessage: "Arraste seus arquivos para cá!",
        maxFilesize: 300, 
        accept: function(file, done) {
            var referencia = $("#referencia").val();
            if (!referencia) {
                bootbox.alert("Por favor, selecione uma Referência antes de enviar o arquivo.");
                done("O campo Referência é obrigatório.");
                // Remove o arquivo da fila, pois ele já foi rejeitado
                this.removeFile(file);
            } else {
                statusEnviar();
                done();
            }
        },
        init: function () {
            this.on("queuecomplete", function (file) {
                
            });
        },
        params:function(files, xhr, chunk){
            // Adiciona a referência aos parâmetros do upload
            if (this.options.params) {
                this.options.params.referencia = $("#referencia").val();
            } else {
                this.options.params = { referencia: $("#referencia").val() };
            }

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    quantidade_notas = parseInt(xhr.response);
                    iniciarSalvamento(); 
                }
            }
        }
    });

    function popularReferencias(){
        var select = $("#referencia");
        select.empty();
        select.append($('<option>', {
            value: '',
            text: 'Selecione...'
        }));

        var start_date = new Date(2025, 10, 1);
        var current = new Date();

        // Ajustamos para o dia 1 para evitar problemas em meses com 31 dias ao subtrair meses
        current.setDate(1);

        while (current >= start_date) {
            var month = current.getMonth() + 1;
            var year = current.getFullYear();
            var text = year + "/" + (month < 10 ? '0' : '') + month;
            var value = (month < 10 ? '0' : '') + month + "/" + year;

            select.append($('<option>', {
                value: value,
                text: text
            }));

            // A diferença crucial: agora SUBTRAÍMOS um mês a cada iteração
            current.setMonth(current.getMonth() - 1);
        }
    }

    function statusEnviar(){
        $("#status1").html('<img src="'+session.urlloading+'" width="25" height="25"/>');
    }

    function iniciarSalvamento(){
        if (quantidade_notas > 0){
            $("#status1").html('<img src="'+session.urlcurrenttheme+'check.gif" />');
            $("#status2").html('<img src="'+session.urlloading+'" width="25" height="25"/>');
            salvar();
        }
    }

    function salvar(){
        $.ajax({
            url:session.urlmiles + URL_IMPORTAR,
            data: {
                op:"salvar",
                indice: quantidade_notas,
                referencia: $("#referencia").val() // Enviando a referência também na operação de salvar
            },
            complete: function(ret){
                const res = parseInt(ret.responseText);
                switch (res) {
                    case 1:
                        quantidade_notas--;
                        if (quantidade_notas >= 1){
                            salvar();
                        }else{
                            $("#status2").html('<img src="'+session.urlcurrenttheme+'check.gif" />');
                            //parent.$("#enviar").button('reset');
                            //parent.$("#enviar").attr("class","btn btn-success");
                            //parent.$("#enviar").html("Enviado");ss
                        }
                    break;
                    case 2:
                        bootbox.alert({
                            title: "Mensagem",
                            message: "<div class='alert alert-danger'><b>Erro:</b> Arquivo de importação da NFSe não está no formato esperado.</div>"
                        });                        
                    break;
                }

                if (res != 1){
                    $("#status2").html('<img src="'+session.urlcurrenttheme+'erro.gif" width="25" />');
                }
            }
        });
    }
});