<?php
include 'conexao.php';

// Entidade Permissoões
$usuario = $_GET["usuario"];
$sql = "SELECT * FROM td_entidadepermissoes WHERE td_usuario = " . $usuario;
$query = $conn->query($sql);

$i = 1;
$t = $query->rowcount();
echo '[{"entidades":[';
while ($linha = $query->fetch()){
	echo '{"entidadedados":"'.$linha["td_entidade"].'^'.$linha["inserir"].'^'.$linha["excluir"].'^'.$linha["editar"].'^'.$linha["visualizar"].'"}';
	if ($i < $t) echo ",";
	$i++;
}
echo ']},';

$sql = "SELECT * FROM td_funcaopermissoes WHERE td_usuario = ".$usuario;
$query = $conn->query($sql);
$i = 1;
$t = $query->rowcount();
echo '{"funcoes":[';
while ($linha = $query->fetch()){
	echo '{"funcoesdados":"'.$linha["td_funcao"].'^'.$linha["permissao"].'"}';
	if ($i < $t) echo ",";
	$i++;
}
echo ']},';

$sql = "SELECT * FROM td_menupermissoes WHERE td_usuario = ".$usuario;
$query = $conn->query($sql);
$i = 1;
$t = $query->rowcount();
echo '{"menus":[';
while ($linha = $query->fetch()){
	echo '{"menu":"'.$linha["td_menu"].'^'.$linha["permissao"].'"}';
	if ($i < $t) echo ",";
	$i++;
}
echo ']}]';
?>