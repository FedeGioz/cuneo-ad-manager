<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pannello Inserzionisti') - CuneoPubblicità</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @stack('styles')
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .sidebar {
            min-width: 250px;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background-color: #f8f9fa;
        }

        .sidebar .nav-link {
            color: #333;
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
        }

        .sidebar .nav-link.active {
            color: #2470dc;
            background-color: #e9ecef;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background-color: #e9ecef;
        }

        .sidebar .nav-link i {
            margin-right: 8px;
            width: 20px;
            text-align: center;
        }

        #main-content {
            flex: 1;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
        }

        footer {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<x-sidebar />

<main id="main-content" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    {{ $slot }}
</main>

<footer class="py-3 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center">
                <p class="mb-0">&copy; {{ date('Y') }} CuneoPubblicità. Tutti i diritti riservati.</p>
            </div>
            <div class="col-md-6 text-md-end">
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@stack('scripts')
</body>
</html>
