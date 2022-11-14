<?php

    $path_current_arquivos      = PATH_PROJECT.'arquivos/';    
    $path_current_build         = PATH_PROJECT.'build/';
    $path_current_classe        = PATH_PROJECT.'classes/';
    $path_current_config        = PATH_PROJECT.'config/';    
    $path_current_controller    = PATH_PROJECT.'controller/';
    $path_current_files         = PATH_PROJECT.'files/';
    $path_current_imagens       = PATH_PROJECT.'imagens/';
    $path_current_tema          = PATH_PROJECT.'tema/';
    $path_current_view          = PATH_PROJECT.'view/';
    $path_current_website       = PATH_PROJECT.'website/';
    $path_current_log           = PATH_PROJECT.'log/';

    $path_current_arquivos_temp         = $path_current_arquivos   .'temp/';
    $path_current_build_js              = $path_current_build      .'js/';
    $path_current_build_css             = $path_current_build      .'css/';
    $path_current_files_cadastro        = $path_current_files      .'cadastro/';
    $path_current_files_consulta        = $path_current_files      .'consulta/';
    $path_current_files_movimentacao    = $path_current_files      .'movimentacao/';
    $path_current_files_relatorio       = $path_current_files      .'relatorio/';
    $path_current_tema_desktop          = $path_current_tema       .'desktop/';
    $path_current_tema_padrao           = $path_current_tema       .'padrao/';

    tdFile::mkdir(PATH_PROJECT);
    tdFile::mkdir($path_current_arquivos);
    tdFile::mkdir($path_current_build);
    tdFile::mkdir($path_current_classe);
    tdFile::mkdir($path_current_config);
    tdFile::mkdir($path_current_controller);
    tdFile::mkdir($path_current_files);
    tdFile::mkdir($path_current_imagens);
    tdFile::mkdir($path_current_tema);
    tdFile::mkdir($path_current_view);
    tdFile::mkdir($path_current_website);
    tdFile::mkdir($path_current_log);

    tdFile::mkdir($path_current_arquivos_temp);
    tdFile::mkdir($path_current_build_js);
    tdFile::mkdir($path_current_build_css);
    tdFile::mkdir($path_current_files_cadastro);
    tdFile::mkdir($path_current_files_consulta);
    tdFile::mkdir($path_current_files_movimentacao);
    tdFile::mkdir($path_current_files_relatorio);
    tdFile::mkdir($path_current_tema_desktop);
    tdFile::mkdir($path_current_tema_padrao);

    // Move a pasta tema   
    $path   = PATH_MILES_LIBRARY . 'tema/padrao/';
    $files  = scandir($path);

    foreach ($files as $file) {
        if ($file != '.' && $file != '..'){
            tdFile::move($path . $file , $path_current_tema_padrao . $file);
        }
    }

    $path   = PATH_MILES_LIBRARY .  'tema/desktop/';
    $files  = scandir($path);
    foreach ($files as $file){
        if ($file != '.' && $file != '..'){
            tdFile::move($path . $file , $path_current_tema_desktop . $file);
        }
    }