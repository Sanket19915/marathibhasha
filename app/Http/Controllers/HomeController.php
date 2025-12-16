<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\WordSuggestionMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Get all categories from database with their entry counts
        $dbCategories = Category::withCount('entries')
            ->orderBy('name_mr')
            ->get();

        // Get static categories list (for categories that might not have data yet)
        $staticCategories = [
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

        // Create a map of database categories by normalized name for quick lookup
        // Normalize by removing all types of whitespace (including non-breaking spaces)
        $dbCategoriesMap = $dbCategories->keyBy(function ($category) {
            return trim(preg_replace('/[\s\x{00A0}\x{2000}-\x{200B}\x{202F}\x{205F}\x{3000}]+/u', ' ', $category->name_mr));
        });

        // Combine: show database categories first, then static ones that aren't in DB
        $allCategories = collect($staticCategories)->map(function ($categoryName) use ($dbCategoriesMap) {
            $normalizedName = trim(preg_replace('/[\s\x{00A0}\x{2000}-\x{200B}\x{202F}\x{205F}\x{3000}]+/u', ' ', $categoryName));
            $dbCategory = $dbCategoriesMap->get($normalizedName);
            
            return [
                'name' => $categoryName,
                'slug' => self::generateSlug($categoryName),
                'entry_count' => $dbCategory ? $dbCategory->entries_count : 0,
                'has_data' => $dbCategory !== null,
            ];
        })->sortBy('name')->values();

        return view('pages.home', [
            'categories' => $allCategories,
            'dbCategories' => $dbCategories,
        ]);
    }

    public function objectives()
    {
        return view('pages.objectives');
    }

    public function appeal()
    {
        return view('pages.appeal');
    }

    public function science()
    {
        return view('pages.science');
    }

    public function marathiMedium()
    {
        return view('pages.marathi-medium');
    }

    public function neetSamplePapers()
    {
        return view('pages.neet-sample-papers');
    }

    public function suggestWord()
    {
        return view('pages.suggest-word');
    }

    public function submitWord(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:255',
            'meaning' => 'required|string',
            'source_reference' => 'required|string',
            'email' => 'nullable|email',
            'name' => 'nullable|string|max:255',
        ]);

        try {
            // Send email to info@marathibhasha.org
            Mail::to('info@marathibhasha.org')->send(
                new WordSuggestionMail(
                    $request->word,
                    $request->meaning,
                    $request->source_reference,
                    $request->name,
                    $request->email
                )
            );
            
            return redirect()->route('suggest-word')
                ->with('success', 'आपली शब्द सुचना आम्हाला मिळाली आहे. धन्यवाद!');
        } catch (\Exception $e) {
            // Log the error if needed
            \Log::error('Word suggestion email failed: ' . $e->getMessage());
            
            return redirect()->route('suggest-word')
                ->with('success', 'आपली शब्द सुचना आम्हाला मिळाली आहे. धन्यवाद!');
        }
    }

    public function category($slug)
    {
        // First, try to get category from database
        $dbCategory = Category::where('slug', $slug)->with('entries')->first();
        
        if ($dbCategory) {
            // Use database category
            $words = $dbCategory->entries->map(function ($entry) {
                return [
                    'english' => $entry->word_en,
                    'marathi' => $entry->meaning_mr,
                ];
            })->toArray();
            
            return view('pages.category', [
                'category' => $dbCategory->name_mr,
                'words' => $words,
                'totalWords' => count($words),
                'slug' => $slug
            ]);
        }

        // Fallback to static category data
        $categoryName = $this->getCategoryNameFromSlug($slug);
        
        if (!$categoryName) {
            abort(404, 'Category not found');
        }

        // Get static category data
        $categoryData = $this->getCategoryData($slug);
        
        // If no data found, return empty array
        $words = $categoryData['words'] ?? [];
        $totalWords = count($words);

        return view('pages.category', [
            'category' => $categoryName,
            'words' => $words,
            'totalWords' => $totalWords,
            'slug' => $slug
        ]);
    }

    private function getCategoryNameFromSlug($slug)
    {
        $categoryMap = [
            'arthashastra' => 'अर्थशास्त्र',
            'aushadhashastra' => 'औषधशास्त्र',
            'karyadarshika' => 'कार्यदर्शिका',
            'karyalayin-shabdavali' => 'कार्यालयीन शब्दांवली',
            'krishishastra' => 'कृषिशास्त्र',
            'ganitashastra' => 'गणितशास्त्र',
            'granthalayashastra' => 'ग्रंथालयशास्त्र',
            'jivashastra' => 'जीवशास्त्र',
            'tatvadnyan-va-tarkashastra' => 'तत्वज्ञान व तर्कशास्त्र',
            'dhatushastra' => 'धातूशास्त्र',
            'nyayavyavahar-kosh' => 'न्यायव्यवहार कोश',
            'padnam-kosh' => 'पदनाम कोश',
            'prashasan-vakyaprayog' => 'प्रशासन वाक्यप्रयोग',
            'banking-shabdavali-hindi' => 'बैंकिंग शब्दांवली (हिंदी)',
            'bhushastra' => 'भूशास्त्र',
            'bhugol' => 'भूगोल',
            'bhautikshastra' => 'भौतिकशास्त्र',
            'marathi-vishvakosh-paribhasha-kosh' => 'मराठी विश्ववकोश परीभाषा कोश',
            'manasashastra' => 'मानसशास्त्र',
            'yantra-abhiyantriki' => 'यंत्र अभियांत्रिकी',
            'rasayanshastra' => 'रसायनशास्त्र',
            'rajyashastra' => 'राज्यशास्त्र',
            'lokaprashasan' => 'लोकप्रशासन',
            'vanijyashastra' => 'वाणिज्यशास्त्र',
            'vikritishastra' => 'विकृतीशास्त्र',
            'vittiya-shabdavali' => 'वित्तीय शब्दांवली',
            'vidyut-abhiyantriki' => 'विद्युत अभियांत्रिकी',
            'vaidnyanik-paribhashik-sanjna' => 'वैज्ञानिक पारिभाषिक संज्ञा',
            'vyavasay-vyavasthapan' => 'व्यवसाय व्यवस्थापन',
            'sharir-paribhasha' => 'शरीर परिभाषा',
            'shasan-vyavahar' => 'शासन व्यवहार',
            'shikshanashastra' => 'शिक्षणशास्त्र',
            'sankhyashastra' => 'संख्याशास्त्र',
            'sahitya-samiksha' => 'साहित्य समीक्षा',
            'sthapathya-abhiyantriki' => 'स्थापत्य अभियांत्रिकी',
        ];

        return $categoryMap[$slug] ?? null;
    }

    private function getCategoryData($slug)
    {
        // Map category slugs to their data
        $categories = [
            'banking-shabdavali-hindi' => [
                'category' => 'बैंकिंग शब्दांवली (हिंदी)',
                'words' => [
                    ['english' => 'abandonment of claim', 'marathi' => 'दावे का परित्याग'],
                    ['english' => 'abatement of duty', 'marathi' => 'शुल्क में कमी'],
                    ['english' => 'abatement of purchase money', 'marathi' => 'क्रय धन में कमी'],
                    ['english' => 'abbreviations', 'marathi' => 'संक्षेपाक्षरसंकेताक्षर'],
                    ['english' => 'abide by the rules', 'marathi' => 'नियमोंका पालन करना'],
                    ['english' => 'ability to invest', 'marathi' => 'निवेश क्षमता'],
                    ['english' => 'ability to pay', 'marathi' => 'भुगतान क्षमता'],
                    ['english' => 'abnormal demand', 'marathi' => 'असामान्य मांग'],
                    ['english' => 'abnormal method of finance', 'marathi' => 'वित्तपोषण की असामान्य प्रणालीपद्धति'],
                    ['english' => 'aboard', 'marathi' => 'जहाजपोत पर, जहाज में'],
                    ['english' => 'abandoned cargo', 'marathi' => 'परित्यक्त माल'],
                    ['english' => 'above average', 'marathi' => 'औसत से अधिकऊपर'],
                    ['english' => 'land holders', 'marathi' => 'भू-धारक'],
                    ['english' => 'land holdings', 'marathi' => 'जोत'],
                    ['english' => 'land lease legislation', 'marathi' => 'भूमि-पट्टा विधानकानून'],
                    ['english' => 'land locked state', 'marathi' => 'भूमि से घिरा प्रदेशराज्य, बंदरगाह विहीन प्रदेशराज्य'],
                    ['english' => 'Land Mortgate Bank', 'marathi' => 'भूमि बंधक बैंक'],
                    ['english' => 'land record', 'marathi' => 'भू-अभिलेख'],
                    ['english' => 'land reform', 'marathi' => 'भूमि सुधार'],
                    ['english' => 'land register', 'marathi' => 'भूमि रजिस्टर'],
                ]
            ],
            // Other categories will have empty words array by default
        ];

        return $categories[$slug] ?? ['category' => '', 'words' => []];
    }

    public function search(Request $request)
    {
        $query = $request->get('search', '');
        $query = trim($query);

        if (empty($query)) {
            return redirect()->route('home');
        }

        $results = [];
        $searchTerm = strtolower($query);

        // Search in database categories first
        $dbCategories = Category::with('entries')->get();
        foreach ($dbCategories as $category) {
            $matchedWords = $category->entries()
                ->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(word_en) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(meaning_mr) LIKE ?', ['%' . $searchTerm . '%']);
                })
                ->get()
                ->map(function ($entry) {
                    return [
                        'english' => $entry->word_en,
                        'marathi' => $entry->meaning_mr,
                    ];
                })
                ->toArray();

            if (!empty($matchedWords)) {
                $results[] = [
                    'category' => $category->name_mr,
                    'slug' => $category->slug,
                    'words' => $matchedWords,
                    'count' => count($matchedWords)
                ];
            }
        }

        // Also search in static categories (for categories not yet in database)
        $allCategories = $this->getAllCategoriesData();
        foreach ($allCategories as $slug => $categoryData) {
            // Skip if we already have this category from database
            $alreadyInResults = collect($results)->contains(function ($result) use ($slug) {
                return $result['slug'] === $slug;
            });
            
            if ($alreadyInResults) {
                continue;
            }

            $categoryName = $this->getCategoryNameFromSlug($slug);
            if (!$categoryName) {
                continue;
            }

            $matchedWords = [];
            foreach ($categoryData['words'] ?? [] as $word) {
                $english = strtolower($word['english'] ?? '');
                $marathi = strtolower($word['marathi'] ?? '');

                if (str_contains($english, $searchTerm) || str_contains($marathi, $searchTerm)) {
                    $matchedWords[] = $word;
                }
            }

            if (!empty($matchedWords)) {
                $results[] = [
                    'category' => $categoryName,
                    'slug' => $slug,
                    'words' => $matchedWords,
                    'count' => count($matchedWords)
                ];
            }
        }

        return view('pages.search', [
            'query' => $query,
            'results' => $results,
            'totalResults' => array_sum(array_column($results, 'count'))
        ]);
    }

    private function getAllCategoriesData()
    {
        $allSlugs = [
            'arthashastra',
            'aushadhashastra',
            'karyadarshika',
            'karyalayin-shabdavali',
            'krishishastra',
            'ganitashastra',
            'granthalayashastra',
            'jivashastra',
            'tatvadnyan-va-tarkashastra',
            'dhatushastra',
            'nyayavyavahar-kosh',
            'padnam-kosh',
            'prashasan-vakyaprayog',
            'banking-shabdavali-hindi',
            'bhushastra',
            'bhugol',
            'bhautikshastra',
            'marathi-vishvakosh-paribhasha-kosh',
            'manasashastra',
            'yantra-abhiyantriki',
            'rasayanshastra',
            'rajyashastra',
            'lokaprashasan',
            'vanijyashastra',
            'vikritishastra',
            'vittiya-shabdavali',
            'vidyut-abhiyantriki',
            'vaidnyanik-paribhashik-sanjna',
            'vyavasay-vyavasthapan',
            'sharir-paribhasha',
            'shasan-vyavahar',
            'shikshanashastra',
            'sankhyashastra',
            'sahitya-samiksha',
            'sthapathya-abhiyantriki',
        ];

        $allData = [];
        foreach ($allSlugs as $slug) {
            $allData[$slug] = $this->getCategoryData($slug);
        }

        return $allData;
    }

    public static function generateSlug($categoryName)
    {
        $slugMap = [
            'अर्थशास्त्र' => 'arthashastra',
            'औषधशास्त्र' => 'aushadhashastra',
            'कार्यदर्शिका' => 'karyadarshika',
            'कार्यालयीन शब्दांवली' => 'karyalayin-shabdavali',
            'कृषिशास्त्र' => 'krishishastra',
            'गणितशास्त्र' => 'ganitashastra',
            'ग्रंथालयशास्त्र' => 'granthalayashastra',
            'जीवशास्त्र' => 'jivashastra',
            'तत्वज्ञान व तर्कशास्त्र' => 'tatvadnyan-va-tarkashastra',
            'धातूशास्त्र' => 'dhatushastra',
            'न्यायव्यवहार कोश' => 'nyayavyavahar-kosh',
            'पदनाम कोश' => 'padnam-kosh',
            'प्रशासन वाक्यप्रयोग' => 'prashasan-vakyaprayog',
            'बैंकिंग शब्दांवली (हिंदी)' => 'banking-shabdavali-hindi',
            'भूशास्त्र' => 'bhushastra',
            'भूगोल' => 'bhugol',
            'भौतिकशास्त्र' => 'bhautikshastra',
            'मराठी विश्ववकोश परीभाषा कोश' => 'marathi-vishvakosh-paribhasha-kosh',
            'मानसशास्त्र' => 'manasashastra',
            'यंत्र अभियांत्रिकी' => 'yantra-abhiyantriki',
            'रसायनशास्त्र' => 'rasayanshastra',
            'राज्यशास्त्र' => 'rajyashastra',
            'लोकप्रशासन' => 'lokaprashasan',
            'वाणिज्यशास्त्र' => 'vanijyashastra',
            'विकृतीशास्त्र' => 'vikritishastra',
            'वित्तीय शब्दांवली' => 'vittiya-shabdavali',
            'विद्युत अभियांत्रिकी' => 'vidyut-abhiyantriki',
            'वैज्ञानिक पारिभाषिक संज्ञा' => 'vaidnyanik-paribhashik-sanjna',
            'व्यवसाय व्यवस्थापन' => 'vyavasay-vyavasthapan',
            'शरीर परिभाषा' => 'sharir-paribhasha',
            'शासन व्यवहार' => 'shasan-vyavahar',
            'शिक्षणशास्त्र' => 'shikshanashastra',
            'संख्याशास्त्र' => 'sankhyashastra',
            'साहित्य समीक्षा' => 'sahitya-samiksha',
            'स्थापत्य अभियांत्रिकी' => 'sthapathya-abhiyantriki',
        ];

        return $slugMap[$categoryName] ?? null;
    }
}

