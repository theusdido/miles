<?php

$sql = "
    SET SQL_SAFE_UPDATES = 0;

    # Variáveis
    SET @categoria 			= getEntidadeId('td_ecommerce_categoria');
    SET @subcategoria		= getEntidadeId('td_ecommerce_subcategoria');
    SET @atributoproduto 	= getEntidadeId('td_ecommerce_atributoproduto');
    SET @atributoopcao		= getEntidadeId('td_ecommerce_atributoprodutoopcao');
    SET @produto			= getEntidadeId('td_ecommerce_produto');
    SET @lojista			= getEntidadeId('td_ecommerce_lojista');
    SET @loja				= getEntidadeId('td_ecommerce_loja');
    SET @endereco			= getEntidadeId('td_ecommerce_endereco');
    SET @usuario			= getEntidadeId('td_usuario');
    SET @cliente			= getEntidadeId('td_ecommerce_cliente');
    SET @sbatributo			= getEntidadeId('td_ecommerce_subcategoriaatributo');
    SET @lojaentrega		= getEntidadeId('td_ecommerce_lojaentrega');

    # Opções do Atributos
    DELETE FROM td_lista WHERE entidadepai =  @atributoproduto AND entidadefilho = @atributoopcao;
    DELETE FROM td_ecommerce_atributoprodutoopcao;

    # Atributos 
    DELETE FROM td_lista WHERE entidadepai = @subcategoria AND entidadefilho =  @atributoproduto;
    DELETE FROM td_ecommerce_atributoproduto;

    # Subcategoria
    DELETE FROM td_lista WHERE entidadepai = @categoria AND entidadefilho =  @subcategoria;
    DELETE FROM td_lista WHERE entidadepai = @subcategoria AND entidadefilho =  @sbatributo;
    DELETE FROM td_ecommerce_subcategoria WHERE loja <> 0;

    # Produto 
    DELETE FROM td_ecommerce_produto;

    # Pedido
    DELETE FROM td_ecommerce_pedido;
    DELETE FROM td_ecommerce_pedidoitem;

    # Lojista
    DELETE FROM td_lista WHERE entidadepai = @lojista AND entidadefilho =  @loja;
    DELETE FROM td_ecommerce_lojista;

    # Loja
    DELETE FROM td_lista WHERE entidadepai = @loja AND entidadefilho =  @categoria;
    DELETE FROM td_ecommerce_loja;

    # Endereço
    DELETE FROM td_lista WHERE entidadepai = @loja AND entidadefilho =  @endereco;
    DELETE FROM td_endereco;
    DELETE FROM td_cidade;
    DELETE FROM td_bairro;

    # Usuário
    DELETE FROM td_lista WHERE entidadepai = @lojista AND entidadefilho =  @usuario;
    DELETE FROM td_lista WHERE entidadepai = @cliente AND entidadefilho =  @usuario;
    DELETE FROM td_usuario WHERE id > 2;

    # Slider
    #DELETE FROM td_slider;

    # Cliente
    DELETE FROM td_ecommerce_cliente;
    DELETE FROM td_lista WHERE entidadepai = @cliente AND entidadefilho = @endereco;
    DELETE FROM td_lista WHERE entidadepai = @cliente AND entidadefilho = @usuario;

    # Opções do Cliente
    DELETE FROM td_ecommerce_clienteopcao;

    # Propostas
    DELETE FROM td_ecommerce_proposta;

    # Negociações
    DELETE FROM td_ecommerce_negociacao;

    # Anexos
    DELETE FROM td_ecommerce_pedidoanexos;

    # Pontuação
    DELETE FROM td_ecommerce_pontuacao;

    # Transação
    #DELETE FROM td_carteiradigital_transacao;

    # Operação
    #DELETE FROM td_carteiradigital_operacao;

    # Movimentação
    DELETE FROM td_carteiradigital_movimentacao;

    # Categorias da Loja
    DELETE FROM td_lista WHERE entidadepai = @loja AND entidadefilho = @categoria;

    # Propaganda
    DELETE FROM td_ecommerce_propaganda;

    # Vendedor
    DELETE FROM td_ecommerce_vendedor;

    # Protocolo de Recuperação de Senha
    DELETE FROM td_usuario_recuperar_senha;

    # Protocolo de Confirmação da Conta
    DELETE FROM td_usuario_cadastro_confirmacao;

    # Conta
    DELETE FROM td_carteiradigital_conta;

    # Propaganda Visualização
    DELETE FROM td_ecommerce_propagandavisualizacao;

    # Atributo da Subcategoria
    DELETE FROM td_ecommerce_subcategoriaatributo;
    DELETE FROM td_lista WHERE entidadepai = @atributoproduto AND entidadefilho =  @sbatributo; 

    # Oportunidade
    DELETE FROM td_ecommerce_oportunidade;

    # Categoria Estabelecimento
    DELETE FROM td_categoriaestabelecimento;

    # Bairro
    DELETE FROM td_ecommerce_bairro;

    # Bairro
    DELETE FROM td_ecommerce_cidade;

    # Cidade
    DELETE FROM td_ecommerce_cidade;

    # Endereço
    DELETE FROM td_ecommerce_endereco;

    # Pontuação Cotação
    #DELETE FROM td_ecommerce_pontuacaocotacao;

    # Produto Foto
    DELETE FROM td_ecommerce_produtofoto;

    # Vendedor convite
    DELETE FROM td_ecommerce_vendedorconvite;

    # Lojista x Endereço
    DELETE FROM td_lista WHERE entidadepai = @lojista AND entidadefilho = @endereco;

    # Lojista x Categoria
    DELETE FROM td_lista WHERE entidadepai = @lojista AND entidadefilho = @categoria;

    # Cliente
    DELETE FROM td_cliente;

    # Cliente x Endereço
    DELETE FROM td_lista WHERE entidadepai = getEntidadeId('td_cliente') AND entidadefilho = getEntidadeId('td_endereco');

    # Usuário x Loja
    DELETE FROM td_lista WHERE entidadepai = @usuario AND entidadefilho = @loja;
    DELETE FROM td_lista WHERE entidadepai = @loja AND entidadefilho = @usuario;

    # Avaliação
    DELETE FROM td_avaliacao;

    # Loja x Entrega
    DELETE FROM td_lista WHERE entidadepai = @loja AND entidadefilho = @lojaentrega;

    # Loja Entrega
    DELETE FROM td_ecommerce_lojaentrega;
";

Transacao::Get()->exec($sql);

var_dump(PATH_CURRENT_FILE);
tdFile::rmdir(PATH_CURRENT_FILE);
tdFile::mkdir(PATH_CURRENT_FILE_TEMP);