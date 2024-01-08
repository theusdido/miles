<?php

    // Atualiza a cardinalidade dos relacionamentos
    include PATH_TDC . 'tdrelacionamento.class.php';
    tdRelacionamento::updateCardinalidade();

    // Campos antigos da LISTA
    $conn->exec('ALTER TABLE '.LISTA.' CHANGE entidadepai entidadepai INT NULL;');
    $conn->exec('ALTER TABLE '.LISTA.' CHANGE entidadefilho entidadefilho INT NULL;');
    $conn->exec('ALTER TABLE '.LISTA.' CHANGE regpai regpai INT NULL;');
    $conn->exec('ALTER TABLE '.LISTA.' CHANGE regfilho regfilho INT NULL;');

    Transacao::Fechar();