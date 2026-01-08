#!/bin/bash

# Este script automatiza a geração dos arquivos PEM para autenticação mTLS.
# Ele extrai a chave privada e o certificado público (com a cadeia completa)
# do arquivo PFX fornecido.

# As senhas solicitadas são a senha de importação do arquivo .pfx.
# Por padrão, no código PHP, a senha configurada é 'goes1234'.

# Define o caminho base para os arquivos de certificado
CERT_PATH="/var/www/miles/vendor/theusdido/miles-library/controller/integracao/sn_nfse"
PFX_FILE="${CERT_PATH}/certificado.pfx"
PRIVATE_KEY_FILE="${CERT_PATH}/chave_privada.pem"
PUBLIC_CERT_FILE="${CERT_PATH}/certificado_publico.pem"

echo "Iniciando a geração dos arquivos PEM para autenticação mTLS..."

# Passo 1: Remover arquivos PEM existentes (e backups) para garantir um início limpo
echo "Removendo arquivos .pem existentes..."
rm -f "${PRIVATE_KEY_FILE}"
rm -f "${PUBLIC_CERT_FILE}"
rm -f "${PRIVATE_KEY_FILE}.bak"
rm -f "${PUBLIC_CERT_FILE}.bak"

# Passo 2: Gerar a chave privada não criptografada
echo "Extraindo a chave privada não criptografada..."
echo "Será solicitada a 'Import Password:' do arquivo PFX (provavelmente 'goes1234')."
openssl pkcs12 -legacy -in "${PFX_FILE}" -nocerts -nodes -out "${PRIVATE_KEY_FILE}"

if [ $? -ne 0 ]; then
    echo "ERRO: Falha ao extrair a chave privada. Verifique a senha do PFX e o comando."
    exit 1
fi

# Passo 3: Gerar o certificado público COM a cadeia completa
echo "Extraindo o certificado público COM a cadeia completa..."
echo "Será solicitada a 'Import Password:' do arquivo PFX novamente (provavelmente 'goes1234')."
openssl pkcs12 -legacy -in "${PFX_FILE}" -nokeys -chain -out "${PUBLIC_CERT_FILE}"

if [ $? -ne 0 ]; then
    echo "ERRO: Falha ao extrair o certificado público com a cadeia. Verifique a senha do PFX e o comando."
    exit 1
fi

echo "Geração dos arquivos PEM concluída com sucesso!"
echo "Os arquivos ${PRIVATE_KEY_FILE} e ${PUBLIC_CERT_FILE} foram criados/atualizados."
echo "Agora, por favor, tente enviar a nota fiscal novamente."
