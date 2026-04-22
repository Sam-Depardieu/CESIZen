<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CESIZen | Ministère de la Santé et de la Prévention</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            /* Official French Government Colors (DSFR) */
            --fr-blue: #000091;
            --fr-blue-hover: #1212ff;
            --fr-red: #E1000F;
            --fr-text: #161616;
            --fr-background: #F6F6F6;

            /* CESIZen Adapted for RGAA (High Contrast) */
            --cz-navy: #000074; /* Darker than 000080 for better contrast */
            --cz-mustard: #715200; /* Darkened mustard for text on white (Contrast > 7:1) */
            --cz-mustard-bg: #F8B803; /* Original mustard for backgrounds only */
            --cz-jade: #00635B; /* Darkened jade for text (Contrast > 4.5:1) */
            --cz-jade-bg: #26A69A; /* Original jade for backgrounds */
            --cz-indigo: #303F9F;
            --cz-coral: #C62828;
        }

        /* Focus state for RGAA */
        :focus-visible {
            outline: 3px solid var(--fr-blue);
            outline-offset: 2px;
        }

        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: var(--fr-blue);
            color: white;
            padding: 8px;
            z-index: 100;
            transition: top 0.2s;
        }
        .skip-link:focus {
            top: 0;
        }
    </style>
</head>
<body class="bg-[#F6F6F6] text-[#161616] min-h-screen font-sans antialiased overflow-x-hidden">

    <a href="#main-content" class="skip-link">Aller au contenu principal</a>

    <!-- Government Official Header -->
    @include('layouts.header')

    <div class="flex min-h-[calc(100vh-80px)]">

        <!-- Institutional Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Workspace -->
        <main id="main-content" class="flex-1 flex flex-col bg-[#F6F6F6] overflow-y-auto">

            <div class="p-6 lg:p-10 max-w-7xl mx-auto w-full space-y-10">

                <!-- Hero Section: Accessible and Structured -->
                <section class="bg-white border border-slate-200 p-8 lg:p-12 shadow-sm flex flex-col md:flex-row items-center gap-10 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-2 h-full bg-[--cz-mustard-bg]"></div>

                    <div class="flex-1 space-y-6">
                        <h2 class="text-4xl font-black text-slate-900 leading-tight">
                            Bonjour <span class="text-[--fr-blue]">{{ Auth::user()->name ?? 'Cher étudiant' }}</span>
                        </h2>
                        <p class="text-slate-700 text-lg leading-relaxed max-w-2xl">
                            Le <span class="font-bold">Ministère de la Santé</span> vous propose cet espace sécurisé pour suivre votre bien-être au quotidien. Retrouvez vos outils de prévention et vos diagnostics.
                        </p>
                        <div class="flex flex-wrap gap-4 pt-2">
                            <a href="{{ route('diagnostics.index') }}" class="px-8 py-3.5 bg-[#000091] text-white font-bold rounded-sm hover:bg-blue-800 transition-all shadow-md">
                                Faire mon bilan de santé
                            </a>
                            <a href="{{ route('relaxation.index') }}" class="px-8 py-3.5 bg-white border-2 border-[#000091] text-[#000091] font-bold rounded-sm hover:bg-slate-50 transition-all">
                                Voir mes favoris
                            </a>
                        </div>
                    </div>
                </section>

                <!-- Grid of Modules -->
                <section aria-labelledby="modules-title">
                    <h3 id="modules-title" class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                        Vos outils de suivi
                        <span class="w-8 h-1 bg-[--cz-mustard-bg] rounded-full"></span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        <!-- Tracker (Jade) -->
                        <div class="bg-white p-8 border border-slate-200 shadow-sm transition-colors relative group">
                            <div class="w-12 h-12 rounded-sm bg-teal-50 flex items-center justify-center mb-6 text-[--cz-jade]" aria-hidden="true">
                                <x-heroicon-o-clock class="h-7 w-7" />
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 mb-2">Journal Émotionnel</h4>
                            <p class="text-slate-600 text-sm leading-relaxed mb-6">Suivez l'évolution de votre état émotionnel avec l'outil d'auto-évaluation.</p>
                            <a href="{{ route('emotions.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[--cz-jade] hover:underline underline-offset-4 decoration-2">
                                Accéder au journal
                                <x-heroicon-o-arrow-right class="h-4 w-4" aria-hidden="true" />
                            </a>
                        </div>

                        <!-- Diagnostics (Mustard) -->
                        <div class="bg-white p-8 border border-slate-200 shadow-sm transition-colors relative group">
                            <div class="w-12 h-12 rounded-sm bg-orange-50 flex items-center justify-center mb-6 text-[--cz-mustard]" aria-hidden="true">
                                <x-heroicon-o-chart-bar class="h-7 w-7" />
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 mb-2">Tests de Stress</h4>
                            <p class="text-slate-600 text-sm leading-relaxed mb-6">Réalisez des diagnostics validés scientifiquement par nos experts.</p>
                            <a href="{{ route('diagnostics.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[--cz-mustard] hover:underline underline-offset-4 decoration-2">
                                Commencer un test
                                <x-heroicon-o-arrow-right class="h-4 w-4" aria-hidden="true" />
                            </a>
                        </div>

                        <!-- Info -->
                        <div class="bg-white p-8 border border-slate-200 shadow-sm transition-colors relative group">
                            <div class="w-12 h-12 rounded-sm bg-blue-50 flex items-center justify-center mb-6 text-[--fr-blue]" aria-hidden="true">
                                <x-heroicon-o-document-text class="h-7 w-7" />
                            </div>
                            <h4 class="text-xl font-bold text-slate-900 mb-2">Guides Pratiques</h4>
                            <p class="text-slate-600 text-sm leading-relaxed mb-6">Consultez les fiches de prévention sur le sommeil et la nutrition.</p>
                            <a href="#" class="inline-flex items-center gap-2 text-sm font-bold text-[--fr-blue] hover:underline underline-offset-4 decoration-2">
                                Lire les guides
                                <x-heroicon-o-arrow-right class="h-4 w-4" aria-hidden="true" />
                            </a>
                        </div>

                    </div>
                </section>

                <!-- RGPD / Information Footer -->
                <section class="bg-slate-100 p-8 border border-slate-200 rounded-sm">
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <div class="w-12 h-12 bg-white rounded-sm flex items-center justify-center text-[--fr-blue] border border-slate-200 shadow-sm flex-shrink-0" aria-hidden="true">
                            <x-heroicon-o-shield-check class="w-6 h-6" />
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900 mb-2">Vos données sont protégées</h4>
                            <p class="text-slate-600 text-sm leading-relaxed">
                                Conformément au RGPD et aux recommandations de la CNIL, vos données de santé sont strictement confidentielles.
                                Elles sont hébergées sur des infrastructures sécurisées de l'État et ne sont jamais partagées sans votre accord.
                            </p>
                        </div>
                    </div>
                </section>

            </div>

            <!-- Global Footer Gov -->
            
        </main>
    </div>
    @include('layouts.footer')
</body>
</html>
