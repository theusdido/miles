<?php
    #var_dump($_GET['hash']);

    $_path_view_alteracaosenha = PATH_MVC_VIEW . 'alteracaosenha.php';
    if (file_exists($_path_view_alteracaosenha)){
        include $_path_view_alteracaosenha;
    }else{
        showMessage('Arquivo de não encontrado!');
    }