# Skill Edição Direta - Buster/ALBUM-COPA

## Regra PRINCIPAL

**TODA modificação é feita DIRETAMENTE nos arquivos do projeto local. NÃO salva na nuvem automaticamente.**

Projeto em: `/data/data/com.termux/files/home/album-copa/`

## Regras OBRIGATÓRIAS

1. ✅ EDITAR arquivos locais DIRETAMENTE
2. ✅ NUNCA salvar na nuvem sem commit/push manual
3. ✅ NUNCA pedir permissão - se o usuário pediu, FAÇA direto
4. ✅ REVISAR a modificação antes de entregar
5. ✅ SUBIR no GitHub quando solicitado

## Fluxo de Trabalho

1. Usuário pede uma modificação
2. Localizar o arquivo correto no projeto
3. Usar Edit ou Write para modificar DIRETAMENTE o arquivo
4. Verificar se a modificação foi aplicada corretamente
5. Subir no GitHub: `git add`, `git commit`, `git push`

## Arquivos do Projeto (Principais)

| Caminho | Descrição |
|---------|-----------|
| `app/Http/Controllers/` | Controllers do usuário |
| `app/Http/Controllers/Admin/` | Controllers do admin |
| `app/Models/` | Models Eloquent |
| `app/Http/Middleware/` | Middlewares |
| `routes/web.php` | Todas as rotas |
| `database/migrations/` | Migrações |
| `resources/js/` | Front-end React |
| `resources/css/` | Estilos CSS |
| `config/` | Configurações Laravel |

## Permissões
- Edit: modificar arquivos existentes → DIRETO
- Write: criar novos arquivos → DIRETO
- Bash: rodar artisan, npm → DIRETO
- Read: ler arquivos → DIRETO