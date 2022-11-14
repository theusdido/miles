<?php
  // Módulos E-Commerce
  $modules = array(
    array( "name" => "geral", "title" => "Geral", "components" => 
      array(
        array('name' => 'carrinhocompras' , 'title' => 'Carrinho de Compras'),
        array('name' => 'cliente' , 'title' => 'Cliente'),        
        array('name' => 'configuracoes' , 'title' => 'Configurações'),
        array('name' => 'recuperacaosenha' , 'title' => 'Recuperar Senha'),
        array('name' => 'loja'                    ,'title' => 'Loja'),
        array('name' => 'modalidade'                    ,'title' => 'Modalidade')
      )
    ),
    array( "name" => "comercial", "title" => "Comercial", "components" => 
      array(
        array('name' => 'pedido' , 'title' => 'Pedido'),
        array('name' => 'pedidoemail' , 'title' => 'Pedido E-Mail'),
        array('name' => 'propaganda' , 'title' => 'Propaganda'),
        array('name' => 'statuspedido' , 'title' => 'Status do Pedido'),
        array('name' => 'metodopagamento' , 'title' => 'Método de Pagamento')
      )
    ),
    array( "name" => "mercadoria", "title" => "Mercadoria", "components" => 
      array(
        array('name' => 'categoria' , 'title' => 'Categoria'),
        array('name' => 'especificacaotecnica' , 'title' => 'Especificação Técnica'),
        array('name' => 'marca' , 'title' => 'Marca'),
        array('name' => 'peso' , 'title' => 'Peso'),
        array('name' => 'produto' , 'title' => 'Produto'),
        array('name' => 'subcategoria' , 'title' => 'Subcategoria'),
        array('name' => 'tamanhoproduto' , 'title' => 'Tamanho Produto'),
        array('name' => 'tipoproduto' , 'title' => 'Tipo de Produto'),
        array('name' => 'unidademedida' , 'title' => 'Unidade de Medida'),
        array('name' => 'fichatecnica' , 'title' => 'Ficha Técnica'),
		    array('name' => 'grupoproduto' , 'title' => 'Grupo de Produto'),
        array('name' => 'produtocor' , 'title' => 'Cor de Produto'),
        array('name' => 'produtotamanho' , 'title' => 'Tamanho de Produto')
      )
    ),
    array( "name" => "pagseguro", "title" => "Pagseguro", "components" => 
      array(
        array( 'name' => 'pagseguro' , 'title' => 'Configurações' ),
        array( 'name' => 'statuspedidopagseguro' , 'title' => 'Status de Pagamento' ),
        array( 'name' => 'metodopagamentopagseguro' , 'title' => 'Método de Pagamento' )
      )
    ),
    array( "name" => "atendimento", "title" => "Atendimento", "components" => 
      array(
        array('name' => 'diafechado' , 'title' => 'Dia Fechado'),
        array('name' => 'horaatendimento' , 'title' => 'Hora de Atendimento')
      )
    ),
    array( "name" => "representante", "title" => "Representante", "components" => 
      array(
        array( 'name' => 'comissao' , 'title' => 'Comissão' ),
        array( 'name' => 'representante' , 'title' => 'Representante' )
      )
    ),
    array( "name" => "dashboard", "title" => "Dashboard", "components" => 
      array(
        array( 'name' => 'indicadores' , 'title' => 'Indicadores' ),
        array( 'name' => 'visitantes' , 'title' => 'Visitantes' )
      )
    ),
    array( "name" => "estoque", "title" => "Estoque", "components" => 
      array(
        array( 'name' => 'operacaoestoque' , 'title' => 'Operação de Estoque' ),
        array( 'name' => 'posicaogeralestoque' , 'title' => 'Posição Geral de Estoque' ),
        array( 'name' => 'tipooperacaoestoque' , 'title' => 'Tipo de Operação de Estoque' )
      )
    ),
    array( "name" => "fidelizacao", "title" => "Fidelização", "components" => 
      array(
        array( 'name' => 'pontuacao' , 'title' => 'Pontuação' )
      )
    ),
    array( "name" => "preco", "title" => "Preço", "components" => 
      array(
        array( 'name' => 'tabelapreco' , 'title' => 'Tabela de Preço' )        
      )
    ),    
    array( "name" => "envio", "title" => "Envio", "components" => 
      array(
        array( 'name' => 'transportadora' , 'title' => 'Transportadora' ),
        array( 'name' => 'taxaentrega' , 'title' => 'Taxa de Entrega' ),
        array( 'name' => 'expedicao' , 'title' => 'Expedição' )
      )
    ),
    array( "name" => "endereco", "title" => "Endereço", "components" => 
      array(
        array('name' => 'pais' , 'title' => 'País'),
        array('name' => 'uf' , 'title' => 'Estado'),
        array('name' => 'cidade' , 'title' => 'Cidade'),
        array('name' => 'bairro' , 'title' => 'Bairro')
      )
    )
  );