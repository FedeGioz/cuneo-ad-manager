@extends('layouts.advertiser')
@section('title', 'Storico Pagamenti')

@section('content')
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-lg-8">
                <h1>Pagamenti</h1>
                <p class="text-muted">Gestisci il saldo del tuo account e visualizza lo storico delle transazioni</p>
            </div>
            <div class="col-lg-4 text-end">
                <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#addFundingModal">
                    <i class="fas fa-plus-circle me-2"></i> Aggiungi fondi
                </button>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                <i class="fas fa-wallet text-success"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Saldo attuale</h6>
                                <h3 class="mb-0">€{{ number_format(Auth::user()->balance ?? 0, 2) }}</h3>
                            </div>
                        </div>
                        <p class="text-muted mb-0">Disponibile per le campagne</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="fas fa-coins text-primary"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Totale finanziato</h6>
                                <h3 class="mb-0">€{{ number_format($total ?? 0, 2) }}</h3>
                            </div>
                        </div>
                        <p class="text-muted mb-0">Totale fondi aggiunti</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                                <i class="fas fa-receipt text-info"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-0">Transazioni</h6>
                                <h3 class="mb-0">{{ $transactions ?? 0 }}</h3>
                            </div>
                        </div>
                        <p class="text-muted mb-0">Totale operazioni di finanziamento</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Storico transazioni</h5>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Importo</th>
                        <th>Stato</th>
                        <th>Data</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($fundings as $funding)
                        <tr>
                            <td>{{ $funding->id }}</td>
                            <td>
                                <span class="fw-bold {{ $funding->status == 'paid' ? 'text-success' : 'text-muted' }}">+€{{ number_format($funding->amount, 2) }}</span>
                            </td>
                            <td>
                                <span class="fw-bold">{{ ucfirst($funding->status) }}</span>
                            </td>
                            <td>{{ $funding->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <div class="mb-3">
                                        <i class="fas fa-coins fa-3x text-muted"></i>
                                    </div>
                                    <h5>Nessuna transazione trovata</h5>
                                    <p class="text-muted">Aggiungi fondi al tuo account per iniziare</p>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFundingModal">
                                        <i class="fas fa-plus-circle me-2"></i> Aggiungi fondi ora
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($fundings) && method_exists($fundings, 'links'))
                <div class="card-footer bg-white">
                    {{ $fundings->links() }}
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="addFundingModal" tabindex="-1" aria-labelledby="addFundingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFundingModalLabel">Aggiungi fondi all'account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>
                <form action="{{ route('advertisers.payment.checkout') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="amount" class="form-label">Importo da aggiungere (€)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">€</span>
                                <input type="number" min="10" step="0.01" class="form-control" id="amount" name="amount" placeholder="100.00" required>
                            </div>
                            <div class="form-text">Importo minimo: €10.00</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annulla</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-arrow-right me-1"></i> Continua al pagamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border-radius: 10px;
            transition: transform 0.2s;
        }

        .table td, .table th {
            padding: 1rem;
        }

        .form-check-inline.border {
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .form-check-inline.border:hover {
            background-color: rgba(25, 135, 84, 0.05);
        }

        input[type="radio"]:checked + label {
            font-weight: 500;
        }

        input[type="radio"]:checked + label i {
            color: #198754;
        }

        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }
    </style>
@endpush
