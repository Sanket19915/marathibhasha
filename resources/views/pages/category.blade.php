@extends('layouts.app')

@section('title', $category)

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

        <!-- Page Title -->
        <div class="text-start mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-red-600 mb-4">
                {{ $category }}
            </h1>
        </div>

        @if($totalWords > 0)
            <!-- Navigation Bar with Alphabet -->
            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-4">
                    <a href="#" class="px-3 py-1 text-red-600 font-semibold hover:bg-red-100 rounded">All</a>
                    @foreach(range('A', 'Z') as $letter)
                        <a href="#" class="px-2 py-1 text-red-600 hover:bg-red-100 rounded transition-colors">
                            {{ $letter }}
                        </a>
                    @endforeach
                </div>

                <!-- Search Bar -->
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        id="word-search"
                        placeholder="Search words..." 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none text-gray-900"
                        dir="ltr"
                    >
                    <button 
                        onclick="searchWords()"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Search
                    </button>
                </div>
            </div>
            <!-- Word Count -->
            <div class="mb-6">
                <p class="text-gray-600 text-sm md:text-base">
                    There are currently <span class="font-semibold text-gray-900">{{ $totalWords }}</span> names in this directory.
                </p>
            </div>

            <!-- Words List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="words-container">
                @foreach($words as $word)
                    <div class="word-item border-b border-gray-200 pb-4" data-english="{{ strtolower($word['english']) }}" data-marathi="{{ strtolower($word['marathi']) }}">
                        <div class="mb-2">
                            <strong class="text-gray-900 text-base md:text-lg">{{ $word['english'] }}</strong>
                        </div>
                        <div class="text-gray-700 text-sm md:text-base">
                            {{ $word['marathi'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- No Results Message (Hidden by default) -->
            <div id="no-results" class="hidden text-center py-12">
                <p class="text-gray-500 text-lg">No words found matching your search.</p>
            </div>
        @else
            <!-- No Words Found Message -->
            <div class="text-center py-12">
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-8">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-600 text-lg font-medium mb-2">या श्रेणीत अजून शब्द उपलब्ध नाहीत</p>
                    <p class="text-gray-500 text-sm">कृपया नंतर पुन्हा तपासा</p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function searchWords() {
        const searchTerm = document.getElementById('word-search').value.toLowerCase().trim();
        const wordItems = document.querySelectorAll('.word-item');
        const noResults = document.getElementById('no-results');
        let visibleCount = 0;

        wordItems.forEach(item => {
            const english = item.getAttribute('data-english');
            const marathi = item.getAttribute('data-marathi');
            
            if (english.includes(searchTerm) || marathi.includes(searchTerm)) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (visibleCount === 0 && searchTerm !== '') {
            noResults.classList.remove('hidden');
            document.getElementById('words-container').style.display = 'none';
        } else {
            noResults.classList.add('hidden');
            document.getElementById('words-container').style.display = 'grid';
        }
    }

    // Search on Enter key
    document.getElementById('word-search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchWords();
        }
    });

    // Filter by alphabet
    document.querySelectorAll('a[href="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const letter = this.textContent.trim();
            
            if (letter === 'All') {
                // Show all words
                document.querySelectorAll('.word-item').forEach(item => {
                    item.style.display = 'block';
                });
                document.getElementById('word-search').value = '';
                document.getElementById('no-results').classList.add('hidden');
                document.getElementById('words-container').style.display = 'grid';
            } else {
                // Filter by first letter
                const wordItems = document.querySelectorAll('.word-item');
                let visibleCount = 0;

                wordItems.forEach(item => {
                    const english = item.getAttribute('data-english');
                    if (english.startsWith(letter.toLowerCase())) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                document.getElementById('word-search').value = '';
                
                if (visibleCount === 0) {
                    document.getElementById('no-results').classList.remove('hidden');
                    document.getElementById('words-container').style.display = 'none';
                } else {
                    document.getElementById('no-results').classList.add('hidden');
                    document.getElementById('words-container').style.display = 'grid';
                }
            }
        });
    });
</script>
@endsection

