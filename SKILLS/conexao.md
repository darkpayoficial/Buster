# Skill Análise de Conexão - Buster/ALBUM-COPA

## Como Funciona

Analisa a conexão entre Banco de Dados, Front-end e Back-end para verificar se está tudo funcionando.

## Regras OBRIGATÓRIAS

1. ✅ VERIFICAR cada ponte de conexão
2. ✅ NUNCA assumir que está funcionando sem checar
3. ✅ SUBIR no GitHub após corrigir

## Pontos de Conexão

### 1. Banco → Model → Controller
- [ ] Migration cria a tabela corretamente?
- [ ] Model tem `$fillable` com todos os campos?
- [ ] Model tem `$casts` para tipos especiais?
- [ ] Controller usa o Model corretamente?
- [ ] Relacionamentos estão definidos nos Models?

### 2. Controller → Rota → Página
- [ ] Rota aponta para o Controller correto?
- [ ] Controller retorna `Inertia::render()` para páginas?
- [ ] Controller retorna `JSON` para rotas API?
- [ ] Middleware `auth` nas rotas protegidas?
- [ ] Middleware `admin` nas rotas admin?

### 3. Página → Props → Controller
- [ ] Página Inertia recebe as props do Controller?
- [ ] Tipos TypeScript batem com as props?
- [ ] `usePage()` acessa dados globais?
- [ ] Formulários usam `useForm()` do Inertia?

### 4. API → Front-end
- [ ] Endpoints API retornam JSON?
- [ ] Front-end faz fetch/axios pra URL correta?
- [ ] Autenticação está passando token/sessão?
- [ ] Erros são tratados no front-end?

### 5. Admin → CRUD Completo
- [ ] Listar (index) → GET
- [ ] Criar (create/store) → GET/POST
- [ ] Editar (edit/update) → GET/PUT
- [ ] Deletar (destroy) → DELETE
- [ ] Rotas admin com middleware `auth` + `admin`

## Fluxo de Verificação

```
Migration → Model → Controller → Rota → Página
    ↓          ↓         ↓         ↓       ↓
  Banco     Dados    Lógica    URL     Interface
```

## Projeto: album-copa
- Laravel + React 19 + Inertia.js + Tailwind CSS 4
- Base path: `/data/data/com.termux/files/home/album-copa/`
- GitHub: `https://github.com/darkpayoficial/Buster`