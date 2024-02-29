<style>
	.ar-moldura{
		border:1px solid #DDD;
		width:210mm;
		height:91mm;
	}
	.ar-borda-tracejada{
		border:1px dashed #000;
		width:188mm;
		margin:0 10mm;
		height:91mm;
		position:absolute;
	}
	.ar-quadro-destinatario{
		border:1px solid #000;
		width:74mm;
		height:49mm;
		position:absolute;
		top:10mm;
	}
	.ar-quadro-tentativa{
		border:1px solid #000;
		width:71mm;
		height:52mm;
		position:absolute;
		left:74mm;
		top:10mm;
	}
	.ar-quadro-carimbo{
		border:1px solid #000;
		width:43mm;
		height:52mm;
		position:absolute;
		left:145mm;
		top:10mm;
	}
	.ar-quadro-endereco{
		border-top:1px solid #DDD;
		border-left:1px solid #000;
		border-right:1px solid #000;
		border-bottom:1px solid #000;
		width:74mm;
		height:10mm;
		position:absolute;
		top:59mm;
	}
	.ar-quadro-declaracao{
		border-top:1px solid #000;
		border-left:1px solid #000;
		border-right:1px solid #000;
		border-bottom:1px solid #DDD;
		width:71mm;
		height:7mm;
		position:absolute;
		top:62mm;
		left:74mm;
	}
	.ar-quadro-rubrica{
		border:1px solid #000;
		width:43mm;
		height:28mm;
		position:absolute;
		top:62mm;
		left:145mm;
	}
	.ar-quadro-usocliente{
		border:1px solid #000;
		width:145mm;
		height:7mm;		
		position:absolute;
		top:69mm;
	}
	.ar-quadro-assinatura{
		border-top:1px solid #000;
		border-left:1px solid #000;
		border-right:1px solid #000;
		border-bottom:1px solid #DDD;
		width:107mm;
		height:7mm;		
		position:absolute;
		top:76mm;
	}
	.ar-quadro-dataentrega{
		border-top:1px solid #000;
		border-left:1px solid #DDD;
		border-right:1px solid #000;
		border-bottom:1px solid #DDD;
		width:38mm;
		height:7mm;		
		position:absolute;	
		top:76mm;
		left:107mm;
	}
	.ar-quadro-nome{
		border:1px solid #000;
		width:107mm;
		height:7mm;		
		position:absolute;
		top:83mm;
	}
	.ar-quadro-documentoidentidade{
		border-top:1px solid #000;
		border-left:1px solid #DDD;
		border-right:1px solid #000;
		border-bottom:1px solid #000;
		width:38mm;
		height:7mm;		
		position:absolute;	
		top:83mm;
		left:107mm;
	}
	.ar-logo{
		position:absolute;
		width:75mm;
		top:0.8mm;
	}
	.ar-aoscuidados{
		font-family:Arial;
		font-size:6px;
		position:absolute;
		left:77mm;
		top:2mm;
		text-align:center;
	}
	.ar-innovare-logo{
		position:absolute;
		left:93mm;
		top:0.5mm;
	}
	.ar-triangulo{
		position:absolute;
		left:125mm;
		width:20mm;
		z-index:1000;
	}
	.mp{
		font-family:Arial;
		font-size:18px;
		font-weight:bold;
		position:absolute;
		right:5mm;
		top:1mm;
	}
	.ar-destinatario{
		font-family:Arial;
		font-size:9px;
		font-weight:bold;		
		float:left;
		clear:left;
		
	}
	.ar-destinatario-nome,.ar-destinatario-endereco,.ar-destinatario-cidade{
		font-family:Arial;
		font-size:9px;
		float:left;
		clear:left;
	}
	.ar-destinatario-cep{
		font-family:Arial;
		font-size:12px;
		font-weight:bold;	
		float:left;
		clear:left;
	}
	.tentativasdeentrega,.tentativasdeentrega1,.tentativasdeentrega2,.tentativasdeentrega3{
		font-family:Arial;
		font-size:7px;
		font-weight:bold;	
		float:left;
		clear:left;
		margin:3px;
	}
	.atencao{
		font-family:Arial;
		font-size:8px;
		font-weight:bold;
		position:absolute;
		top:10mm;
		right:5mm;
	}
	.atencao-texto{
		font-family:Arial;
		font-size:8px;
		position:absolute;
		top:12mm;
		right:5mm;
		width:10mm;
		text-align:center;
	}
	.motivodadevolucao{
		font-family:Arial;
		font-size:7px;
		float:left;
		clear:left;
		width:70mm;
		margin-top:10mm;
	}
	.motivodadevolucao-quadro1,.motivodadevolucao-quadro2,.motivodadevolucao-quadro3,.motivodadevolucao-quadro4,.motivodadevolucao-quadro5
	,.motivodadevolucao-quadro6,.motivodadevolucao-quadro7,.motivodadevolucao-quadro8{
		font-family:Arial;
		font-size:7px;
		float:left;
		border:1px solid #000;
		padding:1px 2px;
	}
	.motivodadevolucao-texto1,.motivodadevolucao-texto2,.motivodadevolucao-texto3,.motivodadevolucao-texto4,.motivodadevolucao-texto5
	,.motivodadevolucao-texto5,.motivodadevolucao-texto6,.motivodadevolucao-texto7,.motivodadevolucao-texto8{
		font-family:Arial;
		font-size:7px;
		float:left;
		margin:2px 1px;
		width:32mm;
	}
	.carimbo-texto{
		font-family:Arial;
		font-size:6px;
		float:left;
		width:100%;		
		text-align:center;
	}
	.carimbo-sl{
		font-family:Arial;
		font-size:20px;
		font-weight:bold;
		position:absolute;
		bottom:1mm;
		left:1mm;
	}
	.endereco-devolucao{
		font-family:Arial;
		font-size:8px;
		font-weight:bold;
		float:left;		
	}
	.endereco-centralizador{
		font-family:Arial;
		font-size:7px;
		font-weight:bold;
		float:left;	
		clear:left;		
	}
	.declaracao-conteudo,.rubrica-carteiro,.usocliente-texto,.usocliente-texto,.dataentrega-texto,.dataentrega-mascara,.nome-texto,.documentoidentidade-texto{
		font-family:Arial;
		font-size:6px;		
	}
