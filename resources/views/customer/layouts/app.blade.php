<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shop') - ShopSphere</title>
    <meta name="description" content="@yield('meta_description', 'ShopSphere - Multi-vendor eCommerce marketplace')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg ss-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-globe2"></i> ShopSphere
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Search -->
                <div class="ss-search-wrapper mx-auto">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search for products...">
                    <div id="searchResults" class="ss-search-results shadow-lg"></div>
                </div>

                <ul class="navbar-nav ms-auto align-items-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop.index') }}">
                            <i class="bi bi-shop me-1"></i>Shop
                        </a>
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
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.85rem; font-weight: 600;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="d-none d-lg-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="border-radius: 12px; min-width: 200px;">
                                @if(Auth::user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2 text-primary"></i>Admin Panel
                                        </a>
                                    </li>
                                @elseif(Auth::user()->isVendor())
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('vendor.dashboard') }}">
                                            <i class="bi bi-shop me-2 text-primary"></i>Vendor Panel
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('account.index') }}">
                                        <i class="bi bi-person me-2 text-muted"></i>My Account
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('orders.index') }}">
                                        <i class="bi bi-box-seam me-2 text-muted"></i>My Orders
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('wishlist.index') }}">
                                        <i class="bi bi-heart me-2 text-muted"></i>Wishlist
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm ms-2 px-3" href="{{ route('register') }}" style="border-radius: 50px; font-weight: 600;">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="ss-footer mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5><i class="bi bi-globe2 me-2"></i>ShopSphere</h5>
                    <p>Your trusted multi-vendor electronics marketplace. Discover premium products from verified sellers worldwide.</p>
                    <div class="footer-social mt-3">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('shop.index') }}">Shop</a></li>
                        <li><a href="{{ route('shop.index', ['sort' => 'newest']) }}">New Arrivals</a></li>
                        <li><a href="{{ route('shop.index', ['sort' => 'popular']) }}">Best Sellers</a></li>
                        <li><a href="{{ route('vendor.register') }}">Sell on ShopSphere</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>Support</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('page.show', 'about-us') }}">About Us</a></li>
                        <li><a href="{{ route('page.show', 'contact-us') }}">Contact Us</a></li>
                        <li><a href="{{ route('page.show', 'privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('page.show', 'terms-of-service') }}">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5>Contact Info</h5>
                    <ul class="footer-links">
                        <li><i class="bi bi-geo-alt me-2"></i>123 Commerce St, Tech City, TC 12345</li>
                        <li><i class="bi bi-envelope me-2"></i>support@shopsphere.com</li>
                        <li><i class="bi bi-telephone me-2"></i>+1 (555) 123-4567</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p class="mb-0">&copy; {{ date('Y') }} ShopSphere. All rights reserved.</p>
            </div>
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
