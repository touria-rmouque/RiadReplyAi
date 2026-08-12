<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('titre', 'Dashboard') · RiadReply AI</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ink:        "#1C1917",
                        accent:     "#B5502F",
                        "accent-dark": "#8F3D22",
                        navy:       "#10202E",
                        "navy-soft":"#16293A",
                        stone:      "#F6F4F1",
                        line:       "#E7E3DC",
                        muted:      "#78716C",
                    },
                    fontFamily: {
                        display: ["Outfit", "sans-serif"],
                        sans:    ["Inter", "sans-serif"],
                        mono:    ["JetBrains Mono", "monospace"],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background: #F6F4F1;
        }

        .card {
            background: #FFFFFF;
            border: 1px solid #E7E3DC;
            border-radius: 14px;
        }

        .card-hover {
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .card-hover:hover {
            border-color: #D8D2C6;
            box-shadow: 0 4px 16px rgba(28, 25, 23, .05);
        }

        .mono {
            font-family: "JetBrains Mono", monospace;
        }

        .sidebar-link {
            transition: background-color .15s ease, color .15s ease;
            border-left: 2px solid transparent;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, .04);
        }

        .sidebar-active {
            background: rgba(181, 80, 47, .12);
            border-left: 2px solid #B5502F;
            color: #FDF0D5;
        }

        .btn-primary {
            background: #B5502F;
            transition: background-color .15s ease;
        }

        .btn-primary:hover {
            background: #8F3D22;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: #D8D2C6;
            border-radius: 30px;
        }

        /* --- Sidebar collapse --- */
        #sidebar {
            width: 16rem;
            transition: width .18s ease;
        }

        #sidebar.collapsed {
            width: 5rem;
        }

        #sidebar.collapsed .sidebar-label,
        #sidebar.collapsed .sidebar-logo-text,
        #sidebar.collapsed .sidebar-section-label {
            display: none;
        }

        #sidebar.collapsed .sidebar-link,
        #sidebar.collapsed .sidebar-footer-btn {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        #sidebar.collapsed .sidebar-logo-row {
            justify-content: center;
        }

        #sidebar-toggle svg {
            transition: transform .18s ease;
        }

        #sidebar.collapsed #sidebar-toggle svg {
            transform: rotate(180deg);
        }
    </style>
