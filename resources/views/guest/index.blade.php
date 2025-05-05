@extends('layouts.home')

@section('title', 'CuneoPubblicità')

@section('hero')
    <div class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1>Benvenuti su CuneoPubblicità</h1>
                    <p class="lead">La piattaforma di annunci locali per le aziende e i servizi della città di Cuneo</p>
                    <div class="mt-4">
                        <a href="#" class="btn btn-light btn-lg me-2">Sfoglia Annunci</a>
                        <a href="#" class="btn btn-outline-light btn-lg">Categorie</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-center mb-4">Scopri le Aziende di Cuneo</h2>
            <p class="text-center">
                Benvenuto nella vetrina digitale delle imprese cuneesi. Qui puoi trovare prodotti, servizi e offerte speciali
                dalle migliori aziende locali, tutto in un unico posto.
            </p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <h3>Annunci in Evidenza</h3>
            <hr>
        </div>
    </div>

    <div class="row annunci-in-evidenza">
        @if(count($featuredAds) > 0)
            @foreach($featuredAds as $ad)
                <div class="card" style="width: 18rem;">
                    <img src="{{ asset('storage/'.$ad['creative_path']) }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ $ad['ad_title'] }}</h5>
                        <p class="card-text">{{ $ad['ad_description'] }}</p>
                        <a href="{{ route('redirect', ['campaignId' => $ad['id']]) }}" class="btn btn-primary">Scopri di più</a>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <p class="text-center">Nessun annuncio in evidenza al momento.</p>
            </div>
        @endif
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <h3>Categorie Popolari</h3>
            <hr>
        </div>

        @foreach($categories as $category)
            <div class="col-md-3 col-6 mb-3">
                <a href="#" class="category-link text-decoration-none" data-category="{{ $category }}">
                    <div class="card text-center py-3">
                        <div class="card-body">
                            <i class="fa fa-{{ $category === 'ristorazione' ? 'utensils' :
                                            ($category === 'shopping' ? 'shopping-bag' :
                                            ($category === 'servizi' ? 'briefcase' : 'home')) }} mb-3 fs-3"></i>
                            <h5 class="card-title">{{ ucfirst($category) }}</h5>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="category-ads-container" style="display: none;">
        <div class="row mb-4">
            <div class="col-12">
                <h3 id="category-title">Annunci per categoria</h3>
                <hr>
            </div>
        </div>
        <div class="row category-ads">
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush
