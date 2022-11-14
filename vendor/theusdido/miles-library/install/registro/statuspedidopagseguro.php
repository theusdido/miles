<?php
	$entidadeNome = PREFIXO . "ecommerce_statuspedido";
	
	inserirRegistro($conn,$entidadeNome,1,array("descricao","significado"),array("'Aguardando Pagamento'","'o comprador iniciou a transação, mas até o momento o PagSeguro não recebeu nenhuma informação sobre o pagamento.'"));
	inserirRegistro($conn,$entidadeNome,2,array("descricao","significado"),array("'Em Análise'","'o comprador optou por pagar com um cartão de crédito e o PagSeguro está analisando o risco da transação.'"));
	inserirRegistro($conn,$entidadeNome,3,array("descricao","significado"),array("'Paga'","'a transação foi paga pelo comprador e o PagSeguro já recebeu uma confirmação da instituição financeira responsável pelo processamento.'"));
	inserirRegistro($conn,$entidadeNome,4,array("descricao","significado"),array("'Disponível'","'a transação foi paga e chegou ao final de seu prazo de liberação sem ter sidoretornada e sem que haja nenhuma disputa aberta.'"));
	inserirRegistro($conn,$entidadeNome,5,array("descricao","significado"),array("'Em Disputa'","'o comprador, dentro do prazo de liberação da transação, abriu uma disputa.'"));
	inserirRegistro($conn,$entidadeNome,6,array("descricao","significado"),array("'Devolvida'","'o valor da transação foi devolvido para o comprador.'"));
	inserirRegistro($conn,$entidadeNome,7,array("descricao","significado"),array("'Cancelada'","'a transação foi cancelada sem ter sido finalizada.'"));