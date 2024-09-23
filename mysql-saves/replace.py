import re
import fileinput

# Définir les chaînes de texte à remplacer
ancienne_chaine = "`caf_id`, `creator_id`, `status`, `status_observation`, `created_at`, `updated_at`"
nouvelle_chaine = "`caf_id`, `creator_id`, `status`, `comment`, `created_at`, `updated_at`"

# Nom du fichier
fichier_nom = "./cofina_credit_old_data.sql"

# Lire le fichier et remplacer le texte
with fileinput.FileInput(fichier_nom, inplace=True, backup='.bak') as fichier:
    for ligne in fichier:
        # Utiliser re.sub pour remplacer toutes les occurrences de la chaîne de texte
        nouvelle_ligne = re.sub(ancienne_chaine, nouvelle_chaine, ligne)
        # Afficher la nouvelle ligne (redirigée vers le fichier grâce à fileinput)
        print(nouvelle_ligne, end='')

print(f"Remplacement de '{ancienne_chaine}' par '{nouvelle_chaine}' effectué dans '{fichier_nom}'.")
