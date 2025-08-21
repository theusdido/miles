 <?php
/*
	* Framework MILES
	* @license : Teia Tecnologia WEB
	* @link https://teia.tec.br

	* tdRelacionamento
	* Data de Criacao: 21/02/2022
	* @author: [ Edilson Bitencourt ] (@theusdido)
*/
class tdRelacionamento 
{
	/*  
		* Método getCardinalidade
		* Data de Criacao: 21/02/2022
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		* Retorna a cardinalidade do relacionamento

	*/
	public static function getCardinalidade($relacionamento_tipo)
	{
        switch($relacionamento_tipo){

            case 1: return '11'; break;
            case 3: return '11'; break;
            case 4: return '11'; break;
            case 7: return '11'; break;
            case 9: return '11'; break;
    
            case 2: return '1N'; break;
            case 6: return '1N'; break;
            case 8: return '1N'; break;
    
            case 5: return 'NN'; break;
            case 10: return 'NN'; break;
            default: return '';
        }
	}

	/*  
		* Método updateCardinalidade
		* Data de Criacao: 21/02/2022
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		* Atualizada a cardinalidade do relacionamento

	*/
    public static function updateCardinalidade()
    {

        foreach (tdc::d(RELACIONAMENTO) as $r)
        {
            $relacionamento = tdc::p(RELACIONAMENTO,$r->id);
            $relacionamento->cardinalidade = self::getCardinalidade($r->tipo);
            $relacionamento->isUpdate();
            $relacionamento->armazenar();
        }

        Transacao::Fechar();
    }
}