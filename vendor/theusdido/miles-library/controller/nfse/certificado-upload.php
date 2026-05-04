<?php
    require PATH_CLASS . 'nfse/snnfse.class.php';

    // Exemplo de como receber o arquivo e a senha via POST
    $arquivo_tmp = $_FILES['arquivo']['tmp_name'];
    $senha_pfx = tdc::r('senha_dz'); // A senha original do arquivo .pfx    

    $nfse = new snNFSE();
    tdc::wj($nfse->createPEMFiles($arquivo_tmp, $senha_pfx));