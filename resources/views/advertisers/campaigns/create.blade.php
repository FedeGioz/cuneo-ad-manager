@extends('layouts.advertiser')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Crea nuova campagna</h1>
            <a href="{{ route('advertisers.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> Indietro
            </a>
        </div>

        <form action="{{ route('advertisers.campaigns.create') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Dettagli campagna</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Nome campagna</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="ad_category" class="form-label">Categoria</label>
                            <select class="form-select @error('ad_category') is-invalid @enderror" id="ad_category" name="ad_category" required>
                                <option value="" disabled {{ old('ad_category') ? '' : 'selected' }}>Seleziona categoria</option>
                                <option value="Ristoranti" {{ old('ad_category') == 'Ristoranti' ? 'selected' : '' }}>Ristoranti</option>
                                <option value="Negozi" {{ old('ad_category') == 'Negozi' ? 'selected' : '' }}>Negozi</option>
                                <option value="Servizi" {{ old('ad_category') == 'Servizi' ? 'selected' : '' }}>Servizi</option>
                                <option value="Eventi" {{ old('ad_category') == 'Eventi' ? 'selected' : '' }}>Eventi</option>
                                <option value="Altro" {{ old('ad_category') == 'Altro' ? 'selected' : '' }}>Altro</option>
                            </select>
                            @error('ad_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="device" class="form-label">Dispositivo</label>
                            <select class="form-select @error('device') is-invalid @enderror" id="device" name="device" required>
                                <option value="all" selected>Tutti</option>
                                <option value="desktop">Desktop</option>
                                <option value="mobile">Mobile</option>
                            </select>
                            @error('device')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="daily_budget" class="form-label">Budget giornaliero (€)</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" step="0.01" min="5" class="form-control @error('daily_budget') is-invalid @enderror" id="daily_budget" name="daily_budget" value="{{ old('daily_budget', 10) }}" required>
                                @error('daily_budget')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Minimo €5 al giorno</div>
                        </div>

                        <div class="col-md-6">
                            <label for="max_bid" class="form-label">Offerta massima (€)</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" step="0.01" min="0.01" class="form-control @error('max_bid') is-invalid @enderror" id="max_bid" name="max_bid" value="{{ old('max_bid', 0.20) }}" required>
                                @error('max_bid')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Offerta massima per impression</div>
                        </div>

                        <div class="col-md-6">
                            <label for="frequency_capping" class="form-label">Limite impressioni per utente/giorno</label>
                            <input type="number" min="0" step="1" class="form-control @error('frequency_capping') is-invalid @enderror" id="frequency_capping" name="frequency_capping" value="{{ old('frequency_capping', 3) }}">
                            @error('frequency_capping')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">0 = nessun limite</div>
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Data inizio</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required>
                            @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Data fine</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', date('Y-m-d', strtotime('+7 days'))) }}" required>
                            @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="target_url" class="form-label">URL di destinazione</label>
                            <input type="url" class="form-control @error('target_url') is-invalid @enderror" id="target_url" name="target_url" value="{{ old('target_url') }}" placeholder="https://www.esempio.it" required>
                            @error('target_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ad Content -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Contenuto annuncio</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ad_format" class="form-label">Formato annuncio</label>
                            <select class="form-select @error('ad_format') is-invalid @enderror" id="ad_format" name="ad_format" required>
                                <option value="display" selected>Display</option>
                                <option value="video">Video</option>
                            </select>
                            @error('ad_format')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 format-dependent display-format">
                            <label for="ad_type" class="form-label">Tipo banner</label>
                            <select class="form-select @error('ad_type') is-invalid @enderror" id="ad_type" name="ad_type" required>
                                <option value="static_banner" selected>Banner statico</option>
                                <option value="video_banner">Banner video</option>
                            </select>
                            @error('ad_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="ad_size" class="form-label">Dimensioni</label>
                            <select class="form-select @error('ad_size') is-invalid @enderror" id="ad_size" required>
                                <option value="" selected disabled>Seleziona dimensione</option>
                                <optgroup label="Display" class="format-group display-format">
                                    <option value="950x250">950 x 250 - Billboard</option>
                                    <option value="315x300">315 x 300 - Square</option>
                                    <option value="300x250">300 x 250 - Medium rectangle</option>
                                    <option value="468x60">468 x 60 - Banner</option>
                                    <option value="305x99">305 x 99 - Mobile leaderboard</option>
                                    <option value="320x480">320 x 480 - Mobile interstitial</option>
                                    <option value="300x100">300 x 100 - 3:1 Rectangle</option>
                                </optgroup>
                                <optgroup label="Video" class="format-group video-format">
                                    <option value="16x9">16:9 - Widescreen</option>
                                    <option value="1x1">1:1 - Square</option>
                                </optgroup>
                            </select>
                            <input type="hidden" name="ad_width" id="ad_width" value="{{ old('ad_width') }}">
                            <input type="hidden" name="ad_height" id="ad_height" value="{{ old('ad_height') }}">
                            @error('ad_width')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="ad_title" class="form-label">Titolo</label>
                            <input type="text" class="form-control @error('ad_title') is-invalid @enderror" id="ad_title" name="ad_title" value="{{ old('ad_title') }}" required maxlength="60">
                            <div class="form-text">Massimo 60 caratteri</div>
                            @error('ad_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="ad_description" class="form-label">Descrizione</label>
                            <textarea class="form-control @error('ad_description') is-invalid @enderror" id="ad_description" name="ad_description" rows="3" maxlength="150" required>{{ old('ad_description') }}</textarea>
                            <div class="form-text">Massimo 150 caratteri</div>
                            @error('ad_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="image" class="form-label">Immagine/Video</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            <div class="form-text format-dependent display-format">Formato consigliato: 1200x628px, max 2MB</div>
                            <div class="form-text format-dependent video-format d-none">Formati supportati: MP4, max 50MB, durata max 30 secondi</div>
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Targeting -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Targeting</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Area geografica</label>
                            <div id="map" style="height: 300px" class="mb-2 border rounded"></div>
                            <input type="hidden" name="geo_targeting" id="geo_targeting" value="{{ old('geo_targeting', 'Cuneo') }}">
                            <div class="form-text">Raggio predefinito: 10km dal centro di Cuneo</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="income_targeting" class="form-label">Fascia di reddito</label>
                            <select class="form-select @error('income_targeting') is-invalid @enderror" id="income_targeting" name="income_targeting">
                                <option value="all" selected>Tutti</option>
                                <option value="rich">Alto reddito</option>
                                <option value="poor">Basso reddito</option>
                            </select>
                            @error('income_targeting')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="wifi_cellular_targeting" class="form-label">Connessione</label>
                            <select class="form-select @error('wifi_cellular_targeting') is-invalid @enderror" id="wifi_cellular_targeting" name="wifi_cellular_targeting">
                                <option value="all" selected>Tutti</option>
                                <option value="wifi">Solo Wi-Fi</option>
                                <option value="cellular">Solo dati mobili</option>
                            </select>
                            @error('wifi_cellular_targeting')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="os_targeting" class="form-label">Sistema operativo</label>
                            <select class="form-select @error('os_targeting') is-invalid @enderror" id="os_targeting" name="os_targeting">
                                <option value="all" selected>Tutti</option>
                                <option value="android">Android</option>
                                <option value="ios">iOS</option>
                                <option value="windows">Windows</option>
                                <option value="mac">Mac</option>
                                <option value="linux">Linux</option>
                            </select>
                            @error('os_targeting')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="browser_targeting" class="form-label">Browser</label>
                            <select class="form-select @error('browser_targeting') is-invalid @enderror" id="browser_targeting" name="browser_targeting">
                                <option value="all" selected>Tutti</option>
                                <option value="chrome">Chrome</option>
                                <option value="firefox">Firefox</option>
                                <option value="safari">Safari</option>
                                <option value="opera">Opera</option>
                                <option value="edge">Edge</option>
                            </select>
                            @error('browser_targeting')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="browser_language_targeting" class="form-label">Lingue browser</label>
                            <select class="form-select @error('browser_language_targeting') is-invalid @enderror" id="browser_language_targeting" name="browser_language_targeting">
                                <option value="all" selected>Tutte</option>
                                <option value="it">Italiano</option>
                                <option value="en">Inglese</option>
                                <option value="fr">Francese</option>
                                <option value="es">Spagnolo</option>
                                <option value="de">Tedesco</option>
                            </select>
                            @error('browser_language_targeting')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="keyword_targeting" class="form-label">Parole chiave (separate da virgola)</label>
                            <input type="text" class="form-control @error('keyword_targeting') is-invalid @enderror" id="keyword_targeting" name="keyword_targeting" value="{{ old('keyword_targeting') }}" placeholder="sport, musica, tecnologia">
                            @error('keyword_targeting')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="isp_targeting" class="form-label">Provider internet (separati da virgola)</label>
                            <input type="text" class="form-control @error('isp_targeting') is-invalid @enderror" id="isp_targeting" name="isp_targeting" value="{{ old('isp_targeting') }}" placeholder="Telecom, Vodafone, Wind">
                            @error('isp_targeting')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="ip_targeting" class="form-label">Targeting IP (separati da virgola)</label>
                            <input type="text" class="form-control @error('ip_targeting') is-invalid @enderror" id="ip_targeting" name="ip_targeting" value="{{ old('ip_targeting') }}" placeholder="192.168.1.1, 10.0.0.1/24">
                            @error('ip_targeting')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('advertisers.index') }}" class="btn btn-outline-secondary px-4">Annulla</a>
                <button type="submit" class="btn btn-primary px-5">Crea campagna</button>
            </div>

            <input type="hidden" name="debug_mode" value="1">
        </form>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        .format-dependent.d-none {
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize map centered on Cuneo
        const map = L.map('map').setView([44.3839, 7.5430], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Add a marker for Cuneo center
        const marker = L.marker([44.3839, 7.5430]).addTo(map)
            .bindPopup('Centro di Cuneo').openPopup();

        // Add a circle for targeting radius (10km)
        const circle = L.circle([44.3839, 7.5430], {
            color: 'blue',
            fillColor: '#30f',
            fillOpacity: 0.1,
            radius: 10000
        }).addTo(map);

        // Validate dates to ensure end date is after start date
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = new Date(this.value);
            const endDateInput = document.getElementById('end_date');
            const endDate = new Date(endDateInput.value);

            if (endDate < startDate) {
                // Set end date to start date + 7 days
                const newEndDate = new Date(startDate);
                newEndDate.setDate(newEndDate.getDate() + 7);
                endDateInput.valueAsDate = newEndDate;
            }
        });

        // Handle ad format changes
        document.getElementById('ad_format').addEventListener('change', function() {
            const format = this.value;
            const adSizeSelect = document.getElementById('ad_size');

            // Show/hide format-specific elements
            document.querySelectorAll('.format-dependent').forEach(el => {
                el.classList.add('d-none');
            });

            document.querySelectorAll(`.${format}-format`).forEach(el => {
                el.classList.remove('d-none');
            });

            // Update options in the select
            const optgroups = adSizeSelect.querySelectorAll('optgroup');
            for (const group of optgroups) {
                const options = group.querySelectorAll('option');
                if (group.classList.contains(`${format}-format`)) {
                    // Show options for selected format
                    options.forEach(opt => opt.style.display = '');
                    group.style.display = '';
                } else {
                    // Hide options for non-selected format
                    options.forEach(opt => opt.style.display = 'none');
                    group.style.display = 'none';
                }
            }

            // Reset selection
            adSizeSelect.selectedIndex = 0;
        });

        // In your existing JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // First, check your CSRF token is being sent
            const form = document.querySelector('form');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Initialize the hidden fields on page load
            const adSizeSelect = document.getElementById('ad_size');
            if (adSizeSelect.value) {
                const dimensions = adSizeSelect.value.split('x');
                document.getElementById('ad_width').value = dimensions[0];
                document.getElementById('ad_height').value = dimensions[1];
            } else if (adSizeSelect.options.length > 1) {
                // Select the first non-disabled option if none selected
                adSizeSelect.selectedIndex = 1;
                const dimensions = adSizeSelect.value.split('x');
                document.getElementById('ad_width').value = dimensions[0];
                document.getElementById('ad_height').value = dimensions[1];
            }
        });

        // Keep the existing event listener for changes
        document.getElementById('ad_size').addEventListener('change', function() {
            if (this.value) {
                const dimensions = this.value.split('x');
                document.getElementById('ad_width').value = dimensions[0];
                document.getElementById('ad_height').value = dimensions[1];
            }
        });

        // Add to your JavaScript
        document.getElementById('image').addEventListener('change', function() {
            if (this.files[0] && this.files[0].size > 2 * 1024 * 1024) { // 2MB
                alert('File too large. Maximum size is 2MB.');
                this.value = '';
            }
        });

        // Initialize format display
        document.getElementById('ad_format').dispatchEvent(new Event('change'));
    </script>
@endpush
