<?php
	$entidadeNome = getSystemPREFIXO() . "periodicidade";
	inserirRegistro($conn,$entidadeNome,1, array("descricao"), array("'Diário'"));
	inserirRegistro($conn,$entidadeNome,2, array("descricao"), array("'Semanal'"));
	inserirRegistro($conn,$entidadeNome,3, array("descricao"), array("'Quinzenal'"));
	inserirRegistro($conn,$entidadeNome,4, array("descricao"), array("'Mensal'"));
    inserirRegistro($conn,$entidadeNome,5, array("descricao"), array("'Bimestral'"));
    inserirRegistro($conn,$entidadeNome,6, array("descricao"), array("'Trimensal'"));
    inserirRegistro($conn,$entidadeNome,7, array("descricao"), array("'Semestral'"));
    inserirRegistro($conn,$entidadeNome,8, array("descricao"), array("'Anual'"));
    inserirRegistro($conn,$entidadeNome,9, array("descricao"), array("'Bianual'"));