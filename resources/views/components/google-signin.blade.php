@if(!Auth::check())
<div id="google-signin-container" class="mb-4">
    @if(config('services.google.client_id'))
        <!-- Google Sign-In Button -->
        <div class="g_id_signin"
             data-type="standard"
             data-shape="rectangular"
             data-theme="outline"
             data-text="signin_with"
             data-size="large"
             data-logo_alignment="left"
             data-width="auto">
        </div>
    @else
        <!-- Fallback if Google Client ID is not configured -->
        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800">
                Google Sign-In is not configured. Please add GOOGLE_CLIENT_ID to your .env file.
            </p>
        </div>
    @endif
</div>

@if(config('services.google.client_id'))
<script>
    // Wait for Google Sign-In SDK to load
    function initGoogleSignIn() {
        if (typeof google !== 'undefined' && google.accounts && google.accounts.id) {
            try {
                google.accounts.id.initialize({
                    client_id: '{{ config('services.google.client_id') }}',
                    callback: handleGoogleSignIn,
                    auto_select: true,
                    cancel_on_tap_outside: false
                });

                // One Tap prompt (automatic sign-in)
                // Note: One Tap may not work on localhost due to Google restrictions
                google.accounts.id.prompt((notification) => {
                    if (notification.isNotDisplayed()) {
                        console.log('One Tap not displayed:', notification.getNotDisplayedReason());
                    } else if (notification.isSkippedMoment()) {
                        console.log('One Tap skipped:', notification.getSkippedReason());
                    } else if (notification.isDismissedMoment()) {
                        console.log('One Tap dismissed:', notification.getDismissedReason());
                    }
                });
            } catch (error) {
                console.error('Error initializing Google Sign-In:', error);
            }
        } else {
            // Retry if SDK hasn't loaded yet
            setTimeout(initGoogleSignIn, 500);
        }
    }

    // Start initialization when page loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGoogleSignIn);
    } else {
        // DOM already loaded
        if (typeof google !== 'undefined' && google.accounts) {
            initGoogleSignIn();
        } else {
            // Wait for Google SDK script to load
            window.addEventListener('load', function() {
                setTimeout(initGoogleSignIn, 100);
            });
        }
    }

    // Handle Google Sign-In callback
    function handleGoogleSignIn(response) {
        if (!response || !response.credential) {
            console.error('Invalid response from Google Sign-In');
            alert('साइन-इन करताना त्रुटी आली. कृपया पुन्हा प्रयत्न करा.');
            return;
        }

        // Send credential to server
        fetch('{{ route('google.one-tap') }}', {
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
                // Redirect to home or intended page
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
</script>
@endif
@endif

