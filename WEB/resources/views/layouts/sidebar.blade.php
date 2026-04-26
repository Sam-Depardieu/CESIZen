<aside class="hidden lg:flex w-80 flex-col bg-white border-r border-slate-200 overflow-y-auto" role="navigation" aria-label="Menu latéral">
    <nav class="flex-1 px-6 py-10 space-y-1">
        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-4 px-4">Navigation</p>

        <!-- Tableau de bord -->
        <a href="{{ route('home') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all {{ request()->routeIs('home') ? 'text-white bg-[#000091] font-bold shadow-md' : 'text-slate-700 hover:bg-slate-50 font-medium' }}"
           {!! request()->routeIs('home') ? 'aria-current="page"' : '' !!}>
            <x-heroicon-o-home class="h-5 w-5" aria-hidden="true" />
            Tableau de bord
        </a>

        <!-- Mon Journal Émotionnel -->
        <a href="{{ route('emotions.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all group {{ request()->routeIs('emotions.*') ? 'text-white bg-[#000091] font-bold shadow-md' : 'text-slate-700 hover:bg-slate-50 font-medium border-b border-slate-100/50' }}"
           {!! request()->routeIs('emotions.*') ? 'aria-current="page"' : '' !!}>
            <div class="w-8 h-8 rounded-sm flex items-center justify-center transition-colors {{ request()->routeIs('emotions.*') ? 'bg-white/20 text-white' : 'bg-teal-50 text-[--cz-jade]' }}" aria-hidden="true">
                <x-heroicon-o-clock class="h-4 w-4" />
            </div>
            Mon Journal Émotionnel
        </a>

        <!-- Diagnostics Stress -->
        <a href="{{ route('diagnostics.index') }}"
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all group {{ request()->routeIs('diagnostics.*') ? 'text-white bg-[#000091] font-bold shadow-md' : 'text-slate-700 hover:bg-slate-50 font-medium border-b border-slate-100/50' }}"
           {!! request()->routeIs('diagnostics.*') ? 'aria-current="page"' : '' !!}>
            <div class="w-8 h-8 rounded-sm flex items-center justify-center transition-colors {{ request()->routeIs('diagnostics.*') ? 'bg-white/20 text-white' : 'bg-orange-50 text-[--cz-mustard]' }}" aria-hidden="true">
                <x-heroicon-o-chart-bar class="h-4 w-4" />
            </div>
            Diagnostics Stress
        </a>

        <!-- Exercices de Respiration -->
        <a href="{{ route('relaxation.index') }}#breathing-app"
           class="flex items-center gap-4 px-4 py-3 rounded-sm transition-all group {{ request()->routeIs('relaxation.*') ? 'text-white bg-[#000091] font-bold shadow-md' : 'text-slate-700 hover:bg-slate-50 font-medium border-b border-slate-100/50' }}"
           {!! request()->routeIs('relaxation.*') ? 'aria-current="page"' : '' !!}>
            <div class="w-8 h-8 rounded-sm flex items-center justify-center transition-colors {{ request()->routeIs('relaxation.*') ? 'bg-white/20 text-white' : 'bg-indigo-50 text-indigo-700' }}" aria-hidden="true">
                <x-heroicon-o-heart class="h-4 w-4" />
            </div>
            Exercices de Respiration
        </a>

        <div class="pt-10 space-y-1">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-4 px-4">Services de l'État</p>
            <a href="#" class="flex items-center justify-between px-4 py-2 text-sm text-slate-600 hover:text-[--fr-blue] font-medium transition-colors">
                <span>Guide Santé Étudiante</span>
                <x-heroicon-o-document-text class="h-4 w-4" aria-hidden="true" />
            </a>
            <a href="#" class="flex items-center justify-between px-4 py-2 text-sm text-slate-600 hover:text-[--fr-blue] font-medium transition-colors">
                <span>Ligne d'Urgence</span>
                <x-heroicon-o-phone class="h-4 w-4" aria-hidden="true" />
            </a>
        </div>
    </nav>

    <!-- Alert Block -->
    <div class="p-6">
        <div class="bg-slate-900 rounded-sm p-6 text-white relative overflow-hidden">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Besoin d'aide ?</p>
            <p class="text-xs font-medium leading-relaxed mb-4">Une ligne d'écoute gratuite est disponible pour tous les étudiants.</p>
            <a href="tel:0800130000" class="flex items-center justify-center gap-2 w-full py-2.5 bg-white text-slate-900 text-sm font-bold rounded-sm hover:bg-slate-100 transition-colors">
                <x-heroicon-o-phone class="h-4 w-4" aria-hidden="true" />
                0 800 130 000
            </a>
        </div>
    </div>
</aside>
