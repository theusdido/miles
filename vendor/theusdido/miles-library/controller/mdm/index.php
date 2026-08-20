<?php
    $_mdm_page = tdc::o('pagina');
    $_mdm_page->setTitle('MDM - Miles Database Management');    
    var_dump(getURLContent(URL_API . '?controller=page&page=mdm/home'));
    #var_dump(getURL(URL_API . '?controller=page&page=mdm/home'));
    #var_dump(getURL('../index.php' . '?controller=page&page=mdm/home'));
    #var_dump(getPathContent(PATH_API . '?controller=page&page=mdm/home'));
    #var_dump(getPathContent('/var/www/miles/index.php' . '?controller=page&page=mdm/home'));
    #$_mdm_page->addBody(getURL(URL_API . '?controller=page&page=mdm/home'));
    #$_mdm_page->addBody(getPathContent(PATH_API . '?controller=page&page=mdm/home'));
    exit;
    $_mdm_page->addBody(getURLContent(URL_API . '?controller=page&page=mdm/home'));
    $_mdm_page->addScript(Config::getJsConfig());
    $_mdm_page->mostrar();    