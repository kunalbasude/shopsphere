/**
 * ShopSphere - Cart Page AJAX
 */

document.addEventListener('DOMContentLoaded', function() {

    // ─── Quantity Update ───────────────────────────────────
    document.querySelectorAll('.btn-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            const delta = parseInt(this.dataset.delta);
            const input = this.closest('.input-group').querySelector('input');
            const newQty = Math.max(0, parseInt(input.value) + delta);

            fetch(`/cart/update/${itemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ quantity: newQty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (newQty === 0) {
                        document.querySelector(`.cart-item[data-item-id="${itemId}"]`).remove();
                    } else {
                        input.value = newQty;
                    }
                    updateCartTotals(data.cart);
                }
            });
        });
    });

    // ─── Remove Item ───────────────────────────────────────
    document.querySelectorAll('.btn-remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const itemId = this.dataset.itemId;

            fetch(`/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`.cart-item[data-item-id="${itemId}"]`).remove();
                    updateCartTotals(data.cart);
                }
            });
        });
    });

    // ─── Apply Coupon ──────────────────────────────────────
    const applyCouponBtn = document.getElementById('applyCouponBtn');
    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', function() {
            const code = document.getElementById('couponCode').value.trim();
            if (!code) return;

            fetch('/cart/coupon/apply', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ code })
            })
            .then(res => res.json())
            .then(data => {
                const msg = document.getElementById('couponMessage');
                msg.textContent = data.message;
                msg.className = `small mt-1 ${data.success ? 'text-success' : 'text-danger'}`;
                if (data.cart) updateCartTotals(data.cart);
            });
        });
    }

    // ─── Apply Reward Points ───────────────────────────────
    const applyRewardsBtn = document.getElementById('applyRewardsBtn');
    if (applyRewardsBtn) {
        applyRewardsBtn.addEventListener('click', function() {
            const points = parseInt(document.getElementById('rewardPoints').value);
            if (!points || points <= 0) return;

            fetch('/cart/rewards/apply', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ points })
            })
            .then(res => res.json())
            .then(data => {
                if (data.cart) updateCartTotals(data.cart);
                showToast(data.message);
            });
        });
    }
});

function updateCartTotals(cart) {
    document.getElementById('subtotal').textContent = '$' + parseFloat(cart.subtotal).toFixed(2);
    document.getElementById('discount').textContent = '-$' + parseFloat(cart.discount).toFixed(2);
    document.getElementById('rewardDiscount').textContent = '-$' + parseFloat(cart.reward_discount).toFixed(2);
    document.getElementById('total').textContent = '$' + parseFloat(cart.total).toFixed(2);

    const cartCount = document.getElementById('cartCount');
    if (cartCount) cartCount.textContent = cart.items_count;
}
