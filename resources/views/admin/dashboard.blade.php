@extends('layouts.admin')

@section('title', __('common.dashboard'))
@section('page-title', __('common.dashboard'))

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            {{ __('common.welcome') }}, {{ Auth::user()->name ?? 'Admin' }}
        </h2>
        <p class="text-gray-600">
            प्रशासक डॅशबोर्डमध्ये आपले स्वागत आहे.
        </p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Words -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Words</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalWords) }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Categories</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalCategories) }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Recent Words (Last 30 Days) -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">New Words (30 days)</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2">{{ number_format($recentWords) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Last 7 days: {{ number_format($last7DaysWords) }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Recent Categories (Last 30 Days) -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">New Categories (30 days)</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($recentCategories) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Last 7 days: {{ number_format($last7DaysCategories) }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recently Added Categories -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recently Added Categories (Last 30 Days)</h3>
            </div>
            <div class="p-6">
                @if($recentCategoriesList->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentCategoriesList as $category)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $category->name_mr }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Added: {{ $category->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-700">{{ number_format($category->entries_count) }}</p>
                                    <p class="text-xs text-gray-500">words</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No new categories added in the last 30 days.</p>
                @endif
            </div>
        </div>

        <!-- Top Categories by Word Count -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Categories by Word Count</h3>
            </div>
            <div class="p-6">
                @if($topCategories->count() > 0)
                    <div class="space-y-3">
                        @foreach($topCategories as $category)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $category->name_mr }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-orange-600">{{ number_format($category->entries_count) }}</p>
                                    <p class="text-xs text-gray-500">words</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No categories found.</p>
                @endif
            </div>
        </div>

        <!-- Categories Without Words -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Categories Without Words</h3>
                <p class="text-xs text-gray-500 mt-1">Total: {{ $emptyCategories->count() }}</p>
            </div>
            <div class="p-6 max-h-96 overflow-y-auto">
                @if($emptyCategories->count() > 0)
                    <div class="space-y-2">
                        @foreach($emptyCategories as $category)
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $category->name_mr }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Created: {{ $category->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="ml-3">
                                    <span class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">
                                        0 words
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">All categories have words!</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('admin.word-suggestions') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow relative">
            @if($pendingSuggestions > 0)
                <span class="absolute top-4 right-4 bg-orange-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                    {{ $pendingSuggestions }} New
                </span>
            @endif
            <div class="flex items-center">
                <div class="shrink-0 bg-blue-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Word Suggestions</h3>
                    <p class="text-sm text-gray-600">View and manage user-submitted word suggestions</p>
                    @if($pendingSuggestions > 0)
                        <p class="text-sm text-orange-600 font-medium mt-1">{{ $pendingSuggestions }} pending review</p>
                    @endif
                </div>
            </div>
        </a>

        <a href="{{ route('admin.settings') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="shrink-0 bg-orange-100 rounded-md p-3">
                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                    <p class="text-sm text-gray-600">Manage Google Sign-In and Analytics keys</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
