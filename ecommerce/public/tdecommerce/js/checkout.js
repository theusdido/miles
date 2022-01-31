var currentetapacheckout = 1;
$(document).ready(function(){
	
	$("#etapas-checkout").show();
    
	
	carrinho.checkout();
	permitirEtapaPagamento();

	if ($(window).width() <= 480){
		$("#linha-etapas .btn-group").addClass("btn-group-vertical");
	}else{
		$("#linha-etapas .btn-group").removeClass("btn-group-vertical");
	}
	$(".btn-etapa-checkout").removeAttr("disabled");

	// Configuração inicial do pagamento
	$("#btn-checkout-pagamento").attr("disabled",true);

    // Inicializa a borda no resumo da compra
    bordaBtnCheckout(1);

    // Força a clicar no botão de entrega
    $("#btn-checkout-entrega").removeClass("btn-success btn-default").addClass("btn-danger");
    $("#btn-checkout-pagamento,.etapa-proxima").attr("disabled",true);
    $(".td-valorfrete-carrinho").html("R$ 0,00");

    if (currentetapa == 2){
        $("#btn-checkout-autenticacao").click();
    }

});


function carregarEtapaCheckout(url){
	$("#etapa-checkout").html(
	'<div style="width:100%;margin:75px auto">' +
		'<center>' +
			'<img width="32" align="middle" src="'+session.url_system_theme+'loading.gif">' +
			'<p class="text-muted">Aguarde</p>' +
		'</center>' +
	'</div>'
	);
	$("#etapa-checkout").load(session.path_tdecommerce + url);
}
$(".list-group .list-group-item").click(function(){
	$(".list-group .list-group-item").removeClass("disabled");
	$(this).addClass("disabled");
});
function verificaEntregaStatus(){
	$("#btn-checkout-entrega").removeClass("btn-default");
	if (parseInt($("#taxaok").val()) != 1 ){
		$("#btn-checkout-entrega").addClass("btn-danger");
		$("#btn-checkout-entrega").removeClass("btn-success");
	}else{
		$("#btn-checkout-entrega").removeClass("btn-danger");
		$("#btn-checkout-entrega").addClass("btn-success");
	}
}

function VerificarEtapaPagamento(){
	$("#btn-checkout-pagamento").click();
}

function verificaEtapaEndereco(){
	if ($("#btn-checkout-endereco").hasClass("btn-success")){
		$("#btn-checkout-endereco").click();
	}
}

function verificaEtapaAutenticacao(){
	if ($("#btn-checkout-autenticacao").hasClass("btn-success")){
		$("#btn-checkout-autenticacao").click();
	}
}

function bordaBtnCheckout(etapa){
	$("#etapas-checkout button").css("border-bottom","3px solid transparent");
	var btncheckout = "";
	switch(etapa){
		case 1: btncheckout = "#btn-checkout-resumocompra"; break;
		case 2: btncheckout = "#btn-checkout-autenticacao"; break;
		case 3: btncheckout = "#btn-checkout-endereco"; break;
		case 4: btncheckout = "#btn-checkout-entrega"; break;
		case 5: btncheckout = "#btn-checkout-pagamento"; break;
	}
	currentetapacheckout = etapa;
	$(btncheckout).css("border-bottom","3px solid #000");
}

$("#btn-checkout-resumocompra").click(function(){
	bordaBtnCheckout(1);
	carrinho.checkout();
});

function permitirEtapaPagamento(){
	if ($(".btn-etapa-checkout").hasClass("btn-danger")){
		$("#btn-checkout-pagamento").attr("disabled",true);
	}
}

$("#btn-checkout-pagamento").click(function(){
	if ($("#btn-checkout-entrega").hasClass("btn-success")){
		carregarEtapaCheckout('index.php?controller=resumodacompra&op-resumo=pagamento');
	}
});
