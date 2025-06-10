#!/bin/bash
echo "🛑 Arrêt des conteneurs..."
# Se place dans le dossier du projet
cd /home/ilion/Documents/vide-grenier-en-ligne-master

# Arrête et supprime tout (conteneurs, réseaux, volumes)
docker-compose -p vide-grenier-en-ligne-master down -v
echo "✅ Tous les conteneurs sont arrêtés."