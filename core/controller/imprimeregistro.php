<?php
$style = tdc::html("style");
$style->type = "text/css";
$style->add('
	hr{
		boder:1px solid #DDD;
		float:left;
		width:100%;
	}
	#cabecalho-id{
		float:left;
		width:106px;
	}
	#cabecalho-descricao{
		float:left;
	}
	#cabecalho-empresa{
		float:right;
	}
	
	#imprimirregistro-header,#imprimirregistro-body,#imprimirregistro-footer {
		float:left;
		width:100%;
	}
	#rodape-usuario{
		float:left;
	}
	#rodape-datahora {
		float:right;
	}
	.descricao-span{
		width:150px;
		float:left;
		text-align:right;
		margin-right:10px;
	}
');
$style->mostrar();
$imprimir = tdc::o("imprimirregistro",[tdc::r("entidade"),tdc::r("registro")]);
$imprimir->imprimir();