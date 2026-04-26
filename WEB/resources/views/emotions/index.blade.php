@extends('layouts.app')

@section('title', 'Journal Émotionnel')

@section('content')
<div class="max-w-4xl mx-auto space-y-10">

    <!-- Nouveau Record -->
    <div class="bg-white p-8 border border-slate-200 shadow-sm">
        <h2 class="text-xl font-bold mb-6">Comment vous sentez-vous aujourd'hui ?</h2>
        <form action="{{ route('emotions.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">Votre émotion dominante</label>
                <div class="grid grid-cols-3 md:grid-cols-5 gap-4">
                    @foreach(['Très bien' => '😊', 'Bien' => '🙂', 'Neutre' => '😐', 'Pas top' => '🙁', 'Stressé' => '😫'] as $label => $emoji)
                    <label class="cursor-pointer group">
                        <input type="radio" name="emotion" value="{{ $label }}" class="peer hidden" required>
                        <div class="p-4 border-2 border-slate-100 rounded-xl text-center peer-checked:border-[--fr-blue] peer-checked:bg-blue-50 transition-all">
                            <span class="block text-2xl mb-1">{{ $emoji }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 group-hover:text-slate-900">{{ $label }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">Intensité (1 à 5)</label>
                <input type="range" name="intensity" min="1" max="5" step="1" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-[--fr-blue]">
                <div class="flex justify-between text-[10px] font-bold text-slate-400 mt-2 px-1">
                    <span>FAIBLE</span>
                    <span>MODÉRÉE</span>
                    <span>TRÈS FORTE</span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">Notes (Optionnel)</label>
                <textarea name="note" rows="3" class="w-full border-slate-200 rounded-lg focus:ring-[--fr-blue] focus:border-[--fr-blue]" placeholder="Décrivez votre état..."></textarea>
            </div>

            <button type="submit" class="w-full py-4 bg-[#000091] text-white font-bold rounded-sm hover:bg-blue-800 shadow-md transition-all uppercase tracking-widest">
                Enregistrer dans mon journal
            </button>
        </form>
    </div>

    <!-- Historique -->
    <div class="space-y-4">
        <h2 class="text-xl font-bold">Historique récent</h2>
        @forelse($records as $record)
        <div class="bg-white p-6 border border-slate-100 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="text-3xl">
                    @switch($record->emotion)
                        @case('Très bien') 😊 @break
                        @case('Bien') 🙂 @break
                        @case('Neutre') 😐 @break
                        @case('Pas top') 🙁 @break
                        @case('Stressé') 😫 @break
                        @default 😐
                    @endswitch
                </div>
                <div>
                    <p class="font-bold text-slate-900 capitalize">{{ $record->emotion }}</p>
                    <p class="text-xs text-slate-500">{{ $record->created_at->diffForHumans() }}</p>
                    @if($record->note)
                        <p class="text-sm text-slate-600 mt-1 italic">"{{ $record->note }}"</p>
                    @endif
                </div>
            </div>
            <div class="flex gap-1">
                @for($i=1; $i<=5; $i++)
                    <div class="w-2 h-6 rounded-full {{ $i <= $record->intensity ? 'bg-[--cz-jade-bg]' : 'bg-slate-100' }}"></div>
                @endfor
            </div>
        </div>
        @empty
        <p class="text-center text-slate-400 py-10">Aucun enregistrement pour le moment.</p>
        @endforelse
    </div>
</div>
@endsection
