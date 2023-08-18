<?php
	class Atributo {
		public static function load(int $subcategoria){
			$ft 			= tdc::f("atributo","=",$subcategoria);

			$retorno = [];
			$lista = getListaRegFilhoObject(getEntidadeId("ecommerce_subcategoria"),getEntidadeId("ecommerce_atributoproduto"),$subcategoria);
			foreach($lista as $l){
				array_push($retorno,array(
					"id" => $l->id,
					"descricao" => $l->descricao,
					"itens" => tdc::da("td_ecommerce_atributoprodutoopcao",tdc::f("atributo","=",$l->id))
				));
			}
			return $retorno;
		}
	}