</head>
<body class="font-sans text-ink antialiased min-h-screen">
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside id="sidebar" class="shrink-0 bg-navy flex flex-col">

        {{-- Logo --}}
        <div class="px-4 py-6 border-b border-white/10">
            <div class="sidebar-logo-row flex items-center justify-between gap-2">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 shrink-0 rounded-xl bg-accent flex items-center justify-center">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24">
                            <path d="M3 10.5L12 3L21 10.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.5Z"
                                  stroke="white" stroke-width="1.7" stroke-linejoin="round"/>
                            <path d="M9 21V12H15V21" stroke="white" stroke-width="1.7" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <div class="sidebar-logo-text min-w-0">
                        <h2 class="font-display font-semibold text-lg text-white leading-tight truncate">
                            RiadReply
                        </h2>
                        <p class="text-xs text-white/50 truncate">
                            AI Reputation Manager
                        </p>
                    </div>
                </div>

                <button id="sidebar-toggle" type="button" aria-label="Réduire le menu"
                    class="shrink-0 w-7 h-7 rounded-md flex items-center justify-center text-white/50 hover:text-white hover:bg-white/10 transition">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                        <path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-6 space-y-1">

            <p class="sidebar-section-label px-4 pb-2 text-xs uppercase tracking-widest text-white/40">
                Principal
            </p>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               title="Dashboard"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
               {{ request()->routeIs('dashboard') ? 'sidebar-active font-semibold' : 'text-white/70 hover:bg-white/10' }}">

                <svg width="20" height="20" class="shrink-0" fill="none" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5" stroke="currentColor" stroke-width="1.8"/>
                </svg>

                <span class="sidebar-label">Dashboard</span>
            </a>

            {{-- Établissements --}}
            <a href="{{ route('establishments.index') }}"
               title="Mes établissements"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
               {{ request()->routeIs('establishments.*') ? 'sidebar-active font-semibold' : 'text-white/70 hover:bg-white/10' }}">

                <svg width="20" height="20" class="shrink-0" fill="none" viewBox="0 0 24 24">
                    <path d="M4 20V5a2 2 0 012-2h12a2 2 0 012 2v15" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M9 20V15h6v5" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M8 8h.01M12 8h.01M16 8h.01M8 12h.01M12 12h.01M16 12h.01"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>

                <span class="sidebar-label">Mes établissements</span>
            </a>

            {{-- Analyser un avis --}}
            <a href="{{ route('reviews.create') }}"
               title="Analyser un avis"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
               {{ request()->routeIs('reviews.create') ? 'sidebar-active font-semibold' : 'text-white/70 hover:bg-white/10' }}">

                <svg width="20" height="20" class="shrink-0" fill="none" viewBox="0 0 24 24">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>

                <span class="sidebar-label">Analyser un avis</span>
            </a>

            {{-- Mes avis --}}
            <a href="{{ route('reviews.index') }}"
               title="Mes avis"
               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-xl
               {{ request()->routeIs('reviews.index') || request()->routeIs('reviews.show') ? 'sidebar-active font-semibold' : 'text-white/70 hover:bg-white/10' }}">

                <svg width="20" height="20" class="shrink-0" fill="none" viewBox="0 0 24 24">
                    <path d="M21 15A2 2 0 0119 17H7L3 21V5A2 2 0 015 3H19A2 2 0 0121 5V15Z"
                          stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                </svg>

                <span class="sidebar-label">Mes avis</span>
            </a>

        </nav>

        {{-- Footer --}}
        <div class="border-t border-white/10 p-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button title="Déconnexion" class="sidebar-footer-btn w-full flex items-center gap-3 rounded-lg px-4 py-2.5 text-sm
                    text-white/70 hover:bg-white/5 hover:text-white transition">
                    <svg width="18" height="18" class="shrink-0" fill="none" viewBox="0 0 24 24">
                        <path d="M9 21H5A2 2 0 0 1 3 19V5A2 2 0 0 1 5 3H9M16 17L21 12L16 7M21 12H9"
                              stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="sidebar-label">Déconnexion</span>
                </button>
            </form>
        </div>

    </aside>

    <div class="flex-1 min-w-0 flex flex-col">

        {{-- Header --}}
        <header class="bg-white border-b border-line px-8 py-4">
            <div class="flex items-center justify-between gap-4">

                {{-- Partie gauche : titre de la page --}}
                <h1 class="text-xl font-display font-semibold text-ink truncate">
                    @yield('titre', 'Dashboard')
                </h1>

                {{-- Partie droite --}}
                <div class="flex items-center gap-3 shrink-0">

                    {{-- Établissement actif --}}
                    <details class="relative">
                        <summary class="cursor-pointer list-none flex items-center gap-2 pl-2.5 pr-3 py-1.5 rounded-lg border border-line hover:border-accent/40 transition">
                            <span class="w-6 h-6 shrink-0 rounded-md bg-stone flex items-center justify-center">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" class="text-ink/60">
                                    <path d="M4 20V5a2 2 0 012-2h12a2 2 0 012 2v15" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M9 20V15h6v5" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                            </span>
                            <span class="text-sm font-medium text-ink max-w-[140px] truncate">
                                {{ auth()->user()->currentEstablishment?->name ?? 'Aucun établissement' }}
                            </span>
                            @if(auth()->user()->establishments->count() > 1)
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" class="text-muted shrink-0">
                                    <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            @endif
                        </summary>

                        @if(auth()->user()->establishments->count() > 1)
                            <div class="absolute right-0 mt-2 w-64 bg-white rounded-xl border border-line shadow-xl z-50 overflow-hidden">
                                <p class="px-4 pt-3 pb-2 text-xs font-semibold text-muted uppercase tracking-wide">Changer d'établissement</p>
                                @foreach(auth()->user()->establishments as $item)
                                    <form method="POST" action="{{ route('establishments.switch', $item) }}">
                                        @csrf
                                        <button class="w-full text-left px-4 py-2.5 hover:bg-stone transition flex items-center justify-between text-sm text-ink">
                                            <span class="truncate">{{ $item->name }}</span>
                                            @if(auth()->user()->currentEstablishment?->id == $item->id)
                                                <svg width="14" height="14" class="text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24">
                                                    <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        @endif
                    </details>

                    {{-- Profil --}}
                    <details class="relative">
                        <summary class="list-none cursor-pointer">
                            <div class="w-9 h-9 rounded-full bg-accent flex items-center justify-center text-white text-sm font-semibold hover:opacity-90 transition">
                                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                            </div>
                        </summary>

                        {{-- Dropdown --}}
                        <div class="absolute right-0 mt-2 w-64 rounded-xl bg-white border border-line overflow-hidden shadow-lg z-50">

                            <div class="p-4 border-b border-line">
                                <p class="text-sm font-medium text-ink">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-muted">{{ auth()->user()->email }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center gap-3 px-4 py-3 text-sm text-ink hover:bg-stone transition">
                                <svg width="16" height="16" class="shrink-0 text-muted" fill="none" viewBox="0 0 24 24">
                                    <path d="M12 20h9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                                </svg>
                                Modifier le profil
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm text-accent hover:bg-stone transition">
                                    <svg width="16" height="16" class="shrink-0" fill="none" viewBox="0 0 24 24">
                                        <path d="M9 21H5A2 2 0 0 1 3 19V5A2 2 0 0 1 5 3H9M16 17L21 12L16 7M21 12H9"
                                              stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </details>

                </div>
            </div>
        </header>

        <main class="flex-1">
            <div class="max-w-7xl mx-auto px-8 py-8">

                {{-- Flash message --}}
                @if (session('status'))
                    <div id="flash-status" class="mb-6 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-700 transition-all duration-300">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">Succès</p>
                            <p class="text-sm">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif

                {{-- Errors --}}
                @if ($errors->any())
                    <div id="flash-errors" class="mb-6 rounded-xl border border-accent/20 bg-accent/5 px-5 py-4 transition-all duration-300">
                        <div class="flex items-center gap-2 mb-2">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                                <path d="M12 8v5m0 4h.01M10.29 3.86L1.82 18A2 2 0 003.53 21h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"
                                      stroke="#B5502F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p class="text-sm font-semibold text-accent-dark">Des erreurs sont présentes</p>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-sm text-accent-dark">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Content --}}
                <div>
                    @yield('contenu')
                </div>

            </div>
        </main>

    </div>
</div>

<script>
    (function () {
        const sidebar = document.getElementById('sidebar');
        const toggle  = document.getElementById('sidebar-toggle');
        const KEY = 'riadreply_sidebar_collapsed';

        if (!sidebar || !toggle) return;

        if (localStorage.getItem(KEY) === '1') {
            sidebar.classList.add('collapsed');
        }

        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem(KEY, sidebar.classList.contains('collapsed') ? '1' : '0');
        });
    })();

    // Auto-dismiss des bannières de succès / erreur
    (function () {
        function autoDismiss(id, delay) {
            const el = document.getElementById(id);
            if (!el) return;
            setTimeout(() => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-6px)';
                setTimeout(() => el.remove(), 300);
            }, delay);
        }

        autoDismiss('flash-status', 4000);
        autoDismiss('flash-errors', 6000);
    })();
</script>

@stack('scripts')
</body>
</html>