@extends('layouts.app')

@section('title', 'Mon Profil')
@section('header_title', 'Gestion du compte')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Informations Personnelles -->
    <div class="bg-white p-8 border border-slate-200 shadow-sm">
        <div class="flex items-center gap-4 mb-8 border-l-4 border-[#000091] pl-6">
            <h2 class="text-2xl font-black text-slate-900">Informations personnelles</h2>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="bg-teal-50 border-l-4 border-[#00635B] p-4 mb-8" role="alert">
                <p class="text-[#00635B] text-sm font-bold">Vos informations ont été mises à jour avec succès.</p>
            </div>
        @endif

        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('patch')

            <div class="space-y-2">
                <label for="name" class="block text-sm font-bold text-slate-700">Nom complet</label>
                <input id="name" name="name" type="text" class="w-full border-2 border-slate-300 p-3 focus:border-[#000091] focus:ring-0 bg-slate-50" value="{{ old('name', $user->name) }}" required autocomplete="name">
                @error('name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="email" class="block text-sm font-bold text-slate-700">Adresse électronique</label>
                <input id="email" name="email" type="email" class="w-full border-2 border-slate-300 p-3 focus:border-[#000091] focus:ring-0 bg-slate-50" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="px-8 py-3 bg-[#000091] text-white font-bold rounded-sm hover:bg-blue-800 transition-all shadow-md uppercase tracking-wider text-sm">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    <!-- Changement de Mot de Passe -->
    <div class="bg-white p-8 border border-slate-200 shadow-sm">
        <div class="flex items-center gap-4 mb-8 border-l-4 border-[#000091] pl-6">
            <h2 class="text-2xl font-black text-slate-900">Sécurité du compte</h2>
        </div>

        @if (session('status') === 'password-updated')
            <div class="bg-teal-50 border-l-4 border-[#00635B] p-4 mb-8" role="alert">
                <p class="text-[#00635B] text-sm font-bold">Votre mot de passe a été modifié.</p>
            </div>
        @endif

        <form method="post" action="{{ route('profile.password') }}" class="space-y-6">
            @csrf
            @method('put')

            <div class="space-y-2">
                <label for="current_password" class="block text-sm font-bold text-slate-700">Mot de passe actuel</label>
                <input id="current_password" name="current_password" type="password" class="w-full border-2 border-slate-300 p-3 focus:border-[#000091] focus:ring-0 bg-slate-50" autocomplete="current-password">
                @error('current_password', 'updatePassword') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="password" class="block text-sm font-bold text-slate-700">Nouveau mot de passe</label>
                <input id="password" name="password" type="password" class="w-full border-2 border-slate-300 p-3 focus:border-[#000091] focus:ring-0 bg-slate-50" autocomplete="new-password">
                @error('password', 'updatePassword') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2">
                <label for="password_confirmation" class="block text-sm font-bold text-slate-700">Confirmer le mot de passe</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="w-full border-2 border-slate-300 p-3 focus:border-[#000091] focus:ring-0 bg-slate-50" autocomplete="new-password">
            </div>

            <div class="pt-4">
                <button type="submit" class="px-8 py-3 bg-[#000091] text-white font-bold rounded-sm hover:bg-blue-800 transition-all shadow-md uppercase tracking-wider text-sm">
                    Mettre à jour le mot de passe
                </button>
            </div>
        </form>
    </div>

    <!-- Zone de Danger -->
    <div class="bg-[#FAEFEF] p-8 border border-red-200 shadow-sm">
        <div class="flex items-center gap-4 mb-6 border-l-4 border-[#E1000F] pl-6">
            <h2 class="text-2xl font-black text-[#E1000F]">Suppression du compte</h2>
        </div>

        <p class="text-sm text-slate-700 mb-8 leading-relaxed">
            Une fois votre compte supprimé, toutes vos données de santé, votre journal émotionnel et vos favoris seront définitivement effacés de nos serveurs sécurisés. Cette action est irréversible.
        </p>

        <button onclick="document.getElementById('delete-modal').classList.remove('hidden')" class="px-8 py-3 bg-[#E1000F] text-white font-bold rounded-sm hover:bg-red-800 transition-all shadow-md uppercase tracking-wider text-sm">
            Supprimer mon compte définitivement
        </button>
    </div>
</div>

<!-- Modal de suppression (Simplifié pour l'exemple) -->
<div id="delete-modal" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100] flex items-center justify-center p-4">
    <div class="bg-white max-w-md w-full p-8 shadow-2xl border-t-8 border-[#E1000F]">
        <h3 class="text-xl font-black mb-4">Êtes-vous absolument sûr ?</h3>
        <p class="text-sm text-slate-600 mb-8">Veuillez saisir votre mot de passe pour confirmer la suppression définitive de votre compte.</p>

        <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
            @csrf
            @method('delete')

            <input type="password" name="password" placeholder="Mot de passe" class="w-full border-2 border-slate-300 p-3 focus:border-[#E1000F] focus:ring-0 bg-slate-50" required>

            <div class="flex gap-4">
                <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')" class="flex-1 py-3 bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition-all">Annuler</button>
                <button type="submit" class="flex-1 py-3 bg-[#E1000F] text-white font-bold hover:bg-red-800 transition-all shadow-md">Confirmer</button>
            </div>
        </form>
    </div>
</div>
@endsection
