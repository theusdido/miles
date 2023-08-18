<?php

    $path_current_arquivos      = $_path_project_install.'arquivos' . DIRECTORY_SEPARATOR;
    $path_current_build         = $_path_project_install.'build' . DIRECTORY_SEPARATOR;
    $path_current_classe        = $_path_project_install.'classes' . DIRECTORY_SEPARATOR;
    $path_current_config        = $_path_project_install.'config' . DIRECTORY_SEPARATOR;
    $path_current_controller    = $_path_project_install.'controller' . DIRECTORY_SEPARATOR;
    $path_current_files         = $_path_project_install.'files' . DIRECTORY_SEPARATOR;
    $path_current_imagens       = $_path_project_install.'imagens' . DIRECTORY_SEPARATOR;
    $path_current_tema          = $_path_project_install.'tema' . DIRECTORY_SEPARATOR;
    $path_current_view          = $_path_project_install.'view' . DIRECTORY_SEPARATOR;
    $path_current_website       = $_path_project_install.'website' . DIRECTORY_SEPARATOR;
    $path_current_log           = $_path_project_install.'log' . DIRECTORY_SEPARATOR;
    $path_current_page          = $_path_project_install.'page' . DIRECTORY_SEPARATOR;

    $path_current_arquivos_temp         = $path_current_arquivos   .'temp' . DIRECTORY_SEPARATOR;
    $path_current_build_js              = $path_current_build      .'js' . DIRECTORY_SEPARATOR;
    $path_current_build_css             = $path_current_build      .'css' . DIRECTORY_SEPARATOR;
    $path_current_files_cadastro        = $path_current_files      .'cadastro' . DIRECTORY_SEPARATOR;
    $path_current_files_consulta        = $path_current_files      .'consulta' . DIRECTORY_SEPARATOR;
    $path_current_files_movimentacao    = $path_current_files      .'movimentacao'. DIRECTORY_SEPARATOR;
    $path_current_files_relatorio       = $path_current_files      .'relatorio' . DIRECTORY_SEPARATOR;
    $path_current_tema_desktop          = $path_current_tema       .'desktop' . DIRECTORY_SEPARATOR;
    $path_current_tema_padrao           = $path_current_tema       .'padrao' . DIRECTORY_SEPARATOR;

    tdFile::mkdir($_path_project_install);
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
    tdFile::mkdir($path_current_page);

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
    $path   = PATH_MILES_LIBRARY . 'tema'.DIRECTORY_SEPARATOR.'padrao'.DIRECTORY_SEPARATOR;
    $files  = scandir($path);

    foreach ($files as $file) {
        if ($file != '.' && $file != '..'){
            tdFile::move($path . $file , $path_current_tema_padrao . $file);
        }
    }

    $path   = PATH_MILES_LIBRARY .  'tema'. DIRECTORY_SEPARATOR.'desktop'.DIRECTORY_SEPARATOR;
    $files  = scandir($path);
    foreach ($files as $file){
        if ($file != '.' && $file != '..'){
            tdFile::move($path . $file , $path_current_tema_desktop . $file);
        }
    }

    tdFile::add($path_current_build_js . FILE_MDM_JS_COMPILE);