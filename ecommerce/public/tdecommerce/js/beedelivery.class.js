function BeeDelivery(){
    this.project = 1;
    this.regioes = [];
    this.elemento;
}

with (BeeDelivery){
    prototype.setRegiao = function() {
        var instancia = this;
        $.ajax({
            url:session.path_webservice,
            crossDomain: true,
            data:{
                controller:"website/ecommerce/transportadoras/beedelivery",
                op:"listaregioes",
                project:this.project,
                token:"43fa61c213c85fb565748a5dd50c8186",
                service:"beedelivery/regioes"
            },
            complete:function(ret){
                var retorno = JSON.parse(ret.responseText);
                var dados   = retorno.dados;

                instancia.elemento.append($("<option value=''>SELECIONE</option>"));
                for(r in dados.regions){
                    var dado 	= dados.regions[r];
                    var option 	= $("<option value='"+dado.region_id+"'>" + dado.description + "</option>");					
					switch(dado.region_id){
						case 5469:
						case 5465:
						case 5461:
						case 5462:
						case 5464:
						case 5463:
						case 5468:
						case 5467:
						case 5472:
						case 5471:
						case 5470:
						case 5474:
						case 5473:
						case 5466:
						case 5460:
						case 8653:
						case 5459:
						case 8020:
						case 8017:
						case 8021:
						case 8016:
						case 8018:
						case 8019:
						case 8015:
						case 15494:
						case 5477:
						case 19264:
						case 6387:
						case 5487:
						case 14496:
						case 6392:
						case 6384:
						case 6385:
						case 6383:
						case 6390:
						case 6391:
						case 8226:
						case 6389:
						case 12382:
						case 6388:
						case 6386:
						case 6371:
						case 5483:
						case 5482:
						case 4442:
						case 15033:
						case 8390:
						case 5478:
						case 11387:
						case 5486:
						case 5485:
						case 5481:
						case 3635:
						case 3580:
						case 12536:
						case 15017:
						case 3602:
						case 3576:
						case 12731:
						case 5476:
						case 8640:
						case 8637:
						case 5475:
						case 4444:
						case 5480:
						case 5484:
						case 8191:
						case 8059:
						case 8658:
						case 5479:
						case 10114:
							var is_add 	= false;
						break;
						default:
							var is_add 	= true;
					}
					if(is_add){						
						instancia.elemento.append(option);
						instancia.regioes[dado.region_id] = dado;
					}
                }
                instancia.setValores();
            },
            error:function(ret){
                console.log(ret.responseText);
            }
        });
    }
    prototype.setValores = function(regiao){
        
        if (regiao == null || regiao == undefined){
            var regiao = this.elemento.val();
        }
        if (regiao != '' && regiao != undefined){
            var fee         = parseFloat(this.regioes[regiao].fee);
            var returnFee   = parseFloat(this.regioes[regiao].returnFee);
            var fullfee     = fee + returnFee;

            $("#bee-fee").html(fee.toLocaleString("pt-BR",{ style: 'currency', currency: 'BRL' }));
            $("#bee-returnfee").html(returnFee.toLocaleString("pt-BR",{ style: 'currency', currency: 'BRL' }));       
            $("#bee-fullfee").html(fullfee . toLocaleString("pt-BR",{ style: 'currency', currency: 'BRL' }));
            carrinho.setValorFrete(fullfee);
        }else{
            carrinho.setValorFrete(0);
        }
        carrinho.setValorTotal();
        
    }
    prototype.setElemento = function(elemento){
        var instancia = this;
        this.elemento = elemento;
        elemento.change(function(){
            instancia.setValores($(this).val());
        });
        
    }
}
