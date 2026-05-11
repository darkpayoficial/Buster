# Skill Frontend - Buster/ALBUM-COPA

## Stack do Frontend
- **React 19** + TypeScript + Inertia.js
- **Tailwind CSS 4** + tailwindcss-animate
- **Radix UI** (shadcn/ui style components)
- **Swiper** para carrosséis
- **Lucide React** para ícones

## Tema DARK como PADRÃO
```css
/* Cores principais */
--primary: #28e504 (verde)
--background: #000000 (preto)
--card: oklch(20.5% 0 0)
--foreground: oklch(98.5% 0 0)
--border: oklch(26.9% 0 0)
--muted: oklch(26.9% 0 0)
```

## Padrões de Componentes

### Estrutura de Página
```tsx
// Layout padrão dark
<div className="bg-background text-foreground min-h-screen">
  <Header />
  <main className="container mx-auto px-4">
    {/* conteúdo */}
  </main>
</div>
```

### Cards e Containers
```tsx
// Card padrão
<div className="bg-card border border-border rounded-lg p-4">
  {/* conteúdo */}
</div>

// Card com hover
<div className="bg-card border border-border rounded-lg p-4 hover:bg-card/80 transition-colors">
  {/* conteúdo */}
</div>
```

### Botões
```tsx
// Botão primário (verde)
<button className="bg-primary text-primary-foreground hover:bg-primary/90 rounded-lg px-4 py-2">
  Ação
</button>

// Botão secundário
<button className="bg-secondary text-secondary-foreground hover:bg-secondary/80 rounded-lg px-4 py-2">
  Ação
</button>

// Botão ghost
<button className="hover:bg-muted rounded-lg px-4 py-2">
  Ação
</button>
```

### Inputs
```tsx
// Input padrão dark
<input className="bg-input border border-border rounded-lg px-4 py-2 text-foreground placeholder:text-muted-foreground focus:ring-2 focus:ring-primary" />
```

## Regras OBRIGATÓRIAS

1. ✅ SEMPRE usar tema dark como padrão
2. ✅ USAR variáveis CSS (--primary, --background, etc.)
3. ✅ USAR componentes Radix UI existentes
4. ✅ MANTER consistência visual
5. ❌ NUNCA usar cores hardcoded (#fff, #000, etc.)
6. ❌ NUNCA quebrar o layout existente

## Arquivos de Componentes
- `resources/js/components/` - Componentes React
- `resources/js/components/ui/` - Componentes UI base
- `resources/js/pages/` - Páginas Inertia
- `resources/css/app.css` - Estilos globais
- `resources/views/app.blade.php` - Blade template principal

## Localização dos Assets
- `public/build/assets/` - JS/CSS compilados
- `public/img/` - Imagens
- `public/storage/` - Uploads