<?php
    $project_name           = tdc::r("projeto", 'Miles');
    $dir_miles              = tdc::r("diretorio", 'miles/');
    $prefix_system          = tdc::r("prefixo", 'td');    

    $configs = array(
        'PROJETO_DESC='.$project_name,
        'PROJETO_FOLDER='.$dir_miles,
        'DATABASE_PADRAO=desenv',
        'PATH_LOGO_PADRAO=tema/padrao/logo.png',
        'PATH_IMG_RODAPE_PADRAO=tema/padrao/rodape.png',
        'PREFIXO='.$prefix_system,
        'CURRENT_PROJECT=1',
        'CURRENT_DATABASE=desenv',
        'CODIGOCLIENTE=0',
        'THEME=desktop'
    );
    $fpCurrentConfig    = fopen($path_current_config . 'current_config.inc',"w");
    foreach($configs as $c){
        fwrite($fpCurrentConfig,trim($c)."\n");
    }
    fclose($fpCurrentConfig);