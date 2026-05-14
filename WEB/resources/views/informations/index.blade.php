@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-indigo-900">Informations & Santé Mentale</h1>
        <p class="text-gray-600">Découvrez nos conseils et articles pour mieux gérer votre quotidien.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($informations as $info)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
            @if($info->image_url)
                <img src="{{ asset('storage/' . $info->image_url) }}" alt="{{ $info->title }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 bg-indigo-50 flex items-center justify-center">
                    <svg class="w-12 h-12 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            @endif

            <div class="p-6">
                <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-semibold rounded-full mb-3">
                    {{ $info->category }}
                </span>
                <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $info->title }}</h2>
                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                    {!! strip_tags($info->content) !!}
                </p>
                <a href="{{ route('informations.show', $info->id) }}" class="text-indigo-600 font-semibold hover:text-indigo-800 flex items-center">
                    Lire la suite
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-12">
        {{ $informations->links() }}
    </div>
</div>
@endsection
