<?php

// validar_xml.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

function print_usage() {
    echo "Uso: php validar_xml.php <caminho_para_xml> <caminho_para_xsd>\n";
    echo "Exemplo: php validar_xml.php debug_nfse.xml vendor/theusdido/miles-library/controller/integracao/sn_nfse/xsd/GerarNfseEnvio_v1.00.xsd\n";
}

if ($argc < 3) {
    echo "Erro: Argumentos insuficientes.\n\n";
    print_usage();
    exit(1);
}

$xmlPath = $argv[1];
$xsdPath = $argv[2];

if (!file_exists($xmlPath)) {
    echo "Erro: Arquivo XML não encontrado em: $xmlPath\n";
    exit(1);
}

if (!file_exists($xsdPath)) {
    echo "Erro: Arquivo XSD não encontrado em: $xsdPath\n";
    exit(1);
}

echo "=============================================\n";
echo "Iniciando validação do Schema XML\n";
echo "=============================================\n";
echo "Arquivo XML: $xmlPath\n";
echo "Arquivo XSD: $xsdPath\n";
echo "---------------------------------------------\n\n";


// Habilita o uso de erros internos da libxml para capturá-los
libxml_use_internal_errors(true);

$dom = new DOMDocument();
// LibXML não gosta de espaços em branco, então removemos antes de carregar
$dom->loadXML(file_get_contents($xmlPath), LIBXML_NOBLANKS);

if ($dom->schemaValidate($xsdPath)) {
    echo "SUCESSO: O XML é válido de acordo com o schema XSD.\n";
} else {
    echo "FALHA: O XML é inválido. Erros encontrados:\n\n";
    $errors = libxml_get_errors();
    foreach ($errors as $error) {
        echo "---------------------------------------------\n";
        echo "Nível: " . get_error_level_string($error->level) . "\n";
        echo "Código: " . $error->code . "\n";
        echo "Linha: " . $error->line . "\n";
        echo "Coluna: " . $error->column . "\n";
        echo "Mensagem: " . trim($error->message) . "\n";

        // Adiciona a sugestão de correção
        $suggestion = get_suggestion_for_message(trim($error->message));
        if ($suggestion) {
            echo "\n\x1b[32mSugestão: " . $suggestion . "\x1b[0m\n";
        }
    }
    libxml_clear_errors();
    echo "---------------------------------------------\n";
}

function get_error_level_string($level) {
    switch ($level) {
        case LIBXML_ERR_WARNING:
            return 'Warning';
        case LIBXML_ERR_ERROR:
            return 'Error';
        case LIBXML_ERR_FATAL:
            return 'Fatal Error';
    }
    return 'Unknown';
}

/**
 * Analisa a mensagem de erro da libxml e tenta fornecer uma sugestão útil.
 *
 * @param string $message A mensagem de erro.
 * @return string|null A sugestão ou null se nenhuma for encontrada.
 */
function get_suggestion_for_message($message) {
    // Mapa de tipos de dados comuns e suas regras
    $type_suggestions = [
        'TSDec1V2'      => "O valor deve ser um número com até 1 dígito na parte inteira e 2 casas decimais. Ex: 5.00",
        'TSDec2V2'      => "O valor deve ser um número com até 2 dígitos na parte inteira e 2 casas decimais. Ex: 99.99",
        'TSDec15V2'     => "O valor deve ser um número com até 15 dígitos na parte inteira e 2 casas decimais. Ex: 150.00",
        'TSData'        => "A data deve estar no formato AAAA-MM-DD.",
        'TSDateTimeUTC' => "A data e hora deve estar no formato UTC completo: AAAA-MM-DDThh:mm:ssZ ou AAAA-MM-DDThh:mm:ss-03:00.",
        'TSCNPJ'        => "O CNPJ deve conter 14 dígitos numéricos, sem formatação.",
        'TSCPF'         => "O CPF deve conter 11 dígitos numéricos, sem formatação.",
        'TSCodMunIBGE'  => "O código do município deve ter 7 dígitos, conforme tabela do IBGE.",
        'TSChaveNFSe'   => "A Chave da NFS-e deve conter 50 dígitos numéricos."
    ];

    // Procura por erros de tipo de dado (ex: '5.0000' is not a valid value of the atomic type 'TSDec1V2')
    if (preg_match("/is not a valid value of the atomic type '([^']*)'/", $message, $matches)) {
        $type = str_replace('{http://www.sped.fazenda.gov.br/nfse}', '', $matches[1]);
        if (isset($type_suggestions[$type])) {
            return $type_suggestions[$type];
        }
    }
    
    // Procura por erros de elemento não esperado (geralmente casing ou ordem errada)
    if (preg_match("/Element '([^']+)': This element is not expected. Expected is \( '([^']*)' \)/", $message, $matches)) {
        $found = str_replace('{http://www.sped.fazenda.gov.br/nfse}', '', $matches[1]);
        $expected = str_replace('{http://www.sped.fazenda.gov.br/nfse}', '', $matches[2]);
        return "A tag <$found> não era esperada. O schema esperava a tag <$expected>. Verifique a capitalização (maiúsculas/minúsculas) e a ordem dos elementos.";
    }

    // Procura por erro de raiz de validação
     if (preg_match("/No matching global declaration available for the validation root/", $message)) {
        return "O elemento raiz do seu XML não corresponde ao schema que você está usando para validar. Verifique se você está usando o arquivo XSD correto para o XML gerado (ex: GerarNfseEnvio_v1.00.xsd para um XML que começa com <GerarNfseEnvio>).";
    }

    return null;
}

echo "\nValidação finalizada.\n";

?>