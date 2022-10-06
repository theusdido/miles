<?php
	$op = tdClass::Read("op");
	$conn = Transacao::Get();
	$connCorreios = Conexao::Abrir("correios");
	$selecionado = tdClass::Read("selecionado");
	if ($op == "retorna_lista_uf")
	{
		$sql = "SELECT id,nome,sigla FROM uf WHERE id > 0";
		$query = $connCorreios->query($sql);
		while ($linha = $query->fetch()){
			echo "<option value='" . strtoupper($linha["sigla"]) . "' " . ($selecionado==$linha["sigla"]?"selected":"") .">" . utf8_encode($linha["nome"]) . "</option>";
		}
		exit;
	}
	else if ($op == "retorna_lista_localidade")
	{
		$sql = "select a.id,TRIM(a.nome) nome,a.uf from localidade a,uf b WHERE a.uf = b.id AND b.sigla = '" . $_GET["uf"] . "'";
		$query = $connCorreios->query($sql);
		while ($linha = $query->fetch()){
			echo "<option value='" . utf8_encode($linha["nome"]) . "' " . (strtoupper(retirarAcentos($selecionado))==strtoupper(retirarAcentos(utf8_encode($linha["nome"])))?"selected":"")  . ">" . utf8_encode($linha["nome"]) . "</option>";
		}
		exit;
	}
	elseif ($op == "retorna_lista_bairro")
	{
		$sql = "SELECT id,nome FROM bairro WHERE localidade = " . getIdLocalidade($_GET["localidade"],$_GET["uf"]); ##class(Correios.Localidade).getIDLocalidade(%request.Get("localidade"),%request.Get("uf"))
		$query = $connCorreios->query($sql);
		while ($linha = $query->fetch()){
			echo "<option value='" .utf8_encode($linha["nome"]) . "' ".(strtoupper(retirarAcentos($selecionado))==strtoupper(retirarAcentos(utf8_encode($linha["nome"])))?"selected":"") .">".utf8_encode($linha["nome"])."</option>";
		}
		
		$sql = "SELECT id,nome FROM td_imobiliaria_bairro WHERE cidade = " . getIdCidade($_GET["localidade"],$_GET["uf"]); 
		$query = $conn->query($sql);
		while ($linha = $query->fetch()){
			echo "<option value='" .utf8_encode($linha["nome"]) . "' ". ($selecionado == $linha["id"]?"selected":"") .">".utf8_encode($linha["nome"])."</option>";
		}		
		exit;
	}
	elseif ($op == "add_bairro")
	{
		
		$descricao = ($_GET["descricao"]);
		$cidade = getIdCidade($_GET["cidade"],$_GET["uf"]);	

		$sqlBairro = "SELECT id FROM td_imobiliaria_bairro WHERE nome = '".utf8charset($descricao)."' AND cidade = " . $cidade;
		$queryBairro = $conn->query($sqlBairro);
		if ($queryBairro->rowCount() <= 0){
			$bairro = tdClass::Criar("persistent",array("td_imobiliaria_bairro"))->contexto;
			$id = $bairro->proximoID();
			$bairro->id = $id;
			$bairro->projeto = 1;
			$bairro->empresa = 1;
			$bairro->nome = $descricao;
			$bairro->cidade = $cidade;
			$salvar =  $bairro->armazenar();
			Transacao::Commit();
			if ($salvar){

				// Salva o bairro na "base" dos correios
				// Retorna ID do novo bairro
				
				//$localidade = ##class(Correios.Localidade).getIDLocalidade(%request.Get("cidade"),%request.Get("uf"))
				
				echo $id;
				/*
				if ($_GET["retorno"] == "C"){
					echo $id;
				}else{
					//echo  ##class(Correios.Bairro).add(%request.Get("descricao"),localidade)
				}
				*/
			}else{
				//D $System.Status.DisplayError(salvar)
			}
		}else{
			/*
			$localidade = ##class(Correios.Localidade).getIDLocalidade(%request.Get("cidade"),%request.Get("uf"))
			I ##class(Correios.Bairro).existeBairro(%request.Get("descricao"),localidade) = 0 {
				D ##class(Correios.Bairro).add(%request.Get("descricao"),localidade)
			}
		 	W rs.id
			*/
			echo $queryBairro->fetch()[0];
		}
	}
	elseif ($op == "salva_endereco")
	{
		$enderecoOBJ = tdClass::Criar('persistent',array("td_imobiliaria_endereco"))->contexto;
		$id = $enderecoOBJ->proximoID();
		#$cidade = getIdCidade(tdClass::Read("localidade"),tdClass::Read("uf"));
		#$bairro = getIdBairro(tdClass::Read("bairro"),$cidade);

		$bairro = is_numeric(tdClass::Read("bairro"))?tdClass::Read("bairro"):getIdBairro(tdClass::Read("bairro"),getIdCidade(tdClass::Read("localidade"),getIdEstado(tdClass::Read("uf"))));
		$logradouro = tdClass::Read("logradouro");
		$cep = tdClass::Read("cep");

		$sql = "SELECT id FROM td_imobiliaria_endereco WHERE td_bairro = {$bairro} AND logradouro = '{$logradouro}' AND cep = '{$cep}'";
		$query = $conn->query($sql);
		if ($query->rowCount() > 0)
		{
			$idSel = $query->fetch()[0];
			echo $idSel;
		}else{

			$end = tdClass::Criar("persistent",array("td_imobiliaria_endereco"))->contexto;
			$end->id = $id;			
			$end->bairro = $bairro;
			$end->logradouro = $logradouro;
			$end->cep = $cep;
			$end->empresa = 1;
			$end->projeto = 1;
			$end->armazenar();
			Transacao::Commit();
			echo $id;
		}
	}
	elseif ($op == "busca_geral")
	{
		
		$termo = tdClass::Read("termo");
		#$where = " WHERE tags %CONTAINS ("_WHERE_")"
		$limit = $termo==""?"LIMIT 30":"";
		$sql = " SELECT 
					a.localidade,
					(SELECT c.nome FROM bairro c WHERE c.id = a.bairro_inicial) bairro ,
					a.cep,
					a.nome,
					TRIM(a.tipo) tipo,
					(SELECT nome FROM localidade b WHERE b.id= a.localidade) cidade
				FROM logradouro a " . $limit;

		$cidade = $estado = $bairro = "";
		$query = $connCorreios->query($sql);
		While ($linha = $query->fetch())
		{
			$UF = $connCorreios->query("SELECT (SELECT b.sigla FROM uf b WHERE a.uf = b.id) sigla FROM localidade a WHERE a.id = {$linha["localidade"]} LIMIT 1")->fetch()[0];
			
			/*
			I cidade.uf > 0 {
				S estado = ##class(Correios.UF).%OpenId(cidade.uf)
			}
			I rs.bairroinicial > 0 {
				S bairro = ##class(Correios.Bairro).%OpenId(rs.bairroinicial)
			}
			*/			
			echo $linha["cep"] . "^" . $UF . "^" . utf8_encode($linha["cidade"]) . "^" . utf8_encode($linha["bairro"]) . "^" . utf8_encode($linha["tipo"]) . " " . utf8_encode($linha["nome"]) . "^" . "^^^^" . "~";
		}
	}
	function getIdCidade($cidade,$uf){
		global $conn;
		$ufID = is_numeric($uf)?$uf:getIdEstado($uf);
		$sql = "SELECT id FROM td_imobiliaria_cidade WHERE nome LIKE '%{$cidade}%' AND estado = '{$ufID}';";
		$query = $conn->query($sql);
		if ($query->rowCount() > 0){
			return $query->fetch()[0];
		}else{
			$cidadeOBJ 				= tdClass::Criar("persistent",array("td_imobiliaria_cidade"))->contexto;
			$idCidade 				= $cidadeOBJ->proximoID();
			$cidadeOBJ->id 			= $idCidade;
			$cidadeOBJ->empresa 	= Session::Get()->empresa;
			$cidadeOBJ->projeto 	= CURRENT_PROJECT_ID;
			$cidadeOBJ->estado 		= $ufID;
			$cidadeOBJ->nome 		= $cidade;
			//$cidadeOBJ->sigla = getSiglaLocalidade($cidade,$uf);
			$cidadeOBJ->armazenar();
			
			return $idCidade;
		}	
	}
	function getIdEstado($uf){
		global $conn;
		
		$sql = "SELECT id FROM td_imobiliaria_estado WHERE sigla = '{$uf}';";
		$query = $conn->query($sql);
		if ($query->rowCount() > 0){
			return $query->fetch()[0];
		}else{
			$ufOBJ 					= tdClass::Criar("persistent",array("td_imobiliaria_estado"))->contexto;
			$idUF 					= $ufOBJ->proximoID();
			$ufOBJ->id 				= $idUF;
			$ufOBJ->empresa 		= Session::Get()->empresa;
			$ufOBJ->projeto 		= CURRENT_PROJECT_ID;
			$ufOBJ->pais 			= 1;
			$ufOBJ->nome 			= getNomeUF($uf);
			$ufOBJ->sigla 			= $uf;
			$ufOBJ->denominacao 	= '';
			$ufOBJ->armazenar();	
			return $idUF;
		}
	}
	function getIdLocalidade($cidade,$uf){
		global $connCorreios;
		$ufID = getIdUF($uf);
		$sql = "SELECT id FROM localidade WHERE nome LIKE '%{$cidade}%' AND uf = '{$ufID}';";
		$query = $connCorreios->query($sql);
		return $query->fetch()[0];
	}
	function getIdUF($uf){
		global $connCorreios;
		$sql = "SELECT id FROM uf WHERE sigla = '{$uf}';";
		$query = $connCorreios->query($sql);
		return $query->fetch()[0];
	}
	function getNomeUF($sigla){
		global $connCorreios;
		$sql = "SELECT nome FROM uf WHERE sigla = '{$sigla}';";
		$query = $connCorreios->query($sql);
		return $query->fetch()[0];		
	}
	function getSiglaLocalidade($cidade,$uf){
		global $connCorreios;
		
		$ufID = is_numeric($uf)?$uf:getIdUF($uf);
        $sql = "SELECT sigla FROM localidade WHERE nome LIKE '%{$cidade}%' AND uf = '{$ufID}';";
		$query = $connCorreios->query($sql);
		return $query->fetch()[0];
	}
	
	function getIdBairro($bairro,$cidade){
		global $conn;
		$sql = "SELECT id FROM td_imobiliaria_bairro WHERE nome LIKE '%{$bairro}%' AND cidade = {$cidade};";
		$query = $conn->query($sql);
		if ($query->rowCount() > 0){
			return $query->fetch()[0];
		}else{
			return 0;
		}
	}