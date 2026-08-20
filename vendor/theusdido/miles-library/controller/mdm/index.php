<?php
    $_mdm_page = tdc::o('pagina');
    $_mdm_page->setTitle('MDM - Miles Database Management');
    $_mdm_page->addBody(getURLContent(URL_API . '?controller=page&page=mdm/home'));
    $_mdm_page->addScript(Config::getJsConfig());
    $_mdm_page->mostrar();