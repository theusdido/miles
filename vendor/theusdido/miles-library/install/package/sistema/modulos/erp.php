<?php
  // Módulo ERP
  $modules = array(
    
    // Boleto
    array( "name" => "boleto", "title" => "Boleto", "components" => 
      array(
        array('name' => 'boleto' , 'title' => 'Boleto')
      )
    ),

    // Comercial
    array( "name" => "comercial", "title" => "Comercial", "components" => 
      array(
        array('name' => 'pedidovenda'       ,'title' => 'Pedido de Venda'),
        array('name' => 'statuspedidovenda' ,'title' => 'Status Pedido de Venda')
      )
    ),

    // Contábil
    array( "name" => "contabil", "title" => "Contábil", "components" => 
      array(
        array('name' => 'centrocusto'         ,'title' => 'Centro de Custo'),
        array('name' => 'elementocusto'       ,'title' => 'Elemento de Custo'),
        array('name' => 'fonterenda'          ,'title' => 'Fonte de Renda'),
        array('name' => 'situacaotributaria'  ,'title' => 'Situação Tributária')
      )
    ),

    // Financeiro
    array( "name" => "financeiro", "title" => "Financeiro", "components" => 
      array(
        array('name' => 'banco'               ,'title' => 'Banco'),
        array('name' => 'contabancaria'       ,'title' => 'Conta Bancária'),
        array('name' => 'contasapagar'        ,'title' => 'Contas à Pagar'),
        array('name' => 'contasareceber'      ,'title' => 'Contas à Receber'),
        array('name' => 'despesa'             ,'title' => 'Despesa'),
        array('name' => 'formapagamento'      ,'title' => 'Forma Pagamento'),
        array('name' => 'formarecebimento'    ,'title' => 'Forma de Recebimento'),
        array('name' => 'operacaobancaria'    ,'title' => 'Operação Bancária'),
        array('name' => 'receita'             ,'title' => 'Receita'),
        array('name' => 'tipocontabancaria'   ,'title' => 'Tipo Conta Bancária')
      )
    ),

    // Material
    array( "name" => "material", "title" => "Material", "components" => 
      array(
        array('name' => 'operacaoestoque'     ,'title' => 'Operação Estoque'),
        array('name' => 'posicaogeralestoque'   ,'title' => 'Posição Geral de Estoque'),
        array('name' => 'tipooperacaoestoque'      ,'title' => 'Tipo de Operação de Estoque')
      )
    ),

    // NFSe
    array( "name" => "nfse", "title" => "NFSe", "components" => 
      array(
        array('name' => 'nfse',             'title' => 'NFSe'),
        array('name' => 'tomador',          'title' => 'Tomador'),
        array('name' => 'servico',          'title' => 'Serviço'),
        array('name' => 'item',             'title' => 'Item'),
        array('name' => 'parcelas',         'title' => 'Parcelas'),
        array('name' => 'deducoes',         'title' => 'Deduções'),
        array('name' => 'intermediario',    'title' => 'Intermediário'),
        array('name' => 'construcaocivil',  'title' => 'Construção Civil'),
        array('name' => 'prestador',        'title' => 'Prestador'),
        array('name' => 'transportadora',   'title' => 'Transportadora')
      )
      ),

    // Pessoa
    array( "name" => "pessoa", "title" => "Pessoa", "components" => 
      array(
        array('name' => 'orgaoemissorrg'      ,'title' => 'Orgão Emissor RG'),
        array('name' => 'pessoa'              ,'title' => 'Pessoa'),
        array('name' => 'pessoafisica'        ,'title' => 'Pessoa Física'),
        array('name' => 'pessoajuridica'      ,'title' => 'Pessoa Jurídica'),
        array('name' => 'profissao'           ,'title' => 'Profissão'),
        array('name' => 'redesocial'          ,'title' => 'Rede Social'),
        array('name' => 'referenciacomercial' ,'title' => 'Referencia Comercial'),
        array('name' => 'relacao'             ,'title' => 'Relação')
      )
    ),

    // Produto
    array( "name" => "produto", "title" => "Produto", "components" => 
      array(
        array('name' => 'categoriaproduto'    ,'title' => 'Categoria de Produto'),
        array('name' => 'fabricante'          ,'title' => 'Fabricante'),
        array('name' => 'fornecedor'          ,'title' => 'Fornecedor'),
        array('name' => 'marca'               ,'title' => 'Marca'),
        array('name' => 'produto'             ,'title' => 'Produto'),
        array('name' => 'unidademedida'       ,'title' => 'Unidade Medida')
      )
    ),

    // RH
    array( "name" => "rh", "title" => "Recursos Humanos", "components" => 
      array(
        array('name' => 'colaborador'         ,'title' => 'Colaborador')
      )
    ),

    // Serviço
    array( "name" => "servico", "title" => "Serviço", "components" => 
      array(
        array('name' => 'servico'             ,'title' => 'Serviço'),
        array('name' => 'tiposervico'         ,'title' => 'Tipo de Serviço')    
      )
    )    
    
  );