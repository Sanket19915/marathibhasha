<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DictionaryEntry;
use App\Models\WordSuggestion;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Total statistics
        $totalWords = DictionaryEntry::count();
        $totalCategories = Category::count();
        
        // Recent statistics (last 30 days)
        $recentWords = DictionaryEntry::where('created_at', '>=', now()->subDays(30))->count();
        $recentCategories = Category::where('created_at', '>=', now()->subDays(30))->count();
        
        // Last 7 days statistics
        $last7DaysWords = DictionaryEntry::where('created_at', '>=', now()->subDays(7))->count();
        $last7DaysCategories = Category::where('created_at', '>=', now()->subDays(7))->count();
        
        // Recent categories with entry counts
        $recentCategoriesList = Category::withCount('entries')
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Categories with most entries
        $topCategories = Category::withCount('entries')
            ->orderBy('entries_count', 'desc')
            ->limit(5)
            ->get();
        
        // Categories without words (empty categories)
        $emptyCategories = Category::withCount('entries')
            ->having('entries_count', '=', 0)
            ->orderBy('name_mr')
            ->get();
        
        // Pending word suggestions
        $pendingSuggestions = WordSuggestion::where('status', 'pending')->count();
        
        return view('admin.dashboard', [
            'totalWords' => $totalWords,
            'totalCategories' => $totalCategories,
            'recentWords' => $recentWords,
            'recentCategories' => $recentCategories,
            'last7DaysWords' => $last7DaysWords,
            'last7DaysCategories' => $last7DaysCategories,
            'recentCategoriesList' => $recentCategoriesList,
            'topCategories' => $topCategories,
            'emptyCategories' => $emptyCategories,
            'pendingSuggestions' => $pendingSuggestions,
        ]);
    }

    public function wordSuggestions(Request $request)
    {
        // Filter by status if provided
        $statusFilter = $request->get('status');
        $query = WordSuggestion::with('category');
        
        if ($statusFilter && in_array($statusFilter, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $statusFilter);
        }
        
        $suggestions = $query->orderBy('created_at', 'desc')->paginate(20);
        $pendingCount = WordSuggestion::where('status', 'pending')->count();
        $approvedCount = WordSuggestion::where('status', 'approved')->count();
        $rejectedCount = WordSuggestion::where('status', 'rejected')->count();
        
        // Get all categories for dropdown
        $categories = Category::orderBy('name_mr')->get();
        
        // Check for duplicate words for each suggestion (case-insensitive)
        foreach ($suggestions as $suggestion) {
            $suggestion->duplicates = DictionaryEntry::whereRaw('LOWER(word_en) = ?', [strtolower($suggestion->word)])
                ->with('category')
                ->get();
        }
        
        return view('admin.word-suggestions', [
            'suggestions' => $suggestions,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'categories' => $categories,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function updateWordSuggestion(Request $request, $id)
    {
        $suggestion = WordSuggestion::findOrFail($id);
        
        // Validate based on status
        if ($request->status === 'approved') {
            $request->validate([
                'status' => 'required|in:pending,approved,rejected',
                'admin_notes' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'meaning' => 'nullable|string|max:5000',
            ], [
                'category_id.required' => 'Please select a category when approving a word suggestion.',
            ]);
        } else {
            $request->validate([
                'status' => 'required|in:pending,approved,rejected',
                'admin_notes' => 'nullable|string',
                'category_id' => 'nullable|exists:categories,id',
                'meaning' => 'nullable|string|max:5000',
            ]);
        }

        $message = 'Word suggestion updated successfully!';
        
        // Update meaning if provided
        $meaningToUse = $request->filled('meaning') ? $request->meaning : $suggestion->meaning;
        
        // If approving, add to dictionary
        if ($request->status === 'approved' && $request->category_id) {
            // Check if word already exists in this category (case-insensitive)
            $existingEntry = DictionaryEntry::where('category_id', $request->category_id)
                ->whereRaw('LOWER(word_en) = ?', [strtolower($suggestion->word)])
                ->first();
            
            if (!$existingEntry) {
                // Create dictionary entry with edited meaning (if provided)
                DictionaryEntry::create([
                    'category_id' => $request->category_id,
                    'word_en' => $suggestion->word,
                    'meaning_mr' => $meaningToUse,
                ]);
                $message = 'Word suggestion approved and successfully added to dictionary!';
            } else {
                // Update existing entry with new meaning if provided
                if ($request->filled('meaning') && $existingEntry->meaning_mr !== $meaningToUse) {
                    $existingEntry->update(['meaning_mr' => $meaningToUse]);
                    $message = 'Word suggestion approved. Existing word updated with new meaning!';
                } else {
                    $message = 'Word suggestion approved, but word already exists in this category. Status updated.';
                }
            }
        }
        
        // Update suggestion (including meaning if edited)
        $suggestion->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'category_id' => $request->category_id,
            'meaning' => $meaningToUse, // Save edited meaning
        ]);

        return redirect()->route('admin.word-suggestions')
            ->with('success', $message);
    }

    public function deleteWordSuggestion($id)
    {
        $suggestion = WordSuggestion::findOrFail($id);
        $word = $suggestion->word;
        $suggestion->delete();

        return redirect()->route('admin.word-suggestions')
            ->with('success', "Word suggestion '{$word}' has been deleted successfully.");
    }
}




