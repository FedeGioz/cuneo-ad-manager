@extends('layouts.app')

@push('script')
    <script>
        const data = {
            labels: @json($data->map(fn ($data) => $data->date)),
            datasets: [{
                label: 'CTR Rate',
                backgroundColor: 'rgba(255, 99, 132, 0.3)',
                borderColor: 'rgb(255, 99, 132)',
                @if($data) data: @json($data->map(fn ($data) => $data->aggregate)),
                @else
                @@endif
            }]
        };
        const config = {
            type: 'bar',
            data: data
        };
        const myChart = new Chart(
            document.getElementById('ctrChart'),
            config
        );
    </script>
@endpush

@push('styles')
@endpush

<canvas id="ctrChart"></canvas>
@section('content')
    <h1>ABDUL</h1>
@endsection
