<?php
$link = "../config/log.txt";
if (file_exists($link)){
	$config = $config = file ($link);
}else{
	throw new Exception("Arquivo configuração com o banco de dados não existe");
}
#echo stripos("/* 11/03/2015 17:24:33^Edilson Bitencourt */ UPDATE menu  SET projeto = '', descricao = 'Forma de Pagamento', link = '?controller=crud&t=11&op=listar', target = '', pai = '1' WHERE (id = '9');", "SELECT");
#exit;
foreach ($config as $linha){
	$l = explode(" ",$linha);
	if (isset($l[5])){
		if (stripos($linha,'SELECT") == ""){
			echo $linha . "<br/>";
		}	
	}
}