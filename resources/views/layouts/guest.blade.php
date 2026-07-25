<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RiadReply AI') }}</title>

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
        .btn-primary { background: #B5502F; transition: background-color .15s ease; }
        .btn-primary:hover { background: #8F3D22; }
        .mono { font-family: "JetBrains Mono", monospace; }
        .dot-pattern {
            background-image: radial-gradient(rgba(255,255,255,.07) 1px, transparent 1px);
            background-size: 22px 22px;
        }
    </style>
</head>
<body class="font-sans text-ink antialiased">
<div class="min-h-screen flex">

    {{-- Panneau de marque (desktop uniquement) --}}
    <div class="hidden lg:flex lg:w-[44%] xl:w-[38%] bg-navy relative flex-col justify-between p-12 dot-pattern">

        <div class="flex items-center gap-3">
            <div class="w-10 h-10 shrink-0 rounded-xl bg-accent flex items-center justify-center">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                    <path d="M3 10.5L12 3L21 10.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.5Z"
                          stroke="white" stroke-width="1.7" stroke-linejoin="round"/>
                    <path d="M9 21V12H15V21" stroke="white" stroke-width="1.7" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <p class="font-display font-semibold text-white leading-tight">RiadReply</p>
                <p class="text-xs text-white/40">AI Reputation Manager</p>
            </div>
        </div>

        <div class="max-w-sm">
            <p class="font-display text-3xl leading-snug text-white mb-4">
                Réponds à tes avis clients en quelques secondes.
            </p>
            <p class="text-white/50 text-sm leading-relaxed">
                L'IA détecte la langue, le sentiment et rédige une réponse personnalisée pour chaque avis, dans le ton de ton établissement.
            </p>
        </div>

        <p class="text-xs text-white/30">© {{ date('Y') }} RiadReply AI</p>
    </div>

    {{-- Panneau formulaire --}}
    <div class="flex-1 flex items-center justify-center bg-stone lg:bg-white px-6 py-12">
        <div class="w-full max-w-sm">

            {{-- Logo mobile uniquement --}}
            <div class="flex lg:hidden items-center justify-center gap-2.5 mb-8">
                <div class="w-9 h-9 rounded-lg bg-accent flex items-center justify-center">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24">
                        <path d="M3 10.5L12 3L21 10.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V10.5Z"
                              stroke="white" stroke-width="1.7" stroke-linejoin="round"/>
                        <path d="M9 21V12H15V21" stroke="white" stroke-width="1.7" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="font-display font-semibold text-ink">RiadReply</span>
            </div>

            {{ $slot }}
        </div>
    </div>

</div>
</body>
</html>