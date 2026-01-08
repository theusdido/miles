import aiomysql
import os
from dotenv import load_dotenv

load_dotenv()

# Determina o ambiente (desenvolvimento ou produção)
ENVIRONMENT = os.getenv("ENVIRONMENT", "development")

# Carrega as configurações do .env com base no ambiente
if ENVIRONMENT == "production":
    DB_HOST = os.getenv("PROD_DB_HOST")
    DB_PORT = int(os.getenv("PROD_DB_PORT", 3306))
    DB_USER = os.getenv("PROD_DB_USER")
    DB_PASSWORD = os.getenv("PROD_DB_PASSWORD")
    DB_NAME = os.getenv("PROD_DB_NAME")
else:
    DB_HOST = os.getenv("DEV_DB_HOST")
    DB_PORT = int(os.getenv("DEV_DB_PORT", 3306))
    DB_USER = os.getenv("DEV_DB_USER")
    DB_PASSWORD = os.getenv("DEV_DB_PASSWORD")
    DB_NAME = os.getenv("DEV_DB_NAME")

# Validação das variáveis de ambiente do banco de dados
if any(v is None for v in [DB_HOST, DB_USER, DB_PASSWORD, DB_NAME]):
    raise ValueError("Missing one or more critical database environment variables (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME). Check your .env file.")

pool = None

async def get_db_pool():
    """Retorna o pool de conexões, criando-o se não existir."""
    global pool
    if pool is None:
        pool = await aiomysql.create_pool(
            host=DB_HOST,
            port=DB_PORT,
            user=DB_USER,
            password=DB_PASSWORD,
            db=DB_NAME,
            autocommit=True
        )
    return pool

async def execute_query(query: str, args=None):
    """Executa uma consulta no banco de dados e retorna os resultados."""
    db_pool = await get_db_pool()
    async with db_pool.acquire() as conn:
        async with conn.cursor(aiomysql.DictCursor) as cursor:
            await cursor.execute(query, args)
            result = await cursor.fetchall()
            return result

async def get_db_schema():
    """Busca o esquema detalhado das tabelas do banco de dados."""
    db_pool = await get_db_pool()
    async with db_pool.acquire() as conn:
        async with conn.cursor(aiomysql.DictCursor) as cursor:
            # Query para obter detalhes das colunas
            await cursor.execute(f"""
                SELECT
                    TABLE_NAME,
                    COLUMN_NAME,
                    DATA_TYPE,
                    COLUMN_KEY,
                    IS_NULLABLE,
                    EXTRA
                FROM
                    information_schema.COLUMNS
                WHERE
                    TABLE_SCHEMA = '{DB_NAME}'
                ORDER BY
                    TABLE_NAME, ORDINAL_POSITION;
            """)
            columns_info = await cursor.fetchall()

            # Query para obter informações de chaves estrangeiras
            await cursor.execute(f"""
                SELECT
                    TABLE_NAME,
                    COLUMN_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME
                FROM
                    information_schema.KEY_COLUMN_USAGE
                WHERE
                    CONSTRAINT_SCHEMA = '{DB_NAME}' AND REFERENCED_TABLE_NAME IS NOT NULL
                ORDER BY
                    TABLE_NAME, COLUMN_NAME;
            """)
            foreign_keys_info = await cursor.fetchall()

    schema_details = {}

    for col in columns_info:
        table_name = col['TABLE_NAME']
        if table_name not in schema_details:
            schema_details[table_name] = {'columns': [], 'primary_keys': [], 'foreign_keys': []}
        
        column_info = f"  - {col['COLUMN_NAME']} ({col['DATA_TYPE']}"
        if col['IS_NULLABLE'] == 'NO':
            column_info += " NOT NULL"
        if col['EXTRA'] == 'auto_increment':
            column_info += " AUTO_INCREMENT"
        column_info += ")"
        
        schema_details[table_name]['columns'].append(column_info)
        if col['COLUMN_KEY'] == 'PRI':
            schema_details[table_name]['primary_keys'].append(col['COLUMN_NAME'])

    for fk in foreign_keys_info:
        table_name = fk['TABLE_NAME']
        if table_name in schema_details:
            fk_info = (
                f"  - FOREIGN KEY ({fk['COLUMN_NAME']}) "
                f"REFERENCES {fk['REFERENCED_TABLE_NAME']}({fk['REFERENCED_COLUMN_NAME']})"
            )
            schema_details[table_name]['foreign_keys'].append(fk_info)

    # Formata o esquema para ser mais legível para o LLM
    schema_str = ""
    for table_name, details in schema_details.items():
        schema_str += f"\nTabela: {table_name}\n"
        for col_info in details['columns']:
            schema_str += f"{col_info}\n"
        if details['primary_keys']:
            schema_str += f"  - PRIMARY KEY ({', '.join(details['primary_keys'])})\n"
        for fk_info in details['foreign_keys']:
            schema_str += f"{fk_info}\n"
            
    return schema_str
