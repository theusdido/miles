<?php
    
    $nota = tdc::p('td_erp_nfse_nota', tdc::r('nota'));
    $nota->inativo = 1;
    if ($nota->armazenar()){
        tdc::wj(array(
            "status" => "success",
            "message" => "Nota excluída com sucesso."
        ));
    }else{
        tdc::wj(array(
            "status" => "error",
            "message" => "Erro ao excluir a nota. Tente novamente."
        ));
    }