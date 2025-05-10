@extends('layouts.advertiser')

@section('title', 'Pannello Inserzionisti - CuneoPubblicità')

@section('content')
    <div class="my-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>Pannello Inserzionisti</h1>
                <p class="lead">Gestisci le tue campagne pubblicitarie e monitora i risultati</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('advertisers.campaigns.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Nuova Campagna
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-light mb-3">
                    <div class="card-body text-center">
                        <h3>{{ $campaigns->where('status', 'active')->count() ?? 0 }}</h3>
                        <p class="mb-0">Campagne Attive</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light mb-3">
                    <div class="card-body text-center">
                        <h3>{{ $totalImpressions ?? 0 }}</h3>
                        <p class="mb-0">Impressioni Totali</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light mb-3">
                    <div class="card-body text-center">
                        <h3>{{ $totalClicks ?? 0 }}</h3>
                        <p class="mb-0">Click Totali</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-light mb-3">
                    <div class="card-body text-center">
                        <h3>{{ $totalImpressions > 0 ? number_format(($totalClicks/$totalImpressions) * 100, 2) . '%' : '0%' }}</h3>
                        <p class="mb-0">CTR</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Le Tue Campagne</h5>
                <div>
                    <select class="form-select form-select-sm" id="campaignFilter">
                        <option value="all">Tutte le campagne</option>
                        <option value="active">Attive</option>
                        <option value="paused">In pausa</option>
                        <option value="completed">Completate</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                @if(isset($campaigns) && $campaigns->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Nome Campagna</th>
                                <th>Categoria</th>
                                <th>Stato</th>
                                <th>Budget</th>
                                <th>Date</th>
                                <th>Impressioni</th>
                                <th>Click</th>
                                <th>Azioni</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($campaigns as $campaign)
                                <tr>
                                    <td>{{ $campaign->name }}</td>
                                    <td>{{ ucfirst($campaign->ad_category) }}</td>
                                    <td>
                                        @if(now()->between($campaign->start_date, $campaign->end_date) and $campaign->status == 'active')
                                            <span class="badge bg-success">Attiva</span>
                                        @elseif(now()->lt($campaign->start_date))
                                            <span class="badge bg-warning text-dark">Programmata</span>
                                        @elseif($campaign->status == 'paused')
                                            <span class="badge bg-warning text-warning text-black">In Pausa</span>
                                        @else
                                            <span class="badge bg-secondary">Completata</span>
                                        @endif
                                    </td>
                                    <td>€{{ number_format($campaign->daily_budget, 2) }}/giorno</td>
                                    <td>{{ \Carbon\Carbon::parse($campaign->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($campaign->end_date)->format('d/m/Y') }}</td>
                                    <td>{{ $campaign->impressions ?? 0 }}</td>
                                    <td>{{ $campaign->clicks ?? 0 }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if($campaign->status == 'paused')
                                                <a href="{{ route('advertisers.campaigns.start', ['id' => $campaign->id]) }}" class="btn btn-outline-primary">
                                                    <i class="fa fa-play"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('advertisers.campaigns.pause', ['id' => $campaign->id]) }}" class="btn btn-outline-primary">
                                                    <i class="fa fa-pause"></i>
                                                </a>
                                            @endif
                                            <a href="#" class="btn btn-outline-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-outline-secondary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteCampaign">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>

                                        <div class="modal fade" id="deleteCampaign" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Conferma eliminazione</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Sei sicuro di voler eliminare la campagna "{{ $campaign->name }}"?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                                                        <a href="{{ route('advertisers.campaigns.delete', ['id' => $campaign->id]) }}" class="btn btn-danger">Elimina</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fa fa-bullhorn fa-3x mb-3 text-muted"></i>
                        <h4>Nessuna campagna attiva</h4>
                        <p>Crea la tua prima campagna pubblicitaria per raggiungere nuovi clienti a Cuneo</p>
                        <a href="{{ route('advertisers.campaigns.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Crea campagna
                        </a>
                    </div>
                @endif
            </div>
        </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        .card-body:has(#performanceChart) {
            height: 400px; /* Adjust this height as needed */
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Performance Chart
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'],
                datasets: [{
                    label: 'Impressioni',
                    data: [120, 190, 300, 250, 280, 320, 410],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2
                }, {
                    label: 'Click',
                    data: [12, 19, 30, 25, 28, 32, 41],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Map setup for Cuneo
        const map = L.map('map').setView([44.3839, 7.5430], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        L.marker([44.3839, 7.5430]).addTo(map)
            .bindPopup('Centro di Cuneo');
    </script>
@endpush
