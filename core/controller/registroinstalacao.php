<?php
    // Usuários
    inserirRegistro($conn,getSystemPrefixo() . "usuario",1, array("nome","email","senha","permitirexclusao","permitirtrocarempresa",getSystemFKPrefixo() . "grupousuario","perfilusuario",getSystemFKPrefixo() . "perfil"), array("'Root'","'root'","'63a9f0ea7bb98050796b649e85481845'",1,1,1,0,0));

    // Menu
    inserirRegistro($conn,getSystemPrefixo() . "menu",1, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Administração'","'#'","''",0,1,"'adm'",0,1));
    $usuarioID = getEntidadeId("usuario",$conn);
    inserirRegistro($conn,getSystemPrefixo() . "menu",2, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Usuário'","'files/cadastro/".$usuarioID."/".USUARIO.".html'","''",1,1,"''",$usuarioID,1));
    $menuID = getEntidadeId("menu",$conn);
    inserirRegistro($conn,getSystemPrefixo() . "menu",3, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Menu'","'files/cadastro/".$menuID."/".MENU.".html'","''",1,2,"''",$menuID,1));
    $projetoID = getEntidadeId("projeto",$conn);
    inserirRegistro($conn,getSystemPrefixo() . "menu",4, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Projeto'","'files/cadastro/".$projetoID."/".PROJETO.".html'","''",1,3,"''",$projetoID,1));
    $empresaID = getEntidadeId("empresa",$conn);
    inserirRegistro($conn,getSystemPrefixo() . "menu",5, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Empresa'","'files/cadastro/".$empresaID."/".EMPRESA.".html'","''",1,4,"''",$empresaID,1));
    $avisoID = getEntidadeId("aviso",$conn);
    inserirRegistro($conn,getSystemPrefixo() . "menu",6, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Aviso'","'files/cadastro/".$avisoID."/".AVISO.".html'","''",1,5,"''",$avisoID,1));
    $grupousuarioID = getEntidadeId("grupousuario",$conn);
    inserirRegistro($conn,getSystemPrefixo() . "menu",7, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Grupo de Usuário'","'files/cadastro/".$grupousuarioID."/".USUARIOGRUPO.".html'","''",1,6,"''",$grupousuarioID,1));
    
    inserirRegistro($conn,getSystemPrefixo() . "menu",8, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Ticket'","'#'","''",0,2,"'ticket'",0,1));
    $ticketstatusID = getEntidadeId("ticketstatus",$conn);
    inserirRegistro($conn,getSystemPrefixo() . "menu",9, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","td_entidade","tipomenu"), array("'Status'","'files/cadastro/".$ticketstatusID."/".getSystemFKPrefixo()."ticketstatus.html'","''",8,1,"''",$ticketstatusID,1));
    $ticketprioridadeID = getEntidadeId("ticketprioridade",$conn);
    inserirRegistro($conn,getSystemPrefixo() . "menu",10, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Prioridade'","'files/cadastro/".$ticketprioridadeID."/".getSystemFKPrefixo()."ticketprioridade.html'","''",8,2,"''",$ticketprioridadeID,1));
    $tickettipoID = getEntidadeId("tickettipo",$conn);
    inserirRegistro($conn,getSystemPrefixo() . "menu",11, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Tipo'","'files/cadastro/".$tickettipoID."/".getSystemFKPrefixo()."tickettipo.html'","''",8,3,"''",$tickettipoID,1));
    $ticketID = getEntidadeId("ticket",$conn);
    inserirRegistro($conn,getSystemPrefixo() . "menu",12, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Ticket'","'files/cadastro/".$ticketID."/".getSystemFKPrefixo()."ticket.html'","''",8,4,"''",$ticketID,1));
    $ticketinteractionID = getEntidadeId("ticketinteraction",$conn);
    inserirRegistro($conn,getSystemPrefixo() . "menu",13, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Ticket Interação'","'files/cadastro/".$ticketinteractionID."/".getSystemFKPrefixo()."ticketinteraction.html'","''",8,5,"''",$ticketinteractionID,1));
    $ticketseguidoresID = getEntidadeId("ticketseguidores",$conn);
    inserirRegistro($conn,getSystemPrefixo() . "menu",14, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Seguidores'","'files/cadastro/".$ticketseguidoresID."/".getSystemFKPrefixo()."ticketseguidores.html'","''",8,6,"''",$ticketseguidoresID,1));

    // Menu Compilar
    inserirRegistro($conn,getSystemPrefixo() . "menu",15, array("descricao","link","target",getSystemFKPreFixo("pai"),"ordem","fixo","entidade","tipomenu"), array("'Compilar'","'index.php?controller=compilar'","''",1,7,"''",0,"'personalizado'"));

    // Grupo Usuário
    inserirRegistro($conn,getSystemPrefixo() . "grupousuario",1, array("descricao"), array("'Desenvolvimento'"));
    inserirRegistro($conn,getSystemPrefixo() . "grupousuario",2, array("descricao"), array("'Administrador'"));

    // Config
    inserirRegistro($conn,getSystemPrefixo() . "config",1, array(
        "urlupload",
        "urlrequisicoes",
        "urlsaveform",
        "urlloadform",
        "urluploadform",
        "urlpesquisafiltro",
        "urlenderecofiltro",
        "urlexcluirregistros",
        "urlinicializacao",
        "urlloading",
        "urlloadgradededados",
        "urlrelatorio",
        "urlmenu",
        "bancodados",
        "linguagemprogramacao",
        "pathfileupload",
        "pathfileuploadtemp",
        "testecharset",
        "tipogradedados"
    ), array(
        "'index.php?controller=upload'",
        "'index.php?controller=requisicoes'",
        "'index.php?controller=salvarform'",
        "'index.php?controller=loadform'",
        "'index.php?controller=upload'",
        "'index.php'",
        "'index.php'",
        "'index.php?controller=excluirregistros'",
        "'index.php?controller=inicializacao'",
        "'index.php?controller=loading'",
        "'index.php?controller=gradededados'",
        "'index.php?controller=relatorio'",
        "'index.php?controller=menu'",
        "'mysql'",
        "'php'",
        "'project/arquivos'",
        "'project/arquivos/temp'",
        "'á'",
        "'table'"
    ));

    // Status
    inserirRegistro($conn,getSystemPrefixo() . "status",1, array("descricao","classe"), array("'Ativo'","'td-status-ativo'"));
    inserirRegistro($conn,getSystemPrefixo() . "status",2, array("descricao","classe"), array("'Sucesso'","'td-status-sucesso'"));
    inserirRegistro($conn,getSystemPrefixo() . "status",3, array("descricao","classe"), array("'Alerta'","'td-status-alerta'"));
    inserirRegistro($conn,getSystemPrefixo() . "status",4, array("descricao","classe"), array("'Erro'","'td-status-erro'"));
    inserirRegistro($conn,getSystemPrefixo() . "status",5, array("descricao","classe"), array("'Informativo'","'td-status-info'"));			

    // Tipo Aviso
    inserirRegistro($conn,getSystemPrefixo() . "tipoaviso",1, array("descricao"), array("'Sucesso'"));
    inserirRegistro($conn,getSystemPrefixo() . "tipoaviso",2, array("descricao"), array("'Alerta'"));
    inserirRegistro($conn,getSystemPrefixo() . "tipoaviso",3, array("descricao"), array("'Erro'"));
    inserirRegistro($conn,getSystemPrefixo() . "tipoaviso",4, array("descricao"), array("'Informativo'"));

    // Tipo de Conexão com Banco de Dados
    inserirRegistro($conn,getSystemPrefixo() . "typeconnectiondatabase",1, array("nome","descricao"), array("'desenv'","'Desenvolvimento'"));
    inserirRegistro($conn,getSystemPrefixo() . "typeconnectiondatabase",2, array("nome","descricao"), array("'teste'","'Testes'"));
    inserirRegistro($conn,getSystemPrefixo() . "typeconnectiondatabase",3, array("nome","descricao"), array("'homolog'","'Homologação'"));
    inserirRegistro($conn,getSystemPrefixo() . "typeconnectiondatabase",4, array("nome","descricao"), array("'producao'","'Produção'"));

    // Banco de Dados
    inserirRegistro($conn,getSystemPrefixo() . "database",1, array("nome"), array("'mysql'"));
    inserirRegistro($conn,getSystemPrefixo() . "database",2, array("nome"), array("'cache'"));
    
    // Aba - Projeto
    inserirRegistro($conn,getSystemPrefixo() . "abas",2, array("entidade","descricao","atributos"), array(getEntidadeId("abas",$conn),"'Aba'",getAtributoId(getEntidadeId("td_abas",$conn),"nome",$conn)));

    // Local para CharSet
    inserirRegistro($conn,getSystemPrefixo() . "charset",1, array("local","charset"), array("'Página principal (index)'","'D'"));
    inserirRegistro($conn,getSystemPrefixo() . "charset",2, array("local","charset"), array("'Grade de Dados (load)'","'E'"));
    inserirRegistro($conn,getSystemPrefixo() . "charset",3, array("local","charset"), array("'Formulário (load)'","'N'"));
    inserirRegistro($conn,getSystemPrefixo() . "charset",4, array("local","charset"), array("'Classe Campos'","'N'"));
    inserirRegistro($conn,getSystemPrefixo() . "charset",5, array("local","charset"), array("'MDM Embituido PHP'","'E'"));
    inserirRegistro($conn,getSystemPrefixo() . "charset",6, array("local","charset"), array("'MDM Salvar Form com Submit'","'E'"));
    inserirRegistro($conn,getSystemPrefixo() . "charset",7, array("local","charset"), array("'Gerar HTML no CRUD'","'D'"));
    inserirRegistro($conn,getSystemPrefixo() . "charset",8, array("local","charset"), array("'Javascript Embutido no PHP'","'D'"));
    