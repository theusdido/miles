<?php	

	$produto 	= $_GET["produto"];
	$quantidade = $_GET["quantidade"];
	$operacao 	= (int)$_GET["operacao"] == 1?"+":"-";
	$datahora 	= date("Y-m-d H:i:s");
	$conn = Transacao::Get();

	$sql = "SELECT 1 FROM td_erp_material_posicaogeralestoque WHERE produto = " . $produto;
	$query = $conn->query($sql);

	if ($query->rowCount() > 0){
		$sqlUpdate = "UPDATE td_erp_material_posicaogeralestoque SET saldo = (saldo " . $operacao . " " . $quantidade . "), datahora = '{$datahora}' WHERE produto = " . $produto;
		$queryUpdade = $conn->query($sqlUpdate);
	}else{
		$sqlInsert = "INSERT INTO td_erp_material_posicaogeralestoque (id,produto,saldo,datahora) VALUES (".getProxId("erp_material_posicaogeralestoque",$conn).",{$produto},{$quantidade},'{$datahora}');";
		$queryInsert = $conn->query($sqlInsert);
	}
	Transacao::Commit();
