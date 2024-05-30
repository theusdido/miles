<?php
/*
[
	{
		"group":
		{
			"campo":"codigo"
		}
	},
	{
		"order":
		{
			"campo":"codigo",
			"ordenacao":"DESC"
		}
	},
	{
		"limit":
		{
			"inicio":10,
			"quantidade":20
		}
	},	
	{	
		"offset":
		{
			"inicio":10
		}
	},	
]
*/
$propriedadeJSON = json_decode($_GET["propriedades"]);
$islimitinicio = false;
$iniciolimit = "";
$propriedades = "";
foreach($propriedadeJSON as $p){
	if ($propriedadeORDER == ""){
		if (isset($p->order)){
			$propriedadeORDER = " ORDER BY ";
			$order = $p->order;
			$orderordenacao = "";
			foreach($order as $o){
				$tipoordenacao = "";
				if (isset($o->campo)){
					if ($o->campo != ""){
						if (isset($o->ordenacao)){
							if ($o->ordenacao != ""){
								$tipoordenacao = $o->ordenacao;
							}
						}
						$orderordenacao = ($orderordenacao==""?"":",") . "{$o->campo} {$tipoordenacao}";
						$propriedadeORDER .= $orderordenacao;
					}
				}
			}
		}
	}	
	
	if ($propriedadeLIMIT == ""){
		if (isset($p->limit)){
			$limit = $p->limit;
			if (isset($limit->quantidade)){
				if (is_numeric($limit->quantidade)){
					if (isset($limit->inicio)){					
						if ($limit->inicio != '' && is_numeric($limit->inicio)){
							$islimitinicio = true;
							$iniciolimit = isset($limit->inicio)?$limit->inicio.',':'';
						}
					}	
					
					$propriedadeLIMIT = " LIMIT {$iniciolimit}{$limit->quantidade} ";
				}
			}
		}
	}
	
	if ($propriedadeOFFSET == ""){
		if (isset($p->offset)){
			$offset = $p->offset;
			if (isset($offset->inicio)){
				if (is_numeric($offset->inicio)){
					if (!$islimitinicio){
						 $propriedadeOFFSET = " OFFSET {$offset->inicio} ";
					}	
				}
			}
		}	
	}
	
	if ($propriedadeGROUP == ""){
		if (isset($p->group)){
			$group = $p->group;					
			if (isset($group->campo)){
				if ($group->campo != ""){
					$propriedadeGROUP = " GROUP BY {$group->campo} ";
				}
			}
		}	
	}
}
$propriedades = 
	($propriedadeGROUP!=""?$propriedadeGROUP:"") . " " . 
	($propriedadeORDER!=""?$propriedadeORDER:"") . " " . 
	($propriedadeLIMIT!=""?$propriedadeLIMIT:"") . " " . 
	($propriedadeOFFSET!=""?$propriedadeOFFSET:"")	
;