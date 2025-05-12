@extends('layouts.home')

@section('title', 'CuneoPubblicità')

@section('content')
    <div class="row mb-4" id="categories">
        <div class="col-12">
            <h3>Categorie</h3>
            <hr>
        </div>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
        @forelse($categories as $category)
            <div class="col">
                <a href="{{ route('category', ['name' => $category]) }}" class="text-decoration-none">
                    <div class="card h-100 text-center py-3 shadow-sm hover-card">
                        <div class="card-body">
                            <h5 class="card-title">{{ ucfirst($category) }}</h5>
                            <span class="text-primary mt-2 d-block">Visualizza annunci →</span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Nessuna categoria disponibile al momento.
                </div>
            </div>
        @endforelse
    </div>
@endsection
