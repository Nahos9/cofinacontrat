#!/bin/bash

# Téléchargement du fichier Word
wget -O Files/Contract.docx http://cofcredit.cofina.localhost/api/contract/word/3

# Vérification si le téléchargement a réussi
if [ $? -eq 0 ]; then
    echo "Le téléchargement du fichier Word a réussi."
else
    echo "Le téléchargement du fichier Word a échoué. Veuillez vérifier le lien ou votre connexion Internet."
    exit 1
fi

# Ouvrir le fichier avec WPS (veuillez adapter cette partie en fonction de votre configuration)
wps Files/Contract.docx & exit
