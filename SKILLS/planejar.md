# Skill Planejamento - Buster/ALBUM-COPA

## Como Funciona

Quando o usuário pedir algo, você deve PLANEJAR antes de executar.

## Regras OBRIGATÓRIAS

1. ✅ SEMPRE editar direto nos arquivos do projeto
2. ✅ NUNCA pedir permissão - execute direto
3. ✅ REVISAR antes de entregar o resultado
4. ✅ SUBIR tudo no GitHub após modificar

## Fluxo de Planejamento

### 1. Analisar o Pedido
- Entender exatamente o que precisa ser feito
- Identificar quais partes do sistema serão afetadas (BD, back-end, front-end)
- Verificar dependências e impactos

### 2. Mapear os Arquivos
- Listar todos os arquivos que precisam ser criados ou modificados
- Determinar a ORDEM correta de execução:
  1. Banco de Dados (migrations, models)
  2. Back End (controllers, rotas)
  3. Front End (páginas, componentes)
  4. Revisão

### 3. Estruturar o Plano
```
ETAPA 1 - Banco de Dados
- Criar migration para tabela X
- Atualizar model Y

ETAPA 2 - Back End
- Criar controller Z
- Adicionar rotas em web.php

ETAPA 3 - Front End
- Criar página/componente A
- Conectar com Inertia props

ETAPA 4 - Revisão
- Verificar se tudo está conectado
- Subir no GitHub
```

### 4. Executar o Plano
- Executar cada etapa em ordem
- Marcar cada etapa como concluída
- Se encontrar problema, ajustar e continuar

### 5. Revisar e Entregar
- Verificar se todas as etapas foram concluídas
- Confirmar que o que foi pedido foi entregue
- Fazer commit e push pro GitHub

## Projeto: album-copa
- Laravel + React 19 + Inertia.js + Tailwind CSS 4
- Base path: `/data/data/com.termux/files/home/album-copa/`
- GitHub: `https://github.com/darkpayoficial/Buster`