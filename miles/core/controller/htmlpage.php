<?php
$file = "files/cadastro/" . $_GET["file"];
if (file_exists($file)){
	$fp = fopen($file,"r");
	while (!feof($fp)){
		echo htmlspecialchars_decode(fgets($fp,4096));
	}
	fclose($fp);
}else{
	echo "<b>{$file}</b> arquivo não encontrado.";
}
exit;
