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

			// Caracteres desformatados
			$_de 	= array('Ã§','Ã£','Ã“','Ãµ','Ã³');
			$_para	= array('ç','ã','Ó','õ','ó');
			$sqlv 	= "SELECT id,{$atributo} valor FROM {$entidade};";
			$queryv = $conn->query($sqlv);
			while ($linhav = $queryv->fetch()){
				$_valor = str_replace($_de,$_para,$linhav["valor"]);
				if (!isutf8($_valor)){
					// Só funcionou com o comando nativo
					$_valor = utf8_decode($_valor); 
				}
				try {
					$sql 	= 'UPDATE '.$entidade.' SET '.$atributo.' = "'.$_valor.'" WHERE id = ' . $linhav["id"]. ';';
					$query 	= $conn->query($sql);
				}catch(Throwable $t){
					echo $t->getMessage();
				}
			}
		break;
		case 'listar':
            $sql = "SELECT * FROM td_charset ORDER BY id ASC";
            $query = $conn->query($sql);
            while ($linha = $query->fetch()){
                $id 		= $linha["id"];
                $local 		= tdc::utf8($linha["local"]);
                $id_modal 	= 'myModal' . $linha["id"];
                echo '
                        <tr>
                            <td>'.$linha["id"].'
                        </td>
                        <td>'.$local.'</td>
                        <td>
                ';

                //include 'charset_modal.php';

                echo '
                        </td>
                            
                            <td class="text-center">
                                <input type="radio" name="charsetoption-'.$id.'" id="charsetoption-'.$id.'-N" value="N" '.($linha["charset"]=='N'?'checked':'').' onclick="setCharset('.$id.',this)">
                            </td>
                            <td class="text-center">
                                <input type="radio" name="charsetoption-'.$id.'" id="charsetoption-'.$id.'-D" value="D" '.($linha["charset"]=='D'?'checked':'').' onclick="setCharset('.$id.',this)">
                            </td>
                            <td class="text-center">
                                <input type="radio" name="charsetoption-'.$id.'" id="charsetoption-'.$id.'-E" value="E" '.($linha["charset"]=='E'?'checked':'').' onclick="setCharset('.$id.',this)">
                            </td>
                        </tr>
                ';
            }		
		break;
		case 'option-corrigir-entidade':
			echo '
				<option value="td_entidade">Entidade [ td_entidade ]</option>
				<option value="td_atributo">Atributo [ td_atributo ]</option>
			';

			$sqlT 	= "SELECT id,nome,descricao FROM ".ENTIDADE;
			$queryT = $conn->query($sqlT);
			$linhaT = $queryT->fetchAll();
			foreach($linhaT as $dado){
				echo '<option value="'.$dado["id"].'">'. tdc::utf8($dado["descricao"]) .' [ '.$dado["nome"].' ]</option>';
			}
		break;
	}