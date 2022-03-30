 <?php
 
	// Nome da Entidade
	$entidade = getSystemPREFIXO()  . "atributo";

	// Criando Entidade
	criarTabelaDicionario($conn,$entidade);
	
	// Criando Atributos
	criarCampoDicionario($conn,$entidade,"entidade","int",0,0);
	criarCampoDicionario($conn,$entidade,"nome","varchar",120,0);
	criarCampoDicionario($conn,$entidade,"descricao","varchar",120,0);
	criarCampoDicionario($conn,$entidade,"tipo","varchar",50,0);	
	criarCampoDicionario($conn,$entidade,"tamanho","int",0,1);
	criarCampoDicionario($conn,$entidade,"nulo","tinyint",0,0);
	criarCampoDicionario($conn,$entidade,"omissao","varchar",30,1);
	criarCampoDicionario($conn,$entidade,"collection","varchar",35,1);
	criarCampoDicionario($conn,$entidade,"atributos","varchar",30,1);
	criarCampoDicionario($conn,$entidade,"indice","char",2,1);
	criarCampoDicionario($conn,$entidade,"autoincrement","tinyint",0,1);	
	criarCampoDicionario($conn,$entidade,"comentario","text",0,1);
	criarCampoDicionario($conn,$entidade,"exibirgradededados","tinyint",0,1);
	criarCampoDicionario($conn,$entidade,"chaveestrangeira","int",0,1);
	criarCampoDicionario($conn,$entidade,"tipohtml","int",0,1);
	criarCampoDicionario($conn,$entidade,"dataretroativa","smallint",0,1);
	criarCampoDicionario($conn,$entidade,"ordem","float",0,1);
 	criarCampoDicionario($conn,$entidade,"inicializacao","varchar",200,1);
	criarCampoDicionario($conn,$entidade,"readonly","boolean",0,1);
	criarCampoDicionario($conn,$entidade,"exibirpesquisa","boolean",0,1);
	criarCampoDicionario($conn,$entidade,"tipoinicializacao","tinyint",0,1);
	criarCampoDicionario($conn,$entidade,"atributodependencia","int",0,1);
	criarCampoDicionario($conn,$entidade,"labelzerocheckbox","varchar",35,1);
	criarCampoDicionario($conn,$entidade,"labelumcheckbox","varchar",35,1);
	criarCampoDicionario($conn,$entidade,"legenda","varchar",200,1);
	criarCampoDicionario($conn,$entidade,"desabilitar","boolean",0,1);
	criarCampoDicionario($conn,$entidade,"criarsomatoriogradededados","boolean",0,1);
	criarCampoDicionario($conn,$entidade,"naoexibircampo","boolean",0,1);