#!/bin/bash
# Script de sync automático para o projeto Buster
# Monitora mudanças e faz commit/push automaticamente

PROJECT_DIR="/data/data/com.termux/files/home/album-copa"
LOG_FILE="/data/data/com.termux/files/home/album-copa/sync.log"

cd "$PROJECT_DIR" || exit 1

echo "$(date): Iniciando sync automático..." >> "$LOG_FILE"

# Verifica se há mudanças
if git diff --quiet && git diff --staged --quiet; then
    echo "$(date): Nenhuma mudança detectada" >> "$LOG_FILE"
    exit 0
fi

# Adiciona todas as mudanças
git add -A

# Cria commit com timestamp
COMMIT_MSG="Auto-sync: $(date '+%Y-%m-%d %H:%M:%S')"
git commit -m "$COMMIT_MSG" --allow-empty 2>/dev/null

# Faz push
git push origin main 2>&1 >> "$LOG_FILE"

echo "$(date): Sync concluído - $COMMIT_MSG" >> "$LOG_FILE"