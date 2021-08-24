<?php
    // Setando variáveis
    $entidadeNome = "ecommerce_visitantes";
    $entidadeDescricao = "Visitantes";

    // Criando Entidade
    $entidadeID = criarEntidade(
        $conn,
        $entidadeNome,
        $entidadeDescricao,
        $ncolunas=1,
        $exibirmenuadministracao = 0,
        $exibircabecalho = 0,
        $campodescchave = 0,
        $atributogeneralizacao = 0,
        $exibirlegenda = 0,
        $criarprojeto = 0,
        $criarempresa = 0,
        $criarauth = 0,
        $registrounico = 0
    );

    // Criando Atributos
    $cliente        = criarAtributo($conn,$entidadeID,"cliente","Cliente","int",0,0,22,1,getEntidadeId("ecommerce_cliente"));
    $data           = criarAtributo($conn,$entidadeID,"data","Data","date",0,0,11,1);
    $hora           = criarAtributo($conn,$entidadeID,"hora","Hora","time",0,0,28,1);
    $ip             = criarAtributo($conn,$entidadeID,"ip","IP","varchar",25,0,3,0);
    $navegador      = criarAtributo($conn,$entidadeID,"navegador","Navegador","varchar",20,0,3,0);
    $sessao         = criarAtributo($conn,$entidadeID,"sessao","Sessão","char",32,0,3,0);

    // Criando Acesso
    $menu_webiste = addMenu($conn,'E-Commerce','#','','','','ecommerce');

    // Adicionando Menu
    addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".PREFIXO.$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');