<?php
    switch($_op){
        case 'listar-grupo-usuario':
			$sql = "SELECT id,descricao FROM td_grupousuario";
			$query = $conn->query($sql);
			while($linha = $query->fetch()){
				$descricao = tdc::utf8($linha["descricao"]);
				echo "<li href='#' class='list-group-item'>";
				echo "		<div class='cabecalho-lista-usuario' id='".$linha["id"]."'>";
				echo "			<span class='fas fa-folder' aria-hidden='true'></span>";
				echo "				".$descricao;
				echo "		</div>";
				echo "		<ul class='lista-usuario' id='lista-usuario-".$linha["id"]."'>";
				echo "<b>Usuário</b>";
				$sqlusuarios = "SELECT id,nome,perfil FROM td_usuario WHERE (perfilusuario <> 1 OR perfilusuario IS NULL) AND grupousuario = " . $linha["id"];
				$rsusuarios = $conn->query($sqlusuarios);
				While ($linhausuarios = $rsusuarios->fetch()){
					echo "<li><a href='#' class='usuario-na-lista-porusuario' id='".$linhausuarios["id"]."' data-perfil='".$linhausuarios["perfil"]."'>".$linhausuarios["nome"]."</a></li>";
				}
				if ($rsusuarios->rowcount() <= 0){
					echo "<li><b>Nenhum Usuário</b></li>";
				}			
				echo "<b>Perfil</b>";
				$sqlperfil = "SELECT id,nome,perfil FROM td_usuario WHERE perfilusuario = 1 AND grupousuario = ".$linha["id"];
				$queryperfil = $conn->query($sqlperfil);
				While ($linhaperfil = $queryperfil->fetch()){
					echo "<li><a href='#' class='usuario-na-lista-porusuario' id='".$linhaperfil["id"]."' data-perfil='".$linhaperfil["perfil"]."'>".$linhaperfil["nome"]."</a></li>";
				}
				echo "		</ul>";
				echo "	</li>";
			}
        break;        
        case 'listar-entidade':
            $sql = "SELECT id,descricao FROM td_entidade";
            $rs = $conn->query($sql);
            while ($linha = $rs->fetch()){
                $descricao = tdc::utf8($linha["descricao"]);
                echo "<tr data-entidadeid='".$linha["id"]."' data-entidadedescricao='".$descricao."'>";
                echo "		<td><small>".$descricao."</small></td>";
                echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'entidade'); data-op='adicionar' /></center></td>";
                echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'entidade'); data-op='excluir'/></center></td>";
                echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'entidade'); data-op='editar'/></center></td>";
                echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'entidade'); data-op='visualizar'/></center></td>";
                echo "		<td>";
                echo "			<center>";
                echo "				<button type='button' class='btn btn-default abrir-atributos' aria-label='Atributos' >";
                echo "					<span class='fas fa-list' aria-hidden='true'></span>";
                echo "				</button>";
                echo "			</center>";
                echo "		</td>";
                echo "	</tr>";
            }
        break;
        case 'listar-dados-permissoes-usuario':
            $usuario    = $_GET["usuario"];
            $sql        = "SELECT * FROM td_entidadepermissoes WHERE usuario = " . $usuario;
            $query      = $conn->query($sql);

            $i = 1;
            $t = $query->rowcount();
            echo '[{"entidades":[';
            while ($linha = $query->fetch()){
                echo '{"entidadedados":"'.$linha["entidade"].'^'.$linha["inserir"].'^'.$linha["excluir"].'^'.$linha["editar"].'^'.$linha["visualizar"].'"}';
                if ($i < $t) echo ",";
                $i++;
            }
            echo ']},';

            $sql = "SELECT * FROM td_funcaopermissoes WHERE usuario = ".$usuario;
            $query = $conn->query($sql);
            $i = 1;
            $t = $query->rowcount();
            echo '{"funcoes":[';
            while ($linha = $query->fetch()){
                echo '{"funcoesdados":"'.$linha["funcao"].'^'.$linha["permissao"].'"}';
                if ($i < $t) echo ",";
                $i++;
            }
            echo ']},';

            $sql = "SELECT * FROM td_menupermissoes WHERE usuario = ".$usuario;
            $query = $conn->query($sql);
            $i = 1;
            $t = $query->rowcount();
            echo '{"menus":[';
            while ($linha = $query->fetch()){
                echo '{"menu":"'.$linha["menu"].'^'.$linha["permissao"].'"}';
                if ($i < $t) echo ",";
                $i++;
            }
            echo ']}]';            
        break;
        case 'listar-consultas':
            $sql = "
                SELECT a.id,b.descricao 
                FROM td_menu a
                INNER JOIN td_consulta b ON a.entidade = b.entidade
                WHERE a.tipomenu = 'consulta'
            ;";
            $rs = $conn->query($sql);
            if ($rs->rowCount() > 0){
                while ($linha = $rs->fetch()){
                    $descricao = utf8_encode($linha["descricao"]);
                    echo "	<tr menuid='".$linha["id"]."'>";
                    echo "		<td><small>".$descricao."</small></td>";
                    echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'menu'); id='".$linha["id"]."' data-op='menu' /></center></td>";
                    echo "	</tr>";
                }
            }else{
                trNenhumRegistro(2);
            }
        break;
        case 'listar-relatorio':
            $sql = "
                SELECT a.id,b.descricao 
                FROM td_menu a
                INNER JOIN td_relatorio b ON a.entidade = b.entidade
                WHERE a.tipomenu = 'relatorio'
            ;";
            $rs = $conn->query($sql);
            if ($rs->rowCount() > 0){
                while ($linha = $rs->fetch()){
                    $descricao = utf8_encode($linha["descricao"]);
                    echo "	<tr menuid='".$linha["id"]."'>";
                    echo "		<td><small>".$descricao."</small></td>";
                    echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'menu'); id='".$linha["id"]."' data-op='menu' /></center></td>";
                    echo "	</tr>";
                }
            }else{
                trNenhumRegistro(2);
            }
        break;
        case 'listar-funcao':
            $sql = "SELECT id,descricao FROM td_funcao";
            $rs = $conn->query($sql);
            if ($rs->rowCount() > 0){
                while ($linha = $rs->fetch()){
                    $descricao = tdc::utf8($linha["descricao"]);
                    echo "	<tr funcaoid='".$linha["id"]."'>";
                    echo "		<td><small>".$descricao."</small></td>";
                    echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'funcao'); id='".$linha["id"]."' data-op='funcao' /></center></td>";
                    echo "	</tr>";
                }
            }else{
                trNenhumRegistro(2);
            }             
        break;
        case 'listar-menu':            
            $sql = "SELECT id,descricao FROM td_menu WHERE pai = 0 or pai is null or entidade = 0 or entidade is null;";
            $rs = $conn->query($sql);
            if ($rs->rowCount() > 0){
                while ($linha = $rs->fetch()){
                    $descricao = tdc::utf8($linha["descricao"]);
                    echo "	<tr menuid='".$linha["id"]."'>";
                    echo "		<td><small>".$descricao."</small></td>";
                    echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'menu'); id='".$linha["id"]."' data-op='menu' /></center></td>";
                    echo "	</tr>";
                }
            }else{
                trNenhumRegistro(2);
            }                            
        break;
        case 'listar-permissoes-funcoes':            
            $sql    = "SELECT id,descricao FROM td_funcao";
            $query  = $conn->query($sql);
            if ($query->rowCount() > 0){
                While ($linha = $query->fetch()){
                    $descricao = tdc::utf8($linha["descricao"]);
                    echo "	<li href='#' class='list-group-item'>";
                    echo "		<button class='btn btn-default add-usuario-funcao' type='button' id='".$linha["id"]."'>";
                    echo "			<span class='fas fa-plus-circle'></span>";
                    echo " 	</button>";
                    echo "		<small class='descricao-funcao'>".$descricao."</small>";
                    echo "		<table class='table table-hover table-condensed lista_usuarios_funcoes' id='t-funcao-".$linha["id"]."'>";
                    echo "			<tbody>";
        
                    $sqlusuario     = "SELECT a.id,b.nome,b.id usuario FROM td_funcaopermissoes a,usuario b WHERE funcao = ".$linha["id"]." AND a.usuario = b.id AND permissao = 1";
                    $queryusuario   = $conn->query($sqlusuario);
                    While ($linhausuario = $queryusuario->fetch()){
                        $nome = tdc::utf8($linhausuario["nome"]);
                        echo "			<tr>";
                        echo "				<td width='90%'><small class='descricao-usuario'>".$nome."</small></td>";
                        echo "				<td width='10%' align='right'>";
                        echo "					<button type='button' class='btn btn-default excluir-usuario-funcao' aria-label='Excluir Usuário Função' data-usuario='".$linhausuario["usuario"]."' data-funcao='".$linha["id"]."'>";
                        echo "						<span class='fas fa-trash-alt' aria-hidden='true'></span>";
                        echo "					</button>";
                        echo "				</td>";
                        echo "			</tr>";
                    }
                    echo "			</tbody>";
                    echo "		</table>";
                    echo "	</li>";
                }
            }else{
                echo "	<li href='#' class='list-group-item list-group-item-warning'>Nenhum Registro Encontrado</li>";
            }
        break;
        case 'listar-atributos-cadastro':
			$entidadeID =  $_GET["entidade"];
			$sql        = "SELECT id,descricao,entidade FROM td_atributo WHERE entidade = " .$entidadeID;
			$query      = $conn->query($sql);
			While ($linha = $query->fetch()){
				$permissao_editar       = 0;
                $permissao_visualizar   = 0;
				$sqlpermissao       = "SELECT * FROM td_atributopermissoes WHERE atributo = ".$linha["id"]." AND usuario = ".$_GET["usuario"];
				$querypermissao     = $conn->query($sqlpermissao);
                if ($querypermissao->rowCount() > 0){
                    $linhapermissao         = $querypermissao->fetch();
                    $permissao_editar       = $linhapermissao["editar"];
                    $permissao_visualizar   = $linhapermissao["visualizar"];
                }
                $descricao          = tdc::utf8($linha["descricao"]);
                echo "<tr data-entidadeid='".$linha["entidade"]."' id='".$linha["id"]."'>";
                echo "		<td><small>".$descricao."</small></td>";
                #echo"		<td><center><input type='checkbox' onclick=setaPermissao(this,'atributo'); "_$S(rspermissao.inserir=1:"checked",1:"")_" /></center></td>";
                #echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'atributo'); "_$S(rspermissao.excluir=1:"checked",1:"")_" /></center></td>";
                echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'atributo'); ".($permissao_editar==1?"checked":"")." data-op='editar'/></center></td>";
                echo "		<td><center><input type='checkbox' onclick=setaPermissao(this,'atributo'); ".($permissao_visualizar==1?"checked":"")." data-op='visualizar'/></center></td>";
                echo "	</tr>";
                
			}
        break;
        case 'setar-permissao':
            $item = $_POST["item"];
            switch($item){
                case 'entidade':
                    $entidade 	= $_POST["entidade"];
                    $usuario 	= $_POST["usuario"];
                    $acao 		= explode("^",$_POST["acoes"]);
                    $sql 	= "SELECT id FROM td_entidadepermissoes WHERE entidade = ".$entidade." AND usuario = ".$usuario  . " LIMIT 1;";
                    $query 	= $conn->query($sql);
                    if ($query->rowcount() <= 0){
                        $sqlPermissaoEntidade  = "INSERT INTO td_entidadepermissoes (id,projeto,empresa,entidade,usuario,inserir,excluir,editar,visualizar) VALUES ";
                        $sqlPermissaoEntidade  .= "(".getProxId("entidadepermissoes").",1,1,".$entidade.",".$usuario.",".$acao[0].",".$acao[1].",".$acao[2].",".$acao[3].");";
                    }else{
                        $linha = $query->fetch();
                        $sqlPermissaoEntidade = "
                            UPDATE 
                                td_entidadepermissoes 
                            SET
                                projeto = 1,
                                empresa = 1,
                                entidade = ".$entidade.",
                                usuario = ".$usuario.",
                                inserir = ".$acao[0].",
                                excluir = ".$acao[1].",
                                editar =".$acao[2].",
                                visualizar = ".$acao[3]."
                            WHERE id = " . $linha["id"].";
                        ";
                    }
                    $conn->query($sqlPermissaoEntidade);
                break;
                case 'atributo':
                    $atributo = $_POST["atributo"];
                    $usuario = $_POST["usuario"];
                    $acao = explode("^",$_POST["acoes"]);
                    
                    $sql = "SELECT id FROM td_atributopermissoes WHERE atributo = ".$atributo . " AND usuario = ".$usuario . " LIMIT 1 ";
                    $query = $conn->query($sql);
                    if ($query->rowcount() <= 0){
                        $sqlPermissaoAtributo  = "INSERT INTO td_atributopermissoes (id,projeto,empresa,atributo,usuario,editar,visualizar) VALUES ";
                        $sqlPermissaoAtributo  .= "(".getProxId("atributopermissoes").",1,1,".$atributo.",".$usuario.",".$acao[0].",".$acao[1].");";
                    }else{
                        $linha = $query->fetch();
                        $sqlPermissaoAtributo = "UPDATE td_atributopermissoes SET ";
                        $sqlPermissaoAtributo .= "projeto = 1, empresa = 1, atributo = ".$atributo.",usuario = ".$usuario.",editar =".$acao[0].",visualizar = ".$acao[1];
                        $sqlPermissaoAtributo .= " WHERE id = " . $linha["id"];
                    }
                    $conn->query($sqlPermissaoAtributo);
                break;
                case 'funcao':
                    $funcao = $_POST["funcao"];
                    $usuario = $_POST["usuario"];
                    $acao = $_POST["acoes"];
                
                    $sql = "SELECT id FROM td_funcaopermissoes WHERE funcao = ".$funcao." AND usuario = ".$funcao. " LIMIT 1 ";
                    $query = $conn->query($sql);
                    if ($query->rowcount() <= 0){
                        $sqlPermissaoFuncao  = "INSERT INTO td_funcaopermissoes (id,projeto,empresa,funcao,usuario,permissao) VALUES ";
                        $sqlPermissaoFuncao  .= "(".getProxId("funcaopermissoes").",1,1,".$funcao.",".$usuario.",".$acao.");";
                    }else{
                        $linha = $query->fetch();
                        $sqlPermissaoFuncao = "UPDATE td_funcaopermissoes SET ";
                        $sqlPermissaoFuncao .= "projeto = 1, empresa = 1, funcao = ".$funcao.",usuario = ".$usuario.",permissao = ".$acao;
                        $sqlPermissaoFuncao .= " WHERE id = " . $linha["id"];
                    }
                    $conn->query($sqlPermissaoFuncao);
                break;
                case 'menu':
                    $menu 		= $_POST["menu"];
                    $usuario 	= $_POST["usuario"];
                    $acao 		= $_POST["acoes"];
                            
                    $sql = "SELECT id FROM td_menupermissoes WHERE menu = ".$menu." AND usuario = ".$usuario. " LIMIT 1;";	
                    $query = $conn->query($sql);
                    if ($query->rowCount() <= 0){
                        $sqlPermissaoMenu  = "INSERT INTO td_menupermissoes (id,projeto,empresa,menu,usuario,permissao) VALUES ";
                        $sqlPermissaoMenu  .= "(".getProxId("menupermissoes").",1,1,".$menu.",".$usuario.",".$acao.");";
                    }else{
                        $linha = $query->fetch();
                        $sqlPermissaoMenu = "UPDATE td_menupermissoes SET ";
                        $sqlPermissaoMenu .= "projeto = 1, empresa = 1, menu = ".$menu.",usuario = ".$usuario.",permissao = ".$acao;
                        $sqlPermissaoMenu .= " WHERE id = " . $linha["id"] .";";
                    }
                    $conn->query($sqlPermissaoMenu);
                break;
            }
        break;
    }