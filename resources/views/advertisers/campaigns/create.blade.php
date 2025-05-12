{{-- resources/views/advertisers/campaigns/create.blade.php --}}
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
            <h1 class="h3 mb-0">{{ isset($campaign) ? 'Modifica campagna' : 'Crea nuova campagna' }}</h1>
            <a href="{{ route('advertisers.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> Indietro
            </a>
        </div>

        @php
            $formAction = isset($campaign)
                ? route('advertisers.campaigns.update', $campaign->id)
                : route('advertisers.campaigns.create');
        @endphp

        <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($campaign))
                @method('PUT')
            @endif

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Dettagli campagna</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Nome campagna</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                   value="{{ old('name', isset($campaign) ? $campaign->name : '') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="ad_category" class="form-label">Categoria</label>
                            <select class="form-select @error('ad_category') is-invalid @enderror" id="ad_category" name="ad_category" required>
                                <option value="" disabled>Seleziona categoria</option>
                                @php
                                    $categories = ['ristoranti', 'tecnologia', 'immobiliare', 'bar', 'aziende', 'supermercati', 'scuole', 'negozi', 'intrattenimento', 'altro'];
                                    $selectedCategory = old('ad_category', isset($campaign) ? $campaign->ad_category : '');
                                @endphp
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ $selectedCategory == $category ? 'selected' : '' }}>
                                        {{ ucfirst($category) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ad_category')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="device" class="form-label">Dispositivo</label>
                            <select class="form-select @error('device') is-invalid @enderror" id="device" name="device" required>
                                @php
                                    $devices = ['all' => 'Tutti', 'desktop' => 'Desktop', 'mobile' => 'Mobile'];
                                    $selectedDevice = old('device', isset($campaign) ? $campaign->device : 'all');
                                @endphp
                                @foreach($devices as $value => $label)
                                    <option value="{{ $value }}" {{ $selectedDevice == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('device')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="daily_budget" class="form-label">Budget giornaliero (€)</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" step="0.01" min="5" class="form-control @error('daily_budget') is-invalid @enderror"
                                       id="daily_budget" name="daily_budget"
                                       value="{{ old('daily_budget', isset($campaign) ? $campaign->daily_budget : 5) }}" required>
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
                                <input type="number" step="0.01" min="0.01" class="form-control @error('max_bid') is-invalid @enderror"
                                       id="max_bid" name="max_bid"
                                       value="{{ old('max_bid', isset($campaign) ? $campaign->max_bid : 0.05) }}" required>
                                @error('max_bid')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Offerta massima per impression</div>
                        </div>

                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Data inizio</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date"
                                   value="{{ old('start_date', isset($campaign) ? (is_string($campaign->start_date) ? $campaign->start_date : $campaign->start_date->format('Y-m-d')) : date('Y-m-d')) }}" required>
                            @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Data fine</label>
                            @php
                                $defaultEndDate = date('Y-m-d', strtotime('+7 days'));
                                $endDateValue = old('end_date', isset($campaign) ? (is_string($campaign->end_date) ? $campaign->end_date : $campaign->end_date->format('Y-m-d')) : $defaultEndDate);
                            @endphp
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date"
                                   value="{{ $endDateValue }}" required>
                            @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="target_url" class="form-label">URL di destinazione</label>
                            <input type="url" class="form-control @error('target_url') is-invalid @enderror" id="target_url" name="target_url"
                                   value="{{ old('target_url', isset($campaign) ? $campaign->target_url : '') }}"
                                   placeholder="https://www.esempio.it" required>
                            @error('target_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Contenuto annuncio</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="ad_title" class="form-label">Titolo</label>
                            <input type="text" class="form-control @error('ad_title') is-invalid @enderror" id="ad_title" name="ad_title"
                                   value="{{ old('ad_title', isset($campaign) ? $campaign->ad_title : '') }}" required maxlength="60">
                            <div class="form-text">Massimo 60 caratteri</div>
                            @error('ad_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="ad_description" class="form-label">Descrizione</label>
                            <textarea class="form-control @error('ad_description') is-invalid @enderror" id="ad_description" name="ad_description" rows="3"
                                      maxlength="150" required>{{ old('ad_description', isset($campaign) ? $campaign->ad_description : '') }}</textarea>
                            <div class="form-text">Massimo 150 caratteri</div>
                            @error('ad_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="image" class="form-label">Immagine</label>
                            @if(isset($campaign) && $campaign->creative)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$campaign->creative->path) }}" alt="Current creative" class="img-thumbnail" style="max-height: 200px">
                                    <p class="form-text">Immagine attuale. Carica una nuova immagine per sostituirla.</p>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image"
                                {{ isset($campaign) ? '' : 'required' }}>
                            <div class="form-text format-dependent display-format">Formato consigliato: 1200x628px, max 2MB</div>
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Targeting</h5>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <label class="form-label">Area geografica</label>
                        <div class="country-input-container">
                            @php
                                $geoTargeting = isset($campaign) && $campaign->geo_targeting != 'all' ? $campaign->geo_targeting : '';
                            @endphp
                            <input type="text" id="countryInput" class="form-control" placeholder="Inserisci città o paese"
                                   autocomplete="off" value="{{ $geoTargeting }}">
                            <input type="hidden" name="geo_targeting" id="geo_targeting"
                                   value="{{ old('geo_targeting', isset($campaign) ? $campaign->geo_targeting : 'all') }}">
                            <small class="form-text">Lascia vuoto per selezionare tutte le aree geografiche</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="os_targeting" class="form-label">Sistema operativo</label>
                            <select class="form-select @error('os_targeting') is-invalid @enderror" id="os_targeting" name="os_targeting">
                                @php
                                    $osSystems = ['all' => 'Tutti', 'android' => 'Android', 'ios' => 'iOS', 'windows' => 'Windows', 'mac' => 'Mac', 'linux' => 'Linux'];
                                    $selectedOs = old('os_targeting', isset($campaign) ? $campaign->os_targeting : 'all');
                                @endphp
                                @foreach($osSystems as $value => $label)
                                    <option value="{{ $value }}" {{ $selectedOs == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('os_targeting')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="browser_targeting" class="form-label">Browser</label>
                            <select class="form-select @error('browser_targeting') is-invalid @enderror" id="browser_targeting" name="browser_targeting">
                                @php
                                    $browsers = ['all' => 'Tutti', 'chrome' => 'Chrome', 'firefox' => 'Firefox', 'safari' => 'Safari', 'opera' => 'Opera', 'edge' => 'Edge'];
                                    $selectedBrowser = old('browser_targeting', isset($campaign) ? $campaign->browser_targeting : 'all');
                                @endphp
                                @foreach($browsers as $value => $label)
                                    <option value="{{ $value }}" {{ $selectedBrowser == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('browser_targeting')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="browser_language_targeting" class="form-label">Lingue browser</label>
                            <select class="form-select @error('browser_language_targeting') is-invalid @enderror" id="browser_language_targeting" name="browser_language_targeting">
                                @php
                                    $languages = ['all' => 'Tutte', 'it' => 'Italiano', 'en' => 'Inglese', 'fr' => 'Francese', 'es' => 'Spagnolo', 'de' => 'Tedesco'];
                                    $selectedLanguage = old('browser_language_targeting', isset($campaign) ? $campaign->browser_language_targeting : 'all');
                                @endphp
                                @foreach($languages as $value => $label)
                                    <option value="{{ $value }}" {{ $selectedLanguage == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('browser_language_targeting')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="keyword_targeting" class="form-label">Parole chiave (separate da virgola)</label>
                            @php
                                $keywords = isset($campaign) && is_array($campaign->keyword_targeting) ?
                                    implode(', ', $campaign->keyword_targeting) :
                                    (isset($campaign) ? $campaign->keyword_targeting : '');
                            @endphp
                            <input type="text" class="form-control @error('keyword_targeting') is-invalid @enderror"
                                   id="keyword_targeting" name="keyword_targeting"
                                   value="{{ old('keyword_targeting', $keywords) }}"
                                   placeholder="sport, musica, tecnologia">
                            @error('keyword_targeting')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="isp_targeting" class="form-label">Provider internet (separati da virgola)</label>
                            <input type="text" class="form-control @error('isp_targeting') is-invalid @enderror"
                                   id="isp_targeting" name="isp_targeting"
                                   value="{{ old('isp_targeting', isset($campaign) ? $campaign->isp_targeting : '') }}"
                                   placeholder="Telecom, Vodafone, Wind">
                            @error('isp_targeting')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            @if(isset($campaign))
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Stato campagna</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="status" class="form-label">Stato</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    @php
                                        $statuses = ['active' => 'Attiva', 'paused' => 'In pausa', 'completed' => 'Completata'];
                                        $selectedStatus = old('status', isset($campaign) ? $campaign->status : 'active');
                                    @endphp
                                    @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" {{ $selectedStatus == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-between">
                <a href="{{ route('advertisers.index') }}" class="btn btn-outline-secondary px-4">Annulla</a>
                <button type="submit" class="btn btn-primary px-5">
                    {{ isset($campaign) ? 'Aggiorna campagna' : 'Crea campagna' }}
                </button>
            </div>

            @if(!isset($campaign))
                <input type="hidden" name="debug_mode" value="1">
            @endif
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
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('PLACES_API_KEY')}}&libraries=places"></script>
    <script>
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

        // Add to your JavaScript
        document.getElementById('image').addEventListener('change', function() {
            if (this.files[0] && this.files[0].size > 2 * 1024 * 1024) { // 2MB
                alert('File too large. Maximum size is 2MB.');
                this.value = '';
            }
        });

        // Replace the current country autocomplete script with Google Places API
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('countryInput');
            const hiddenField = document.getElementById('geo_targeting');

            // Set initial value if available
            input.value = hiddenField.value !== 'all' ? hiddenField.value : '';

            // Initialize Google Places Autocomplete
            const autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['(regions)'], // This includes both cities and countries
                fields: ['name', 'address_components'],
                language: 'it' // You can change this to your preferred language
            });

            // Listen for place selection
            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();

                if (!place.name) {
                    hiddenField.value = 'all';
                    return;
                }

                // Look for the country component to get the country code
                let countryCode = null;
                if (place.address_components) {
                    for (const component of place.address_components) {
                        if (component.types.includes('country')) {
                            countryCode = component.short_name; // This is the two-letter country code
                            break;
                        }
                    }
                }

                // Set the country code if found, otherwise fallback to place name
                hiddenField.value = countryCode || place.name;
            });

            // Add "All" option when clearing the field
            input.addEventListener('input', function() {
                if (!this.value.trim()) {
                    hiddenField.value = 'all';
                }
            });

            // Add an "All" option button below the input
            const allOptionContainer = document.createElement('div');
            allOptionContainer.className = 'mt-2';
            const allOptionBtn = document.createElement('button');
            allOptionBtn.type = 'button';
            allOptionBtn.className = 'btn btn-sm btn-outline-secondary';
            allOptionBtn.textContent = 'Target Everywhere (All)';
            allOptionBtn.onclick = () => {
                input.value = '';
                hiddenField.value = 'all';
            };
            allOptionContainer.appendChild(allOptionBtn);
            input.parentNode.appendChild(allOptionContainer);
        });
    </script>
@endpush
