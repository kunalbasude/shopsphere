<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shop') - ShopSphere</title>
    <meta name="description" content="@yield('meta_description', 'ShopSphere - Multi-vendor eCommerce marketplace')">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar-brand { font-weight: 700; }
        .product-card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: transform 0.2s; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 4px 16px rgba(0,0,0,0.12); }
        .product-card .card-img-top { height: 220px; object-fit: cover; border-radius: 12px 12px 0 0; }
        .badge-discount { position: absolute; top: 10px; right: 10px; }
        .btn-wishlist { position: absolute; top: 10px; left: 10px; background: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 36px; height: 36px; }
        .search-wrapper { position: relative; }
        .search-results { position: absolute; top: 100%; left: 0; right: 0; z-index: 1050; background: #fff; border: 1px solid #ddd; border-radius: 8px; max-height: 400px; overflow-y: auto; display: none; }
        .search-results .search-item { padding: 10px 15px; border-bottom: 1px solid #eee; cursor: pointer; }
        .search-results .search-item:hover { background: #f8f9fa; }
        .rating-stars .bi-star-fill { color: #ffc107; }
        footer { background: #1a1a2e; color: #a0a0b0; }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand text-primary" href="{{ route('home') }}">
                <i class="bi bi-globe2"></i> ShopSphere
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Search -->
                <div class="search-wrapper mx-auto" style="width: 400px;">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search products...">
                    <div id="searchResults" class="search-results shadow"></div>
                </div>

                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop.index') }}">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3 fs-5"></i>
                            <span id="cartCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">0</span>
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('wishlist.index') }}">
                                <i class="bi bi-heart fs-5"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                                @elseif(Auth::user()->isVendor())
                                    <li><a class="dropdown-item" href="{{ route('vendor.dashboard') }}">Vendor Panel</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('account.index') }}">My Account</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}">My Orders</a></li>
                                <li><a class="dropdown-item" href="{{ route('wishlist.index') }}">Wishlist</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm ms-2" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="text-white"><i class="bi bi-globe2"></i> ShopSphere</h5>
                    <p>Your trusted multi-vendor marketplace.</p>
                </div>
                <div class="col-md-4">
                    <h6 class="text-white">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('shop.index') }}" class="text-decoration-none text-muted">Shop</a></li>
                        <li><a href="{{ route('vendor.register') }}" class="text-decoration-none text-muted">Sell on ShopSphere</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="text-white">Contact</h6>
                    <p>Email: support@shopsphere.com</p>
                </div>
            </div>
            <hr class="border-secondary">
            <p class="text-center mb-0">&copy; {{ date('Y') }} ShopSphere. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
