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
            @php
                $categories = [
                    'अर्थशास्त्र',
                    'औषधशास्त्र',
                    'कार्यदर्शिका',
                    'कार्यालयीन शब्दांवली',
                    'कृषिशास्त्र',
                    'गणितशास्त्र',
                    'ग्रंथालयशास्त्र',
                    'जीवशास्त्र',
                    'तत्वज्ञान व तर्कशास्त्र',
                    'धातूशास्त्र',
                    'न्यायव्यवहार कोश',
                    'पदनाम कोश',
                    'प्रशासन वाक्यप्रयोग',
                    'बैंकिंग शब्दांवली (हिंदी)',
                    'भूशास्त्र',
                    'भूगोल',
                    'भौतिकशास्त्र',
                    'मराठी विश्ववकोश परीभाषा कोश',
                    'मानसशास्त्र',
                    'यंत्र अभियांत्रिकी',
                    'रसायनशास्त्र',
                    'राज्यशास्त्र',
                    'लोकप्रशासन',
                    'वाणिज्यशास्त्र',
                    'विकृतीशास्त्र',
                    'वित्तीय शब्दांवली',
                    'विद्युत अभियांत्रिकी',
                    'वैज्ञानिक पारिभाषिक संज्ञा',
                    'व्यवसाय व्यवस्थापन',
                    'शरीर परिभाषा',
                    'शासन व्यवहार',
                    'शिक्षणशास्त्र',
                    'संख्याशास्त्र',
                    'साहित्य समीक्षा',
                    'स्थापत्य अभियांत्रिकी',
                ];
            @endphp

            @foreach($categories as $categoryName)
                @php
                    $slug = \App\Http\Controllers\HomeController::generateSlug($categoryName);
                @endphp
                <a href="{{ route('category', $slug) }}" 
                   class="block p-4 border border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-all duration-200 group">
                    <span class="text-red-600 font-medium text-base md:text-lg group-hover:text-orange-600">
                        {{ $categoryName }}
                    </span>
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
