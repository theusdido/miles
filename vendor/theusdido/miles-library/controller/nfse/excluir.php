<?php

    $nota_id = tdc::r('nota');

    $sql = "
        DELETE FROM td_erp_nfse_nota WHERE id = {$nota_id};
        DELETE FROM td_erp_nfse_intermediario WHERE nfse = {$nota_id};
        DELETE FROM td_erp_nfse_item WHERE nfse = {$nota_id};
        DELETE FROM td_erp_nfse_parcelas WHERE nfse = {$nota_id};
        DELETE FROM td_erp_nfse_servico WHERE nfse = {$nota_id};
        DELETE FROM td_erp_nfse_prestador WHERE nfse = {$nota_id};
        DELETE FROM td_erp_nfse_tomador WHERE nfse = {$nota_id};
        DELETE FROM td_erp_nfse_transportadora WHERE nfse = {$nota_id};
    ";

    if ($conn->exec($sql)){
        echo json_encode([
            'status' => 'success',
            'message' => 'Nota fiscal excluída com sucesso.'
        ]);
    }else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Erro ao excluir a nota fiscal.'
        ]);
    }