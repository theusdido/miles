<?php

// Script de Teste para a classe snNFSE (MCP)

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Carrega o autoloader do Composer para ter acesso às classes
require 'vendor/autoload.php';

require 'vendor/theusdido/miles-library/classes/nfse/snnfse.class.php';

// --- INÍCIO DOS MOCKS (Simulação do Banco de Dados) ---

/**
 * Simula a classe PDOStatement para retornar dados de exemplo.
 */
class MockPDOStatement {
    private $data;
    public function __construct($data) { $this->data = $data; }
    public function fetchAll($fetch_style) { return $this->data; }
}

/**
 * Simula a classe PDO para retornar nosso Statement de exemplo.
 */
class MockPDO {
    private $data;
    public function __construct($data) { $this->data = $data; }
    public function query($sql) { return new MockPDOStatement($this->data); }
    public function exec($sql) { return 1; } // Simula sucesso na execução de um INSERT/UPDATE
}

/**
 * Simula a função global getProxId.
 */
function getProxId($table, $conn) {
    return rand(1000, 9999);
}

// Dados de exemplo para uma nota fiscal. Altere conforme sua necessidade.
$mock_nfse_data = [
    'rpsnumero' => rand(100, 999),
    'rpsserie' => 'A1',
    'rpstipo' => '1',
    'demis' => '2025-12-10', // AAAA-MM-DD
    'dcompetencia' => '2025-12-10', // AAAA-MM-DD
    'regesptrib' => '0',
    'issretido' => '0', // 0 = Não, 1 = Sim
    'valservicos' => '150.00',
    'valdeducoes' => '0.00',
    'valpis' => '0.00',
    'valcofins' => '0.00',
    'valinss' => '0.00',
    'valir' => '0.00',
    'valcsll' => '0.00',
    'valiss' => '3.00',
    'valaliqiss' => '2.00',
    'valdescincond' => '0.00',
    'valdesccond' => '0.00',
    'valbasecalculo' => '150.00',
    'itelistserv' => '10.05',
    'cmunincidencia' => '4204608', // Código IBGE do município de incidência
    'cmun' => '4204608', // Código IBGE do município do prestador
    'natop' => '1',
    'discriminacao' => 'Serviços de consultoria & desenvolvimento de software.',
    'tomacpf' => '', // Deixe em branco se for CNPJ
    'tomacnpj' => '11111111111111', // Apenas números
    'tomarazaosocial' => 'EMPRESA TOMADORA DE SERVICOS LTDA',
    'tomaendereco' => 'AVENIDA DO TOMADOR',
    'tomanumero' => '987',
    'tomacomplemento' => 'Sala 10',
    'tomabairro' => 'CENTRO',
    'tomacmun' => '4205407', // Código IBGE do município do tomador
    'tomauf' => 'SC',
    'tomacep' => '88000111',
    'tomaemail' => 'contato@tomador.com',
    'respretencao' => '',
];

// Cria a conexão global simulada que a classe snNFSE espera encontrar
global $conn;
$conn = new MockPDO([$mock_nfse_data]);

// --- FIM DOS MOCKS ---


// --- EXECUÇÃO DO TESTE ---

echo "Iniciando teste de envio de NFS-e...\n";
echo "=======================================\n\n";

try {
    $nfse = new snNFSE();

    // IMPORTANTE: Alterando o endpoint para o ambiente de HOMOLOGAÇÃO (testes).
    $nfse->endpoint = 'https://sefin.nfse.gov.br/SefinNacional/nfse';

    echo "Endpoint de Homologação: " . $nfse->endpoint . "\n";

    // Adiciona o RPS ao lote (o número é apenas para a consulta SQL simulada)
    $nfse->addLoteRPS([$mock_nfse_data['rpsnumero']]);

    // Executa o envio
    $result = $nfse->send();

    // Imprime os resultados
    echo "\n--- Resultado do Envio ---\n";
    echo "Status Final: " . ($result['status'] ?? 'N/A') . "\n";
    echo "HTTP Code: " . ($result['http_code'] ?? 'N/A') . "\n";
    
    if (!empty($result['curl_error'])) {
        echo "ERRO cURL: " . $result['curl_error'] . "\n";
        echo "\nLembrete: Verifique se os caminhos dos certificados PEM e CA na classe snNFSE estão corretos e acessíveis.\n";
    }

    echo "\n--- Mensagens ---\n";
    echo !empty($result['message']) ? $result['message'] : "Nenhuma mensagem retornada.";
    echo "\n";

    echo "\n--- Resposta do Servidor (Decodificada) ---\n";
    if (isset($result['response'])) {
        $response_data = json_decode($result['response'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            print_r($response_data);
        } else {
            echo "A resposta não é um JSON válido:\n";
            echo $result['response'];
        }
    } else {
        echo "Nenhuma resposta do servidor.";
    }
    echo "\n--------------------------\n";


} catch (Exception $e) {
    echo "!!!!!! UMA EXCEÇÃO OCORREU !!!!!!\n";
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
    echo "Trace: \n" . $e->getTraceAsString() . "\n";
}

echo "\nTeste finalizado.\n";

?>
