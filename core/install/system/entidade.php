 <?php

	// Nome da Entidade
	$entidade = getSystemPREFIXO()  . "entidade";

	// Criando Entidade
	criarTabelaDicionario($conn,$entidade);

	// Criando Atributos
	criarCampoDicionario($conn,$entidade,"nome","varchar",120,0);
	criarCampoDicionario($conn,$entidade,"descricao","varchar",120,0);
	criarCampoDicionario($conn,$entidade,"exibirmenuadministracao","tinyint",0,0);
	criarCampoDicionario($conn,$entidade,"exibircabecalho","tinyint",0,0);
	criarCampoDicionario($conn,$entidade,"pai","int",0,1);
	criarCampoDicionario($conn,$entidade,"ncolunas","tinyint",0,0);
	criarCampoDicionario($conn,$entidade,"campodescchave","int",0,1);
	criarCampoDicionario($conn,$entidade,"atributogeneralizacao","int",0,0);
	criarCampoDicionario($conn,$entidade,"exibirlegenda","smallint",0,0);
	criarCampoDicionario($conn,$entidade,"onloadjs","text",0,1);
	criarCampoDicionario($conn,$entidade,"beforesavejs","text",0,1);
	criarCampoDicionario($conn,$entidade,"tipopesquisa","smallint",0,1);
	criarCampoDicionario($conn,$entidade,"htmlpagefile","blob",0,1);
	criarCampoDicionario($conn,$entidade,"registrounico","tinyint",0,1);
	criarCampoDicionario($conn,$entidade,"carregarlibjavascript","tinyint",0,1);
	criarCampoDicionario($conn,$entidade,"pacote","varchar",50,1);
	criarCampoDicionario($conn,$entidade,"btnimportarcsv","boolean",false,1,7);
	criarCampoDicionario($conn,$entidade,"btnexportarcsv","boolean",false,1,7);
	criarCampoDicionario($conn,$entidade,"btnimprimirtodosregistroshtml","boolean",false,1,7);
	criarCampoDicionario($conn,$entidade,"btnimprimirtodosregistrospdf","boolean",false,1,7);
	criarCampoDicionario($conn,$entidade,"tipoaba","varchar",5,1,7);
