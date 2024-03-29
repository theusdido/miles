<?php
	$conn = Transacao::get();
	for ($i=4883;$i<=5154;$i++){
		$sql = "select * from td_arquivos_credor where id = {$i};";
		$query = $conn->query($sql);
		if ($query->rowCount() <= 0){
			echo $i . '<br/>';
		}
	}