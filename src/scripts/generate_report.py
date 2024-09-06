import csv
import MySQLdb
import os
from contextlib import closing

# Configurações do banco de dados
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'passwd': '',
    'db': 'to_do',
}

# Caminho para salvar o arquivo CSV
CSV_FILE_PATH = os.path.join(os.path.dirname(__file__), '../../public/temp/relatorio.csv')

def connect_to_database(config):
    """Conecta ao banco de dados MySQL."""
    return MySQLdb.connect(**config)

def fetch_completed_tasks(cursor):
    """Busca as tarefas concluídas no banco de dados."""
    query = "SELECT task, time, date, completed_at FROM lists WHERE completed = 1"
    cursor.execute(query)
    return cursor.fetchall()

def write_to_csv(file_path, data):
    """Escreve os dados no arquivo CSV."""
    with open(file_path, 'w', newline='', encoding='utf-8-sig') as csvfile:
        csvwriter = csv.writer(csvfile, delimiter=';')
        # Escrever o cabeçalho
        csvwriter.writerow(["Tarefa", "Hora", "Data", "Concluído em"])
        # Escrever os dados
        csvwriter.writerows(data)

def main():
    """Função principal para gerar o relatório."""
    with closing(connect_to_database(DB_CONFIG)) as db, closing(db.cursor()) as cursor:
        # Busca os dados das tarefas concluídas
        completed_tasks = fetch_completed_tasks(cursor)
        # Gera o arquivo CSV com os dados
        write_to_csv(CSV_FILE_PATH, completed_tasks)

if __name__ == "__main__":
    main()
