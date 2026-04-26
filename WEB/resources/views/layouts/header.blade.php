<header role="banner" class="bg-white border-b-2 border-[--fr-blue] sticky top-0 z-50 shadow-sm">
    <div class="container mx-auto px-4 lg:px-8 py-3 flex items-center justify-between">
        <div class="flex items-start justify-start gap-8">
            <div class="flex flex-col border-r-2 border-slate-200 pr-8" aria-hidden="true">
                <img src="{{ asset('img/ministere.png') }}" alt="Logo du Ministère de la Santé et de la Prévention" class="w-24 h-auto">
            </div>

            <div class="flex flex-col">
                <h1 class="text-2xl font-black text-[--fr-blue] tracking-tight">
                    <a href="{{ route('home') }}"><img src="{{ asset('img/CesiZen.png') }}" alt="Logo CesiZen" class="w-32 h-auto"></a>
                </h1>
            </div>
        </div>

        <nav class="flex items-center gap-6" role="navigation" aria-label="Menu utilisateur">
            @auth
                <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-1.5 bg-slate-50 rounded-lg border border-slate-200 shadow-sm hover:border-[#000091] transition-all group">
                    <div class="text-right">
                        <p class="text-sm font-bold leading-none text-slate-900 group-hover:text-[#000091]">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Mon Profil</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=000091&color=fff" class="w-9 h-9 rounded-lg border border-slate-200" alt="Avatar de {{ Auth::user()->name }}">
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-slate-500 hover:text-[#E1000F] uppercase tracking-tighter flex items-center gap-1">
                        <x-heroicon-o-arrow-left-on-rectangle class="h-4 w-4" />
                        Quitter
                    </button>
                </form>
            @else
                <div class="flex items-center gap-4">
                    <a href="/login" class="text-sm font-bold text-[#000091] hover:underline px-2 py-2">Se connecter</a>
                    <a href="/register" class="px-6 py-2.5 bg-[#000091] text-white text-sm font-bold rounded-sm shadow-md hover:bg-blue-800 transition-all">
                        Créer un compte
                    </a>
                </div>
            @endauth
        </nav>
    </div>
</header>
