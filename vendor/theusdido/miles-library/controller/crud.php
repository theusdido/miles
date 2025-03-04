<?php
    $entidade       = tdc::e(tdc::r('entidade'));
    $pagina_crud    = tdc::o('pagina');
    $script         = tdc::html('script');

    $selector_crud  = 'div-crud';
    $div_crud       = tdc::html('div');
    $div_crud->id   = $selector_crud;
    $pagina_crud->add($div_crud);

    $script->add('
        let selector_editar = "#'.$selector_crud.'";
        carregar(session.folderprojectfiles + "files/cadastro/'.$entidade->id.'/'.$entidade->nome.'.html",selector_editar,function(){
            carregarScriptCRUD(\'editarformulario\','.$entidade->id.','.$_id.',selector_editar,{
                is_registrounico:true,
                is_init:false
            });
        });
    ');
    $pagina_crud->addScript($script);
    $pagina_crud->mostrar();