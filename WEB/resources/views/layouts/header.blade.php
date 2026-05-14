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
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 px-4 py-1.5 bg-slate-50 rounded-lg border border-slate-200 shadow-sm hover:border-[#000091] transition-all group">
                        <div class="text-right">
                            <p class="text-sm font-bold leading-none text-slate-900 group-hover:text-[#000091]">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Mon Profil</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=000091&color=fff" class="w-9 h-9 rounded-lg border border-slate-200" alt="Avatar de {{ Auth::user()->name }}">
                        <x-heroicon-o-chevron-down class="h-4 w-4 text-slate-400 group-hover:text-[#000091] transition-transform" ::class="open ? 'rotate-180' : ''" />
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-lg shadow-xl z-50 py-2 overflow-hidden">

                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                            <x-heroicon-o-user class="h-4 w-4 text-slate-400" />
                            Mon compte
                        </a>

                        @if(Auth::user()->role && Auth::user()->role->libelle === 'Admin')
                            <div class="border-t border-slate-100 my-1"></div>
                            <a href="/admin" class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-[--fr-blue] hover:bg-blue-50 transition-colors">
                                <x-heroicon-o-shield-check class="h-4 w-4" />
                                Administration
                            </a>
                        @endif

                        <div class="border-t border-slate-100 my-1"></div>

                        <form action="{{ route('logout') }}" method="POST" class="block w-full">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                                <x-heroicon-o-arrow-left-on-rectangle class="h-4 w-4" />
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
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
