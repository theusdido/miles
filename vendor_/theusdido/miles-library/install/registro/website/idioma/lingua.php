<?php
	$entidadeNome = getSystemPREFIXO() . "website_idioma_lingua";
	inserirRegistro($conn,$entidadeNome,1, array('nome'), array("'Português'"));
	inserirRegistro($conn,$entidadeNome,2, array('nome'), array("'Inglês'"));
    inserirRegistro($conn,$entidadeNome,3, array('nome'), array("'Espanhol'"));