<?php

include 'conexao.php';
include 'funcoes.php';

$op = $_POST["op"];
if ($op == "entidade"){
	$entidade 	= $_POST["entidade"];
	$usuario 	= $_POST["usuario"];
	$acao 		= explode("^",$_POST["acoes"]);

	$sql = "SELECT id FROM td_entidadepermissoes WHERE td_entidade = ".$entidade." AND td_usuario = ".$usuario  . " LIMIT 1;";
	$query = $conn->query($sql);
	if ($query->rowcount() <= 0){
		$sqlPermissaoEntidade  = "INSERT INTO td_entidadepermissoes (id,td_projeto,td_empresa,td_entidade,td_usuario,inserir,excluir,editar,visualizar) VALUES ";
		$sqlPermissaoEntidade  .= "(".getProxIdMDM("entidadepermissoes").",1,1,".$entidade.",".$usuario.",".$acao[0].",".$acao[1].",".$acao[2].",".$acao[3].");";
	}else{
		$linha = $query->fetch();
		$sqlPermissaoEntidade = "UPDATE td_entidadepermissoes SET ";
		$sqlPermissaoEntidade .= "td_projeto = 1, td_empresa = 1, td_entidade = ".$entidade.",td_usuario = ".$usuario.",inserir = ".$acao[0].",excluir = ".$acao[1].",editar =".$acao[2].",visualizar = ".$acao[3];
		$sqlPermissaoEntidade .= " WHERE id = " . $linha["id"];
	}
	$conn->query($sqlPermissaoEntidade);
}elseif ($op == "atributo"){
	$atributo = $_POST["atributo"];
	$usuario = $_POST["usuario"];
	$acao = explode("^",$_POST["acoes"]);
	
	$sql = "SELECT id FROM td_atributopermissoes WHERE td_atributo = ".$atributo . " AND td_usuario = ".$usuario . " LIMIT 1 ";
	$query = $conn->query($sql);
	if ($query->rowcount() <= 0){
		$sqlPermissaoAtributo  = "INSERT INTO td_atributopermissoes (id,td_projeto,td_empresa,td_atributo,td_usuario,editar,visualizar) VALUES ";
		$sqlPermissaoAtributo  .= "(".getProxIdMDM("atributopermissoes").",1,1,".$atributo.",".$usuario.",".$acao[0].",".$acao[1].");";
	}else{
		$linha = $query->fetch();
		$sqlPermissaoAtributo = "UPDATE td_atributopermissoes SET ";
		$sqlPermissaoAtributo .= "td_projeto = 1, td_empresa = 1, td_atributo = ".$atributo.",td_usuario = ".$usuario.",editar =".$acao[0].",visualizar = ".$acao[1];
		$sqlPermissaoAtributo .= " WHERE id = " . $linha["id"];
	}
	$conn->query($sqlPermissaoAtributo);
}elseif ($op == "funcao"){
	$funcao = $_POST["funcao"];
	$usuario = $_POST["usuario"];
	$acao = $_POST["acoes"];

	$sql = "SELECT id FROM td_funcaopermissoes WHERE td_funcao = ".$funcao." AND td_usuario = ".$funcao. " LIMIT 1 ";
	$query = $conn->query($sql);
	if ($query->rowcount() <= 0){
		$sqlPermissaoFuncao  = "INSERT INTO td_funcaopermissoes (id,td_projeto,td_empresa,td_funcao,td_usuario,permissao) VALUES ";
		$sqlPermissaoFuncao  .= "(".getProxIdMDM("funcaopermissoes").",1,1,".$funcao.",".$usuario.",".$acao.");";
	}else{
		$linha = $query->fetch();
		$sqlPermissaoFuncao = "UPDATE td_funcaopermissoes SET ";
		$sqlPermissaoFuncao .= "td_projeto = 1, td_empresa = 1, td_funcao = ".$funcao.",td_usuario = ".$usuario.",permissao = ".$acao;
		$sqlPermissaoFuncao .= " WHERE id = " . $linha["id"];
	}
	$conn->query($sqlPermissaoFuncao);
}elseif ($op == "menu"){
	$menu 		= $_POST["menu"];
	$usuario 	= $_POST["usuario"];
	$acao 		= $_POST["acoes"];
			
	$sql = "SELECT id FROM td_menupermissoes WHERE td_menu = ".$menu." AND td_usuario = ".$usuario. " LIMIT 1;";	
	$query = $conn->query($sql);
	if ($query->rowCount() <= 0){
		$sqlPermissaoMenu  = "INSERT INTO td_menupermissoes (id,td_projeto,td_empresa,td_menu,td_usuario,permissao) VALUES ";
		$sqlPermissaoMenu  .= "(".getProxIdMDM("menupermissoes").",1,1,".$menu.",".$usuario.",".$acao.");";
	}else{
		$linha = $query->fetch();
		$sqlPermissaoMenu = "UPDATE td_menupermissoes SET ";
		$sqlPermissaoMenu .= "td_projeto = 1, td_empresa = 1, td_menu = ".$menu.",td_usuario = ".$usuario.",permissao = ".$acao;
		$sqlPermissaoMenu .= " WHERE id = " . $linha["id"] .";";
	}
	$conn->query($sqlPermissaoMenu);
	}
?>