<?php

include 'conexao.php';
include 'funcoes.php';

$op = $_POST["op"];
if ($op == "entidade"){
	$entidade 	= $_POST["entidade"];
	$usuario 	= $_POST["usuario"];
	$acao 		= explode("^",$_POST["acoes"]);
	$sql 	= "SELECT id FROM td_entidadepermissoes WHERE entidade = ".$entidade." AND usuario = ".$usuario  . " LIMIT 1;";
	$query 	= $conn->query($sql);
	if ($query->rowcount() <= 0){
		$sqlPermissaoEntidade  = "INSERT INTO td_entidadepermissoes (id,projeto,empresa,entidade,usuario,inserir,excluir,editar,visualizar) VALUES ";
		$sqlPermissaoEntidade  .= "(".getProxIdMDM("entidadepermissoes").",1,1,".$entidade.",".$usuario.",".$acao[0].",".$acao[1].",".$acao[2].",".$acao[3].");";
	}else{
		$linha = $query->fetch();
		$sqlPermissaoEntidade = "
			UPDATE 
				td_entidadepermissoes 
			SET
				projeto = 1,
				empresa = 1,
				entidade = ".$entidade.",
				usuario = ".$usuario.",
				inserir = ".$acao[0].",
				excluir = ".$acao[1].",
				editar =".$acao[2].",
				visualizar = ".$acao[3]."
			WHERE id = " . $linha["id"].";
		";
	}
	$conn->query($sqlPermissaoEntidade);
}elseif ($op == "atributo"){
	$atributo = $_POST["atributo"];
	$usuario = $_POST["usuario"];
	$acao = explode("^",$_POST["acoes"]);
	
	$sql = "SELECT id FROM td_atributopermissoes WHERE atributo = ".$atributo . " AND usuario = ".$usuario . " LIMIT 1 ";
	$query = $conn->query($sql);
	if ($query->rowcount() <= 0){
		$sqlPermissaoAtributo  = "INSERT INTO td_atributopermissoes (id,projeto,empresa,atributo,usuario,editar,visualizar) VALUES ";
		$sqlPermissaoAtributo  .= "(".getProxIdMDM("atributopermissoes").",1,1,".$atributo.",".$usuario.",".$acao[0].",".$acao[1].");";
	}else{
		$linha = $query->fetch();
		$sqlPermissaoAtributo = "UPDATE td_atributopermissoes SET ";
		$sqlPermissaoAtributo .= "projeto = 1, empresa = 1, atributo = ".$atributo.",usuario = ".$usuario.",editar =".$acao[0].",visualizar = ".$acao[1];
		$sqlPermissaoAtributo .= " WHERE id = " . $linha["id"];
	}
	$conn->query($sqlPermissaoAtributo);
}elseif ($op == "funcao"){
	$funcao = $_POST["funcao"];
	$usuario = $_POST["usuario"];
	$acao = $_POST["acoes"];

	$sql = "SELECT id FROM td_funcaopermissoes WHERE funcao = ".$funcao." AND usuario = ".$funcao. " LIMIT 1 ";
	$query = $conn->query($sql);
	if ($query->rowcount() <= 0){
		$sqlPermissaoFuncao  = "INSERT INTO td_funcaopermissoes (id,projeto,empresa,funcao,usuario,permissao) VALUES ";
		$sqlPermissaoFuncao  .= "(".getProxIdMDM("funcaopermissoes").",1,1,".$funcao.",".$usuario.",".$acao.");";
	}else{
		$linha = $query->fetch();
		$sqlPermissaoFuncao = "UPDATE td_funcaopermissoes SET ";
		$sqlPermissaoFuncao .= "projeto = 1, empresa = 1, funcao = ".$funcao.",usuario = ".$usuario.",permissao = ".$acao;
		$sqlPermissaoFuncao .= " WHERE id = " . $linha["id"];
	}
	$conn->query($sqlPermissaoFuncao);
}elseif ($op == "menu"){
	$menu 		= $_POST["menu"];
	$usuario 	= $_POST["usuario"];
	$acao 		= $_POST["acoes"];
			
	$sql = "SELECT id FROM td_menupermissoes WHERE menu = ".$menu." AND usuario = ".$usuario. " LIMIT 1;";	
	$query = $conn->query($sql);
	if ($query->rowCount() <= 0){
		$sqlPermissaoMenu  = "INSERT INTO td_menupermissoes (id,projeto,empresa,menu,usuario,permissao) VALUES ";
		$sqlPermissaoMenu  .= "(".getProxIdMDM("menupermissoes").",1,1,".$menu.",".$usuario.",".$acao.");";
	}else{
		$linha = $query->fetch();
		$sqlPermissaoMenu = "UPDATE td_menupermissoes SET ";
		$sqlPermissaoMenu .= "projeto = 1, empresa = 1, menu = ".$menu.",usuario = ".$usuario.",permissao = ".$acao;
		$sqlPermissaoMenu .= " WHERE id = " . $linha["id"] .";";
	}
	$conn->query($sqlPermissaoMenu);
	}
?>