@extends('layouts.advertiser')

@section('title', 'Statistiche')

@section('content')
    <div class="container mt-4">
        <h1>Statistiche delle campagne</h1>
        <br>
        @if($campaigns->count() > 0)
            <div class="mb-4">
                <form action="{{ route('advertisers.statistics') }}" method="GET">
                    <div class="input-group">
                        <select name="campaignId" class="form-select">
                            <option value="">Tutte le campagne</option>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" {{ $selectedCampaignId == $campaign->id ? 'selected' : '' }}>
                                    {{ $campaign->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Filtra</button>
                    </div>
                </form>
            </div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <h5>Tasso di clic (CTR)</h5>
            </div>
            <div class="card-body">
                <canvas id="ctrChart" height="100"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Prestazioni delle campagne</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Nome campagna</th>
                            <th>ID campagna</th>
                            <th>Impression</th>
                            <th>Click</th>
                            <th>CTR</th>
                            <th>Costo</th>
                            <th>eCPM</th>
                            <th>eCPC</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($campaignStats ?? [] as $stat)
                            <tr>
                                <td>{{ $stat->name }}</td>
                                <td>{{ $stat->id }}</td>
                                <td>{{ number_format($stat->impressions) }}</td>
                                <td>{{ number_format($stat->clicks) }}</td>
                                <td>{{ number_format($stat->ctr, 2) }}%</td>
                                <td>${{ number_format($stat->cost, 2) }}</td>
                                <td>${{ number_format($stat->ecpm, 2) }}</td>
                                <td>${{ number_format($stat->ecpc, 2) }}</td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Nessun dato disponibile per le campagne</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const data = {
            labels: @json($data ? $data->map(fn ($item) => $item->date) : []),
            datasets: [{
                label: 'Tasso CTR (%)',
                backgroundColor: 'rgba(255, 99, 132, 0.3)',
                borderColor: 'rgb(255, 99, 132)',
                data: @json($data ? $data->map(fn ($item) => $item->aggregate) : [])
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'CTR (%)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Data'
                        }
                    }
                }
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const myChart = new Chart(
                document.getElementById('ctrChart'),
                config
            );
        });
    </script>
@endpush
