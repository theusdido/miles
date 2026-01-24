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

        var start_date = new Date(2025, 10, 1); // Mês 10 é Novembro
        var current_date = new Date(2026, 0, 14); // Mock: 14 de Janeiro de 2026

        var current = new Date(start_date);

        while (current <= current_date) {
            var month = current.getMonth() + 1;
            var year = current.getFullYear();
            var text = year + "/" + (month < 10 ? '0' : '') + month;
            var value = (month < 10 ? '0' : '') + month + "/" + year;
            select.append($('<option>', {
                value: value,
                text: text
            }));
            current.setMonth(current.getMonth() + 1);
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
                if (parseInt(ret.responseText) == 1){
                    quantidade_notas--;
                    if (quantidade_notas >= 1){
                        salvar();
                    }else{
                        $("#status2").html('<img src="'+session.urlcurrenttheme+'check.gif" />');
                        //parent.$("#enviar").button('reset');
                        //parent.$("#enviar").attr("class","btn btn-success");
                        //parent.$("#enviar").html("Enviado");ss
                    }
                }else{
                    $("#status2").html('<img src="'+session.urlcurrenttheme+'erro.gif" width="25" />');                              
                }
            }
        });
    }
});