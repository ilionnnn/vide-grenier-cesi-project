#!/bin/bash

echo "🚀 Démarrage des conteneurs Docker..."

# Se placer dans le répertoire du projet
cd /home/ilion/Documents/vide-grenier-en-ligne-master || {
  echo "❌ Le dossier du projet n'existe pas."
  exit 1
}

# Construire les conteneurs si nécessaire, puis les lancer
docker-compose -p vide-grenier-en-ligne-master up -d --build

# Vérifie que le démarrage s'est bien passé
if [ $? -eq 0 ]; then
  echo "✅ Conteneurs lancés avec succès."
else
  echo "❌ Échec du démarrage des conteneurs."
  exit 1
fi
