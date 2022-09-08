<?php
    $op = tdc::r('op');
	
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
            $query = $conn->exec("INSERT INTO ".PROJETO." (id,nome) VALUES (1,'".utf8_decode($_POST["nome"])."');");
            if ($query){
                echo 1;
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
				"'projects/".CURRENT_PROJECT_ID."/arquivos'",
				"'projects/".CURRENT_PROJECT_ID."/arquivos/temp'",
				"'á'",
				"'table'",
				2
			));

			// Status
			inserirRegistro($conn,getSystemPREFIXO() . "status",1, array("descricao","classe"), array("'Ativo'","'td-status-ativo'"));
			inserirRegistro($conn,getSystemPREFIXO() . "status",2, array("descricao","classe"), array("'Sucesso'","'td-status-sucesso'"));
			inserirRegistro($conn,getSystemPREFIXO() . "status",3, array("descricao","classe"), array("'Alerta'","'td-status-alerta'"));
			inserirRegistro($conn,getSystemPREFIXO() . "status",4, array("descricao","classe"), array("'Erro'","'td-status-erro'"));
			inserirRegistro($conn,getSystemPREFIXO() . "status",5, array("descricao","classe"), array("'Informativo'","'td-status-info'"));

			// Tipo Aviso
			inserirRegistro($conn,getSystemPREFIXO() . "tipoaviso",1, array("descricao"), array("'Sucesso'"));
			inserirRegistro($conn,getSystemPREFIXO() . "tipoaviso",2, array("descricao"), array("'Alerta'"));
			inserirRegistro($conn,getSystemPREFIXO() . "tipoaviso",3, array("descricao"), array("'Erro'"));
			inserirRegistro($conn,getSystemPREFIXO() . "tipoaviso",4, array("descricao"), array("'Informativo'"));

			// Tipo de Conexão com Banco de Dados
			inserirRegistro($conn,getSystemPREFIXO() . "typeconnectiondatabase",1, array("nome,descricao"), array("'desenv'","'Desenvolvimento'"));
			inserirRegistro($conn,getSystemPREFIXO() . "typeconnectiondatabase",2, array("nome,descricao"), array("'teste'","'Testes'"));
			inserirRegistro($conn,getSystemPREFIXO() . "typeconnectiondatabase",3, array("nome,descricao"), array("'homolog'","'Homologação'"));
			inserirRegistro($conn,getSystemPREFIXO() . "typeconnectiondatabase",4, array("nome,descricao"), array("'producao'","'Produção'"));

			// Banco de Dados
			inserirRegistro($conn,getSystemPREFIXO() . "database",1, array("nome","descricao"), array("'mysql'","'MySQL'"));
			inserirRegistro($conn,getSystemPREFIXO() . "database",2, array("nome","descricao"), array("'cache'","'CACHÉ'"));
			
			// Aba - Projeto
			inserirRegistro($conn,ABAS,2, array("entidade","descricao","atributos"), array(getEntidadeId("abas",$conn),"'Aba'",getAtributoId(getEntidadeId("abas",$conn),"nome",$conn)));

			// Local para CharSet
			inserirRegistro($conn,getSystemPREFIXO() . "charset",1, array("local","charset"), array("'Página principal (index)'","'D'"));
			inserirRegistro($conn,getSystemPREFIXO() . "charset",2, array("local","charset"), array("'Grade de Dados (load)'","'E'"));
			inserirRegistro($conn,getSystemPREFIXO() . "charset",3, array("local","charset"), array("'Formulário (load)'","'N'"));
			inserirRegistro($conn,getSystemPREFIXO() . "charset",4, array("local","charset"), array("'Classe Campos'","'N'"));
			inserirRegistro($conn,getSystemPREFIXO() . "charset",5, array("local","charset"), array("'MDM Embituido PHP'","'E'"));
			inserirRegistro($conn,getSystemPREFIXO() . "charset",6, array("local","charset"), array("'MDM Salvar Form com Submit'","'E'"));
			inserirRegistro($conn,getSystemPREFIXO() . "charset",7, array("local","charset"), array("'Gerar HTML no CRUD'","'D'"));
			inserirRegistro($conn,getSystemPREFIXO() . "charset",8, array("local","charset"), array("'Javascript Embutido no PHP'","'D'"));
			inserirRegistro($conn,getSystemPREFIXO() . "charset",9, array("local","charset"), array("'Javascript mdm.js'","'E'"));
			inserirRegistro($conn,getSystemPREFIXO() . "charset",10, array("local","charset"), array("'Javascript mdm.js ( Relacionamento ) em funcoes.php'","'E'"));
			
			//  Tipo de Ticket
			inserirRegistro($conn,getSystemPREFIXO() . "tickettipo",1, array("descricao"), array("'Alterar"));
			inserirRegistro($conn,getSystemPREFIXO() . "tickettipo",2, array("descricao"), array("'Corrigir"));
			inserirRegistro($conn,getSystemPREFIXO() . "tickettipo",3, array("descricao"), array("'Desenvolver"));
			inserirRegistro($conn,getSystemPREFIXO() . "tickettipo",4, array("descricao"), array("'Estudar"));
			inserirRegistro($conn,getSystemPREFIXO() . "tickettipo",5, array("descricao"), array("'Propôr"));
			inserirRegistro($conn,getSystemPREFIXO() . "tickettipo",6, array("descricao"), array("'Solicitar"));
			inserirRegistro($conn,getSystemPREFIXO() . "tickettipo",7, array("descricao"), array("'Verificar"));
			inserirRegistro($conn,getSystemPREFIXO() . "tickettipo",8, array("descricao"), array("'Contactar"));

			// Status de Ticket
			inserirRegistro($conn,getSystemPREFIXO() . "ticketstatus",1, array("descricao"), array("'Abrir'"));
			inserirRegistro($conn,getSystemPREFIXO() . "ticketstatus",2, array("descricao"), array("'Aguardar'"));
			inserirRegistro($conn,getSystemPREFIXO() . "ticketstatus",3, array("descricao"), array("'Interagir'"));
			inserirRegistro($conn,getSystemPREFIXO() . "ticketstatus",4, array("descricao"), array("'Finalizar'"));
			inserirRegistro($conn,getSystemPREFIXO() . "ticketstatus",5, array("descricao"), array("'Reabrir'"));

			// Prioridade de Ticket
			inserirRegistro($conn,getSystemPREFIXO() . "ticketprioridade",1, array("descricao"), array("'Alta'"));
			inserirRegistro($conn,getSystemPREFIXO() . "ticketprioridade",2, array("descricao"), array("'Média'"));
			inserirRegistro($conn,getSystemPREFIXO() . "ticketprioridade",3, array("descricao"), array("'Baixa'"));

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

