<meta charset="UTF-8">
<?php
	$conn = Transacao::Get();
	/*
		Usuários Padrão
		Verifica se apenas 1 usuário padrão
	*/

	$sql = "SELECT 1 FROM td_usuario WHERE email='theusdido@hotmail.com'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 2){
		echo 'Usuário Principal não passou no teste';
		return false;
	}	

	/*
		Menus
		Verifica se todos os menus estão presentes
	*/

	$sql = "SELECT 1 FROM td_menu WHERE descricao='".utf8charset('Administração')."'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Administração não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_menu WHERE descricao='".utf8charset('Usuário')."'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Usuário não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_menu WHERE descricao='Projeto'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Projeto não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_menu WHERE descricao='Empresa'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Empresa não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_menu WHERE descricao='Aviso'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Aviso não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_menu WHERE descricao='".utf8charset('Grupo de Usuário')."'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Grupo de Usuário não passou no teste';
		return false;
	}
			
	$sql = "SELECT 1 FROM td_menu WHERE descricao='Ticket' AND link = '#'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Ticket não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_menu WHERE descricao='Status'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Ticket Status não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_menu WHERE descricao='Prioridade'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Ticket Prioridade não passou no teste';
		return false;
	}
	
	$sql = "SELECT 1 FROM td_menu WHERE descricao='Tipo'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Ticket Tipo não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_menu WHERE descricao='".utf8charset('Ticket')."' AND link = '#'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Ticket não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_menu WHERE descricao='".utf8charset('Ticket')."' AND link like '%ticket.htm%'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Ticket não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_menu WHERE descricao='".utf8charset('Ticket Interação')."'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Ticket Interação não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_menu WHERE descricao='Seguidores'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Menu Ticket Seguidores não passou no teste';
		return false;
	}

	/*
		Grupo de Usuário
		Verifica se todos os grupos de usuários estão presentes
	*/
	
	$sql = "SELECT 1 FROM td_grupousuario WHERE descricao='Desenvolvimento'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Grupo de Usuário: Desenvolvimento não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_grupousuario WHERE descricao='Administrador'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Grupo de Usuário: Administrador não passou no teste';
		return false;
	}

	/*
		Entidades do Sistema
		Verifica se as entidades do padrão do sistema
	*/

	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE UPPER(TABLE_NAME) = UPPER('td_usuario') AND UPPER(TABLE_SCHEMA) = UPPER('".SCHEMA."')";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Entidade: td_usuario não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE UPPER(TABLE_NAME) = UPPER('td_menu') AND UPPER(TABLE_SCHEMA) = UPPER('".SCHEMA."')";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Entidade: td_menu não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE UPPER(TABLE_NAME) = UPPER('td_projeto') AND UPPER(TABLE_SCHEMA) = UPPER('".SCHEMA."')";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Entidade: td_projeto não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE UPPER(TABLE_NAME) = UPPER('td_empresa') AND UPPER(TABLE_SCHEMA) = UPPER('".SCHEMA."')";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Entidade: td_empresa não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE UPPER(TABLE_NAME) = UPPER('td_grupousuario') AND UPPER(TABLE_SCHEMA) = UPPER('".SCHEMA."')";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Entidade: td_grupousuario não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_entidade WHERE nome='td_usuario'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Entidade: td_usuario não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_entidade WHERE nome='td_menu'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Entidade: td_menu não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_entidade WHERE nome='td_projeto'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Entidade: td_projeto não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_entidade WHERE nome='td_empresa'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Entidade: td_empresa não passou no teste';
		return false;
	}

	$sql = "SELECT 1 FROM td_entidade WHERE nome='td_grupousuario'";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Entidade: td_grupousuario não passou no teste';
		return false;
	}

	/*
		Entidade Config
		Verifica a entidade config e seus atributos
	*/
	$sql = "SELECT  1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'urlupload'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: urlupload não existe';
		return false;
	}
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'urlrequisicoes'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: urlrequisicoes não existe';
		return false;
	}
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'urlsaveform'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: urlsaveform não existe';
		return false;
	}
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'urlloadform'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: urlloadform não existe';
		return false;
	}
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'urlpesquisafiltro'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: urlpesquisafiltro não existe';
		return false;
	}
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'urlenderecofiltro'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: urlenderecofiltro não existe';
		return false;
	}
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'urlexcluirregistros'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: urlexcluirregistros não existe';
		return false;
	}
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'urlinicializacao'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: urlinicializacao não existe';
		return false;
	}
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'urlloading'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: urlloading não existe';
		return false;
	}
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'urlloadgradededados'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: urlloadgradededados não existe';
		return false;
	}
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'urlrelatorio'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: urlrelatorio não existe';
		return false;
	}
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'urlmenu'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: urlmenu não existe';
		return false;
	}	
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'bancodados'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: bancodados não existe';
		return false;
	}
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'td_config' AND COLUMN_NAME = 'linguagemprogramacao'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Atributo: linguagemprogramacao não existe';
		return false;
	}

	$sql = "SELECT * FROM td_config";
	$query = $conn->query($sql);
	$erro = 0;
	while ($linha = $query->fetch()){
		if ($linha["urlupload"] == "") {
			$erro = 1;
			echo 'Atributo urlupload não pode ser vazio';
			break;
		}
		if ($linha["urlrequisicoes"] == "") {
			$erro = 2;
			echo 'Atributo urlrequisicoes não pode ser vazio';
			break;
		}
		if ($linha["urlsaveform"] == "") {
			$erro = 3;
			echo 'Atributo urlsaveform não pode ser vazio';
			break;
		}
		if ($linha["urlloadform"] == "") {
			$erro = 4;
			echo 'Atributo urlloadform não pode ser vazio';
			break;
		}
		if ($linha["urlpesquisafiltro"] == "") {
			$erro = 5;
			echo 'Atributo urlpesquisafiltro não pode ser vazio';
			break;
		}		
		if ($linha["urlenderecofiltro"] == "") {
			$erro = 6;
			echo 'Atributo urlenderecofiltro não pode ser vazio';
			break;
		}
		if ($linha["urlexcluirregistros"] == "") {
			$erro = 7;
			echo 'Atributo urlexcluirregistros não pode ser vazio';
			break;
		}
		if ($linha["urlinicializacao"] == "") {
			$erro = 8;
			echo 'Atributo urlinicializacao não pode ser vazio';
			break;
		}
		if ($linha["urlloading"] == "") {
			$erro = 9;
			echo 'Atributo urlloading não pode ser vazio';
			break;
		}
		if ($linha["urlloadgradededados"] == "") {
			$erro = 10;
			echo 'Atributo urlloadgradededados não pode ser vazio';
			break;
		}
		if ($linha["urlrelatorio"] == "") {
			$erro = 11;
			echo 'Atributo urlrelatorio não pode ser vazio';
			break;
		}
		if ($linha["urlmenu"] == "") {
			$erro = 11;
			echo 'Atributo urlmenu não pode ser vazio';
			break;
		}		
		if ($linha["bancodados"] == "") {
			$erro = 12;
			echo 'Atributo bancodados não pode ser vazio';
			break;
		}		
		if ($linha["linguagemprogramacao"] == "") {
			$erro = 13;
			echo 'Atributo linguagemprogramacao não pode ser vazio';
			break;
		}
	}
	if ($erro > 0) return false;

	/*
		Entidade Status
		Verifica a entidade status e seus atributos
	*/
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE UPPER(TABLE_NAME) = UPPER('td_status') AND UPPER(TABLE_SCHEMA) = UPPER('".SCHEMA."')";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Entidade: td_status não existe';
		return false;
	}

	$sql = "SELECT 1 FROM td_status WHERE classe = 'td-status-ativo'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Valor do Atributo (classe) de td_status: td-status-ativo não existe';
		return false;
	}
	$sql = "SELECT 1 FROM td_status WHERE classe = 'td-status-sucesso'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Valor do Atributo (classe) de td_status: td-status-sucesso não existe';
		return false;
	}
	$sql = "SELECT 1 FROM td_status WHERE classe = 'td-status-alerta'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Valor do Atributo (classe) de td_status: td-status-alerta não existe';
		return false;
	}
	$sql = "SELECT 1 FROM td_status WHERE classe = 'td-status-erro'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Valor do Atributo (classe) de td_status: td-status-erro não existe';
		return false;
	}
	$sql = "SELECT 1 FROM td_status WHERE classe = 'td-status-info'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Valor do Atributo (classe) de td_status: td-status-info não existe';
		return false;
	}

	/*
		Entidade Tipo de Aviso
		Verifica a entidade Tipo de Aviso e seus atributos
	*/
	$sql = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE UPPER(TABLE_NAME) = UPPER('td_tipoaviso') AND UPPER(TABLE_SCHEMA) = UPPER('".SCHEMA."')";
	$query = $conn->query($sql);
	if ($query->rowCount() != 1){
		echo 'Entidade: td_tipoaviso não existe';
		return false;
	}

	$sql = "SELECT 1 FROM td_tipoaviso WHERE descricao = 'Sucesso'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Valor do Atributo (descricao) de td_tipoaviso: Sucesso não existe';
		return false;
	}
	$sql = "SELECT 1 FROM td_tipoaviso WHERE descricao = 'Alerta'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Valor do Atributo (descricao) de td_tipoaviso: Alerta não existe';
		return false;
	}
	$sql = "SELECT 1 FROM td_tipoaviso WHERE descricao = 'Erro'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Valor do Atributo (descricao) de td_tipoaviso: Erro não existe';
		return false;
	}
	$sql = "SELECT 1 FROM td_tipoaviso WHERE descricao = 'Informativo'";
	$query = $conn->query($sql);
	if ($query->rowCount() < 1){
		echo 'Valor do Atributo (descricao) de td_tipoaviso: Informativo não existe';
		return false;
	}

	if (!file_exists("config/estilosite_mysql.ini")){
		echo 'Arquivo de configuração do banco de dados da Estilo Site não encontrado.';
		return false;
	}
	if (!file_exists("arquivos/ticket")){
		echo 'Diretório de armazenamento dos arquivos do Ticket não foi criado.';
		return false;
	}
	echo 'Teste de Instalação, passou.';
?>