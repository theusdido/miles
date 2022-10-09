<?php

	$install 	= tdc::ru('td_instalacao');
    $op 		= tdc::r('op');

	if (!$conn || $conn == 'NULL'){
		$conn = Conexao::getConnection(
			$_SESSION["db_type"],
			$_SESSION["db_host"],
			$_SESSION["db_base"],
			$_SESSION["db_user"],
			$_SESSION["db_password"],
			$_SESSION["db_port"]
		);
	}
    switch($op){
        case 'instalar':
            $query = $conn->query("UPDATE ".INSTALACAO." SET sistemainstalado = 1 WHERE id = 1;");
            if (!$query){
                foreach ($conn->errorInfo() as $erro){
                    echo $erro . "</br>";
                }
            }

			include 'core/controller/install/criarestruturapastas.php';
			include 'core/controller/install/criarmysqlini.php';
			include 'core/controller/install/criarcurrentconfig.php';
            echo 1;
        break;
        case 'instrucao':
            include 'core/install/' . tdc::r("instrucao");
            echo 1;
        break;
        case 'projeto':
			$projeto_nome = tdc::r('nome');
			if ($projeto_nome != ''){
				if ($install->sistemainstalado){
					$sql 	= "UPDATE " . PROJETO . " SET nome = '{$projeto_nome}' WHERE id = 1;";
				}else{
					$sql	= "INSERT INTO ".PROJETO." (id,nome) VALUES (1,'{$projeto_nome}');";
				}
				$query 	= $conn->exec($sql);
				if ($query){
					echo 1;
				}		
			}
        break;
        case 'inserirregistros':

			// Usuários
			inserirRegistro($conn,USUARIO,1, array("nome","email","login","senha","permitirexclusao","permitirtrocarempresa","grupousuario","perfilusuario","perfil"), array("'ROOT'","'root@localhost'","'root'","'63a9f0ea7bb98050796b649e85481845'",1,1,1,0,"null"),true);

			// Menu
			inserirRegistro($conn,MENU,1, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Administração'","'#'","''",0,1,"'adm'",0,"'cadastro'"));
			$usuarioID = getEntidadeId("usuario",$conn);
			inserirRegistro($conn,MENU,2, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Usuário'","'files/cadastro/".$usuarioID."/".getSystemPREFIXO()."usuario.html'","''",1,1,"''",$usuarioID,"'cadastro'"));
			$menuID = getEntidadeId("menu",$conn);
			inserirRegistro($conn,MENU,3, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Menu'","'files/cadastro/".$menuID."/".getSystemPREFIXO()."menu.html'","''",1,2,"''",$menuID,"'cadastro'"));
			$projetoID = getEntidadeId("projeto",$conn);
			inserirRegistro($conn,MENU,4, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Projeto'","'files/cadastro/".$projetoID."/".getSystemPREFIXO()."projeto.html'","''",1,3,"''",$projetoID,"'cadastro'"));
			$empresaID = getEntidadeId("empresa",$conn);
			inserirRegistro($conn,MENU,5, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Empresa'","'files/cadastro/".$empresaID."/".getSystemPREFIXO()."empresa.html'","''",1,4,"''",$empresaID,"'cadastro'"));
			$avisoID = getEntidadeId("aviso",$conn);
			inserirRegistro($conn,MENU,6, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Aviso'","'files/cadastro/".$avisoID."/".getSystemPREFIXO()."aviso.html'","''",1,5,"''",$avisoID,"'cadastro'"));
			$grupousuarioID = getEntidadeId("grupousuario",$conn);
			inserirRegistro($conn,MENU,7, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Grupo de Usuário'","'files/cadastro/".$grupousuarioID."/".getSystemPREFIXO()."grupousuario.html'","''",1,6,"''",$grupousuarioID,"'cadastro'"));
			
			inserirRegistro($conn,MENU,8, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Ticket'","'#'","''",0,2,"'ticket'",0,"'cadastro'"));
			$ticketstatusID = getEntidadeId("ticketstatus",$conn);
			inserirRegistro($conn,MENU,9, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Status'","'files/cadastro/".$ticketstatusID."/".getSystemPREFIXO()."ticketstatus.html'","''",8,1,"''",$ticketstatusID,"'cadastro'"));
			$ticketprioridadeID = getEntidadeId("ticketprioridade",$conn);
			inserirRegistro($conn,MENU,10, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Prioridade'","'files/cadastro/".$ticketprioridadeID."/".getSystemPREFIXO()."ticketprioridade.html'","''",8,2,"''",$ticketprioridadeID,"'cadastro'"));
			$tickettipoID = getEntidadeId("tickettipo",$conn);
			inserirRegistro($conn,MENU,11, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Tipo'","'files/cadastro/".$tickettipoID."/".getSystemPREFIXO()."tickettipo.html'","''",8,3,"''",$tickettipoID,"'cadastro'"));
			$ticketID = getEntidadeId("ticket",$conn);
			inserirRegistro($conn,MENU,12, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Ticket'","'files/cadastro/".$ticketID."/".getSystemPREFIXO()."ticket.html'","''",8,4,"''",$ticketID,"'cadastro'"));
			$ticketinteractionID = getEntidadeId("ticketinteraction",$conn);
			inserirRegistro($conn,MENU,13, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Ticket Interação'","'files/cadastro/".$ticketinteractionID."/".getSystemPREFIXO()."ticketinteraction.html'","''",8,5,"''",$ticketinteractionID,"'cadastro'"));
			$ticketseguidoresID = getEntidadeId("ticketseguidores",$conn);
			inserirRegistro($conn,MENU,14, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Seguidores'","'files/cadastro/".$ticketseguidoresID."/".getSystemPREFIXO()."ticketseguidores.html'","''",8,6,"''",$ticketseguidoresID,"'cadastro'"));

			// Menu Compilar
			inserirRegistro($conn,MENU,15, array("descricao","link","target","pai","ordem","fixo","entidade","tipomenu"), array("'Compilar'","'index.php?controller=compilar'","''",1,7,"''",0,"'personalizado'"));

			// Grupo Usuário
			inserirRegistro($conn,USUARIOGRUPO,1, array("descricao"), array("'Desenvolvimento'"));
			inserirRegistro($conn,USUARIOGRUPO,2, array("descricao"), array("'Administrador'"));

			// Config
			inserirRegistro($conn,CONFIG,1, array(
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
				"tipogradedados",
				"casasdecimais"
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
				"'table'",
				2
			));

			// Status
			inserirRegistro($conn,STATUS,1, array("descricao","classe"), array("'Ativo'","'td-status-ativo'"));
			inserirRegistro($conn,STATUS,2, array("descricao","classe"), array("'Sucesso'","'td-status-sucesso'"));
			inserirRegistro($conn,STATUS,3, array("descricao","classe"), array("'Alerta'","'td-status-alerta'"));
			inserirRegistro($conn,STATUS,4, array("descricao","classe"), array("'Erro'","'td-status-erro'"));
			inserirRegistro($conn,STATUS,5, array("descricao","classe"), array("'Informativo'","'td-status-info'"));

			// Tipo Aviso
			inserirRegistro($conn,TIPOAVISO,1, array("descricao"), array("'Sucesso'"));
			inserirRegistro($conn,TIPOAVISO,2, array("descricao"), array("'Alerta'"));
			inserirRegistro($conn,TIPOAVISO,3, array("descricao"), array("'Erro'"));
			inserirRegistro($conn,TIPOAVISO,4, array("descricao"), array("'Informativo'"));

			// Tipo de Conexão com Banco de Dados
			inserirRegistro($conn,TYPECONNECTIONDATABASE,1, array("nome,descricao"), array("'desenv'","'Desenvolvimento'"));
			inserirRegistro($conn,TYPECONNECTIONDATABASE,2, array("nome,descricao"), array("'teste'","'Testes'"));
			inserirRegistro($conn,TYPECONNECTIONDATABASE,3, array("nome,descricao"), array("'homolog'","'Homologação'"));
			inserirRegistro($conn,TYPECONNECTIONDATABASE,4, array("nome,descricao"), array("'producao'","'Produção'"));

			// Banco de Dados
			inserirRegistro($conn,DATABASE,1, array("nome","descricao"), array("'mysql'","'MySQL'"));
			inserirRegistro($conn,DATABASE,2, array("nome","descricao"), array("'cache'","'CACHÉ'"));
			
			// Aba - Projeto
			inserirRegistro($conn,ABAS,2, array("entidade","descricao","atributos"), array(getEntidadeId("abas",$conn),"'Aba'",getAtributoId(getEntidadeId("abas",$conn),"nome",$conn)));

			// Local para CharSet
			inserirRegistro($conn,CHARSET,1, array("local","charset"), array("'Página principal (index)'","'N'"));
			inserirRegistro($conn,CHARSET,2, array("local","charset"), array("'Grade de Dados (load)'","'N'"));
			inserirRegistro($conn,CHARSET,3, array("local","charset"), array("'Formulário (load)'","'N'"));
			inserirRegistro($conn,CHARSET,4, array("local","charset"), array("'Classe Campos'","'N'"));
			inserirRegistro($conn,CHARSET,5, array("local","charset"), array("'MDM Embituido PHP'","'N'"));
			inserirRegistro($conn,CHARSET,6, array("local","charset"), array("'MDM Salvar Form com Submit'","'N'"));
			inserirRegistro($conn,CHARSET,7, array("local","charset"), array("'Gerar HTML no CRUD'","'N'"));
			inserirRegistro($conn,CHARSET,8, array("local","charset"), array("'Javascript Embutido no PHP'","'N'"));
			inserirRegistro($conn,CHARSET,9, array("local","charset"), array("'Javascript mdm.js'","'N'"));
			inserirRegistro($conn,CHARSET,10, array("local","charset"), array("'Javascript mdm.js ( Relacionamento ) em funcoes.php'","'N'"));
			inserirRegistro($conn,CHARSET,11, array("local","charset"), array("'Campo descrição da classe Menu ( menu.class.php - E )'","'N'"));
			
			//  Tipo de Ticket
			inserirRegistro($conn,TICKETTIPO,1, array("descricao"), array("'Alterar"));
			inserirRegistro($conn,TICKETTIPO,2, array("descricao"), array("'Corrigir"));
			inserirRegistro($conn,TICKETTIPO,3, array("descricao"), array("'Desenvolver"));
			inserirRegistro($conn,TICKETTIPO,4, array("descricao"), array("'Estudar"));
			inserirRegistro($conn,TICKETTIPO,5, array("descricao"), array("'Propôr"));
			inserirRegistro($conn,TICKETTIPO,6, array("descricao"), array("'Solicitar"));
			inserirRegistro($conn,TICKETTIPO,7, array("descricao"), array("'Verificar"));
			inserirRegistro($conn,TICKETTIPO,8, array("descricao"), array("'Contactar"));

			// Status de Ticket
			inserirRegistro($conn,TICKETSTATUS,1, array("descricao"), array("'Abrir'"));
			inserirRegistro($conn,TICKETSTATUS,2, array("descricao"), array("'Aguardar'"));
			inserirRegistro($conn,TICKETSTATUS,3, array("descricao"), array("'Interagir'"));
			inserirRegistro($conn,TICKETSTATUS,4, array("descricao"), array("'Finalizar'"));
			inserirRegistro($conn,TICKETSTATUS,5, array("descricao"), array("'Reabrir'"));

			// Prioridade de Ticket
			inserirRegistro($conn,TICKETPRIORIDADE,1, array("descricao"), array("'Alta'"));
			inserirRegistro($conn,TICKETPRIORIDADE,2, array("descricao"), array("'Média'"));
			inserirRegistro($conn,TICKETPRIORIDADE,3, array("descricao"), array("'Baixa'"));

        break;

        case 'arquivos':
            /*
			tdInstall::$projeto = CURRENT_PROJECT_ID;
			tdInstall::criarArquivosConfiguracao(array(
				"PROJECT_DESC" => '',
				"CURRENT_DATABASE" => ''
			));
            */
        break;
		case 'versao':
			include 'core/controller/install/version.php';
		break;
    }

