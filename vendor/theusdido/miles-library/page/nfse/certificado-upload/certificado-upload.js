$(function(){
    const URL_IMPORTAR = '?controller=nfse/certificado-upload';

    $("#btn-show-password").click(function(){
        let input = $("#senha");
        let icon = $(this).find("i");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
            icon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            input.attr("type", "password");
            icon.removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    var meuDropzone = new Dropzone("#formulario" , {
        url:session.urlmiles + URL_IMPORTAR,
        paramName: "arquivo", 
        dictDefaultMessage: "Arraste o certificado (.pfx) para cá!",
        maxFilesize: 50,
        acceptedFiles: ".pfx",
        accept: function(file, done) {
            let senha = $("#senha").val();
            if (!senha) {
                done('error-senha-vazia');                
            } else {
                $('#senha_dz').val(senha);
                done();
            }
        },
        error: function(file, error_message) {
            if (error_message === 'error-senha-vazia'){
                setStatus('error', "Por favor, digite a Senha do Certificado antes de enviar o arquivo.");
            }else if (!file.accepted){
                let error_msg = "<div class='alert alert-danger'><b>Error: </b>Certificado Digital não aceito.</div>";
                error_msg += `<p><small>O arquivo <b>${file.name}</b> não está no formato <code>.pfx</code>, padrão para certificados digitais.</small></p>`;

                setStatus('error', error_msg);                
            }
            this.removeFile(file);
        },
        params:function(files, xhr, chunk){
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    $("#status").html('<img src="'+session.urlcurrenttheme+'check.gif" />');
                    try {
                        let res = JSON.parse(xhr.responseText);
                        setStatus(res.status, res.message);
                    } catch (e) {
                        setStatus('error', 'Erro ao atualizar o Certificado Digital. Por favor, entre em contato com o suporte.');
                    }
                }
            }
        }
    });    
});

function setStatus(status, mensagem = '', callback_function = null){
    let icone = status == 'success' ? 'check.gif' : 'erro.gif';
    $("#status").html('<img src="'+session.urlcurrenttheme+icone+'" width="25" />');

    bootbox.alert({
        title: "Mensagem",
        message: mensagem,
        callback: callback_function
    });    
}