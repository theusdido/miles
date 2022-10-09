<?php
    include_once 'core/classes/tdc/tdfile.class.php';

    $path_root_project          = 'project';
    $path_current_arquivos      = $path_root_project.'arquivos/';    
    $path_current_build         = $path_root_project.'build/';
    $path_current_classe        = $path_root_project.'classes/';
    $path_current_config        = $path_root_project.'config/';    
    $path_current_controller    = $path_root_project.'controller/';
    $path_current_files         = $path_root_project.'files/';
    $path_current_imagens       = $path_root_project.'imagens/';
    $path_current_tema          = $path_root_project.'tema/';
    $path_current_view          = $path_root_project.'view/';
    $path_current_website       = $path_root_project.'website/';
    $path_current_log           = $path_root_project.'log/';

    $path_current_arquivos_temp         = $path_current_arquivos   .'temp/';
    $path_current_build_js              = $path_current_build      .'js/';
    $path_current_build_css             = $path_current_build      .'css/';
    $path_current_files_cadastro        = $path_current_files      .'cadastro/';
    $path_current_files_consulta        = $path_current_files      .'consulta/';
    $path_current_files_movimentacao    = $path_current_files      .'movimentacao/';
    $path_current_files_relatorio       = $path_current_files      .'relatorio/';
    $path_current_tema_desktop          = $path_current_tema       .'desktop/';
    $path_current_tema_padrao           = $path_current_tema       .'padrao/';

    tdFile::mkdir($path_root_project,true);
    tdFile::mkdir($path_current_arquivos,true);
    tdFile::mkdir($path_current_build,true);
    tdFile::mkdir($path_current_classe,true);
    tdFile::mkdir($path_current_config,true);
    tdFile::mkdir($path_current_controller,true);
    tdFile::mkdir($path_current_files,true);
    tdFile::mkdir($path_current_imagens,true);
    tdFile::mkdir($path_current_tema,true);
    tdFile::mkdir($path_current_view,true);
    tdFile::mkdir($path_current_website,true);
    tdFile::mkdir($path_current_log,true);

    tdFile::mkdir($path_current_arquivos_temp,true);
    tdFile::mkdir($path_current_build_js,true);
    tdFile::mkdir($path_current_build_css,true);
    tdFile::mkdir($path_current_files_cadastro,true);
    tdFile::mkdir($path_current_files_consulta,true);
    tdFile::mkdir($path_current_files_movimentacao,true);
    tdFile::mkdir($path_current_files_relatorio,true);
    tdFile::mkdir($path_current_tema_desktop,true);
    tdFile::mkdir($path_current_tema_padrao,true);


    // Move a pasta tema do core   
    $path   =  'core/tema/padrao/';
    $files  = scandir($path);

    foreach ($files as $file) {
        if ($file != '.' && $file != '..'){
            copy($path . $file , $path_current_tema_padrao . $file);
        }
    }
    
    $path   =  'core/tema/desktop/';
    $files = scandir($path);
    foreach ($files as $file){
        if ($file != '.' && $file != '..'){
            copy($path . $file , $path_current_tema_desktop . $file);
        }
    }