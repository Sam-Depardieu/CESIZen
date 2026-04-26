@extends('layouts.app')

@section('title', 'Journal des Diagnostics')
@section('header_title', 'Mon Historique de Stress')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-900 uppercase">Journal de bord</h2>
            <p class="text-slate-500">Retrouvez l'évolution de vos bilans de stress.</p>
        </div>
        <a href="{{ route('diagnostics.index') }}" class="px-6 py-2 bg-[--fr-blue] text-white font-bold rounded-sm shadow-md hover:bg-blue-800 transition-all text-sm uppercase tracking-widest">
            Nouveau test
        </a>
    </div>

    <div class="bg-white border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Date du bilan</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Score</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Niveau de Risque</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($history as $result)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-5">
                            <p class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($result->date_passage)->translatedFormat('d F Y') }}</p>
                            <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($result->date_passage)->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="text-xl font-black text-[--fr-blue]">{{ $result->score_total }}</span>
                        </td>
                        <td class="px-6 py-5">
                            @if($result->niveau_stress === 'Élevé')
                                <span class="px-3 py-1 bg-red-50 text-red-700 text-[10px] font-black uppercase rounded-full border border-red-100">Risque Élevé</span>
                            @elseif($result->niveau_stress === 'Modéré')
                                <span class="px-3 py-1 bg-orange-50 text-orange-700 text-[10px] font-black uppercase rounded-full border border-orange-100">Risque Modéré</span>
                            @else
                                <span class="px-3 py-1 bg-teal-50 text-teal-700 text-[10px] font-black uppercase rounded-full border border-teal-100">Risque Faible</span>
                            @endif

                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach($result->events as $event)
                                    <span class="text-[9px] bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded-sm border border-slate-200" title="{{ $event->event_name }}">
                                        {{ Str::limit($event->event_name, 20) }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-slate-400 italic">
                            Vous n'avez pas encore effectué de bilan de stress.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
