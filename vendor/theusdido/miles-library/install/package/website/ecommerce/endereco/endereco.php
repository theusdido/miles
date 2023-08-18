<?php
	// Endereço
	$entidadeNomeEndereco 		= "ecommerce_endereco";
	$entidadeDescricaoEndereco 	= "Endereço";
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNomeEndereco,
		$entidadeDescricaoEndereco,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	$paisID 	= installDependencia('ecommerce_pais','package/website/ecommerce/endereco/pais');
	$ufID 		= installDependencia('ecommerce_uf','package/website/ecommerce/endereco/uf');
	$cidadeID 	= installDependencia('ecommerce_cidade','package/website/ecommerce/endereco/cidade');
	$bairroID 	= installDependencia('ecommerce_bairro','package/website/ecommerce/endereco/bairro');	

	criarAtributo($conn,$entidadeID,"bairro","Bairro",'int',0,1,22,0,$bairroID,0,"");
	criarAtributo($conn,$entidadeID,"cidade","Cidade",'int',0,1,22,0,$cidadeID,0,"");
	criarAtributo($conn,$entidadeID,"uf","Estado ( UF )",'int',0,1,4,0,$ufID,0,"");
	criarAtributo($conn,$entidadeID,"pais","País",'int',0,1,4,0,$paisID,0,"");

	criarAtributo($conn,$entidadeID,"logradouro","Logradouro","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$entidadeID,"numero","Número","varchar","5",1,3,1,0,0,"");
	criarAtributo($conn,$entidadeID,"complemento","Complemento","varchar","200",1,3,1,0,0,"");
	criarAtributo($conn,$entidadeID,"cep","CEP","varchar","10",0,9,1,0,0,"");
	criarAtributo($conn,$entidadeID,"bairro_nome","Bairro ( Nome )","varchar",200,1,16,0,0,0,"");
	criarAtributo($conn,$entidadeID,"cidade_nome","Cidade ( Nome )","varchar",200,1,16,0,0,0,"");
	criarAtributo($conn,$entidadeID,"uf_sigla","UF ( Sigla )","char",2,1,16,0,0,0,"");
	criarAtributo($conn,$entidadeID,"uf_nome","UF ( Nome )","varchar",200,1,16,0,0,0,"");
	criarAtributo($conn,$entidadeID,"pais_nome","País ( Nome )","varchar",200,1,16,0,0,0,"");
	criarAtributo($conn,$entidadeID,"pais_sigla","País ( Sigla )","char",2,1,16,0,0,0,"");