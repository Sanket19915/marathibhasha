@extends('layouts.app')

@section('title', __('common.home'))

@section('content')
<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Title -->
        <div class="text-start mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-red-600 mb-2">
                {{ __('common.home') }} {{ __('common.glossary_of_terms') }}
            </h1>
        </div>

        <!-- Introductory Text -->
        <div class="mb-8">
            <p class="text-gray-700 text-base md:text-lg leading-relaxed">
                {{ __('common.published_glossaries_list') }}
            </p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @foreach($categories as $category)
                <a href="{{ route('category', $category['slug']) }}" 
                   class="block p-4 border border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-all duration-200 group">
                    <div class="flex items-center justify-between">
                        <span class="text-red-600 font-medium text-base md:text-lg group-hover:text-orange-600">
                            {{ $category['name'] }}
                        </span>
                        @if($category['has_data'] && $category['entry_count'] > 0)
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                {{ number_format($category['entry_count']) }}
                            </span>
                        @elseif(!$category['has_data'])
                            <span class="text-xs text-gray-400 italic">
                                {{ __('common.coming_soon') ?? 'Coming soon' }}
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Google Sign-In (if not logged in) -->
        @if(!Auth::check())
        <div class="mt-12 flex justify-center">
            <x-google-signin />
        </div>
        @endif
    </div>
</div>
@endsection
