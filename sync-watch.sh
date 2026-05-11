#!/bin/bash
# Script de sync automático contínuo para o projeto Buster
# Roda em background e faz sync a cada 5 minutos

PROJECT_DIR="/data/data/com.termux/files/home/album-copa"
LOG_FILE="/data/data/com.termux/files/home/album-copa/sync.log"
INTERVAL=300  # 5 minutos

cd "$PROJECT_DIR" || exit 1

echo "$(date): Iniciando monitor de sync automático (intervalo: ${INTERVAL}s)" >> "$LOG_FILE"

while true; do
    sleep $INTERVAL

    # Verifica se há mudanças
    if git diff --quiet && git diff --staged --quiet; then
        continue
    fi

    echo "$(date): Mudanças detectadas, sincronizando..." >> "$LOG_FILE"

    # Adiciona todas as mudanças
    git add -A

    # Cria commit com timestamp
    COMMIT_MSG="Auto-sync: $(date '+%Y-%m-%d %H:%M:%S')"
    git commit -m "$COMMIT_MSG" --allow-empty 2>/dev/null

    # Faz push
    git push origin main 2>&1 >> "$LOG_FILE"

    echo "$(date): Sync concluído - $COMMIT_MSG" >> "$LOG_FILE"
done