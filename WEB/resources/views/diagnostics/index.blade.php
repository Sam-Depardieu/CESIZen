@extends('layouts.app')

@section('title', 'Bilan de Stress')
@section('header_title', 'Évaluation de Stress (Holmes & Rahe)')

@section('content')
<div class="max-w-4xl mx-auto space-y-10">

    @if(session('score'))
        <div class="bg-white p-8 border-l-8 border-[--fr-blue] shadow-lg">
            <h2 class="text-2xl font-black mb-4">Votre Résultat : <span class="text-[--fr-blue]">{{ session('score') }} points</span></h2>
            <div class="p-6 bg-slate-50 rounded-lg">
                @if(session('score') >= 300)
                    <p class="font-bold text-red-700 uppercase mb-2">Risque élevé de maladie</p>
                    <p class="text-slate-600">Votre score indique un niveau de stress très important. Nous vous conseillons vivement de consulter un professionnel de santé.</p>
                @elseif(session('score') >= 150)
                    <p class="font-bold text-orange-700 uppercase mb-2">Risque modéré</p>
                    <p class="text-slate-600">Vous avez traversé beaucoup de changements récemment. Prenez du temps pour vous reposer.</p>
                @else
                    <p class="font-bold text-teal-700 uppercase mb-2">Risque faible</p>
                    <p class="text-slate-600">Votre niveau de stress lié aux changements de vie semble stable.</p>
                @endif
            </div>
            <a href="{{ route('diagnostics.index') }}" class="mt-6 inline-block text-[--fr-blue] font-bold hover:underline">Recommencer le test</a>
        </div>
    @else
        <div class="bg-white p-8 border border-slate-200 shadow-sm">
            <div class="mb-8">
                <h2 class="text-xl font-bold mb-2">Quels événements avez-vous vécus ces 12 derniers mois ?</h2>
                <p class="text-sm text-slate-500 italic">Cochez toutes les cases qui correspondent à votre situation actuelle.</p>
            </div>

            <form action="{{ route('diagnostics.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 gap-3 max-h-[500px] overflow-y-auto pr-4 border-b border-t border-slate-100 py-6">
                    @foreach($events as $event)
                        <label class="flex items-center gap-4 p-4 hover:bg-slate-50 rounded-lg cursor-pointer transition-colors border border-transparent hover:border-slate-200 group">
                            <input type="checkbox" name="events[]" value="{{ $event->id }}" class="w-5 h-5 text-[--fr-blue] border-slate-300 rounded focus:ring-[--fr-blue]">
                            <span class="flex-1 text-sm font-medium text-slate-700 group-hover:text-slate-900">{{ $event->event_name }}</span>
                            <span class="text-xs font-bold text-slate-400">{{ $event->points }} pts</span>
                        </label>
                    @endforeach
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-4 bg-[#000091] text-white font-bold rounded-sm hover:bg-blue-800 shadow-md transition-all uppercase tracking-widest">
                        Calculer mon score de stress
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
