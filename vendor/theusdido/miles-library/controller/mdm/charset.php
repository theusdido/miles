<?php
    $op = tdc::r('op');
    switch($op){
        case 'setar':

			$id = $_GET["id"];
			$charset = $_GET["charset"];
			$sql  = "UPDATE td_charset SET charset = '{$charset}' WHERE id = {$id};";
			$query = $conn->exec($sql);

		break;

		case 'listaratributo':
			$entidade = isset($_GET["entidade"]) ? $_GET["entidade"] : '';
			if ($entidade == ''){
				echo 'Entidade não enviada por parametro.';
				exit;
			}

			if (is_numeric($entidade)){
				$sqlT 	= "SELECT id,nome,descricao FROM td_atributo WHERE entidade = {$entidade} AND tipohtml in (1,2,3,14,16,21,27) AND tipo IN ('varchar','char','text');";
				$queryT = $conn->query($sqlT);
				$linhaT = $queryT->fetchAll();
				foreach($linhaT as $dado){
					echo '<option value="'.$dado["id"].'">'. tdc::utf8($dado["descricao"]) .' [ '.$dado["nome"].' ]</option>';
				}
			}else{
				echo '<option value="descricao"> Descrição [ descricao ]</option>';
			}
		break;

		case 'corrigir':
			//return ord($linhaVCharset["testecharset"]) == 195 ? false : true;
			$entidade = $_GET["entidade"];
			$atributo = $_GET["atributo"];
			
			if (is_numeric($entidade)){
				$sqle = "SELECT nome FROM td_entidade WHERE id = {$entidade};";
				$querye 	= $conn->query($sqle);
				$linhae 	= $querye->fetch();
				$entidade 	= $linhae["nome"];
			}

			if (is_numeric($atributo)){
				$sqla 		= "SELECT nome FROM td_atributo WHERE id = {$atributo};";
				$querya 	= $conn->query($sqla);
				$linhaa 	= $querya->fetch();
				$atributo 	= $linhaa["nome"];				
			}

			$sqlv 	= "SELECT id,{$atributo} valor FROM {$entidade};";
			$queryv = $conn->query($sqlv);
			while ($linhav = $queryv->fetch()){
				if (isutf8($linhav["valor"])){
					try {
						$valor 	= utf8_decode($linhav["valor"]); //Só funcionou com o comando nativo
						$sql 	= 'UPDATE '.$entidade.' SET '.$atributo.' = "'.$valor.'" WHERE id = ' . $linhav["id"]. ';';
						$query 	= $conn->query($sql);
					}catch(Throwable $t){
						echo $t->getMessage();
					}
				}
			}
		break;
	}