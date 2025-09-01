<?php
	$entidadeNome = getSystemPREFIXO() . "geral_periodicidade";
	inserirRegistro($conn,$entidadeNome,1, array("descricao","dias"), array("'Diário'",1));
	inserirRegistro($conn,$entidadeNome,2, array("descricao","dias"), array("'Semanal'",7));
	inserirRegistro($conn,$entidadeNome,3, array("descricao","dias"), array("'Quinzenal'",15));
	inserirRegistro($conn,$entidadeNome,4, array("descricao","dias"), array("'Mensal'",30));
    inserirRegistro($conn,$entidadeNome,5, array("descricao","dias"), array("'Bimestral'",60));
    inserirRegistro($conn,$entidadeNome,6, array("descricao","dias"), array("'Trimensal'",90));
    inserirRegistro($conn,$entidadeNome,7, array("descricao","dias"), array("'Semestral'",180));
    inserirRegistro($conn,$entidadeNome,8, array("descricao","dias"), array("'Anual'",365));
    inserirRegistro($conn,$entidadeNome,9, array("descricao","dias"), array("'Bianual'",730));