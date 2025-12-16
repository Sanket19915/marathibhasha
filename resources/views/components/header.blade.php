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
    <div class="border-gray-200 relative">
        <div class="mx-auto max-w-7xl">
            <!-- Top Right Google Sign-In Button -->
            <div class="absolute top-2 right-4 z-10" style="min-width: 200px;">
                @if(!Auth::check())
                    @php
                        $clientId = \App\Models\Setting::get('google_client_id') ?: config('services.google.client_id');
                    @endphp
                    @if($clientId)
                        <div id="header-google-signin-button" style="display: block !important; visibility: visible !important;">
                            <div class="g_id_signin"
                                 data-type="standard"
                                 data-shape="rectangular"
                                 data-theme="outline"
                                 data-text="signin_with"
                                 data-size="large"
                                 data-logo_alignment="left"
                                 data-width="auto">
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-red-600">Google Sign-In not configured</p>
                    @endif
                @else
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 px-3 py-1 rounded hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                @endif
            </div>
            
            <div class="flex flex-col md:flex-row items-center gap-8">
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
                       <h1 class="text-lg font-bold text-orange-600 mt-2">
                       {{ __('common.welcome_message') }}
                       </h1>
                </div>
                <div class="flex gap-2 mt-2 w-full items-center justify-center">

                
                    <!-- Search Bar -->
                    @auth
                        <form action="{{ route('search') }}" method="GET" class="flex gap-2 items-center" id="search-form">
                            <input 
                                type="text" 
                                name="search" 
                                id="search-input"
                                placeholder="{{ __('common.search_placeholder') ?? 'Search words...' }}" 
                                class="border border-gray-300 rounded-lg text-gray-900 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 min-w-[200px]"
                                dir="ltr"
                                autocomplete="off"
                                value="{{ request('search') }}"
                                required
                            >
                            <button 
                                type="submit" 
                                class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors text-sm font-medium"
                            >
                                Search
                            </button>
                        </form>
                    @else
                        <div class="flex flex-col items-center gap-2">
                            <div class="flex gap-2 items-center">
                                <input 
                                    type="text" 
                                    placeholder="{{ __('common.search_placeholder') ?? 'Search words...' }}" 
                                    class="border border-gray-300 rounded-lg text-gray-900 px-4 py-2 text-sm min-w-[200px] opacity-50 cursor-not-allowed"
                                    dir="ltr"
                                    disabled
                                >
                                <button 
                                    type="button"
                                    onclick="alert('कृपया शोधण्यासाठी वरच्या उजव्या कोपऱ्यात Google साइन-इन करा')"
                                    class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors text-sm font-medium"
                                >
                                    Search
                                </button>
                            </div>
                            <p class="text-xs text-gray-600">कृपया शोधण्यासाठी वरच्या उजव्या कोपऱ्यात Google साइन-इन करा</p>
                        </div>
                    @endauth
                    
                    <!-- Hidden Google Sign-In for One Tap (always present but hidden) -->
                    <div class="hidden">
                        <x-google-signin />
                    </div>

                    <!-- Description Text -->
                    <p class="text-black text-center text-sm md:text-base leading-relaxed mb-4">
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

@if(!Auth::check())
@php
    $clientId = \App\Models\Setting::get('google_client_id') ?: config('services.google.client_id');
@endphp
@if($clientId)
<script>
    // Initialize Google Sign-In button in header
    function initHeaderGoogleButton() {
        if (typeof google !== 'undefined' && google.accounts && google.accounts.id) {
            const headerButton = document.getElementById('header-google-signin-button');
            if (headerButton) {
                const signInDiv = headerButton.querySelector('.g_id_signin');
                if (signInDiv) {
                    try {
                        // Initialize Google Sign-In
                        google.accounts.id.initialize({
                            client_id: '{{ $clientId }}',
                            callback: handleHeaderGoogleSignIn,
                            auto_select: false,
                            cancel_on_tap_outside: false
                        });
                        
                        // Render the button
                        google.accounts.id.renderButton(signInDiv, {
                            type: 'standard',
                            shape: 'rectangular',
                            theme: 'outline',
                            text: 'signin_with',
                            size: 'large',
                            logo_alignment: 'left',
                            width: 'auto'
                        });
                    } catch (error) {
                        console.error('Error initializing header Google Sign-In:', error);
                    }
                }
            }
        } else {
            // Retry if SDK hasn't loaded yet
            setTimeout(initHeaderGoogleButton, 500);
        }
    }

    // Handle Google Sign-In callback
    function handleHeaderGoogleSignIn(response) {
        if (!response || !response.credential) {
            console.error('Invalid response from Google Sign-In');
            alert('साइन-इन करताना त्रुटी आली. कृपया पुन्हा प्रयत्न करा.');
            return;
        }

        // Send credential to server
        fetch('{{ route("google.one-tap") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                credential: response.credential
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect || '/';
            } else {
                console.error('Sign-in failed:', data.message);
                alert('साइन-इन करताना त्रुटी आली. कृपया पुन्हा प्रयत्न करा.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('साइन-इन करताना त्रुटी आली. कृपया पुन्हा प्रयत्न करा.');
        });
    }

    // Start initialization
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeaderGoogleButton);
    } else {
        if (typeof google !== 'undefined' && google.accounts) {
            initHeaderGoogleButton();
        } else {
            window.addEventListener('load', function() {
                setTimeout(initHeaderGoogleButton, 100);
            });
        }
    }
</script>
@endif
@endif

