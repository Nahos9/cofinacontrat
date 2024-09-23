#!/usr/bin/bash
if [[ $EUID -ne 0 ]]; then
    echo "Ce script nécessite des droits de super-utilisateur. Demande de droits..."
    if ! sudo true; then
        echo "Échec de la demande de droits de super-utilisateur. Arrêt du script."
        exit 1
    fi
fi
#Suppression de l'ancien container
docker stop oracle_cofina_credit;
docker rm oracle_cofina_credit;

#Création de la base de données:
docker container create -it --name oracle_cofina_credit -p 1521:1521 -e ORACLE_PWD=welcome123 container-registry.oracle.com/database/express:latest;
docker update --restart=always oracle_cofina_credit;

#Démarage de la base de données:
docker start oracle_cofina_credit;

#Attendre pour le démarage de la bd
sleep 30;

#Création de l'utilisateur et configuration:
sqlplus -S system/welcome123@localhost:1521/xe @./user_creation.sql;

#Migration laravel
php artisan migrate --seed

#Génération de la documentation scribe
sudo chmod -R 777 .scribe
sudo chmod -R 777 public
php artisan scribe:generate;
sudo chmod -R 777 storage

#Connexion à la base de données
# sqlplus system/welcome123@localhost:1521/xe;
