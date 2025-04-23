<!-- Create file: resources/views/components/bootstrap-sidebar.blade.php -->
<div class="sidebar bg-dark text-white" id="sidebar-wrapper">
    <div class="sidebar-header d-flex align-items-center p-3">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="sidebar-logo me-3" width="40">
        <h3 class="fs-5 mb-0">CuneoPubblicità</h3>
        <button class="btn btn-link text-white d-md-none ms-auto" id="sidebarCollapseBtn">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="profile-section p-3 border-bottom border-secondary">
        <div class="d-flex align-items-center mb-3">
            <img src="{{ Auth::user()->profile_photo_url }}" class="rounded-circle me-2" width="40" height="40">
            <div>
                <h6 class="mb-0">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h6>
                <small class="text-muted">{{ Auth::user()->company_name }}</small>
            </div>
        </div>
        <div class="balance-info bg-success bg-opacity-25 p-2 rounded">
            <small>Saldo disponibile</small>
            <h5 class="mb-0">€{{ number_format(Auth::user()->balance ?? 0, 2) }}</h5>
        </div>
    </div>

    <ul class="nav flex-column mt-2">
        <li class="nav-item">
            <a href="{{ route('advertisers.index') }}" class="nav-link {{ request()->routeIs('advertisers.index') ? 'active text-dark' : 'text-white' }}">
                <i class="fas fa-tachometer-alt me-2"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('advertisers.campaigns.create') }}" class="nav-link {{ request()->routeIs('advertisers.campaigns.create') ? 'active text-dark' : 'text-white' }}">
                <i class="fas fa-bullhorn me-2"></i>
                Nuova Campagna
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('advertisers.statistics') }}" class="nav-link {{ request()->routeIs('advertisers.statistics') ? 'active text-dark' : 'text-white' }}">                <i class="fas fa-chart-bar me-2"></i>
                Statistiche
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('advertisers.payments') }}" class="nav-link {{ request()->routeIs('advertisers.payments') ? 'active text-dark' : 'text-white' }}">                <i class="fas fa-credit-card me-2"></i>
                Pagamenti
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('advertisers.settings') }}" class="nav-link {{ request()->routeIs('advertisers.settings') ? 'active text-dark' : 'text-white' }}">                <i class="fas fa-cog me-2"></i>
                Impostazioni
            </a>
        </li>
    </ul>

    <div class="mt-auto p-3 border-top border-secondary">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </div>
</div>

<style>
    .sidebar {
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 999;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }

    .nav-link {
        color: #ced4da;
        transition: all 0.3s;
        border-radius: 5px;
        margin: 2px 10px;
    }

    .nav-link:hover, .nav-link.active {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .sidebar-logo {
        max-height: 40px;
    }

    .balance-info {
        border-left: 4px solid #198754;
    }

    @media (max-width: 767.98px) {
        .sidebar {
            margin-left: -250px;
        }

        .sidebar.active {
            margin-left: 0;
        }

        .content-wrapper {
            margin-left: 0;
        }
    }
</style>
