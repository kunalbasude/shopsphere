<div class="card">
    <div class="list-group list-group-flush">
        <a href="{{ route('account.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('account.index') ? 'active' : '' }}">
            <i class="bi bi-person"></i> My Account
        </a>
        <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="bi bi-bag"></i> My Orders
        </a>
        <a href="{{ route('wallet.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('wallet.*') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i> My Wallet
        </a>
        <a href="{{ route('reward-points.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('reward-points.*') ? 'active' : '' }}">
            <i class="bi bi-star"></i> Reward Points
        </a>
        <a href="{{ route('wishlist.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('wishlist.*') ? 'active' : '' }}">
            <i class="bi bi-heart"></i> My Wishlist
        </a>
    </div>
</div>
