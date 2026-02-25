/**
 * ShopSphere - Main AJAX Application JS
 */

document.addEventListener('DOMContentLoaded', function() {

    // ─── AJAX Search ───────────────────────────────────────
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const term = this.value.trim();

            if (term.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`/shop/search?q=${encodeURIComponent(term)}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(products => {
                    if (products.length === 0) {
                        searchResults.innerHTML = '<div class="search-item text-muted">No results found.</div>';
                    } else {
                        searchResults.innerHTML = products.map(p => `
                            <a href="/shop/${p.slug}" class="search-item d-flex gap-2 align-items-center text-decoration-none text-dark">
                                <img src="${p.image ? '/' + p.image : 'https://via.placeholder.com/40'}" width="40" height="40" style="object-fit:cover;border-radius:4px;">
                                <div>
                                    <strong>${p.name}</strong><br>
                                    <small class="text-primary">$${parseFloat(p.price).toFixed(2)}</small>
                                </div>
                            </a>
                        `).join('');
                    }
                    searchResults.style.display = 'block';
                });
            }, 300);
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    }

    // ─── Add to Cart (from product listings) ───────────────
    document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            addToCart(productId, 1, null);
        });
    });

    // ─── Wishlist Toggle ───────────────────────────────────
    document.querySelectorAll('.btn-wishlist-toggle').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            fetch('/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.className = data.added ? 'bi bi-heart-fill text-danger' : 'bi bi-heart';
                    }
                    showToast(data.message);
                }
            });
        });
    });

    // ─── Move Wishlist to Cart ─────────────────────────────
    document.querySelectorAll('.btn-move-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            const wishlistId = this.dataset.wishlistId;
            fetch(`/wishlist/${wishlistId}/move-to-cart`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.closest('.col-md-3').remove();
                    showToast(data.message);
                }
            });
        });
    });
});

// ─── Helper: Add to Cart ───────────────────────────────────
function addToCart(productId, quantity, variantId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity,
            variant_id: variantId
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const cartCount = document.getElementById('cartCount');
            if (cartCount) cartCount.textContent = data.cart.items_count;
            showToast(data.message);
        }
    });
}

// ─── Simple Toast Notification ─────────────────────────────
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'position-fixed bottom-0 end-0 m-3 p-3 bg-dark text-white rounded shadow';
    toast.style.zIndex = '9999';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
