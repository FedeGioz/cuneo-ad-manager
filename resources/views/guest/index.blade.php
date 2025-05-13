@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
@endphp
@extends('layouts.home')

@section('title', 'CuneoPubblicità')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-center mb-4">Scopri le Aziende di Cuneo</h2>
            <p class="text-center">
                Benvenuto nella vetrina digitale delle imprese cuneesi. Qui puoi trovare prodotti, servizi e offerte
                speciali
                dalle migliori aziende locali, tutto in un unico posto.
            </p>
        </div>
    </div>

    <div class="row mb-4" id="featured-ads">
        <div class="col-12">
            <h3>Annunci in Evidenza</h3>
            <hr>
        </div>
    </div>

    <div class="row mb-5">
        @if(count($featuredAds) > 0)
            @foreach($featuredAds as $ad)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        @if(isset($ad['creative_path']))
                            <img src="{{ Storage::url($ad['creative_path']) }}" class="card-img-top"
                                 alt="{{ $ad['ad_title'] }}">
                        @else
                            <div class="bg-light text-center p-5">
                                <i class="fa fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $ad['ad_title'] }}</h5>
                            <p class="card-text">{{ Str::limit($ad['ad_description'], 100) }}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="{{ route('redirect', ['campaignId' => $ad['id']]) }}"
                               class="btn btn-primary w-100">Scopri di più</a>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Nessun annuncio in evidenza al momento. Torna a trovarci presto!
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush
