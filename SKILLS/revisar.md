# Skill Revisão de Modificações - Buster/ALBUM-COPA

## Como Funcena

Verifica se as modificações foram REALMENTE aplicadas no código do projeto.

## Regras OBRIGATÓRIAS

1. ✅ SEMPRE ler o arquivo depois de modificar para confirmar
2. ✅ VERIFICAR se o código novo está consistente com o projeto
3. ✅ CHECAR se não quebrou nada existente
4. ✅ SUBIR no GitHub após confirmar tudo certo

## Checklist de Revisão

### Para cada modificação:

1. **Leitura de Confirmação**
   - Depois de editar, LER o arquivo novamente
   - Confirmar que a mudança está lá
   - Verificar se não apagou nada por acidente

2. **Consistência**
   - O código segue o mesmo padrão do resto do projeto?
   - Variáveis, funções e classes estão nomeadas corretamente?
   - Imports estão completos?

3. **Funcionalidade**
   - A lógica faz sentido?
   - Não vai quebrar outras partes do sistema?
   - Rotas, controllers e views estão conectados?

4. **Segurança**
   - Dados do usuário estão validados?
   - Não há SQL injection ou XSS?
   - Middlewares `auth` e `admin` nas rotas protegidas?

5. **GitHub**
   - `git status` para ver o que mudou
   - `git diff` para revisar as mudanças
   - `git add`, `git commit`, `git push` para subir

## Projeto: album-copa
- Laravel + React 19 + Inertia.js + Tailwind CSS 4
- Base path: `/data/data/com.termux/files/home/album-copa/`
- GitHub: `https://github.com/darkpayoficial/Buster`