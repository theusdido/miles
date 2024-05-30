<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link http://teia.tec.br

    * Classe para implementar recursos do MDM ( Miles Database Module )
    * Data de Criacao: 15/01/2022
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class mdm
{
    private $conceito_id = 0;
    private $entidade; 
    private $conceito_type = 'cadastro';
    public function __construct
    (
        $conceito_id,
        $conceito_type = null,
        $conceito_entidade_id = 0
    )
    {
        $this->conceito_id = $conceito_id;
        if ($conceito_type != null) $this->conceito_type = $conceito_type;
        $this->setEntidade($conceito_entidade_id);
    }

    public function setEntidade
    (
        $entidade_id
    ){
        
        if ($this->conceito_type == 'cadastro' && $entidade_id == 0){
            $entidade_id = $this->conceito_id;
        }

        $this->entidade = tdc::e($entidade_id);
    }
    public function getEntidade(){
        return $this->entidade;
    }

    public function gerarHtml
    ()
    {
        $entidade 		= $this->getEntidade();
        $descricao_doc 	= $entidade->id . " - " . $entidade->descricao . "[ ".$entidade->nome." ]";

        switch($this->conceito_type){
            case 'cadastro':
                $path 		= PATH_FILES_CADASTRO . $entidade->id . "/";
            break;
            case 'consulta':
                $path 		= PATH_FILES_CONSULTA . tdc::r('id') . "/";
            break;
            case 'movimentacao':
                $path 		= PATH_FILES_MOVIMENTACAO . tdc::r('id') . '/';
            break;
            case 'relatorio':
                $path 		= PATH_FILES_RELATORIO . tdc::r('id') . '/';	
            break;
        }
    
        // Documentação
        $datacriacaodoc = "* @Data de Criacao: ".date("d/m/Y H:i:s");
        $authordoc 		= "* @Criado por: ".Session::get('username').", @id: ".Session::get('userid');
        $paginadoc 		= "* @Página: {$descricao_doc}";
    
        // Cria o diretório do registro caso não exista
        if (!file_exists($path)){
            $old = umask(0);
            tdFile::mkdir($path,0777 , true);
            umask($old);
        }
    
        // Cria o arquivo HTML
        $htmlFile = $path . $entidade->nome . '.html';
        $fp = fopen($htmlFile ,'w');
        fwrite($fp,htmlespecialcaracteres(

            getURL(URL_MILES,array(
                'params' => array(
                    'entidade'          => $entidade->id,
                    'currentproject'    => CURRENT_PROJECT_ID,          
                    'controller'        => 'gerar' . $this->conceito_type,
                    'principal'         => true
                )
            ))

            ,1));
        fclose($fp);
    
        // Cria o arquivo HTML Embutido Dinâmico
        $dhtmlFile = $path . $entidade->nome . '.htm';
        if (!file_exists($dhtmlFile)){
            $fp = fopen($dhtmlFile,'w');
            fwrite($fp,"<!--\n * HTML Personalizado \n {$datacriacaodoc} \n {$authordoc} \n {$paginadoc} \n\n Escreve seu código HTML personalizado aqui! \n-->\n");
            fclose($fp);
        }	
    
        // Cria o arquivo CSS
        $cssFile = $path . $entidade->nome . '.css';
        if (!file_exists($cssFile)){
            $fp = fopen($cssFile ,'w');
            fwrite($fp,"/*\n * CSS Personalizado \n {$datacriacaodoc} \n {$authordoc} \n {$paginadoc} \n\n Escreve seu código CSS personalizado aqui! \n*/\n");
            fclose($fp);
        }
    
        // Cria o arquivo JS
        $jsFile = $path . $entidade->nome . '.js';
        if (!file_exists($jsFile)){
            $fp = fopen($jsFile ,'w');
            fwrite($fp,"/*\n * JS Personalizado \n {$datacriacaodoc} \n {$authordoc} \n {$paginadoc} \n */\n\n");
            fwrite($fp,"// Invocado ao clicar no botão Novo");
            fwrite($fp,"\n");
            fwrite($fp,"function beforeNew(){");
            fwrite($fp,"\n\t var btnnew = arguments[0];");
            fwrite($fp,"\n");
            fwrite($fp,"}");
            fwrite($fp,"\n");
            fwrite($fp,"// Executa após o carregamento padrão de uma novo registro");
            fwrite($fp,"\n");
            fwrite($fp,"function afterNew(){");					
            fwrite($fp,"\n\t var contexto = arguments[0];");
            fwrite($fp,"\n");					
            fwrite($fp,"}");
            fwrite($fp,"\n");
            fwrite($fp,"// Invocado ao clicar no botão Salvar");
            fwrite($fp,"\n");
            fwrite($fp,"function beforeSave(){");
            fwrite($fp,"\n\t var btnsave = arguments[0];");
            fwrite($fp,"\n");
            fwrite($fp,"}");
            fwrite($fp,"\n");
            fwrite($fp,"// Executa após o salvamento padrão de um registro");
            fwrite($fp,"\n");
            fwrite($fp,"function afterSave(){");
            fwrite($fp,"\n\t var fp = arguments[0];");
            fwrite($fp,"\n\t var btnsave = arguments[1];");
            fwrite($fp,"\n");
            fwrite($fp,"}");
            fwrite($fp,"\n");
            fwrite($fp,"// Invocado ao clicar no botão Editar ");
            fwrite($fp,"\n");
            fwrite($fp,"function beforeEdit(){");
            fwrite($fp,"\n\t var entidade = arguments[0];");
            fwrite($fp,"\n\t var registro = arguments[1];");
            fwrite($fp,"\n");
            fwrite($fp,"}");
            fwrite($fp,"\n");
            fwrite($fp,"// Executa após o carregamento padrão da edição de registro");
            fwrite($fp,"\n");
            fwrite($fp,"function afterEdit(){");
            fwrite($fp,"\n\t var entidade = arguments[0];");
            fwrite($fp,"\n\t var registro = arguments[1];");					
            fwrite($fp,"\n");
            fwrite($fp,"}");
            fwrite($fp,"\n");
            fwrite($fp,"// Invocado ao clicar no botão Voltar");
            fwrite($fp,"\n");
            fwrite($fp,"function beforeBack(){");
            fwrite($fp,"\n\t var btnback = arguments[0];");
            fwrite($fp,"\n");
            fwrite($fp,"}");
            fwrite($fp,"\n");
            fwrite($fp,"// Executa após a ação de voltar a tela anterior");
            fwrite($fp,"\n");
            fwrite($fp,"function afterBack(){");
            fwrite($fp,"\n\t var btnback = arguments[0];");
            fwrite($fp,"\n");
            fwrite($fp,"}");
            fwrite($fp,"\n");
            fwrite($fp,"// Invocado ao clicar no botão Deletar");
            fwrite($fp,"\n");
            fwrite($fp,"function beforeDelete(){");
            fwrite($fp,"\n");
            fwrite($fp,"}");
            fwrite($fp,"\n");
            fwrite($fp,"// Executa após a exclusão de um registro");
            fwrite($fp,"\n");
            fwrite($fp,"function afterDelete(){");
            fwrite($fp,"\n");
            fwrite($fp,"}");
            fwrite($fp,"\n");
            fwrite($fp,"if (typeof funcionalidade === 'undefined') var funcionalidade = 'cadastro';");
            fwrite($fp,"\n\n/* \n ### Escreva seu código JavaScript abaixo dessa linha ou dentro das funções acima ### \n*/\n");
            fclose($fp);
        }
        
        // Permissão total ao arquivo
        chmod($htmlFile     ,0777);
        chmod($dhtmlFile    ,0777);
        chmod($cssFile      ,0777);
        chmod($jsFile       ,0777);

        // Cria o MDM File JavaScript Compile
        include PATH_MDM_CONTROLLER . 'javascriptfile.php';

    }

}