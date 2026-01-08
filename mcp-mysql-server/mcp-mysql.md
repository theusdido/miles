# mcp-mysql-server

Este serviço é uma API FastAPI que traduz perguntas em linguagem natural para consultas SQL em um banco de dados MySQL, utilizando a API do Gemini.

## 1. Instalação

O projeto utiliza um ambiente virtual Python. Se o ambiente `.venv` não existir, crie-o. As dependências precisam ser instaladas.

1.  **Navegue até o diretório do serviço:**
    ```bash
    cd mcp-mysql-server
    ```

2.  **Crie o ambiente virtual (se ainda não existir):**
    ```bash
    python3 -m venv .venv
    ```

3.  **Ative o ambiente virtual:**
    ```bash
    source .venv/bin/activate
    ```

4.  **Instale as dependências:**
    ```bash
    pip install -r requirements.txt
    ```

## 2. Configuração

As credenciais do banco de dados e a chave da API do Gemini são configuradas no arquivo `.env`.

1.  **Crie ou edite o arquivo `.env` no diretório `mcp-mysql-server/` com o seguinte conteúdo, substituindo os valores de exemplo:**
    ```
    # Credenciais do Banco de Dados de Desenvolvimento
    DEV_DB_HOST=localhost
    DEV_DB_PORT=3306
    DEV_DB_USER=seu_usuario_dev
    DEV_DB_PASSWORD=sua_senha_dev
    DEV_DB_NAME=seu_banco_dev

    # Credenciais do Banco de Dados de Produção
    PROD_DB_HOST=seu_host_prod
    PROD_DB_PORT=3306
    PROD_DB_USER=seu_usuario_prod
    PROD_DB_PASSWORD=sua_senha_prod
    PROD_DB_NAME=seu_banco_prod

    # Chave da API do Gemini
    GEMINI_API_KEY=SUA_CHAVE_API_AQUI
    ```

2.  **Importante:** O arquivo `app/database.py` atualmente carrega apenas um conjunto de variáveis de banco de dados (ex: `DB_HOST`). Você precisará adaptar o código para carregar as variáveis corretas (desenvolvimento ou produção) de acordo com o ambiente desejado.

## 3. Como Iniciar o Serviço

Após a instalação e configuração, o servidor pode ser iniciado com `uvicorn`.

1.  **A partir do diretório raiz (`/var/www/miles`), ative o ambiente virtual:**
    ```bash
    source mcp-mysql-server/.venv/bin/activate
    ```

2.  **Inicie o servidor:**
    ```bash
    uvicorn mcp-mysql-server.app.main:app --reload --app-dir .
    ```
    Ou, se você já estiver no diretório `mcp-mysql-server`:
    ```bash
    source .venv/bin/activate
    uvicorn app.main:app --reload
    ```
    O servidor estará disponível em `http://127.0.0.1:8000`.

## 4. Como Utilizar o Serviço

A API possui os seguintes endpoints:

*   `GET /`: Retorna uma mensagem de status.
*   `POST /query`: Recebe uma pergunta em linguagem natural e a converte para SQL.

### Exemplo de uso com `curl`

Para fazer uma pergunta ao serviço, envie uma requisição POST para o endpoint `/query`:

```bash
curl -X POST "http://127.0.0.1:8000/query" \
-H "Content-Type: application/json" \
-d 
'{'
  "question": "Quantos usuários se cadastraram no último mês?"
}'
```

A resposta será um JSON contendo a consulta SQL gerada e os resultados da execução dessa consulta no banco de dados.
