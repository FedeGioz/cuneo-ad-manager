@extends('layouts.home')

@section('title', 'CuneoPubblicità')

@section('hero')
    <div class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1>Benvenuti su CuneoPubblicità</h1>
                    <p class="lead">La tua piattaforma di annunci locali per le aziende e i servizi della città di Cuneo</p>
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

        <div class="col-md-4">
            <div class="card ad-card">
                <img src="" class="card-img-top" alt="Annuncio in evidenza">
                <div class="card-body">
                    <span class="badge bg-primary mb-2">Ristorazione</span>
                    <h5 class="card-title">Ristorante Da Luigi</h5>
                    <p class="card-text">Specialità piemontesi nel cuore di Cuneo. Prenota ora e ricevi il 10% di sconto!</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">Maggiori informazioni</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card ad-card">
                <img src="" class="card-img-top" alt="Annuncio in evidenza">
                <div class="card-body">
                    <span class="badge bg-success mb-2">Shopping</span>
                    <h5 class="card-title">Boutique Eleganza</h5>
                    <p class="card-text">Nuova collezione autunno-inverno disponibile. Sconti fino al 30% sui capi selezionati!</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">Maggiori informazioni</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card ad-card">
                <img src="" class="card-img-top" alt="Annuncio in evidenza">
                <div class="card-body">
                    <span class="badge bg-info mb-2">Servizi</span>
                    <h5 class="card-title">Studio Tecnico Rossi</h5>
                    <p class="card-text">Consulenza tecnica professionale per privati e aziende. Prima consultazione gratuita!</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">Maggiori informazioni</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <h3>Categorie Popolari</h3>
            <hr>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <a href="#" class="text-decoration-none">
                <div class="card text-center py-3">
                    <div class="card-body">
                        <i class="fa fa-utensils mb-3 fs-3"></i>
                        <h5 class="card-title">Ristorazione</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <a href="#" class="text-decoration-none">
                <div class="card text-center py-3">
                    <div class="card-body">
                        <i class="fa fa-shopping-bag mb-3 fs-3"></i>
                        <h5 class="card-title">Shopping</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <a href="#" class="text-decoration-none">
                <div class="card text-center py-3">
                    <div class="card-body">
                        <i class="fa fa-briefcase mb-3 fs-3"></i>
                        <h5 class="card-title">Servizi</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <a href="#" class="text-decoration-none">
                <div class="card text-center py-3">
                    <div class="card-body">
                        <i class="fa fa-home mb-3 fs-3"></i>
                        <h5 class="card-title">Immobiliare</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endpush
