import asyncio

async def main():
    try:
        from app.database import get_db_schema
        from app.llm_service import translate_to_sql

        print("Testing get_db_schema()...")
        schema = await get_db_schema()
        print("Schema retrieved:")
        print(schema)

        if not schema:
            print("Schema is empty, skipping translate_to_sql test.")
            return

        print("\nTesting translate_to_sql()...")
        question = "listar o nome de todas as tabelas"
        sql_query = await translate_to_sql(question, schema)
        print("SQL query generated:")
        print(sql_query)

    except Exception as e:
        print(f"An error occurred: {e}")

if __name__ == "__main__":
    asyncio.run(main())
