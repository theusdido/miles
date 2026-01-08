import os
import google.generativeai as genai
from dotenv import load_dotenv

load_dotenv()

# Configuração da API do Gemini
GEMINI_API_KEY = os.getenv("GEMINI_API_KEY")
if GEMINI_API_KEY is None:
    raise ValueError("Missing GEMINI_API_KEY environment variable. Check your .env file.")
genai.configure(api_key=GEMINI_API_KEY, transport='rest')
model = genai.GenerativeModel('gemini-1.5-pro-latest')

async def translate_to_sql(question: str, schema: str) -> str:
    """
    Usa um LLM para traduzir uma pergunta em linguagem natural para uma consulta SQL.
    """
    print(f"DEBUG: Schema passed to LLM:\n{schema}")

    prompt = f"""
    You are a MySQL expert. Based on the database schema provided, generate ONLY the SQL query that answers the user's question.

    **Database Schema:**
    {schema}

    **User Question:**
    "{question}"

    **Rules:**
    1.  Respond ONLY with the raw SQL query.
    2.  DO NOT include any explanations, comments, markdown formatting (like ```sql), or introductory text.
    3.  Ensure the SQL query is syntactically correct for MySQL.
    4.  If you cannot answer the question using the provided schema, respond EXACTLY with "QUERY_INVALIDA".

    **Example:**
    User Question: "What are all the tables?"
    SQL Query: SHOW TABLES;

    **SQL Query:**
    """

    try:
        response = await model.generate_content_async(prompt)
        sql_query = response.text.strip()
        print(f"DEBUG: LLM raw response: '{sql_query}'")
        
        # Remove any leading/trailing whitespace, but no other stripping
        return sql_query.strip()
    except Exception as e:
        print(f"DEBUG: Erro ao chamar a API do LLM: {e}")
        return "QUERY_INVALIDA"
