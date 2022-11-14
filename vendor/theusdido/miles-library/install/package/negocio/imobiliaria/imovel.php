<?php
	// Setando variáveis
	$entidadeNome 		= "imobiliaria_imovel";
	$entidadeDescricao 	= "Imóvel";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
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

	// Atributos
	$filial 					= criarAtributo($conn,$entidadeID,"filial","Filial","int",0,0,4,0,installDependencia('empresa','system/'),0,"",1,0);
	$empreendimento 			= criarAtributo($conn,$entidadeID,"empreendimento","Empreendimento","int",0,1,4,0,installDependencia('imobiliaria_empreendimento','package/negocio/imobiliaria/empreendimento'),0,"",1,0);
	$administradoracondominio 	= criarAtributo($conn,$entidadeID,"administradoracondominio","Administradora Condominio","int",0,1,4,0,installDependencia('imobiliaria_administradoracondominio','package/negocio/imobiliaria/administradoracondominio'),0,"",1,0);		
	$tipoimovel 				= criarAtributo($conn,$entidadeID,"tipoimovel","Tipo","int",0,0,4,0,installDependencia('imobiliaria_tipoimovel','package/negocio/imobiliaria/tipoimovel'),0,"",1,0);	
	$tipopiso 					= criarAtributo($conn,$entidadeID,"tipopiso","Tipo de Piso","int",0,1,4,0,installDependencia('imobiliaria_tipopiso','package/negocio/imobiliaria/tipopiso'),0,"",1,0);	
	$mobiliado 					= criarAtributo($conn,$entidadeID,"mobiliado","Mobiliado ?","int",0,1,4,0,installDependencia('imobiliaria_mobiliado','package/negocio/imobiliaria/mobiliado'),0,"",1,0);
	$valor 						= criarAtributo($conn,$entidadeID,"valoraluguel","Valor","float",0,1,13);
	$descricaodestaque 			= criarAtributo($conn,$entidadeID,"descricaodestaque","Descrição Destaque","varchar",200,1,3,1);	
	$restricoesproprietario 	= criarAtributo($conn,$entidadeID,"restricoesproprietario","Restrição Proprietario","varchar",200,1,3);
	$latituelongitute 			= criarAtributo($conn,$entidadeID,"longitutelatitute","Latitute/Longitute","varchar",200,1,3);
	$finalidade 				= criarAtributo($conn,$entidadeID,"finalidade",array("Finalidade ?","Aluguel","Venda"),"tinyint",0,1,7);
	$lancamento 				= criarAtributo($conn,$entidadeID,"lancamento","Lançamento ?","tinyint",0,1,7);
	$ofertasemana 				= criarAtributo($conn,$entidadeID,"ofertasemana","Oferta da Semana ?","tinyint",0,1,7);

	// Capa
	$abaCapa 					= criarAba($conn,$entidadeID,"Capa",array($filial,$empreendimento,$administradoracondominio,$tipoimovel,$tipopiso,$mobiliado,$valor,$descricaodestaque,$restricoesproprietario,$latituelongitute,$finalidade,$lancamento,$ofertasemana));

	// Licenças
	$habitese 					= criarAtributo($conn,$entidadeID,"habitese","Habite-se","varchar",30,1,3);
	$matricula 					= criarAtributo($conn,$entidadeID,"matricula","Matrícula","varchar",20,1,3);	
	$abaLicenca 				= criarAba($conn,$entidadeID,"Licenças",array($habitese,$matricula));	

	// Box/Garagem
	$numerobox 					= criarAtributo($conn,$entidadeID,"numerobox","Número do Box","int",0,1,25);
	$matriculabox 				= criarAtributo($conn,$entidadeID,"matriculabox","Matrícula Box","varchar",20,1,3);			
	$abaBox 					= criarAba($conn,$entidadeID,"Box/Garagem",array($numerobox,$matriculabox));
	
	// Áreas
	$areatotal 					= criarAtributo($conn,$entidadeID,"areatotal","Área Total","float",0,1,26);	
	$areautil					= criarAtributo($conn,$entidadeID,"areautil","Área Útil","float",0,1,26);
	$areaconstruida 			= criarAtributo($conn,$entidadeID,"areaconstruida","Área Construida","float",0,1,26);
	$areaprivativa 				= criarAtributo($conn,$entidadeID,"areaprivativa","Área Privativa","float",0,1,26);	
	$abaAreas 					= criarAba($conn,$entidadeID,"Áreas",array($areatotal,$areautil,$areaconstruida,$areaprivativa));

	// Seguros
	$numeroapoliseseguro 		= criarAtributo($conn,$entidadeID,"numeroapoliseseguro","Número Apolise Seguro","varchar",200,1,3);
	$valorapolise 				= criarAtributo($conn,$entidadeID,"valorapolise","Valor da Apolise","float",0,1,13);
	$valorsegurovendaval 		= criarAtributo($conn,$entidadeID,"valorsegurovendaval","Valor Vendaval","float",0,1,13);
	$abaSeguros 				= criarAba($conn,$entidadeID,"Seguros",array($numeroapoliseseguro,$valorapolise,$valorsegurovendaval));
	
	// Faturas
	$numerofaturaenergia 		= criarAtributo($conn,$entidadeID,"numerofaturaenergia","Número Fatura Energia","varchar",200,1,3);
	$numeromedidoragua 			= criarAtributo($conn,$entidadeID,"numeromedidoragua","Número Medidor Aguá","varchar",200,1,3);
	$numerofaturaagua 			= criarAtributo($conn,$entidadeID,"numerofaturaenergia","Número Fatura Energia","varchar",200,1,3);
	$numeromedidorgas 			= criarAtributo($conn,$entidadeID,"numeromedidorgas","Número Medidor Gás","varchar",200,1,3);
	$abaFaturas 				= criarAba($conn,$entidadeID,"Faturas",array($numerofaturaenergia,$numeromedidoragua,$numerofaturaagua,$numeromedidorgas));
	
	// Agenciamento
	$percentualcomissaoaluguel 	= criarAtributo($conn,$entidadeID,"percentualcomissaoaluguel","% Comissáo Aluguel","int",0,1,26);
	$garantiaaluguel 			= criarAtributo($conn,$entidadeID,"mesesgarantiaaluguel","Meses de Garantia","int",0,1,25);
	$garantiatotal 				= criarAtributo($conn,$entidadeID,"garantiatotal","Garantia Total ?","int",0,1,7);
	$abaAgenciamento 			= criarAba($conn,$entidadeID,"Agenciamento",array($percentualcomissaoaluguel,$garantiaaluguel,$garantiatotal));

	// Criando Acesso
	$menu_webiste 				= addMenu($conn,'Geral','#','',0,0,'geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'geral-' . $entidadeNome,$entidadeID, 'cadastro');

	$iptuAba 					= installDependencia("imobiliaria_iptu",'package/negocio/imobiliaria/iptu');
	$unidadeAba 				= installDependencia("imobiliaria_unidadeimovel",'package/negocio/imobiliaria/unidadeimovel');
	$proprietariosAba 			= installDependencia("imobiliaria_imovelproprietario",'package/negocio/imobiliaria/imovelproprietario');
	$enderecosAba 				= installDependencia("imobiliaria_imovelendereco",'package/negocio/imobiliaria/imovelendereco');
	$fotoAba 					= installDependencia("imobiliaria_imovelfoto",'package/negocio/imobiliaria/imovelfoto');	