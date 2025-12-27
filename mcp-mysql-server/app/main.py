from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from .database import execute_query, get_db_schema
from .llm_service import translate_to_sql

app = FastAPI(
    title="Servidor MCP para MySQL",
    description="Traduz linguagem natural para consultas SQL em um banco de dados MySQL."
)

class QueryRequest(BaseModel):
    question: str

@app.post("/query")
async def handle_query(request: QueryRequest):
    """
    Recebe uma pergunta em linguagem natural, traduz para SQL, executa e retorna o resultado.
    """
    print(f"Recebida pergunta: '{request.question}'")

    # 1. Obter o esquema do banco de dados
    try:
        schema = await get_db_schema()
        if not schema:
            raise HTTPException(status_code=500, detail="Não foi possível obter o esquema do banco de dados.")
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Erro ao acessar o banco de dados: {e}")

    # 2. Traduzir a pergunta para SQL usando o LLM
    sql_query = await translate_to_sql(request.question, schema)
    print(f"SQL Gerado: {sql_query}")

    if sql_query == "QUERY_INVALIDA" or not sql_query:
        raise HTTPException(status_code=400, detail="Não foi possível traduzir a pergunta para uma consulta SQL válida.")

    # 3. (IMPORTANTE) Validação de Segurança
    # Esta é uma validação MUITO simples. Para produção, use uma biblioteca de análise de SQL.
    if not sql_query.lower().strip().startswith("select"):
        raise HTTPException(status_code=403, detail="Operação não permitida. Apenas consultas SELECT são autorizadas.")

    # 4. Executar a consulta e retornar o resultado
    try:
        results = await execute_query(sql_query)
        return {"sql_query": sql_query, "results": results}
    except Exception as e:
        raise HTTPException(status_code=500, detail=f"Erro ao executar a consulta SQL: {e}")

@app.get("/")
def read_root():
    return {"message": "Servidor MCP está online."}
