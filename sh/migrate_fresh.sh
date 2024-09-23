#!/usr/bin/bash
#Création de l'utilisateur et configuration:
if [[ $EUID -ne 0 ]]; then
    echo "Ce script nécessite des droits de super-utilisateur. Demande de droits..."
    if ! sudo true; then
        echo "Échec de la demande de droits de super-utilisateur. Arrêt du script."
        exit 1
    fi
fi

#Deconnexion de l'utilisateur
sqlplus -S sys/welcome123@localhost:1521/xe as sysdba @./user_logout.sql;

#Migration laravel
php artisan migrate:fresh --seed

#Génération de la documentation scribe
sudo chmod -R 777 .scribe
sudo chmod -R 777 public
php artisan scribe:generate;
sudo chmod -R 777 storage

#Connexion à la base de données
# sqlplus system/welcome123@localhost:1521/xe;
