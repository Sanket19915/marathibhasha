@extends('layouts.admin')

@section('title', __('common.dashboard'))
@section('page-title', __('common.dashboard'))

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">
        {{ __('common.welcome') }}, {{ Auth::user()->name ?? 'Admin' }}
    </h2>
    <p class="text-gray-600">
        प्रशासक डॅशबोर्डमध्ये आपले स्वागत आहे.
    </p>
</div>
@endsection

