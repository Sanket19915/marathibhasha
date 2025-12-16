@extends('layouts.admin')

@section('page-title', 'Word Suggestions')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2">{{ number_format($pendingCount) }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Approved</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($approvedCount) }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ number_format($rejectedCount) }}</p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Word Suggestions List -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Word Suggestions</h3>
            
            <!-- Status Filter -->
            <div class="flex gap-2">
                <a href="{{ route('admin.word-suggestions') }}" 
                   class="px-3 py-1 text-sm rounded-md {{ !isset($statusFilter) || !$statusFilter ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All
                </a>
                <a href="{{ route('admin.word-suggestions', ['status' => 'pending']) }}" 
                   class="px-3 py-1 text-sm rounded-md {{ isset($statusFilter) && $statusFilter === 'pending' ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Pending
                </a>
                <a href="{{ route('admin.word-suggestions', ['status' => 'approved']) }}" 
                   class="px-3 py-1 text-sm rounded-md {{ isset($statusFilter) && $statusFilter === 'approved' ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Approved
                </a>
                <a href="{{ route('admin.word-suggestions', ['status' => 'rejected']) }}" 
                   class="px-3 py-1 text-sm rounded-md {{ isset($statusFilter) && $statusFilter === 'rejected' ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Rejected
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="p-6">
            @if($suggestions->count() > 0)
                <div class="space-y-4">
                    @foreach($suggestions as $suggestion)
                        <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $suggestion->word }}</h4>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($suggestion->status === 'pending') bg-orange-100 text-orange-800
                                            @elseif($suggestion->status === 'approved') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($suggestion->status) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-500 mb-1">
                                        Submitted: {{ $suggestion->created_at->format('M d, Y h:i A') }}
                                    </p>
                                    @if($suggestion->category)
                                        <p class="text-sm text-gray-600 mt-1">
                                            <strong>Category:</strong> {{ $suggestion->category->name_mr }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Duplicate Warning -->
                            @if(isset($suggestion->duplicates) && $suggestion->duplicates->count() > 0)
                                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-start">
                                        <svg class="h-5 w-5 text-yellow-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-yellow-800 mb-1">
                                                ⚠️ This word already exists in the dictionary:
                                            </p>
                                            <ul class="text-sm text-yellow-700 space-y-1">
                                                @foreach($suggestion->duplicates as $duplicate)
                                                    <li>
                                                        • <strong>{{ $duplicate->word_en }}</strong> 
                                                        in category: <strong>{{ $duplicate->category->name_mr }}</strong>
                                                        (Meaning: {{ Str::limit($duplicate->meaning_mr, 50) }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-1">Source/Reference:</p>
                                    <p class="text-gray-900">{{ $suggestion->source_reference }}</p>
                                </div>
                            </div>

                            @if($suggestion->name || $suggestion->email)
                                <div class="mb-4 p-3 bg-gray-50 rounded">
                                    <p class="text-sm text-gray-600">
                                        @if($suggestion->name)
                                            <strong>Name:</strong> {{ $suggestion->name }}
                                        @endif
                                        @if($suggestion->name && $suggestion->email) | @endif
                                        @if($suggestion->email)
                                            <strong>Email:</strong> <a href="mailto:{{ $suggestion->email }}" class="text-orange-600 hover:underline">{{ $suggestion->email }}</a>
                                        @endif
                                    </p>
                                </div>
                            @endif

                            @if($suggestion->admin_notes)
                                <div class="mb-4 p-3 bg-blue-50 rounded">
                                    <p class="text-sm font-medium text-gray-700 mb-1">Admin Notes:</p>
                                    <p class="text-gray-900">{{ $suggestion->admin_notes }}</p>
                                </div>
                            @endif

                            <!-- Update Form -->
                            <form action="{{ route('admin.word-suggestions.update', $suggestion->id) }}" method="POST" class="mt-4 pt-4 border-t border-gray-200">
                                @csrf
                                
                                <!-- Editable Meaning Field (Full Width) -->
                                <div class="mb-4">
                                    <label for="meaning_{{ $suggestion->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                        Meaning <span class="text-gray-500 text-xs">(You can edit this before approving)</span>
                                    </label>
                                    <textarea 
                                        name="meaning" 
                                        id="meaning_{{ $suggestion->id }}"
                                        rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                                        placeholder="Enter or edit the meaning"
                                    >{{ $suggestion->meaning }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Original meaning from user. Edit if needed before approving.
                                    </p>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="status_{{ $suggestion->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                            Status <span class="text-red-500">*</span>
                                        </label>
                                        <select 
                                            name="status" 
                                            id="status_{{ $suggestion->id }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                                            required
                                        >
                                            <option value="pending" {{ $suggestion->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ $suggestion->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ $suggestion->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="category_id_{{ $suggestion->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                            Category
                                            <span class="text-red-500" id="category_required_{{ $suggestion->id }}" style="display: none;">*</span>
                                            <span class="text-xs text-gray-500" id="category_hint_{{ $suggestion->id }}">(Required if approving)</span>
                                        </label>
                                        <select 
                                            name="category_id" 
                                            id="category_id_{{ $suggestion->id }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                                        >
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                    {{ $suggestion->category_id == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name_mr }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1">
                                            Select category to add word when approving
                                        </p>
                                    </div>
                                    <div>
                                        <label for="admin_notes_{{ $suggestion->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                            Admin Notes (Optional)
                                        </label>
                                        <input 
                                            type="text" 
                                            name="admin_notes" 
                                            id="admin_notes_{{ $suggestion->id }}"
                                            value="{{ $suggestion->admin_notes }}"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                                            placeholder="Add notes..."
                                        >
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="text-sm text-gray-600">
                                        @if($suggestion->status === 'approved' && $suggestion->category_id)
                                            <span class="text-green-600">✓ Word added to dictionary</span>
                                        @elseif($suggestion->status === 'approved' && !$suggestion->category_id)
                                            <span class="text-orange-600">⚠ Select category to add word</span>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <button 
                                            type="submit" 
                                            class="px-4 py-2 bg-orange-600 text-white font-medium rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors"
                                        >
                                            Update
                                        </button>
                                        <button 
                                            type="button"
                                            onclick="confirmDelete({{ $suggestion->id }}, '{{ addslashes($suggestion->word) }}')"
                                            class="px-4 py-2 bg-red-600 text-white font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- JavaScript to make category required when approving -->
                                <script>
                                    (function() {
                                        const statusSelect = document.getElementById('status_{{ $suggestion->id }}');
                                        const categorySelect = document.getElementById('category_id_{{ $suggestion->id }}');
                                        const categoryRequired = document.getElementById('category_required_{{ $suggestion->id }}');
                                        const categoryHint = document.getElementById('category_hint_{{ $suggestion->id }}');
                                        
                                        function updateCategoryRequired() {
                                            if (statusSelect.value === 'approved') {
                                                categorySelect.setAttribute('required', 'required');
                                                categoryRequired.style.display = 'inline';
                                                categoryHint.textContent = '(Required when approving)';
                                                categorySelect.classList.add('border-orange-500');
                                            } else {
                                                categorySelect.removeAttribute('required');
                                                categoryRequired.style.display = 'none';
                                                categoryHint.textContent = '(Required if approving)';
                                                categorySelect.classList.remove('border-orange-500');
                                            }
                                        }
                                        
                                        statusSelect.addEventListener('change', updateCategoryRequired);
                                        updateCategoryRequired(); // Run on page load
                                    })();
                                </script>
                            </form>
                            
                            <!-- Delete Form (Hidden) -->
                            <form id="delete-form-{{ $suggestion->id }}" action="{{ route('admin.word-suggestions.delete', $suggestion->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $suggestions->links() }}
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No word suggestions found.</p>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Script -->
<script>
    function confirmDelete(id, word) {
        if (confirm('Are you sure you want to delete the word suggestion for "' + word + '"?\n\nThis action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endsection

