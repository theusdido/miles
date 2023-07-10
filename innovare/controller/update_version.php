<?php

    // Atualiza a cardinalidade dos relacionamentos
    include PATH_TDC . 'tdrelacionamento.class.php';
    tdRelacionamento::updateCardinalidade();

    // Campos antigos da LISTA
    $conn->exec('ALTER TABLE '.LISTA.' CHANGE entidade_pai entidade_pai INT NULL;');
    $conn->exec('ALTER TABLE '.LISTA.' CHANGE entidade_filho entidade_filho INT NULL;');
    $conn->exec('ALTER TABLE '.LISTA.' CHANGE reg_pai reg_pai INT NULL;');
    $conn->exec('ALTER TABLE '.LISTA.' CHANGE reg_filho reg_filho INT NULL;');

    Transacao::Fechar();