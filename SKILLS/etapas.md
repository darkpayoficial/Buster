# Skill Etapas - Buster/ALBUM-COPA

## Como Funciona

Monta um plano DETALHADO passo a passo para executar o que o usuário pediu.

## Regras OBRIGATÓRIAS

1. ✅ SEMPRE editar direto nos arquivos do projeto
2. ✅ NUNCA pedir permissão - execute direto
3. ✅ REVISAR antes de entregar
4. ✅ SUBIR tudo no GitHub após modificar

## Ordem de Execução

SEMPRE seguir esta ordem:
1. **Banco de Dados** (migration → model → seeder)
2. **Back End** (controller → rota → middleware)
3. **Front End** (página → componente → CSS)
4. **Revisão** (testar conexões → subir GitHub)

## Como Montar as Etapas

### Para cada etapa, definir:
- **O quê**: Descrição clara do que será feito
- **Arquivos**: Quais arquivos serão criados/modificados
- **Depende de**: Quais etapas precisam estar concluídas antes

### Exemplo de Estrutura
```
ETAPA 1: Banco de Dados
├── Criar migration da nova tabela
├── Criar/atualizar Model
└── Rodar migrate

ETAPA 2: Back End
├── Criar/atualizar Controller
├── Adicionar rotas em web.php
└── Criar Form Request (se necessário)

ETAPA 3: Front End
├── Criar página React
├── Criar componentes
└── Conectar com Inertia

ETAPA 4: Revisão Final
├── Verificar fluxo completo
├── Commit e push pro GitHub
└── Confirmar entregou o pedido
```

## Projeto: album-copa
- Laravel + React 19 + Inertia.js + Tailwind CSS 4
- Base path: `/data/data/com.termux/files/home/album-copa/`
- GitHub: `https://github.com/darkpayoficial/Buster`