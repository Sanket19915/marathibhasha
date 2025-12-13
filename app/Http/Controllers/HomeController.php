<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\WordSuggestionMail;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home');
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
        // Get category name from slug
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
        $allCategories = $this->getAllCategoriesData();

        // Search across all categories
        foreach ($allCategories as $slug => $categoryData) {
            $categoryName = $this->getCategoryNameFromSlug($slug);
            if (!$categoryName) {
                continue;
            }

            $matchedWords = [];
            foreach ($categoryData['words'] ?? [] as $word) {
                $english = strtolower($word['english'] ?? '');
                $marathi = strtolower($word['marathi'] ?? '');
                $searchTerm = strtolower($query);

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

