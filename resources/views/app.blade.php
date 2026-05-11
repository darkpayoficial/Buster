<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        (function () {
            const appearance = '{{ $appearance ?? "system" }}';

            if (appearance === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                if (prefersDark) {
                    document.documentElement.classList.add('dark');
                }
            }
        })();
    </script>

    @php
        $config = \App\Models\Config::first();
        $appName = $config && $config->app_name ? $config->app_name : 'RASPA GREEN';
        $description = $config && $config->description ? $config->description : 'A melhor plataforma de raspadinhas online do Brasil. Ganhe prêmios incríveis, PIX na unha e muito mais!';
        $keywords = $config && $config->keywords ? $config->keywords : 'raspadinha, sorte, prêmios, jogos, online, brasil, pix';
        $logoImage = $config && $config->favicon ? url('/storage' . $config->favicon) : url('/favicon.svg'); 
        
        $colors = [
            'primary' => $config->primary_color ?? '#4ADE80',
            'secondary' => $config->secondary_color ?? '#1F2937',
            'accent' => $config->accent_color ?? '#6366F1',
            'background' => $config->background_color ?? '#000000',
            'foreground' => $config->foreground_color ?? '#FFFFFF',
            'surface' => $config->card_color ?? '#111827', 
            'muted' => $config->muted_color ?? '#374151',
            'muted-foreground' => $config->muted_foreground_color ?? '#9CA3AF',
            'card' => $config->card_color ?? '#111827',
            'card-foreground' => $config->card_foreground_color ?? '#FFFFFF',
            'border' => $config->border_color ?? '#374151',
            'input' => $config->input_color ?? '#374151',
            'ring' => $config->ring_color ?? '#4ADE80',
        ];
    @endphp

    <style>
        :root {
            --primary: {{ $colors['primary'] }};
            --secondary: {{ $colors['secondary'] }};
            --accent: {{ $colors['accent'] }};
            --background: {{ $colors['background'] }};
            --foreground: {{ $colors['foreground'] }};
            --surface: {{ $colors['surface'] }};
            --muted: {{ $colors['muted'] }};
            --muted-foreground: {{ $colors['muted-foreground'] }};
            --card: {{ $colors['card'] }};
            --card-foreground: {{ $colors['card-foreground'] }};
            --border: {{ $colors['border'] }};
            --input: {{ $colors['input'] }};
            --ring: {{ $colors['ring'] }};

            --primary-foreground: {{ $colors['foreground'] }};
            --secondary-foreground: {{ $colors['foreground'] }};
            --accent-foreground: {{ $colors['foreground'] }};
            --destructive: oklch(57.7% .245 27.325);
            --destructive-foreground: oklch(63.7% .237 25.331);
            --popover: {{ $colors['card'] }};
            --popover-foreground: {{ $colors['card-foreground'] }};

            --sidebar: var(--background);
            --sidebar-foreground: var(--foreground);
            --sidebar-primary: var(--primary);
            --sidebar-primary-foreground: var(--foreground);
            --sidebar-accent: var(--accent);
            --sidebar-accent-foreground: var(--foreground);
            --sidebar-border: var(--border);
            --sidebar-ring: var(--ring);
        }

        .border,
        .border-t,
        .border-b,
        .border-l,
        .border-r,
        .border-x,
        .border-y {
            border-color: var(--border) !important;
        }

        html {
            background-color: var(--background);
            color: var(--foreground);
            color-scheme: light dark;
        }

        html.dark {
            background-color: var(--background);
            color: var(--foreground);
        }

    </style>

    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $keywords }}, {{ strtolower($appName) }}">
    <meta name="author" content="{{ $appName }}">

    <meta property="og:title" content="{{ $appName }} - Raspadinhas Online">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ $appName }}">
    <meta property="og:image" content="{{ $logoImage }}"> 
    <meta property="og:image:width" content="1200"> 
    <meta property="og:image:height" content="630"> 
    <meta property="og:image:alt" content="Logo do {{ $appName }}"> 

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $appName }} - Raspadinhas Online">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $logoImage }}"> 
    <meta name="twitter:image:alt" content="Logo do {{ $appName }}"> 


    <title inertia>{{ $appName }}</title>
    @php
        $favicon = $config && $config->favicon ? $config->favicon : '/favicon.svg';
    @endphp
    <link rel="icon" href="/storage{{ $favicon }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @routes
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.tsx', "resources/js/pages/{$page['component']}.tsx"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>