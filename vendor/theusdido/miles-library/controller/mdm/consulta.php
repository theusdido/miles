<?php

    switch($_op){
        case 'salvar_colunas_configuracoes':
            $atributo               = tdc::r('atributo');
            $coluna                 = tdc::p('td_consultacoluna')->newNotExists('atributo','=',$atributo);
            $coluna->consulta       = tdc::r('consulta');
            $coluna->atributo       = $atributo;
            $coluna->exibirid       = json_decode(tdc::r('exibirid'),true);
            $coluna->alinhamento    = tdc::r('alinhamento');
            $coluna->armazenar();
        break;

        case 'get_config_colunas':
            $filtro = tdc::f();
            $filtro->addFiltro('consulta','=',tdc::r('consulta'));
            $filtro->addFiltro('atributo','=',tdc::r('atributo'));
            $_coluna = tdc::d('td_consultacoluna',$filtro);

            $_alinhamento   = 'left';
            $_exibirid      = false;
            if (sizeof($_coluna) > 0){
                $_alinhamento   = $_coluna[0]->alinhamento;
                $_exibirid      = $_coluna[0]->exibirid;
            }
            tdc::wj(array(
                'alinhamento'   => $_alinhamento,
                'exibirid'      => $_exibirid
            ));            
        break; 
        case 'listar-consulta':
            $sql    = "SELECT id,descricao,entidade FROM ".CONSULTA;
            $query  = $conn->query($sql);

            if ($query->rowCount() > 0){
                foreach ($query->fetchAll() as $linha){
                    $id         		= $linha["id"];
					$entidade_id		= $linha["entidade"];
                    $descricao  		= tdc::utf8($linha["descricao"]);
                    $query      		= $conn->query("SELECT descricao FROM td_entidade WHERE id = $entidade_id;")->fetch();
					$entidade_descricao	= tdc::utf8($query["descricao"]);
                    echo "
                        <tr id='linha-registro-consulta-{$id}'>
                            <td>{$id}</td>
                            <td>{$descricao}</td>
                            <td>{$entidade_descricao}</td>
                            <td align='center'>
                                <button type='button' class='btn btn-info' onclick='gerarConsulta({$id},$entidade_id);'>
                                    <span class='fas fa-code' aria-hidden='true'></span>
                                </button>
                            </td>
                            <td align='center'>
                                <button type='button' class='btn btn-primary' onclick='editarConsulta({$id})'>
                                    <span class='fas fa-pencil-alt' aria-hidden='true'></span>
                                </button>
                            </td>
    
                            <td align='center' >
                                <button type='button' class='btn btn-primary' onclick='excluirConsulta({$id})'>
                                    <span class='fas fa-trash-alt' aria-hidden='true'></span>
                                </button>
                            </td>
                        </tr>
                    ";
                }
            }else{
                echo "
                    <tr>
                        <td colspan='6' class='warning text-center' >Nenhum Registro Encontrado</td>
                    </tr>
                ";
            }
        break;
        case 'listar-entidade-option':
            echo '<option value="0" >-- Selecione --</option>';
            $sql = "SELECT id,nome,descricao FROM ".ENTIDADE;
            $query = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                echo '<option value="'.$linha["id"].'" data-nome="'.$linha["nome"].'">'.$linha["descricao"].' [ '.$linha["nome"].' ]</option>';
            }
        break;
        case 'listar-movimentacao-option':
            echo '<option value="0" >-- Selecione --</option>';
            $sql = "SELECT id,descricao FROM ".MOVIMENTACAO;
            $query = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                echo '<option value="'.$linha["id"].'" >'.$linha["descricao"].'</option>';
            }
        break;
        case 'salvar':
			$id			 			= $_POST["id"];
			$descricao				= $_POST["descricao"];
			$entidade	 			= $_POST["entidade"];
			$movimentacao	 		= isset($_POST["movimentacao"])?$_POST["movimentacao"]:0;			
			$exibirbotaoeditar		= $_POST["exibirbotaoeditar"];
			$exibirbotaoexcluir		= $_POST["exibirbotaoexcluir"];
			$exibirbotaoemmassa		= $_POST["exibirbotaoemmassa"];
			$exibircolunaid			= $_POST["exibircolunaid"];
			$adicionaridfiltro		= $_POST["adicionaridfiltro"];

			if ($id == 0){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM ".CONSULTA);
				$prox = $query_prox->fetch();
				$id = $prox[0];

				$sql = "INSERT INTO ".CONSULTA." (id,descricao,entidade,movimentacao,exibirbotaoeditar,exibirbotaoexcluir,exibirbotaoemmassa,exibircolunaid,adicionaridfiltro) VALUES ({$id},'{$descricao}',{$entidade},{$movimentacao},{$exibirbotaoeditar},{$exibirbotaoexcluir},{$exibirbotaoemmassa},$exibircolunaid,$adicionaridfiltro);";
			}else{
				$sql = "UPDATE ".CONSULTA." SET entidade = {$entidade} , descricao = '{$descricao}' , movimentacao = {$movimentacao} , exibirbotaoeditar = {$exibirbotaoeditar} , exibirbotaoexcluir = {$exibirbotaoexcluir} , exibirbotaoemmassa = {$exibirbotaoemmassa}, exibircolunaid = {$exibircolunaid}, adicionaridfiltro = {$adicionaridfiltro} WHERE id = {$id};";
			}
			$query = $conn->query($sql);
			if($query){
				tdc::wj(array(
					'status' 	=> 'success',
					'id'		=> $id
				));
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}else{
					tdc::wj(array(
						'status' 	=> 'error',
						'id'		=> $id
					));
				}
			}

        break;

        case 'load':
            tdc::wj( tdc::rua(CONSULTA,tdc::r('id')) );
        break;
        case 'listarconsulta':
			$sql = "
				SELECT 
					id,atributo atributo,operador,legenda 
				FROM td_consultafiltro a 
				WHERE consulta = {$_GET["consulta"]} 
				ORDER BY ordem ASC,id DESC
			";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum campo de <strong>filtro</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo 	= $linha["atributo"];
				$operador 	= $linha["operador"];
				$legenda 	= $linha["legenda"];
				$sqlAtributo = "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo = $conn->query($sqlAtributo);
				$linhaAtributo = $queryAtributo->fetch();
				$atributoDescricao = $linhaAtributo["descricao"];
				echo "<li class='list-group-item' data-id='".$linha["id"]."'>
						Atributo <strong>{$atributoDescricao}</strong> com  operador ( <strong>{$operador} ) </strong>
						<button type='button' class='btn btn-default' onclick='excluirFiltro({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-operador='{$operador}' data-idfiltro='{$linha["id"]}' data-legenda='{$linha["legenda"]}' onclick='editarFiltro({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</li>";
			}            
        break;
        case 'listarstatus':
			$sql = "SELECT id,atributo atributo,operador,valor,status FROM td_consultastatus a WHERE consulta = {$_GET["consulta"]}";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum filtro de <strong>status</strong> configurado.</div>';
			}else{
			foreach($query->fetchAll() as $linha){
				$atributo 			= $linha["atributo"];
				$operador			= $linha["operador"];
				$valor 				= $linha["valor"];
				$sqlAtributo 		= "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo 		= $conn->query($sqlAtributo);
				$linhaAtributo 		= $queryAtributo->fetch();
				$atributoDescricao 	= $linhaAtributo["descricao"];
				echo "<span class='list-group-item'>
						Atributo <strong>{$atributoDescricao}</strong> com  operador ( <strong>{$operador} ) </strong>. Valor: <small class='text-info'>{$valor}</small>.
						<button type='button' class='btn btn-default' onclick='excluirStatus({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-operador='{$operador}' data-valor='{$valor}' data-idstatus='{$linha["id"]}' data-status='{$linha["status"]}' onclick='editarStatus({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</span>";
			}
			}
        break;
        case 'listarfiltroinicial':
			$sql = "SELECT id,atributo atributo,operador,legenda,valor FROM td_consultafiltroinicial a WHERE consulta = {$_GET["consulta"]} ORDER BY id DESC";
			$query = $conn->query($sql);
			if ($query->rowCount() <= 0){
				echo '<div class="alert alert-warning alert-dismissible text-center" role="alert">Nenhum campo de <strong>filtro</strong> configurado.</div>';
			}
			foreach($query->fetchAll() as $linha){
				$atributo = $linha["atributo"];
				$operador = $linha["operador"];
				$legenda = $linha["legenda"];
				$sqlAtributo = "SELECT descricao FROM td_atributo WHERE id = " . $atributo;
				$queryAtributo = $conn->query($sqlAtributo);
				$linhaAtributo = $queryAtributo->fetch();
				$atributoDescricao = $linhaAtributo["descricao"];
				$valor = $linha["valor"];
				echo "<span class='list-group-item'>
						Atributo <strong>{$atributoDescricao}</strong> com  operador ( <strong>{$operador} ) </strong>
						<button type='button' class='btn btn-default' onclick='excluirFiltroInicial({$linha["id"]});' style='float:right;margin-top:-4px'>
							<span class='fas fa-trash-alt' aria-hidden='true'></span>
						</button>
						<button id='atributo-editar-{$linha["id"]}' type='button' class='btn btn-default' data-atributo='{$atributo}' data-operador='{$operador}' data-idfiltro='{$linha["id"]}' data-legenda='{$linha["legenda"]}' data-valor='{$valor}' onclick='editarFiltroInicial({$linha["id"]})' style='float:right;margin-top:-4px'>
							<span class='fas fa-edit' aria-hidden='true'></span>
						</button>
					</span>";
			}

        break;
        case 'listar-atributos':
            echo '<option value="0" selected>Nenhum Selecionado</option>';
            $sql = "SELECT id,descricao FROM ".ATRIBUTO." WHERE entidade = " . tdc::r('_entidade');
            $query = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                echo '<option value="'.$linha["id"].'">'.tdc::utf8($linha["descricao"]).'</option>';
            }
        break;
        case 'salvarfiltro':
			$id			 			= isset($_POST["id"])?$_POST["id"]:'';
			$operador				= $_POST["operador"];
			$atributo	 			= $_POST["atributo"];
			$consulta	 			= $_POST["consulta"];
			$legenda 				= $_POST["legenda"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM td_consultafiltro");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO td_consultafiltro (id,operador,atributo,consulta,legenda) VALUES ({$id},'{$operador}',{$atributo},{$consulta},'{$legenda}');";
			}else{
				$sql = "UPDATE consultafiltro SET atributo = {$atributo} , consulta = {$consulta} , operador = '{$operador}' , legenda = '{$legenda}' WHERE id = {$id};";
			}		

			$query = $conn->query($sql);
			if($query){
				// Alterações no atributo
				$conn->query("UPDATE td_atributo SET legenda = '{$legenda}' WHERE id=" . $atributo);
				echo 1;
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}
			}
        break;
        case 'excluirfiltroinicial':
			$sql    = "DELETE FROM td_consultafiltroinicial WHERE id = " . $_GET["id"];
			$query  = $conn->query($sql);
        break;
        case 'excluirfiltro':
			$sql = "DELETE FROM td_consultafiltro WHERE id = " . $_GET["id"];
			$query = $conn->query($sql);
        break;
        case 'listar-status':
            $sql = "SELECT id,descricao FROM td_status";
            $query = $conn->query($sql);
            foreach($query->fetchAll() as $linha){
                echo '<option value="'.$linha["id"].'">'.$linha["descricao"].'</option>';
            }
        break;
        case 'salvarstatus':
            $id			 			= $_POST["id"];
            $operador				= $_POST["operador"];
            $valor					= $_POST["valor"];
            $atributo	 			= $_POST["atributo"];
            $consulta	 			= $_POST["consulta"];
            $status 				= $_POST["status"];

            if ($id == ""){
                $query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM td_consultastatus");
                $prox = $query_prox->fetch();
                $id = $prox[0];
                $sql = "INSERT INTO td_consultastatus (id,operador,valor,atributo,consulta,status) VALUES ({$id},'{$operador}','{$valor}',{$atributo},{$consulta},{$status});";
            }else{
                $sql = "UPDATE td_consultastatus SET atributo = {$atributo} , consulta = {$consulta} , valor = '{$valor}' , operador = '{$operador}' , status = {$status} WHERE id = {$id};";
            }
            $query = $conn->query($sql);
            if($query){
                echo 1;
            }else{
                var_dump($conn->errorInfo());
            }
        break;
        case 'excluirstatus':
			$sql = "DELETE FROM td_consultastatus WHERE id = " . $_GET["id"];
			$query = $conn->query($sql);
        break;      
        case 'salvarfiltroinicial':
			$id			 			= $_POST["id"];
			$operador				= $_POST["operador"];
			$atributo	 			= $_POST["atributo"];
			$consulta	 			= $_POST["consulta"];
			$legenda 				= $_POST["legenda"];
			$valor 					= $_POST["valor"];

			if ($id == ""){
				$query_prox = $conn->query("SELECT IFNULL(MAX(id),0)+1 FROM td_consultafiltroinicial;");
				$prox = $query_prox->fetch();
				$id = $prox[0];
				$sql = "INSERT INTO td_consultafiltroinicial (id,operador,atributo,consulta,legenda,valor) VALUES ({$id},'{$operador}',{$atributo},{$consulta},'{$legenda}','{$valor}');";
			}else{
				$sql = "UPDATE td_consultafiltroinicial SET atributo = {$atributo} , consulta = {$consulta} , operador = '{$operador}' , legenda = '{$legenda}' , valor = '{$valor}' WHERE id = {$id};";
			}
			$query = $conn->query($sql);
			if($query){
				// Alterações no atributo
				$conn->query("UPDATE td_atributo SET legenda = '{$legenda}' WHERE id=" . $atributo);
				echo 1;
			}else{
				if (IS_SHOW_ERROR_MESSAGE){
					var_dump($conn->errorInfo());
				}
			}

        break;
        case 'excluir-consulta':
            $sql = "DELETE FROM td_consulta WHERE id = " . $_GET["id"];
            $conn->exec($sql);

            $sql = "DELETE FROM td_consultastatus WHERE consulta = " . $_GET["id"];
            $conn->exec($sql);
            
            $sql = "DELETE FROM td_consultafiltroinicial WHERE consulta = " . $_GET["id"];
            $conn->exec($sql);

            $sql = "DELETE FROM td_consultafiltro WHERE consulta = " . $_GET["id"];
            $conn->exec($sql);

            $sql = "DELETE FROM td_consultacoluna WHERE consulta = " . $_GET["id"];
            $conn->exec($sql);

        break;
    }