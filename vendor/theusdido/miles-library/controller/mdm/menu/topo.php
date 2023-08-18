<?php
    $self = "menutopohome.php";
    switch($_op){
        case 'listar':
            $indice = 0;
            $pai    = tdc::r('pai');
            $sql = "
                SELECT a.id,a.descricao,
                    (SELECT b.descricao FROM ".MENU." b WHERE b.id = a.pai) pai,
                a.pai,
                a.inativo
                FROM ".MENU." a 
                WHERE pai = {$pai}
                ORDER BY a.pai ASC,a.ordem ASC,a.id ASC
            ";
            $query = $conn->query($sql);
            While ($linha = $query->fetch()){
                $descricao = tdc::utf8($linha["descricao"]);
                echo imprimeLinhaMenu($indice,$linha["id"],$descricao,tdc::utf8($linha["pai"]),$self,$linha["pai"],$linha["inativo"]);
                $indice++;
            } 
        break;
        case 'option-entidade':
            echo '<option value="0">-- Selecione --</option>';
            $sql = "SELECT id,nome,descricao,pacote FROM ".ENTIDADE." ORDER BY id DESC;";
            $query = $conn->query($sql);
            While($linha = $query->fetch()){
                $descricao = tdc::utf8($linha["descricao"]);
                echo "<option value='".$linha["id"]."' data-nome='".$linha["nome"]."' data-pacote='".$linha["pacote"]."' data-descricao='".$descricao."'>".$descricao." [ ".$linha["nome"]." ]</option>";
            }
        break;
        case 'listar-entidade':
            echo '<option value="0">-- Selecione --</option>';

            $sql = "SELECT id,descricao FROM ".MENU." WHERE pai = 0 or pai is null ORDER BY ordem ASC";
            $query = $conn->query($sql);
            While($linha = $query->fetch()){
                $descricao = tdc::utf8($linha["descricao"]);
                $_pai = $linha['id'];
                echo "<option value='".$linha["id"]."'>".$descricao."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;### MENU ###</option>";
                // Submenu
                    echo "<optgroup label='# Submenu #'>";
                    $sql_submenu = "
                        SELECT id,descricao
                        FROM td_menu
                        WHERE pai = {$_pai};
                    ";
                    $query_submenu = $conn->query($sql_submenu);
                    While($linha_submenu = $query_submenu->fetch()){
                        $descricao = tdc::utf8($linha_submenu["descricao"]);
                        echo "<option value='".$linha_submenu["id"]."' data-descricao='".$descricao."'>".$descricao."</option>";
                    }
                echo '</optgroup>';
                // Submenu
            }
        break;
        case 'salvar':
            $id					= $_POST["id"];
            $entidadeRequest	= isset($_POST["entidade"])?$_POST["entidade"]:"";
            $entidade			= $entidadeRequest==""?"0":"'" .$entidadeRequest. "'";
            $descricao			= $_POST["descricao"]==""?"null":"'" .tdc::utf8($_POST["descricao"]). "'";
            $link 				= $_POST["link"]==""?"'#'":"'" .$_POST["link"]. "'";
            $target				= $_POST["target"]==""?"null":"'" .$_POST["target"]. "'";		
            $pai				= isset($_POST["pai"])?$_POST["pai"]:0;
            $tp_menu			= $_POST["tipomenu"];
            $tipomenu			= "'" . $tp_menu . "'";
            $path				= "'" . $_POST["path"] . "'";
            $icon				= "'" . $_POST["icon"] . "'";
            $coluna				= isset($_POST["coluna"])?($_POST["coluna"]==''?0:$_POST["coluna"]):0;
            $fixo				= "'" . $_POST["fixo"] . "'";

            if ($_POST["ordem"] == ''){
                $ordem = getNextValue("menu","ordem",array("pai","=",$pai));
            }else{
                $ordem = $_POST["ordem"];
            }

            // Hack para forçar o conceito com o valor do campo da entidade
            $conceito = $entidade;                
            
            // O campo entidade assumime o valor do conceito quando não for um cadastro
            if ($tp_menu != 'cadastro' && $tp_menu != 'raiz' && $tp_menu != 'personalizado' && $tp_menu != 'conceito'){
            	$sqlTpMenu 	    = 'SELECT entidade FROM td_'.$tp_menu.' WHERE id = ' . $entidadeRequest;
            	$query 			= $conn->query($sqlTpMenu);
            	$linhaTpMenu	= $query->fetch();
            	$entidade		= $linhaTpMenu['entidade'];
            }

            if ($id == ""){
                $idNew = getProxId("menu");
                $sql = "
                    INSERT INTO ".MENU." (
                        id,entidade,descricao,link,target,ordem,pai,tipomenu,
                        path,icon,coluna,fixo,inativo,conceito
                    ) VALUES (
                        ".$idNew.",".$entidade.",".$descricao.",".$link.",".$target.",".$ordem.",".$pai.",".$tipomenu.",
                        ".$path.",".$icon.",".$coluna.",".$fixo.",false,$conceito
                    );";
            }else{
                $sql = "
                    UPDATE ".MENU."
                    SET 
                        entidade = ".$entidade." 
                        , descricao = ".$descricao." 
                        , link = ".$link." 
                        , target = ".$target." 
                        , ordem = ".$ordem." 
                        , pai = ".$pai." 
                        , tipomenu = ".$tipomenu." 
                        , path = ".$path."
                        , icon = ".$icon."
                        , fixo = ".$fixo."
                        , coluna = ".$coluna."
                        , conceito = ".$conceito."
                    WHERE id = ".$id.";
                ";
            }
            try{
                $query = $conn->query($sql);
                if ($query){
                    if ($id == ""){
                        try{
                            // Seta permissão para o usuário que criou o menu
                            $sqlP = "INSERT INTO td_menupermissoes (id,projeto,menu,usuario,permissao) VALUES (DEFAULT,1,{$idNew},1,1);";
                            $conn->exec($sqlP);
                        }catch(Exception $e){
                            $conn->rollBack();
                            if (IS_SHOW_ERROR_MESSAGE){
                                echo $sqlP;
                                var_dump($e->getMessage());
                            }
                            exit;
                        }
                    }
                }
    
                echo json_encode(array(
                    "status" => 1,
                    "msg" => "Salvo com sucesso."
                ));
            }catch(Exception $e){
                if (IS_SHOW_ERROR_MESSAGE){
                    echo 'Errou';
                    echo '<br/>' . $sql;
                    echo '<br/>' . $e->getMessage();
                    var_dump($conn->errorInfo());
                    $conn->rollBack();
                }
                exit;
            }            
        break;
        case 'load':
            tdc::wj( tdc::rua(MENU,tdc::r('id')));
        break;
        case 'inativar':
            $inativar	= $_GET['inativar'];
            $id			= $_GET['id'];
            $sql		= 'UPDATE '.MENU.' SET inativo = '.$inativar.' WHERE id = ' . $id;
            $query 		= $conn->query($sql);
            echo 1; 
        break;
        case 'ordenacao':
            foreach($_GET["dados"] as $d){
                $sql = "UPDATE ".MENU." SET ordem = {$d["ordem"]} WHERE id={$d["menu"]}";
                $query = $conn->exec($sql);
            }
        break;
        case 'excluir':
            $excluir    = tdc::r('id');
            $sql        = "DELETE FROM ".MENU." WHERE id = {$excluir};";
            $query      = $conn->query($sql);
            if ($query){
                // Excluir premissões
                $sqlP = "DELETE FROM td_menupermissoes WHERE menu = {$excluir};";
                $queryP = $conn->query($sqlP);
                if (!$queryP){
                    if (IS_SHOW_ERROR_MESSAGE){
                        echo $sqlP;
                        var_dump($conn->errorInfo());
                    }
                }
                echo 1;
            }else{
                if (IS_SHOW_ERROR_MESSAGE){
                    var_dump($conn->errorInfo());
                }
            }
    
        break;
        case 'carregaentidade':
            echo '<option value="0">-- Selecione --</option>';
            $sql = "SELECT id,nome,descricao,pacote FROM ".ENTIDADE." ORDER BY id DESC;";
            $query = $conn->query($sql);
            While($linha = $query->fetch()){
                $descricao = tdc::utf8($linha["descricao"]);
                echo "<option value='".$linha["id"]."' data-nome='".$linha["nome"]."' data-pacote='".$linha["pacote"]."' data-descricao='".$descricao."'>".$descricao." [ ".$linha["nome"]." ]</option>";
            }
        break;
        case 'carregaconsulta':
            echo '<option value="0">-- Selecione --</option>';
            $sql = "SELECT b.id,a.nome,b.descricao,a.pacote FROM ".ENTIDADE." a,".CONSULTA." b WHERE a.id = b.entidade ORDER BY a.id DESC;";
            $query = $conn->query($sql);
            While($linha = $query->fetch()){
                $descricao = tdc::utf8($linha["descricao"]);
                echo "<option value='".$linha["id"]."' data-nome='".$linha["nome"]."' data-pacote='".$linha["pacote"]."' data-descricao='".$descricao."'>".$descricao." [ ".$linha["nome"]." ]</option>";
            }
        break;
        case 'carregarelatorio':
            echo '<option value="0">-- Selecione --</option>';
            $sql = "SELECT b.id,a.nome,b.descricao,a.pacote FROM ".ENTIDADE." a,".RELATORIO." b WHERE a.id = b.entidade ORDER BY a.id DESC;";
            $query = $conn->query($sql);
            While($linha = $query->fetch()){
                $descricao = tdc::utf8($linha["descricao"]);
                echo "<option value='".$linha["id"]."' data-nome='".$linha["nome"]."' data-pacote='".$linha["pacote"]."' data-descricao='".$descricao."'>".$descricao." [ ".$linha["nome"]." ]</option>";
            }
        break;
        case 'carregamovimentacao':
            echo '<option value="0">-- Selecione --</option>';
            $sql = "SELECT b.id,a.nome,b.descricao,a.pacote FROM ".PREFIXO."entidade a,".PREFIXO."movimentacao b WHERE a.id = b.entidade ORDER BY a.id DESC;";
            $query = $conn->query($sql);
            While($linha = $query->fetch()){
                $descricao = tdc::utf8($linha["descricao"]);
                echo "<option value='".$linha["id"]."' data-nome='".$linha["nome"]."' data-pacote='".$linha["pacote"]."' data-descricao='".$descricao."'>".$descricao." [ ".$linha["nome"]." ]</option>";
            }
        break;
    }

	function imprimeLinhaMenu($indice,$id,$descricao,$pai,$self,$idpai,$inativar){
		global $parmsiframe;
		$retorno 		= "";
		$pai 			= $pai == 0 ? '' : $pai;
		$is_inativo		= $inativar == 1 ? 'false' : 'true';

		$btn_inativo	= $inativar == 1 ? 'danger' : 'primary';
		if ($pai == ""){
			$mais = "
						<button type='button' class='btn btn-default' aria-label='Ver sub menu' onclick=versubmenu(".$id.",'".str_replace(" ","^",$descricao)."')>
						  <span class='fas fa-th-list' id='span-menuraiz-".$id."' aria-hidden='true'></span>
						</button>
			";	
		}else{
			$mais = "";
		}

		$retorno .= "	<tr data-indice='".$indice."' data-menu='".$id."'>";
		$retorno .= "		<td>".$id."</td>";
		$retorno .= "		<td>".$descricao."</td>";
		$retorno .= "		<td>".$pai."</td>";
		$retorno .= "		<td align='center'>".$mais."</td>";
		$retorno .= "		<td align='center'>";
        $retorno .= "			<button type='button' class='btn btn-primary' onclick='editarMenu({$id})'>";
		$retorno .= "				<span class='fas fa-pencil-alt' aria-hidden='true'></span>";
		$retorno .= "			</button>";
		$retorno .= "		</td>";
		$retorno .= "		<td align='center'>";
		$retorno .= "			<button type='button' class='btn btn-$btn_inativo' data-inativar='{$inativar}' onclick=inativarMenu(this,{$id})>";
		$retorno .= "				<span class='fas fa-ban' aria-hidden='true'></span>";
		$retorno .= "			</button>";
		$retorno .= "		</td>";
		$retorno .= "		<td align='center'>";
		$retorno .= "			<button type='button' class='btn btn-primary' onclick=excluirMenu({$id})>";
		$retorno .= "					<span class='fas fa-trash-alt' aria-hidden='true'></span>";
		$retorno .= "				</button>";
		$retorno .= "			</td>

								<td align='center'>
									<div class='btn-group' role='group' aria-label='Ordenação de Elementos'>
										<button type='button' class='btn btn-default btn-sm btn-arrow-order' aria-label='Item Acima' onclick=reodernar(this,'up');>
										  <span class='fas fa-chevron-up' aria-hidden='true'></span>
										</button>

										<button type='button' class='btn btn-default btn-sm btn-arrow-order' aria-label='Item Abaixo' onclick=reodernar(this,'down');>
										  <span class='fas fa-chevron-down' aria-hidden='true'></span>
										</button>
									</div>
								</td>
		";
		$retorno .= "	</tr>";
		return $retorno;
	}
