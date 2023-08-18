<?php
	$entidadeNome = getSystemPREFIXO() . "website_geral_redesocial";
	inserirRegistro($conn,$entidadeNome,1, array('descricao'), array("'Facebook'"));
	inserirRegistro($conn,$entidadeNome,2, array('descricao'), array("'Instagram'"));
    inserirRegistro($conn,$entidadeNome,3, array('descricao'), array("'Twitter'"));
    inserirRegistro($conn,$entidadeNome,4, array('descricao'), array("'Linkedin'"));
    inserirRegistro($conn,$entidadeNome,5, array('descricao'), array("'Youtube'"));