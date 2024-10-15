{
    let _atributo       = 0;
    let ck_editor_list  = [];

    $(document).ready(function(){
        resetTelaTraducao();
        _atributo = $('#atributo').val();
        carregarCampos();
        addBotaoSalvar();
    });

    function carregarCampos(){
        $.ajax({
            url:session.urlmiles,
            dataType:'json',
            data:{
                controller:'idioma/campos',
                atributo:_atributo,
                registro:$('#registro').val()            
            },
            complete:function(res){
                res.responseJSON.forEach(function(e,i){
                    let traducao                    = e;
                    let lingua                      = traducao.lingua;
                    let texto                       = traducao.texto;
                    let _tipohtml_campos_traducao   = td_atributo[_atributo].tipohtml;
                    let fieldset_selector           = '.modal-traduzir-campo .modal-body form fieldset';
                    let campo                       = null;

                    switch(_tipohtml_campos_traducao){
                        case '21':
                            let id_campo    = 'campo-ck-' + lingua.id;
                            campo           = $('<div id="'+id_campo+'" class="ckeditor">');
                            input           = $('<input type="hidden" class="campo-traducao" data-lingua="' + lingua.id + '" />');
                            campo.append(input);


                            let panel       = $('<div class="panel panel-default">');
                            let panel_head  = $('<div class="panel-heading">'+lingua.nome+'</div>');
                            let panel_body  = $('<div class="panel-body">');

                            panel_body.append(campo);

                            panel.append(panel_head);
                            panel.append(panel_body);

                            $(fieldset_selector).append(panel);

                            ck_editor_list.push({
                                campo:id_campo,
                                valor:texto,
                                lingua:lingua.id,
                                instancia:CKEDITOR.appendTo( id_campo , {}, texto )
                            });                        
                            
                        break;
                        case '3':
                        default:
                            let form_group  = $('<div class="form-group">');
                            let label       = $('<label for="" class="control-label">' + lingua.nome + '</label>');
                            campo           = $('<input type="text" class="form-control campo-traducao" data-lingua="' + lingua.id + '" />');
                            form_group.append(label);
                            campo.val(texto);
                            form_group.append(campo);
                            $(fieldset_selector).append(form_group);
                    }

                });
            }
        });
    }

    function addBotaoSalvar(){
        let btn_salvar = $('<button class="btn btn-success" type="button">Salvar</button>');
        btn_salvar.click(function(){
            let traducoes   = [];

            if (ck_editor_list.length > 0){
                ck_editor_list.forEach(function(e){
                    traducoes.push({
                        lingua:e.lingua,
                        traducao:e.instancia.getData()
                    });
                });
            }else{
                $('.campos-traducao .campo-traducao').each(function(){
                    let lingua      = $(this).data('lingua');
                    let traducao    = $(this).val();
                    traducoes.push({
                        lingua:lingua,
                        traducao:traducao
                    });
                });
            }

            console.log(traducoes);
            return;
            $.ajax({
                url:session.urlmiles,
                data:{
                    controller:'idioma/traducao',
                    _op:'add',
                    traducoes:traducoes,
                    atributo:$('#atributo').val(),
                    registro:$('#registro').val()
                },
                complete:function(){

                }
            });
        });
        $('.modal-traduzir-campo .modal-footer').append(btn_salvar);
    }
}

function resetTelaTraducao(){
    $('.campos-traducao , .modal-traduzir-campo .modal-footer').html('');
}