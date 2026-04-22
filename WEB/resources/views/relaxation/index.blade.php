@extends('layouts.app')

@section('title', 'Détente & Respiration')

@section('content')
<div class="max-w-6xl mx-auto space-y-12">

    <!-- Module de Respiration Guidée -->
    <div x-data="breathingApp()" class="bg-white p-8 border border-slate-300 shadow-sm relative overflow-hidden">
        <div class="flex flex-col lg:flex-row gap-12 items-center">

            <!-- Contrôles et Choix du mode -->
            <div class="flex-1 space-y-6 w-full">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2 uppercase tracking-tight">Respiration Guidée</h2>
                    <p class="text-slate-500 text-sm">Sélectionnez un programme pour stabiliser votre rythme cardiaque.</p>
                </div>

                <div class="grid grid-cols-1 gap-4" x-show="!isStarted">
                    <template x-for="mode in modes" :key="mode.name">
                        <button
                            @click="selectedMode = mode"
                            :class="selectedMode.name === mode.name ? 'border-[--fr-blue] bg-blue-50 ring-2 ring-[--fr-blue] ring-opacity-20' : 'border-slate-300 bg-white'"
                            class="flex items-center gap-4 p-5 border-2 rounded-xl transition-all text-left group shadow-sm hover:border-slate-400"
                        >
                            <div :class="mode.colorClass" class="w-12 h-12 rounded-full flex items-center justify-center text-white shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.905c-.921-.154-1.862-.23-2.812-.23H9.25c-.95 0-1.891.076-2.812.23m12.324 3.248c.334.334.613.725.828 1.154M6.75 8.153a6.001 6.001 0 0 0-4.02 5.492m18.77 0c.115.624.175 1.266.175 1.921v2.684m-19.17 0v-2.684c0-.655.06-1.297.175-1.921M18 21V9a6 6 0 0 0-6-6h0a6 6 0 0 0-6 6v12" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-slate-900 text-base" x-text="mode.name"></p>
                                <p class="text-xs text-slate-500 leading-tight mt-1" x-text="mode.desc"></p>
                            </div>
                        </button>
                    </template>

                    <button @click="startExercise()" class="px-8 py-3.5 bg-[#000091] text-white font-bold rounded-sm hover:bg-blue-800 transition-all shadow-md">
                        Lancer la session (5 min)
                    </button>
                </div>

                <!-- Affichage pendant l'exercice -->
                <div class="space-y-8 py-10 text-center lg:text-left" x-show="isStarted" x-cloak>
                    <div>
                        <p class="text-6xl font-light tracking-tighter text-slate-400" x-text="formatTime(totalSeconds)"></p>
                        <p class="text-xs font-bold text-[--fr-blue] uppercase tracking-[0.3em] mt-3" x-text="selectedMode.name"></p>
                    </div>

                    <button @click="stopExercise()" class="px-10 py-4 border-2 border-red-200 text-red-600 font-bold rounded-sm hover:bg-red-50 transition-all uppercase text-xs tracking-widest">
                        Interrompre
                    </button>
                </div>
            </div>

            <!-- Animation du Cercle -->
            <div class="flex-1 flex flex-col items-center justify-center min-h-[450px] w-full bg-slate-50 border border-slate-200 rounded-sm relative">
                <div
                    class="rounded-full flex items-center justify-center transition-all ease-in-out border-8 relative shadow-2xl"
                    :class="selectedMode.borderClass"
                    :style="'width: ' + circleSize + 'px; height: ' + circleSize + 'px; background-color: ' + selectedMode.bgOpacity + '; transition-duration: ' + transitionDuration"
                >
                    <img src="{{ asset('img/CesiZen logo.png') }}" class="absolute w-2/3 opacity-10 pointer-events-none" alt="">

                    <div class="text-center z-10">
                        <p class="text-2xl font-black tracking-widest uppercase mb-1" :class="selectedMode.textClass" x-text="phaseText"></p>
                        <p class="text-5xl font-light" :class="selectedMode.textClass" x-show="isStarted" x-text="phaseTimer"></p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Section Activités -->
    <div class="space-y-8">
        <div class="flex items-center gap-4">
            <h2 class="text-xl font-bold text-slate-900 uppercase tracking-widest whitespace-nowrap">Bibliothèque Méditation</h2>
            <div class="h-1 flex-1 bg-slate-100"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($activities as $activity)
                <div class="bg-white rounded-sm border-2 border-slate-200 overflow-hidden flex flex-col shadow-sm group hover:border-[--fr-blue] transition-all">
                    <div class="h-48 bg-slate-100 flex items-center justify-center relative">
                        @if($activity->type == 'audio')
                            <x-heroicon-o-musical-note class="w-16 h-16 text-slate-300 group-hover:text-blue-200 transition-colors" />
                        @elseif($activity->type == 'video')
                            <x-heroicon-o-play-circle class="w-16 h-16 text-slate-300 group-hover:text-blue-200 transition-colors" />
                        @else
                            <x-heroicon-o-document-text class="w-16 h-16 text-slate-300 group-hover:text-blue-200 transition-colors" />
                        @endif

                        <button
                            onclick="toggleFavorite({{ $activity->id }}, this)"
                            class="absolute top-4 right-4 p-2.5 rounded-full bg-white shadow-md border border-slate-200 transition-all hover:scale-110 {{ in_array($activity->id, $favorites) ? 'text-red-500 border-red-100' : 'text-slate-400' }}"
                        >
                            <x-heroicon-s-heart class="w-6 h-6" />
                        </button>
                    </div>
                    <div class="p-8 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-3 py-1 bg-blue-50 text-[--fr-blue] text-[10px] font-bold uppercase rounded-sm">{{ $activity->type }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-[--fr-blue] transition-colors leading-tight">{{ $activity->title }}</h3>
                        <p class="text-sm text-slate-500 mb-8 flex-1 leading-relaxed">{{ $activity->description }}</p>

                        <a href="{{ $activity->url ?? '#' }}" target="_blank" class="w-full py-4 bg-[--fr-blue] text-white text-center text-xs font-bold rounded-sm shadow-md hover:bg-blue-800 transition-all uppercase tracking-widest">
                            DÉCOUVRIR L'ACTIVITÉ
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-slate-400 italic">Aucune activité disponible.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
async function toggleFavorite(activityId, btn) {
    try {
        const response = await fetch(`/relaxation/${activityId}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();

        if (data.status === 'added') {
            btn.classList.remove('text-slate-400');
            btn.classList.add('text-red-500', 'border-red-100');
        } else {
            btn.classList.remove('text-red-500', 'border-red-100');
            btn.classList.add('text-slate-400');
        }
    } catch (error) {
        console.error('Erreur favoris');
    }
}

function breathingApp() {
    return {
        isStarted: false,
        totalSeconds: 300,
        circleSize: 220,
        transitionDuration: '1000ms',
        phaseText: 'Prêt ?',
        phaseTimer: 0,
        interval: null,

        modes: [
            { name: '7-4-8', desc: 'Sommeil & Détente profonde', inhale: 7, hold: 4, exhale: 8, colorClass: 'bg-indigo-600', borderClass: 'border-indigo-200', textClass: 'text-indigo-600', bgClass: 'bg-indigo-600', bgOpacity: 'rgba(79, 70, 229, 0.05)' },
            { name: '5-5', desc: 'Cohérence Cardiaque classique', inhale: 5, hold: 0, exhale: 5, colorClass: 'bg-teal-600', borderClass: 'border-teal-200', textClass: 'text-teal-600', bgClass: 'bg-teal-600', bgOpacity: 'rgba(13, 148, 136, 0.05)' },
            { name: '4-6', desc: 'Réduction rapide du stress', inhale: 4, hold: 0, exhale: 6, colorClass: 'bg-orange-600', borderClass: 'border-orange-200', textClass: 'text-orange-600', bgClass: 'bg-orange-600', bgOpacity: 'rgba(234, 88, 12, 0.05)' }
        ],
        selectedMode: null,

        init() {
            this.selectedMode = this.modes[1];
        },

        async startExercise() {
            this.isStarted = true;
            this.totalSeconds = 300;

            this.interval = setInterval(() => {
                if (this.totalSeconds > 0) this.totalSeconds--;
                else this.stopExercise();
            }, 1000);

            while (this.isStarted) {
                await this.runPhase('Inspirez', this.selectedMode.inhale, 350);
                if (!this.isStarted) break;
                if (this.selectedMode.hold > 0) {
                    await this.runPhase('Bloquez', this.selectedMode.hold, 350);
                    if (!this.isStarted) break;
                }
                await this.runPhase('Expirez', this.selectedMode.exhale, 220);
            }
        },

        async runPhase(text, seconds, targetSize) {
            this.phaseText = text;
            this.phaseTimer = seconds;
            this.transitionDuration = seconds + 's';
            this.circleSize = targetSize;
            for (let i = 0; i < seconds; i++) {
                if (!this.isStarted) return;
                await new Promise(resolve => setTimeout(resolve, 1000));
                this.phaseTimer--;
            }
        },

        stopExercise() {
            this.isStarted = false;
            this.phaseText = 'Prêt ?';
            this.circleSize = 220;
            this.transitionDuration = '1000ms';
            clearInterval(this.interval);
        },

        formatTime(sec) {
            let m = Math.floor(sec / 60);
            let s = sec % 60;
            return `${m}:${s < 10 ? '0' : ''}${s}`;
        }
    }
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
