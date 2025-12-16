@extends('layouts.admin')

@section('page-title', 'Settings')

@php
    use App\Models\Setting;
@endphp

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900">Application Settings</h2>
        <p class="mt-1 text-sm text-gray-600">Manage Google Sign-In and Analytics configuration</p>
    </div>

    @if(session('success'))
        <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6 space-y-8">
        @csrf

        <!-- Google Sign-In Settings -->
        <div class="border-b border-gray-200 pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Google Sign-In Configuration</h3>
            <p class="text-sm text-gray-600 mb-4">Configure Google OAuth for user authentication</p>

            <div class="space-y-4">
                <div>
                    <label for="google_client_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Google Client ID
                    </label>
                    <input 
                        type="text" 
                        id="google_client_id" 
                        name="google_client_id" 
                        value="{{ Setting::get('google_client_id', '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                        placeholder="e.g., 562488646908-ksfm89ce435o22c8l7e9fu6mt9p49d3u.apps.googleusercontent.com"
                    >
                    <p class="mt-1 text-xs text-gray-500">
                        Get this from <a href="https://console.cloud.google.com/apis/credentials" target="_blank" class="text-orange-600 hover:underline">Google Cloud Console</a>
                    </p>
                </div>

                <div>
                    <label for="google_client_secret" class="block text-sm font-medium text-gray-700 mb-1">
                        Google Client Secret
                    </label>
                    <input 
                        type="password" 
                        id="google_client_secret" 
                        name="google_client_secret" 
                        value="{{ Setting::get('google_client_secret', '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                        placeholder="Enter your Google Client Secret"
                    >
                    <p class="mt-1 text-xs text-gray-500">
                        Keep this secret secure. Never share it publicly.
                    </p>
                </div>

                <div>
                    <label for="google_redirect_uri" class="block text-sm font-medium text-gray-700 mb-1">
                        Google Redirect URI
                    </label>
                    <input 
                        type="url" 
                        id="google_redirect_uri" 
                        name="google_redirect_uri" 
                        value="{{ Setting::get('google_redirect_uri', '') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                        placeholder="e.g., https://marathibhasha.org/auth/google/callback"
                    >
                    <p class="mt-1 text-xs text-gray-500">
                        Must match the redirect URI configured in Google Cloud Console
                    </p>
                </div>
            </div>
        </div>

        <!-- Google Analytics Settings -->
        <div class="pb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Google Analytics Configuration</h3>
            <p class="text-sm text-gray-600 mb-4">Configure Google Analytics tracking for your website</p>

            <div>
                <label for="google_analytics_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Google Analytics Measurement ID
                </label>
                <input 
                    type="text" 
                    id="google_analytics_id" 
                    name="google_analytics_id" 
                    value="{{ Setting::get('google_analytics_id', '') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                    placeholder="e.g., G-XXXXXXXXXX"
                >
                <p class="mt-1 text-xs text-gray-500">
                    Get this from <a href="https://analytics.google.com/" target="_blank" class="text-orange-600 hover:underline">Google Analytics</a>. Format: G-XXXXXXXXXX
                </p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button 
                type="submit" 
                class="px-6 py-2 bg-orange-600 text-white font-medium rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors"
            >
                Save Settings
            </button>
        </div>
    </form>
</div>
@endsection

