# Skill Análise de Contexto - Buster/ALBUM-COPA

## Como Funciona

Analisa TODO o contexto do que foi pedido e verifica se foi executado corretamente.

## Regras OBRIGATÓRIAS

1. ✅ SEMPRE analisar o contexto completo antes de entregar
2. ✅ VERIFICAR se tudo que foi pedido foi realmente feito
3. ✅ CHECAR se está seguindo todas as outras skills
4. ✅ CONFIRMAR se ficou do jeito que o usuário pediu
5. ✅ SUBIR no GitHub após confirmar

## Checklist de Análise

### 1. O que foi pedido?
- Listar TUDO que o usuário pediu
- Verificar se cada item foi implementado

### 2. Seguiu as Skills?
- [ ] Front-end: tema dark, variáveis CSS, componentes Radix UI
- [ ] Back-end: Inertia render, validação, Eloquent ORM
- [ ] Banco: migration antes do model, $fillable, $casts
- [ ] Edição direta: modificou os arquivos locais
- [ ] Revisão: leu o arquivo após modificar

### 3. O código está correto?
- Imports completos?
- Sintaxe sem erros?
- Lógica faz sentido?
- Não quebrou nada existente?

### 4. As conexões estão certas?
- Rota → Controller → View/Inertia page
- Model → Migration → Controller
- Front-end props → Back-end response
- Middleware nas rotas protegidas

### 5. Está no GitHub?
- `git status` verifica mudanças
- `git push origin main` confirma que subiu

## Projeto: album-copa
- Laravel + React 19 + Inertia.js + Tailwind CSS 4
- Base path: `/data/data/com.termux/files/home/album-copa/`
- GitHub: `https://github.com/darkpayoficial/Buster`