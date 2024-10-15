<?php
	$entidadeNome = getSystemPREFIXO() . "ecommerce_tipooperacaoestoque";
	inserirRegistro($conn,$entidadeNome,1, array('descricao','tipo'), array("'Venda'",1));
	inserirRegistro($conn,$entidadeNome,2, array('descricao','tipo'), array("'Devolução'",0));
    inserirRegistro($conn,$entidadeNome,3, array('descricao','tipo'), array("'Estravio'",1));
    inserirRegistro($conn,$entidadeNome,4, array('descricao','tipo'), array("'Retirada'",1));
    inserirRegistro($conn,$entidadeNome,5, array('descricao','tipo'), array("'Entrega'",0));