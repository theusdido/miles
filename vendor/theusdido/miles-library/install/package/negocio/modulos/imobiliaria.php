<?php
  // Módulo Imobiliária
  $modules = array(
    array( "name" => "geral", "title" => "Geral", "components" =>
      array(
        array('name' => 'empresas' , 'title' => 'Empresas'),
        array('name' => 'feriado' , 'title' => 'Feriado'),
        array('name' => 'mobiliado' , 'title' => 'Mobiliado'),
        array('name' => 'operadoratelefone' , 'title' => 'Operadora de Telefone'),
        array('name' => 'periodicidade' , 'title' => 'Periodicidade')
      )
    ),    
    array( "name" => "locador", "title" => "Locador", "components" =>
      array(
        array('name' => 'locador' , 'title' => 'Locador'),
        array('name' => 'extrato' , 'title' => 'Extrato')
      )
    ),
    array( "name" => "locatario", "title" => "Locatário", "components" =>
      array(
        array('name' => 'locatario' , 'title' => 'Locatário')
      )
    ),    
    array( "name" => "imovel", "title" => "Imóvel", "components" =>
      array(
        array('name' => 'imovel' , 'title' => 'Imóvel'),
        array('name' => 'categoriaimovel' , 'title' => 'Categoria'),
        array('name' => 'construtora' , 'title' => 'Construtora'),
        array('name' => 'empreendimento' , 'title' => 'Empreendimento'),
        array('name' => 'imovelendereco' , 'title' => 'Endereço'),
        array('name' => 'imovelfoto' , 'title' => 'Foto'),
        array('name' => 'imovelproprietario' , 'title' => 'Proprietário'),
        array('name' => 'itensimovel' , 'title' => 'Itens'),
        array('name' => 'chaves' , 'title' => 'Chaves'),
        array('name' => 'iptu' , 'title' => 'IPTU'),
        array('name' => 'unidadeimovel' , 'title' => 'Unidade'),
        array('name' => 'unidadesimovel' , 'title' => 'Unidades')
      )
    ),
    array( "name" => "pessoa", "title" => "Pessoa", "components" =>
      array(
        array('name' => 'pessoaendereco' , 'title' => 'Endereço'),
        array('name' => 'pessoafisica' , 'title' => 'Física'),
        array('name' => 'pessoajuridica' , 'title' => 'Jurídica'),
        array('name' => 'profissao' , 'title' => 'Profissão'),
        array('name' => 'ramoatividade' , 'title' => 'Ramo de Atividade'),
        array('name' => 'redesocial' , 'title' => 'Rede Social'),
        array('name' => 'relacao' , 'title' => 'Relação'),
        array('name' => 'telefone' , 'title' => 'Telefone'),
        array('name' => 'tipodocumento' , 'title' => 'Tipo de Documento'),
        array('name' => 'tipodocumentoidentificacao' , 'title' => 'Tipo de Documento de Identificação'),
        array('name' => 'dadosadicionais' , 'title' => 'Dados Adicionais'),
        array('name' => 'email' , 'title' => 'E-Mail'),
        array('name' => 'estadocivil' , 'title' => 'Estado Civíl')
      )
    ),
    array( "name" => "contrato", "title" => "Contrato", "components" =>
      array(
        array('name' => 'contrato' , 'title' => 'Contrato'),
        array('name' => 'evento' , 'title' => 'Evento'),
        array('name' => 'movimentacaomensaltipoevento' , 'title' => 'Tipo de Movimentação Mensal de Evento'),
        array('name' => 'movimentacaomensalevento' , 'title' => 'Movimentação Mensal de Evento'),
        array('name' => 'operacaoevento' , 'title' => 'Operação de Evento'),
        array('name' => 'indicereajuste' , 'title' => 'Índice de Reajuste'),
        array('name' => 'modalidadegarantia' , 'title' => 'Garantia de Garantia'),
        array('name' => 'periodogarantia' , 'title' => 'Período de Garantia'),
        array('name' => 'seguradora' , 'title' => 'Seguradora')
      )
    ),
    array( "name" => "condominio", "title" => "Condomínio", "components" =>
      array(
        array('name' => 'administradoracondominio' , 'title' => 'Administradora')
      )
    ),
    array( "name" => "crm", "title" => "CRM", "components" =>
      array(
        array('name' => 'atendente' , 'title' => 'Atendente'),
        array('name' => 'listainteresse' , 'title' => 'Lista de Interesse'),
        array('name' => 'listainteresseimovel' , 'title' => 'Lista de Interesse ( Imóveis )')
      )
    ),
    array( "name" => "endereco", "title" => "Endereço", "components" =>
      array(
        array('name' => 'endereco' , 'title' => 'Endereço'),
        array('name' => 'pais' , 'title' => 'País'),
        array('name' => 'estado' , 'title' => 'Estado'),
        array('name' => 'cidade' , 'title' => 'Cidade'),
        array('name' => 'bairro' , 'title' => 'Bairro')
      )
      ),
    array( "name" => "financeiro", "title" => "Financeiro", "components" =>
      array(
        array('name' => 'banco' , 'title' => 'Banco'),
        array('name' => 'condicaopagamento' , 'title' => 'Condição de Pagamento'),
        array('name' => 'contabancaria' , 'title' => 'Conta Bancária'),
        array('name' => 'formapagamento' , 'title' => 'Forma de Pagamento'),
        array('name' => 'operadorcaixa' , 'title' => 'Operador(a) Caixa'),
        array('name' => 'movimentacaomensal' , 'title' => 'Movimentação Mensal')
      )
      ),
    array( "name" => "edificil", "title" => "Edifícil", "components" =>
      array(
        array('name' => 'edificil' , 'title' => 'Edifícil'),
        array('name' => 'edificilendereco' , 'title' => 'Endereço')
      )
      ),
    array( "name" => "motivo", "title" => "Motivo", "components" =>
      array(
        array('name' => 'motivoavisoentregaimovel' , 'title' => 'Motivo de Entrega de Imóvel'),
        array('name' => 'motivodesocupacao' , 'title' => 'Motivo Desocupação'),
        array('name' => 'motivonaolocacao' , 'title' => 'Motivo da Não Alocação do Imóvel'),
        array('name' => 'motivoretiradaimovel' , 'title' => 'Motivo da Retirada do Imóvel'),
        array('name' => 'motivoexclusaoboletos' , 'title' => 'Motivo da Exclusão do Boleto'),
        array('name' => 'motivoexclusaoboletos' , 'title' => 'Motivo da Exclusão do Boleto'),
        array('name' => 'motivoexclusaoboletos' , 'title' => 'Motivo da Exclusão do Boleto'),
        array('name' => 'motivoexclusaoboletos' , 'title' => 'Motivo da Exclusão do Boleto')
      )
      ),      
    array( "name" => "tipo", "title" => "Tipo", "components" =>
      array(
        array('name' => 'tipochave' , 'title' => 'Tipo de Chave'),
        array('name' => 'tipodespesa' , 'title' => 'Tipo de Despesa'),
        array('name' => 'tipoendereco' , 'title' => 'Tipo de Endereço'),
        array('name' => 'tipoimovel' , 'title' => 'Tipo de Imóvel'),
        array('name' => 'tipomoeda' , 'title' => 'Tipo de Moeda'),
        array('name' => 'tipopessoa' , 'title' => 'Tipo de Pessoa'),
        array('name' => 'tipopiso' , 'title' => 'Tipo de Piso'),
        array('name' => 'tipotelefone' , 'title' => 'Tipo de Telefone')
      )
    )    
  );