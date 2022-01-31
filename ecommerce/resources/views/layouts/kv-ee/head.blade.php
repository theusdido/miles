<?php
	/*	
	// Id da Entidade ( Website Configurações )
	$sqlIDEntidade = "SELECT id FROM td_entidade WHERE nome = 'td_websiteconfiguracoes' LIMIT 1";
	$queryIDEntidade = $conn->query($sqlIDEntidade);
	if ($queryIDEntidade->rowCount()>0){
		$linhaIDEntidade = $queryIDEntidade->fetch();
		$entidadeIDWC = $linhaIDEntidade["id"];

		$sql = "SELECT * FROM td_websiteconfiguracoes WHERE id=1";
		$query = $conn->query($sql);
		$linha = $query->fetch();
		
		
		// Projeto
		$sqlProjeto = "SELECT * FROM td_projeto WHERE id=1";
		$queryProjeto = $conn->query($sqlProjeto);
		$linhaProjeto = $queryProjeto->fetch();
		
		$metadescricao 	= utf8_encode($linha["metatagdescription"]);
		$metatagauthor 	= utf8_encode($linha["metatagauthor"]);
		$nomeProjeto 	= utf8_encode($linhaProjeto["nome"]);
	}else{
		$metadescricao = $metatagauthor = $nomeProjeto = $entidadeIDWC = "";
	}	
	*/
?>

<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
<link rel="preconnect" href="https://components.mywebsitebuilder.com/" crossorigin="" />
<link rel="preconnect" href="https://in-app.mywebsitebuilder.com/" crossorigin="">
<meta name="viewport" content="width=device-width,maximum-scale=1,minimum-scale=1,initial-scale=1,viewport-fit=cover" />
<meta charset="utf-8" />
<meta http-equiv="content-language" content="pt" />
<meta name="description" content="Granú Empório Natural - Loja De Produtos Naturais e Suplementos em Criciúma SC. Compra agora seu produto natural." />
<title>Home - Granú Produtos Naturais</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="https://components.mywebsitebuilder.com/fonts/font-awesome.css" rel="stylesheet">
<meta property="og:title" content="Home - Granú Produtos Naturais" />
<meta property="og:description" content="Granú Empório Natural - Loja De Produtos Naturais e Suplementos em Criciúma SC. Compra agora seu produto natural." />
<meta property="og:type" content="website" />
<meta property="twitter:title" content="Home - Granú Produtos Naturais" />
<meta property="twitter:description" content="Granú Empório Natural - Loja De Produtos Naturais e Suplementos em Criciúma SC. Compra agora seu produto natural." />
<link prefetch="" prerender="/blog/publicacao" />
<link prefetch="" prerender="/blog" />
<link prefetch="" prerender="/venda/product" />
<link prefetch="" prerender="/venda" />
<link prefetch="" prerender="/contate-nos" />
<link prefetch="" prerender="/localizacao" />
<link prefetch="" prerender="/conheca-a-loja" />
<link href="https://fonts.googleapis.com/css?display=swap&amp;family=Montserrat:400|Arvo:400" rel="stylesheet" />
<link href="/kv-ee/css/style.css" rel="stylesheet" />
<link href="/kv-ee/css/custom.css" rel="stylesheet" />
<script type="application/javascript" src="/kv-ee/js/home.b6354b9a.js"></script>