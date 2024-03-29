<?php
if (isset($_POST)){
	$pais = $estado = $cidade = $bairro = $logradouro = $cep = "";	
	
	if (isset($_POST["pais"])){
		// Busca País
		$sql_pais = tdClass::Criar("sqlcriterio");
		$sql_pais->addFiltro("sigla","=",$_POST["pais"]);		
		$ds_pais = tdClass::Criar("repositorio",array("td_pais"))->carregar($sql_pais);
		if ($ds_pais){
			$pais = $ds_pais[0]->id;
		}
	}
	if (isset($_POST["estado"]) && $pais != ""){
		// Busca Estado
		$sql_estado = tdClass::Criar("sqlcriterio");
		$sql_estado->addFiltro("sigla","=",$_POST["estado"]);
		$sql_estado->addFiltro("pais","=",$pais);
		$ds_estado = tdClass::Criar("repositorio",array("td_estado"))->carregar($sql_estado);
		if ($ds_estado){
			$estado = $ds_estado[0]->id;			
		}		
	}
	if (isset($_POST["cidade"]) || $pais != "" && $estado != ""){
		if (is_numeric($_POST["cidade"])){
			$cidade = $_POST["cidade"];
		}else{
			//Busca Cidade			
			$sql_cidade1 = tdClass::Criar("sqlcriterio");
			$sql_cidade1->add(tdClass::Criar("sqlfiltro",array("nome","=",utf8_decode($_POST["cidade"]))));
			$sql_cidade1->add(tdClass::Criar("sqlfiltro",array("nome","LIKE",'%'.utf8_decode($_POST["cidade"]) .'%')),OU);
			
			$sql_cidade2 = tdClass::Criar("sqlcriterio");			
			$sql_cidade2->addFiltro("estado","=",$estado);
			
			$sql_cidade = tdClass::Criar("sqlcriterio");
			$sql_cidade->add($sql_cidade1);
			$sql_cidade->add($sql_cidade2);
			$ds_cidade = tdClass::Criar("repositorio",array("td_cidade"))->carregar($sql_cidade);
			if ($ds_cidade){
				$cidade = $ds_cidade[0]->id;
			}
		}
	}
	if (isset($_POST["bairro"]) && $pais != "" && $estado != "" && $cidade != ""){
		if (is_numeric($_POST["bairro"])){
			$bairro = $_POST["bairro"];			
		}else{
			
			// Busca Bairro
			
			$sql_bairro1 = tdClass::Criar("sqlcriterio");
			$sql_bairro1->add(tdClass::Criar("sqlfiltro",array("descricao","=",$_POST["bairro"])));
			$sql_cidade1->add(tdClass::Criar("sqlfiltro",array("descricao","LIKE",'%'.$_POST["bairro"] .'%')),OU);
			
			$sql_bairro2 = tdClass::Criar("sqlcriterio");
			$sql_bairro2->addFiltro("cidade","=",$cidade);
			
			$sql_bairro = tdClass::Criar("sqlcriterio");
			$sql_bairro->add($sql_bairro1);
			$sql_bairro->add($sql_bairro2);			
			$ds_bairro = tdClass::Criar("repositorio",array("td_bairro"))->carregar($sql_bairro);
			if ($ds_bairro){
				$bairro = $ds_bairro[0]->id;
			}
		}
	}	
	if (isset($_POST["cep"])){
		// Busca CEP
		$cep = $_POST["cep"];
		
	}	
	if (isset($_POST["logradouro"])){
		// Busca Logradouro
		$logradouro = $_POST["logradouro"];
	}	
	if (isset($_POST["op"])){
		if ($_POST["op"] == "salvar_endereco"){
			$sql_endereco = tdClass::Criar("sqlcriterio");
			#$sql_endereco->addFiltro("cidade","=",$_POST["cidade"]);
			$sql_endereco->addFiltro("bairro","=",$_POST["bairro"]);
			$sql_endereco->addFiltro("cep","=",$cep);
			$sql_endereco->addFiltro("logradouro","=",utf8_decode($logradouro));
			$endereco = tdClass::Criar("repositorio",array("td_endereco"));		
			if ($endereco->quantia($sql_endereco) > 0){
				echo $endereco->carregar($sql_endereco)[0]->id . "^" . $endereco->carregar($sql_endereco)[0]->logradouro;
			}else{
				if ($conn = Transacao::get()){
					$query = $conn->query("SELECT IFNULL(MAX(id),0) + 1 FROM td_endereco");
					$prox = $query->fetch(PDO::FETCH_BOTH);
					$sql = "INSERT INTO td_endereco (id,bairro,cep,logradouro) VALUES ({$prox[0]},{$_POST["cidade"]},'{$cep}','{$logradouro}');";
					$conn->exec($sql);				
					Transacao::fechar();
					echo $prox[0] . "^" . $_POST["logradouro"];
				}
			}		
		}else{
			echo $pais . "^" . $estado . "^" . $cidade . "^" . $bairro . "^" . (isset($_POST["logradouro"])?$_POST["logradouro"]:"") . "^" . (isset($_POST["cep"])?$_POST["cep"]:"");
		}
	}else{
		echo $pais . "^" . $estado . "^" . $cidade . "^" . $bairro . "^" . (isset($_POST["logradouro"])?$_POST["logradouro"]:"") . "^" . (isset($_POST["cep"])?$_POST["cep"]:"");
	}
}
