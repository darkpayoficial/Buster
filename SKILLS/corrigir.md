# Skill Correção de Erros e Bugs - Buster/ALBUM-COPA

## Como Funciona

Identifica e corrige erros e bugs no projeto de forma rápida e eficiente.

## Regras OBRIGATÓRIAS

1. ✅ SEMPRE identificar a CAUSA do erro, não só o sintoma
2. ✅ EDITAR direto nos arquivos do projeto
3. ✅ NUNCA pedir permissão - corrija direto
4. ✅ REVISAR a correção antes de entregar
5. ✅ SUBIR no GitHub após corrigir

## Tipos de Erro e Como Corrigir

### Erros de PHP/Laravel
- **500 Internal Server Error**: Verificar `storage/logs/laravel.log`
- **404 Not Found**: Verificar rotas em `routes/web.php`
- **403 Forbidden**: Verificar middlewares `auth` e `admin`
- **Validation Error**: Verificar validação nos controllers
- **Class not found**: Rodar `composer dump-autoload`
- **Migration error**: Rodar `php artisan migrate:rollback` e migration novamente

### Erros de Front-end/React
- **Componente não renderiza**: Verificar se a página Inertia está registrada
- **Props undefined**: Verificar se o controller está passando os dados
- **Erro de TypeScript**: Verificar tipos das props
- **CSS não aplica**: Verificar classes Tailwind e ordem do CSS

### Erros de Banco de Dados
- **Column not found**: Rodar `php artisan migrate`
- **Table not found**: Verificar se a migration foi rodada
- **Foreign key constraint**: Verificar ordem das migrations
- **SQLSTATE error**: Verificar `config/database.php`

### Erros de Inertia.js
- **Página não carrega**: Verificar se o componente React existe
- **Prop não chega**: Verificar `HandleInertiaRequests.php`
- **Redirect não funciona**: Usar `Inertia::location()` para redirects externos

## Fluxo de Correção

1. **Identificar o erro**: Ler a mensagem de erro e o stack trace
2. **Localizar o arquivo**: Encontrar onde o erro acontece
3. **Entender a causa**: Por que o erro ocorreu?
4. **Corrigir o código**: Editar o arquivo diretamente
5. **Testar**: Verificar se o erro foi resolvido
6. **Subir**: `git add`, `git commit`, `git push`

## Comandos de Debug
```bash
php artisan route:list          # Ver todas as rotas
php artisan migrate:status      # Ver status das migrations
php artisan config:cache        # Limpar cache de config
php artisan view:clear          # Limpar cache de views
php artisan storage:link        # Criar link simbólico
npm run build                   # Compilar front-end
```

## Projeto: album-copa
- Laravel + React 19 + Inertia.js + Tailwind CSS 4
- Base path: `/data/data/com.termux/files/home/album-copa/`
- GitHub: `https://github.com/darkpayoficial/Buster`