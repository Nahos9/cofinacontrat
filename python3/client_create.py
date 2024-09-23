import pandas as pd
import mysql.connector

# Lecture du fichier Excel
df = pd.read_excel('clients_nafa.xlsx')

# Connexion à la base de données MySQL
conn = mysql.connector.connect(
    host="localhost",
    user="pim",
    password="licdovic",
    database="cofina_credit"
)
cursor = conn.cursor()
# Création de la table MySQL
colonnes = [
    ('MATRICULE_CLIENT', 'VARCHAR(15) PRIMARY KEY'),
    ('INTITULE_COMPTE', 'VARCHAR(255)'),
    ('NO_COMPTE', 'VARCHAR(30) UNIQUE'),
    ('CODE_AGENCE', 'VARCHAR(5)'),
    ('AGENCE', 'VARCHAR(50)'),
    ('INT_CATEGORIE', 'VARCHAR(50)'),
    ('NOM_APPAFF', 'VARCHAR(255)'),
    ('SEXE', 'VARCHAR(5)'),
    ('NOM_GESTIONNAIRE', 'VARCHAR(255)'),
    ('CODE_SSECTEUR', 'VARCHAR(5)'),
    ('LIB_SSECT', 'VARCHAR(255)'),
    ('LIEU_NAISSANCE', 'VARCHAR(150)'),
    ('TEL_PORT', 'VARCHAR(25)'),
    ('DATE_NAISSANCE', 'DATE'),
    ('CODE_NATION', 'VARCHAR(5)'),
    ('LIB_NATION', 'VARCHAR(50)'),
    ('ADRESSE_1', 'VARCHAR(255)'),
    ('CODE_TYPE_PIECE', 'VARCHAR(5)'),
    ('TYPE_PIECE', 'VARCHAR(50)'),
    ('NUMERO_PIECE_IDENTITE', 'VARCHAR(50) UNIQUE'),
]
cursor.execute("DROP TABLE IF EXISTS clients;")

create_table_query = f"CREATE TABLE clients ({', '.join([f'`{table_name}` {table_type}' for table_name, table_type in colonnes])})"
cursor.execute(create_table_query)

# Insertion des données dans la table MySQL
for index, row in df.iterrows():
    values = ', '.join([f"""'{value.replace('"', ' ').replace("'", " ") if isinstance(
        value, str) else value }'""" for value in row])
    insert_query = f"INSERT INTO clients ({', '.join([f'`{table_name}`' for table_name, table_creation in colonnes])}) VALUES ({values})".replace(
        "'nan'", "null").replace("'NaT'", "null")
    # input(insert_query)
    cursor.execute(insert_query)
    print(f"\n {insert_query} \n")

# Valider les changements et fermer la connexion
conn.commit()
conn.close()

print("Les données ont été insérées avec succès dans la base de données MySQL.")
