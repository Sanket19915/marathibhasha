@extends('layouts.app')

@section('title', __('common.search') . ': ' . $query)

@section('content')
<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center text-orange-600 hover:text-orange-700 font-medium transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('common.home') }} कडे परत
            </a>
        </div>

        <!-- Search Results Header -->
        <div class="mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-red-600 mb-4">
                शोध परिणाम
            </h1>
            <p class="text-gray-600 text-base md:text-lg">
                "<strong class="text-gray-900">{{ $query }}</strong>" साठी <strong class="text-gray-900">{{ $totalResults }}</strong> परिणाम सापडले
            </p>
        </div>

        @if($totalResults > 0)
            <!-- Results by Category -->
            <div class="space-y-8">
                @foreach($results as $result)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <!-- Category Header -->
                        <div class="mb-4">
                            <h2 class="text-2xl font-bold text-red-600 mb-2">
                                {{ $result['category'] }}
                            </h2>
                            <p class="text-gray-600 text-sm">
                                {{ $result['count'] }} शब्द सापडले
                            </p>
                        </div>

                        <!-- Words in this Category -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($result['words'] as $word)
                                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:border-orange-500 transition-colors">
                                    <div class="mb-2">
                                        <strong class="text-gray-900 text-base md:text-lg">{{ $word['english'] }}</strong>
                                    </div>
                                    <div class="text-gray-700 text-sm md:text-base">
                                        {{ $word['marathi'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- View All in Category Link -->
                        <div class="mt-4 text-right">
                            <a href="{{ route('category', $result['slug']) }}" 
                               class="text-orange-600 hover:text-orange-700 font-medium text-sm">
                                या श्रेणीतील सर्व शब्द पहा →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-12">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-8">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg font-medium mb-2">
                        "<strong class="text-gray-900">{{ $query }}</strong>" साठी कोणतेही परिणाम सापडले नाहीत
                    </p>
                    <p class="text-gray-500 text-sm mb-4">
                        कृपया वेगळा शब्द शोधून पहा
                    </p>
                    <a href="{{ route('home') }}" 
                       class="inline-block px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors font-medium">
                        {{ __('common.home') }} कडे परत
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection



