<?php
    
    $style                  = tdc::html('style');
    $style->type            = 'text/css';
    $style->add('
        .list-group a {
            display:flex;
        }
    ');
    $style->mostrar();
    
    $item_list_html_object  = 'a';
    $target                 = '_blank';

    // Funções do Sistema
    $aFuncoes               = tdc::html($item_list_html_object);
    $aFuncoes->href         = URL_SYSTEM . "funcoes.js";
    $aFuncoes->target       = $target;
    $aFuncoes->add('funcoes.js');

    // Validação dos campos do formulário
    $aValidar               = tdc::html($item_list_html_object);
    $aValidar->href         = URL_SYSTEM . "validar.js";
    $aValidar->target       = $target;
    $aValidar->add('validar.js');
    
    // Grade de Dados
    $aGrade                 = tdc::html($item_list_html_object);
    $aGrade->href           = URL_CLASS_TDC . "gradededados.class.js";
    $aGrade->target         = $target;
    $aGrade->add('gradededados.class.js');

    // Classe do formulário
    $aFormulario                = tdc::html($item_list_html_object);
    $aFormulario->href          = URL_CLASS_TDC . "formulario.class.js";
    $aFormulario->target        = $target;
    $aFormulario->add('formulario.class.js');

    // MDM Javascript Compile
    $aMDMJS                = tdc::html($item_list_html_object);
    $aMDMJS->href          = Asset::url('FILE_MDM_JS_COMPILE');
    $aMDMJS->target        = $target;
    $aMDMJS->add('mdm.js');    

    // Classe do formulário
    $aMenu                = tdc::html($item_list_html_object);
    $aMenu->href          = URL_CLASS_TDC . "menu.class.js";
    $aMenu->target        = $target;
    $aMenu->add('menu.class.js');

    // Lista
    $lista                         = tdc::o('listgroup');
    $lista->addItemList($aFuncoes);
    $lista->addItemList($aValidar);
    $lista->addItemList($aGrade);
    $lista->addItemList($aFormulario);
    $lista->addItemList($aMDMJS);
    $lista->addItemList($aMenu);
    $lista->mostrar();