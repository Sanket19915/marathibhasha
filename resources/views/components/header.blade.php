<!-- Sticky Header -->
<header class="sticky top-0 z-50 w-full bg-white">
    <!-- Top Black Header Bar -->
    <div class="bg-black text-white py-2 overflow-hidden">
        <div class="marquee-container">
            <div class="marquee-content">
                <span class="text-sm">
                    • {{ __('common.neet_announcement') }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    • {{ __('common.neet_announcement') }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    • {{ __('common.neet_announcement') }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </span>
            </div>
        </div>
    </div>

  

    <!-- Logo and Search Section -->
    <div class="  border-gray-200">
   

        
        <div class=" mx-auto max-w-7xl  ">
            <div class="flex flex-col md:flex-row items-center  gap-8">
                <!-- Logo Section -->
                <div class="shrink-0 flex flex-col items-center">
                    <!-- Logo Image -->
                    <div class="logo-octagon">
                        <img 
                            src="{{ asset('images/logo/logo-1.png') }}" 
                            alt="{{ __('common.marathi_bhasha') }}" 
                            class="w-24 h-24 object-contain"
                        >
                    </div>
                    
                </div>

                <!-- Search Section -->
                 <div class="text-center ">
                 <div class="text-start mb-1">
                       <h1 class="text-lg font-bold text-orange-600">
                       {{ __('common.welcome_message') }}
                       </h1>
                </div>
                <div class="flex gap-2  w-full items-center justify-center">

                
                    <!-- Search Bar -->
                    <form action="{{ route('search') }}" method="GET" class="shrink-0" id="search-form">
                        <input 
                            type="text" 
                            name="search" 
                            id="search-input"
                            placeholder="{{ __('common.search_placeholder') }}" 
                            class="search-input border border-gray-300 rounded-lg text-gray-900 px-2 py-1 h-6 text-sm focus:outline-none"
                            dir="ltr"
                            autocomplete="off"
                            value="{{ request('search') }}"
                        >
                    </form>

                    <!-- Description Text -->
                    <p class="text-black text-center text-sm md:text-base leading-relaxed">
                        {{ __('common.description') }}
                    </p>
                </div>
                </div>
            </div>
        </div>
    </div>

      <!-- Navigation Links Bar -->
      <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4">
            <div class="flex flex-wrap items-center justify-center gap-3 md:gap-4 text-sm md:text-base">
                <a href="{{ route('home') }}" class="text-black hover:text-orange-600 transition-colors {{ request()->routeIs('home') ? 'text-orange-600 font-semibold underline' : '' }}">
                    {{ __('common.home') }}
                </a>
                <span class="text-gray-300 hidden md:inline">|</span>
                <a href="{{ route('objectives') }}" class="text-black hover:text-orange-600 transition-colors {{ request()->routeIs('objectives') ? 'text-orange-600 font-semibold underline' : '' }}">
                    {{ __('common.objectives') }}
                </a>
                <span class="text-gray-300 hidden md:inline">|</span>
                <a href="{{ route('appeal') }}" class="text-black hover:text-orange-600 transition-colors {{ request()->routeIs('appeal') ? 'text-orange-600 font-semibold underline' : '' }}">
                    {{ __('common.challenge') }}
                </a>
                <span class="text-gray-300 hidden md:inline">|</span>
                <a href="#" class="text-black hover:text-orange-600 transition-colors">
                    {{ __('common.feedback') }}
                </a>
                <span class="text-gray-300 hidden md:inline">|</span>
                <a href="{{ route('science') }}" class="px-4 py-2 bg-amber-800 text-white rounded-lg hover:bg-amber-900 font-medium transition-colors {{ request()->routeIs('science') ? 'ring-2 ring-orange-500' : '' }}">
                    {{ __('common.science_11_12') }}
                </a>
                <span class="text-gray-300 hidden md:inline">|</span>
                <a href="{{ route('marathi-medium') }}" class="text-black hover:text-orange-600 transition-colors {{ request()->routeIs('marathi-medium') ? 'text-orange-600 font-semibold underline' : '' }}">
                    {{ __('common.marathi_medium_acceptance') }}
                </a>
                <span class="text-gray-300 hidden md:inline">|</span>
                <a href="#" class="text-black hover:text-orange-600 transition-colors">
                    {{ __('common.contact') }}
                </a>
                <span class="text-gray-300 hidden md:inline">|</span>
                <a href="{{ route('neet-sample-papers') }}" class="text-black hover:text-orange-600 transition-colors {{ request()->routeIs('neet-sample-papers') ? 'text-orange-600 font-semibold underline' : '' }}">
                    {{ __('common.neet_sample_papers') }}
                </a>
                <span class="text-gray-300 hidden md:inline">|</span>
                <a href="{{ route('suggest-word') }}" class="text-black hover:text-orange-600 transition-colors {{ request()->routeIs('suggest-word') ? 'text-orange-600 font-semibold underline' : '' }}">
                    {{ __('common.suggest_word') }}
                </a>
            </div>
        </div>
    </div>
</header>

