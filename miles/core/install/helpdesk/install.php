<?php

include '../conexao.php';
include '../../system/funcoes.php';

$config = parse_ini_file("../../config/config.inc");
define("PREFIXO",$config["PREFIXO"] . "_");

$conn->beginTransaction();

// Instalação do TICKET
include 'status.php';
include 'prioridade.php';
include 'tipo.php';
include 'ticket.php';
include 'seguidores.php';
include 'anexos.php';

$conn->commit();

?>