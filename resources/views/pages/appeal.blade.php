@extends('layouts.app')

@section('title', __('common.challenge'))

@section('content')
<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Title -->
        <div class="text-start mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-orange-600 mb-4">
                {{ __('common.challenge') }}
            </h1>
        </div>

        <!-- Content Section -->
        <div class="max-w-7xl mx-auto">
            <div class="prose prose-lg max-w-none">
                <div class="text-gray-700 text-base md:text-lg leading-relaxed space-y-4">
                    <p class="font-semibold text-lg">
                        मराठीत शब्दकोश, परिभाषा कोश निर्माण करणाऱ्या किंवा संकलन करणाऱ्या व्यक्ती अथवा संस्थांना आवाहन
                    </p>

                    <p>
                        जर आपण मराठी भाषेत शब्दकोश, परिभाषा कोश, ज्ञानकोश, पारिभाषिक शब्दावली, स्पष्टीकरणकोश अथवा लघु संदर्भकोश (vade-mecum) अथवा अतुलसंग्रह किंवा सर्वसमावेशक सारांश असलेला सारग्रंथ (compendium) प्रकाशित केले असेल तर त्या बद्दल माहिती आम्हाला द्या.
                    </p>

                    <p>
                        या संकेतस्थळावर आम्ही या विषयावरील ग्रंथांची सूची उपलब्ध करणार आहोत. या सूचीत ग्रंथ समाविष्ट करण्या करता आम्हाला माहिती खालीलप्रमाणे पाठवा:
                    </p>

                    <ol class="list-decimal list-inside space-y-3 ml-4">
                        <li>ग्रंथाचे नाव</li>
                        <li>संकलन करणाऱ्या व्यक्तींचे अथवा संस्थेचे नाव</li>
                        <li>प्रकाशकाचे नाव</li>
                        <li>प्रकाशन वर्ष</li>
                        <li>वर्तमान किंवा सद्यकालीन उपलब्धता किंवा विक्रेत्यांची माहिती</li>
                        <li>माहिती पुरवणाऱ्यांचे नाव आणि संपर्क तपशील</li>
                        <li>पुस्तक जर आज उपलब्ध नसेल तर त्याच्या प्रकाशन हक्कासंबंधी माहिती</li>
                    </ol>

                    <p class="mt-6">
                        माहिती <a href="mailto:info@saninnovations-002-site10.atempurl.com" class="text-orange-600 hover:text-orange-700 underline">info@saninnovations-002-site10.atempurl.com</a> या वी-पत्त्यावर पाठवा.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


