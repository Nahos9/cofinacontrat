sql_file='cofina-credit-2024-05-16_08-05-07.sql'
current_date=$(date '+%d-%m-%Y---%H-%M-%S');

folder_name="dossier_${current_date}";

mkdir "$folder_name";


cd "$folder_name";

cp "../$sql_file" .
cp "../replace.py" .


mysql -e "DROP DATABASE IF EXISTS tmp_old"; mysql -e "create database tmp_old"; mysql tmp_old < $sql_file;

mysqldump cofina_credit > cofina_credit.sql; mysqldump --no-data cofina_credit > cofina_credit_structure.sql; mysqldump --no-create-info --complete-insert tmp_old > cofina_credit_old_data.sql;

mysql -e "DROP DATABASE IF EXISTS cofina_credit_save"; mysql -e "create database cofina_credit_save"; mysql cofina_credit_save < cofina_credit.sql;

python3 replace.py

mysql -e "DROP DATABASE IF EXISTS cofina_credit"; mysql -e "create database cofina_credit"; mysql cofina_credit < cofina_credit_structure.sql; mysql cofina_credit < cofina_credit_old_data.sql;

cd ..
