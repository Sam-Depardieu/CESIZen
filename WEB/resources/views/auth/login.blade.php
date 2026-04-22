<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion | CESIZen - Ministère de la Santé</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --fr-blue: #000091; }
        .fr-input-group { margin-bottom: 1.5rem; }
        .fr-label { display: block; font-size: 0.875rem; font-weight: 700; margin-bottom: 0.5rem; color: #161616; }
        .fr-input { width: 100%; padding: 0.75rem; border: 2px solid #3a3a3a; border-radius: 0; background-color: #eee; transition: border-color 0.2s; }
        .fr-input:focus { border-color: var(--fr-blue); outline: 3px solid var(--fr-blue); outline-offset: 2px; }
    </style>
</head>
<body class="bg-[#F6F6F6] min-h-screen flex flex-col font-sans">
    @include('layouts.header')

    <main id="main-content" class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-lg bg-white border border-slate-200 shadow-xl p-12">
            <div class="mb-10 text-left border-l-4 border-[#000091] pl-6">
                <h1 class="text-3xl font-black text-slate-900 mb-2">Se connecter</h1>
                <p class="text-slate-600 font-medium">Espace sécurisé pour les étudiants du CESI</p>
            </div>

            @if ($errors->any())
                <div class="bg-[#FAEFEF] border-l-4 border-[#E1000F] p-4 mb-8" role="alert">
                    <p class="text-[#E1000F] text-sm font-bold">Erreur : {{ $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-8">
                @csrf
                <div class="fr-input-group">
                    <label for="email" class="fr-label">Adresse électronique (email)</label>
                    <input type="email" id="email" name="email" required
                           class="fr-input"
                           value="{{ old('email') }}"
                           aria-required="true"
                           autocomplete="email">
                </div>

                <div class="fr-input-group">
                    <label for="password" class="fr-label">Mot de passe</label>
                    <input type="password" id="password" name="password" required
                           class="fr-input"
                           aria-required="true"
                           autocomplete="current-password">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-5 h-5 border-2 border-slate-400 text-[#000091] focus:ring-[#000091]">
                        <span class="text-sm font-bold text-slate-700 group-hover:text-[#000091]">Rester connecté</span>
                    </label>
                    <a href="#" class="text-sm font-bold text-[#000091] hover:underline">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="w-full py-4 bg-[#000091] text-white font-bold text-lg hover:bg-blue-800 shadow-md transition-all uppercase tracking-widest">
                    Valider la connexion
                </button>
            </form>

            <div class="mt-12 pt-8 border-t border-slate-100">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Pas encore de compte ?</h2>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-[#000091] text-[#000091] font-bold hover:bg-blue-50 transition-all">
                    Créer mon espace personnel
                </a>
            </div>
        </div>
    </main>

    @include('layouts.footer')
</body>
</html>
