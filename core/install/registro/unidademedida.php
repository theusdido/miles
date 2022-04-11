<?php
    $entidadeNome = PREFIXO . "ecommerce_unidademedida";
    inserirRegistro($conn,$entidadeNome,1, array("descricao","sigla"), array("'Unidade'","'UN'"));
    inserirRegistro($conn,$entidadeNome,2, array("descricao","sigla"), array("'Quilograma'","'Kg'"));
    inserirRegistro($conn,$entidadeNome,3, array("descricao","sigla"), array("'Miligrama'","'mg'"));
    inserirRegistro($conn,$entidadeNome,4, array("descricao","sigla"), array("'Peça'","'PC'"));
