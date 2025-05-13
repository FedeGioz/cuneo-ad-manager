<!DOCTYPE html>
<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuneo Pubblicità</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }
        .ad-card {
            border-radius: 8px;
            transition: transform 0.2s;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .ad-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .hero-section {
            background: linear-gradient(135deg, #4a90e2, #825ee4);
            color: white;
            padding: 60px 0;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'CuneoPubblicità') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('advertisers.categories.show') }}">Categories</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    @if(!auth()->check())
                        <a class="nav-link" href="{{ url('/login') }}">Pubblicizza la tua azienda</a>
                    @else
                        <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</nav>

<main>
    <div class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1>Benvenuti su CuneoPubblicità</h1>
                    <p class="lead">La piattaforma di annunci locali per le aziende e i servizi della città di Cuneo</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        @yield('content')
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
