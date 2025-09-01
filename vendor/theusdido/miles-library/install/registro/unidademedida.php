<?php
    $entidadeNome = PREFIXO . "ecommerce_unidademedida";
    inserirRegistro($conn,$entidadeNome,1, array("descricao","sigla"), array("'Quilograma'","'Kg'"));
    inserirRegistro($conn,$entidadeNome,2, array("descricao","sigla"), array("'Miligrama'","'mg'"));