</style>

<div class="ar-moldura">
	<div class="ar-borda-tracejada">
		<img src="../imagens/ardigital-logo.png" class="ar-logo"/>
		<div class="ar-aoscuidados">
			AC CENTRAL/SC <br /> 30/09/2015 <br /> LOTE 45
		</div>
		<img src="../imagens/ar-innovare-logo.png" class="ar-innovare-logo" />
		<img src="../imagens/ardigital-triangulo.png" class="ar-triangulo" />
		<label class="mp">MP</label>
		<div class="ar-quadro-destinatario">
			<label class="ar-destinatario">DESTINATÁRIO</label>
			<label class="ar-destinatario-nome">Joaquim Manoel da Silva</label>
			<label class="ar-destinatario-endereco">Q.202, Lote 2,Bloco A, Apto. 100</label>
			<label class="ar-destinatario-cidade">Brasilia - DF</label>
			<label class="ar-destinatario-cep">71937-720</label>
		</div>
		<div class="ar-quadro-tentativa">
			<label class="tentativasdeentrega">TENTATIVAS DE ENTREGA</label>
			<label class="tentativasdeentrega1">1&ordf; __/__/____ __:__h</label>
			<label class="tentativasdeentrega2">2&ordf; __/__/____ __:__h</label>
			<label class="tentativasdeentrega3">3&ordf; __/__/____ __:__h</label>
			<label class="atencao">ATEN&Ccedil;&Atilde;O:</label>
			<label class="atencao-texto">ap&oacute;s a 3&ordf; tentativa, devolver o objeto.</label>
			<label class="motivodadevolucao">MOTIVOS DA DEVOLU&Ccedil;&Atilde;O</label>
			<label class="motivodadevolucao-quadro1">1</label>
			<label class="motivodadevolucao-texto1">Mudou-se</label>
			<label class="motivodadevolucao-quadro2">2</label>
			<label class="motivodadevolucao-texto2">Endere&ccedil;o Insuficiente</label>
			<label class="motivodadevolucao-quadro3">3</label>
			<label class="motivodadevolucao-texto3">N&atilde;o Existe N&uacute;mero</label>			
			<label class="motivodadevolucao-quadro4">4</label>
			<label class="motivodadevolucao-texto4">Desconhecido</label>			
			<label class="motivodadevolucao-quadro5">5</label>
			<label class="motivodadevolucao-texto5">Recusado</label>
			<label class="motivodadevolucao-quadro6">6</label>
			<label class="motivodadevolucao-texto6">N&atilde;o Procurado</label>
			<label class="motivodadevolucao-quadro7">7</label>
			<label class="motivodadevolucao-texto7">Ausente</label>
			<label class="motivodadevolucao-quadro8">8</label>
			<label class="motivodadevolucao-texto8">Falecido</label>	
			<label class="motivodadevolucao-quadro1">9</label>
			<label class="motivodadevolucao-texto1">Outros______________________________________________</label>			
		</div>
		<div class="ar-quadro-carimbo">
			<label class="carimbo-texto">CARIMBO <br/> UNIDADE DE ENTREGA</label>
			<label class="carimbo-sl">SL</label>
		</div>
		<div class="ar-quadro-endereco">
			<label class="endereco-devolucao">ENDERE&Ccedil;O PARA DEVOLU&Ccedil;&Atilde;O DO AR</label>
			<label class="endereco-centralizador">Centralizador Regional</label>
		</div>
		<div class="ar-quadro-declaracao">
			<label class="declaracao-conteudo">DECLARA&Ccedil;&Atilde;O DE CONTE&Uacute;DO (OPCIONAL)</label>
		</div>
		<div class="ar-quadro-rubrica">
			<label class="rubrica-carteiro">RUBRICA E MATR&Iacute;CULA DO CARTEIRO</label>
		</div>
		<div class="ar-quadro-usocliente">
			<label class="usocliente-texto">PARA USO DO CLIENTE (OPCIONAL)</label>
		</div>
		<div class="ar-quadro-assinatura">
			<label class="usocliente-texto">ASSINATURA DO RECEBEDOR</label>
		</div>
		<div class="ar-quadro-dataentrega">
			<label class="dataentrega-texto">DATA DE ENTREGA</label>
			<label class="dataentrega-mascara">__/__/____</label>
		</div>
		<div class="ar-quadro-nome">
			<label class="nome-texto">NOME LEG&iacute;VEL DO RECEBEDOR</label>
		</div>
		<div class="ar-quadro-documentoidentidade">
			<label class="documentoidentidade-texto">N&ordm; DOCUMENTO DE IDENTIDADE</label>
		</div>				
	</div>
</div>