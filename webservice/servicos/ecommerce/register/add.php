<?php
    
    $_data              = (array)$_data;
	$tipo				= $_data['tipo'];
	$nome 				= $_data['nome'];
	$cpf                = isset($_data['cpf']) ? $_data['cpf'] : '';
    $cnpj               = isset($_data['cnpj']) ? $_data['cnpj'] : '';
    $inscricaoestadual 	= isset($_data['inscricaoestadual']) ? $_data['inscricaoestadual'] : '';
	$endereco			= $_data['endereco'];
	$cep 				= $_data['cep'];
	$complemento 		= $_data['complemento'];
	$numero 			= $_data['numero'];
	$uf 				= $_data['estado'];
	$senha 				= md5($_data['senha']);
    $csenha             = md5($_data['csenha']);

    // Cidade
    $cidade 	    = $_data['cidade'];
    $cidadedesc     = $_data['cidade'];
    $sqlCidade 	    = "SELECT id FROM td_ecommerce_cidade WHERE nome = '{$cidade}' and uf = {$uf};";
    $queryCidade    = $conn->query($sqlCidade);
    if ($queryCidade->rowCount() > 0){
        $linhaCidade    = $queryCidade->fetch();
        $cidade         = $linhaCidade["id"];
    }else{
        $idcidade       = getProxId("ecommerce_cidade",$conn);
        $sqlCidadeI     = "INSERT INTO td_ecommerce_cidade (id,nome,uf) VALUES (".$idcidade.",'{$cidade}',{$uf});";
        $queryCidadeI   = $conn->query($sqlCidadeI);
        if ($queryCidadeI){
            $cidade = $idcidade; 
        }else{
            $cidade = 0;
        }
    }
    
    // Bairro
    $bairro         = $_data['bairro'];
    $bairrodesc     = $_data['bairro'];
    $sqlBairro      = "SELECT id FROM td_ecommerce_bairro WHERE nome = '{$bairro}' and cidade = {$cidade};";
    $queryBairro    = $conn->query($sqlBairro);
    if ($queryBairro->rowCount() > 0){
        $linhaBairro    = $queryBairro->fetch();
        $bairro         = $linhaBairro["id"];
    }else{
        $idbairro       = getProxId("ecommerce_bairro",$conn);
        $sqlBairroI     = "INSERT INTO td_ecommerce_bairro (id,nome,cidade) VALUES (".$idbairro.",'{$bairro}',{$cidade});";
        $queryBairroI   = $conn->query($sqlBairroI);
        if ($queryBairroI){
            $bairro = $idbairro;
        }else{
            $bairro = 0;
        }
    }

    // Endereço
    $idendereco 		= getProxId("ecommerce_endereco",$conn);
    $sqlEndereco 		= "INSERT INTO td_ecommerce_endereco 
        (
            id,
            bairro,
            logradouro,
            numero,
            complemento,
            cep,
            cidade,
            uf,
            bairro_nome,
            cidade_nome
        ) VALUES (
            ".$idendereco.",
            {$bairro},
            '{$endereco}',
            '{$numero}',
            '{$complemento}',
            '{$cep}',
            {$cidade},
            {$uf},
            '{$bairrodesc}',
            '{$cidadedesc}'
        );";
    $queryEndereco = $conn->exec($sqlEndereco);

    $email 		= $_data['email'];
    $telefone 	= $_data['telefone'];
    
    if ($nome == "" || $email == ""){
        echo 'Nome e E-Mail são obrigatórios';
        return false;
    }

    if (!isemail($email)){
        echo 'E-Mail está no formato inválido';
        return false;			
    }
    
    if (strlen($senha) < 8){
        echo 'Senha precisa ter 8 digitos';
        return false;
    }

    if ($senha != $csenha){
        echo 'Senhas não coincidem';
        return false;
    }

    $idcliente  = getProxId("ecommerce_cliente",$conn);
    $sql        = "INSERT INTO td_ecommerce_cliente (id,nome,cpf,email,telefone,senha,inativo) VALUES (".$idcliente.",'".$nome."','".$cpf."','".$email."','".$telefone."','{$senha}',0);";
    $query = $conn->query($sql);
    if ($query){

        // Usuário 
        $usuario 						= tdc::p("td_usuario");
        $usuario->nome 					= $nome;
        $usuario->email					= $email;
        $usuario->login					= $email;
        $usuario->senha					= md5($senha);
        $usuario->permitirexclusao 		= 0;
        $usuario->permitirtrocarempresa = 0;
        $usuario->grupousuario 			= 3;
        $usuario->perfil 				= 0;
        $usuario->perfilusuario			= 0;
        $usuario->inativo 				= 1;
        $usuario->armazenar();

        //Lista ( Endereço )
        $entidadecliente    = getEntidadeId("ecommerce_cliente",$conn);
        $entidadeendereco   = getEntidadeId("ecommerce_endereco",$conn);
        $idlista            = getProxId("lista",$conn);
        $sqlLista           = "INSERT INTO td_lista (id,entidadepai,entidadefilho,regpai,regfilho) VALUES ({$idlista},{$entidadecliente},{$entidadeendereco},{$idcliente},{$idendereco});";
        $queryLista = $conn->query($sqlLista);
        $conn->commit();

        // include("../miles/lib/phpmailer/PHPMailerAutoload.php");
        // /* ********************************
        //     ENVIA PARA O CLIENTE
        // ******************************** */
        // $mail = new PHPMailer();
        // $mail->SetLanguage("en","../miles/lib/phpmailer/language/");
        // $mail->IsSMTP();
        // $mail->CharSet='UTF-8';
        // $mail->SMTPDebug 	= 0;
        // $mail->SMTPAuth 	= true;
        // $mail->Username 	= "automatico@villafrancioni.com.br"; 
        // $mail->Password 	= "villa@Teia.10";			
        // $mail->SMTPSecure 	= 'ssl';
        // $mail->Host 		= "smtps.uhserver.com";
        // $mail->Port 		= 465;
        // $mail->From 		= "automatico@villafrancioni.com.br";
        // $mail->FromName 	= "Villa Francioni";

        // $mail->AddAddress("{$email}",$nome);

        // //wrap seta o tamanhdo do texto por linha
        // $mail->WordWrap = 50;
        // $mail->IsHTML(true); //enviar em HTML

        // $mail->Subject 		= "Cadastro Realizado !";
        // $msg = '
        //     <img src="https://teia.tec.br/villafrancioni.com.br/loja/images/logo2.png" width="200" />
        //     <br/><br/><br/>
        //     <p>Olá, <b>'.$nome.'</b>. Obrigado pela seu cadastro.</p>
        //     <br/>
        //     <br/>
        //     <p><i>Esta é uma mensagem automática. Por favor, não responda este e-mail.</i></p>
        //     <br>Villa Francioni<br>Rodovia SC-114 | Km 300 | São Joaquim | Santa Catarina | Brasil
        // ';
        // $mail->Body = $msg;
        // if(!$mail->Send())
        // {
        //     echo $mail->ErrorInfo;
        //     return false;
        // }

        /* ********************************
            ENVIA PARA A EMPRESA
        ******************************** */			
        // $mailEmpresa = new PHPMailer();
        // $mailEmpresa->SetLanguage("en","../miles/lib/phpmailer/language/");
        // $mailEmpresa->CharSet		= 'UTF-8';
        // $mailEmpresa->SMTPDebug 	= 0;
        // $mailEmpresa->IsSMTP(); 
        // $mailEmpresa->SMTPAuth 		= true;
        // $mailEmpresa->Username 		= "automatico@villafrancioni.com.br"; 
        // $mailEmpresa->Password 		= "villa@Teia.10";			
        // $mailEmpresa->SMTPSecure 	= 'ssl';
        // $mailEmpresa->Host 			= "smtps.uhserver.com";
        // $mailEmpresa->Port 			= 465;
        // $mailEmpresa->From 			= "automatico@villafrancioni.com.br";
        // $mailEmpresa->FromName 		= "Villa Francioni ( E-Commerce )";

        // $mailEmpresa->AddAddress("{$emailpedido}","E-Mail Pedido");

        // $mailEmpresa->WordWrap = 50;
        // $mailEmpresa->IsHTML(true);			
        // $mailEmpresa->Subject = "NOVO CADASTRO";
        // $msg = '
        // <img src="https://teia.tec.br/villafrancioni.com.br/loja/images/logo2.png" width="200" />
        //     <br/><br/><br/>
        //     <p><b>Novo Cadastro Realizado!</b></p>
        //     <br/>
        //     <h3>Dados do Cliente:</h3>
        //     <table cellspadding="0" colspacing="0" width="500" border="0">
        //         <tbody>
        //             <tr>
        //                 <td width="30%">ID</td>
        //                 <td width="70%">'.$idcliente.'</td>
        //             </tr>					
        //             <tr>
        //                 <td>Razão Social</td>
        //                 <td>'.$nome.'</td>
        //             </tr>
        //             <tr>
        //                 <td>CPF</td>
        //                 <td>'.$cpf.'</td>
        //             </tr>
        //             <tr>
        //                 <td>Telefone</td>
        //                 <td>'.$telefone.'</td>
        //             </tr>						
        //             <tr>
        //                 <td>E-Mail</td>
        //                 <td>'.$email.'</td>
        //             </tr>
        //             <tr>
        //                 <td>CEP</td>
        //                 <td>'.$cep.'</td>
        //             </tr>
        //             <tr>
        //                 <td>Endereço</td>
        //                 <td>'.$bairrodesc.', '.($numero==''?'S/N':$numero).'.  - '.$complemento.'</td>
        //             </tr>
        //             <tr>
        //                 <td>&nbsp;</td>
        //                 <td>'.$cidadedesc.'/</td>
        //             </tr>
        //         </tbody>
        //     </table>
        //     <br/>
        //     <p><i>Esta é uma mensagem automática. Por favor, não responda este e-mail.</i></p>
        //     <br>Villa Francioni<br>Rodovia SC-114 | Km 300 | São Joaquim | Santa Catarina | Brasil
        // ';
        // $mailEmpresa->Body = $msg;
        // if(!$mailEmpresa->Send())
        // {
        //     echo $mailEmpresa->ErrorInfo;
        //     exit;
        // }

        //setAutenticacao($idcliente,$nome);
        // echo '
        //     <div class="alert alert-success"><b>Obrigado!</b><br/>Seu cadastro foi realizado com Sucesso.</div>
        //     <p id="retornamos-no-email"><a href="'.URL_SITE.'entrar">Clique aqui para efetuar o login.</a></p>
        // ';
        
        $_SESSION['session_token'] = md5($idcliente . date('YmdHis'));
        $retorno['status']  = 'success';
        $retorno['data']    = array(
            'user_id'       => $idcliente,
            'user'          => tdc::pa('td_ecommerce_cliente',$idcliente),
            'auth_token'    => $_SESSION['session_token']
        );
    }else{
        echo $sql;
        var_dump($conn->errorInfo());
        $conn->rollback();
    }