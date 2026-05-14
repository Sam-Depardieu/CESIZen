@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('informations.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour aux articles
        </a>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            @if($information->image_url)
                <img src="{{ asset('storage/' . $information->image_url) }}" alt="{{ $information->title }}" class="w-full h-96 object-cover">
            @endif

            <div class="p-8 md:p-12">
                <span class="inline-block px-4 py-1 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-full mb-6">
                    {{ $information->category }}
                </span>

                <h1 class="text-4xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ $information->title }}
                </h1>

                <div class="flex items-center text-gray-500 mb-8 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Publié le {{ $information->created_at->format('d/m/Y') }}
                </div>

                <div class="prose prose-indigo max-w-none text-gray-700 leading-relaxed text-lg">
                    {!! $information->content !!}
                </div>

                <div class="mt-12 p-6 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600 italic">
                                Information de santé publique - CESIZen. Ces contenus sont rédigés à but informatif et ne sauraient remplacer une consultation professionnelle.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
