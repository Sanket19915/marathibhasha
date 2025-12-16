@extends('layouts.app')

@section('title', __('common.suggest_word'))

@section('content')
<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-orange-600 mb-4">
                {{ __('common.suggest_word') }}
            </h1>
        </div>

        <!-- Content Section -->
        <div class="max-w-7xl mx-auto">
                <!-- Introduction Text -->
                <div class="mb-6">
                    <p class="text-gray-700 text-base md:text-lg leading-relaxed mb-4">
                        {{ __('common.suggest_new_word') }}
                    </p>
                    <p class="text-gray-700 text-base md:text-lg leading-relaxed">
                        {{ __('common.contact_email') }} 
                        <a href="mailto:info@marathibhasha.org" class="text-orange-600 hover:text-orange-700 underline">info@marathibhasha.org</a>
                    </p>
                    <p class="text-gray-700 text-base md:text-lg leading-relaxed mt-4">
                        {{ __('common.please_mention') }}
                    </p>
                    <ul class="list-disc list-inside space-y-2 text-gray-700 text-base md:text-lg ml-4 mt-2">
                        <li>{{ __('common.word') }}</li>
                        <li>{{ __('common.meaning') }}</li>
                        <li>{{ __('common.source_reference') }}</li>
                    </ul>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('suggest-word.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            आपले नाव (वैकल्पिक)
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none text-gray-900"
                            placeholder="आपले नाव"
                        >
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            आपला ई-मेल (वैकल्पिक)
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none text-gray-900"
                            placeholder="example@email.com"
                        >
                    </div>

                    <!-- Word Field -->
                    <div>
                        <label for="word" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('common.word') }} <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="word" 
                            id="word"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none text-gray-900"
                            placeholder="शब्द"
                            dir="ltr"
                        >
                        @error('word')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meaning Field -->
                    <div>
                        <label for="meaning" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('common.meaning') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="meaning" 
                            id="meaning"
                            rows="4"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none text-gray-900"
                            placeholder="शब्दार्थ"
                        ></textarea>
                        @error('meaning')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Source and Reference Field -->
                    <div>
                        <label for="source_reference" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('common.source_reference') }} <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="source_reference" 
                            id="source_reference"
                            rows="4"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none text-gray-900"
                            placeholder="शब्दाचे स्रोत आणि संदर्भ"
                        ></textarea>
                        @error('source_reference')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-colors font-medium"
                        >
                            {{ __('common.submit') }}
                        </button>
                    </div>
                </form>
            
        </div>
    </div>
</div>
@endsection




