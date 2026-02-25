/**
 * ShopSphere - Checkout AJAX
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('placeOrderBtn');
        btn.disabled = true;
        btn.textContent = 'Processing...';

        const formData = new FormData(form);
        const data = Object.fromEntries(formData);

        fetch('/checkout/process', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify(data)
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(data => { throw new Error(data.message || 'Something went wrong.'); });
            }
            return res.json();
        })
        .then(response => {
            // COD or direct redirect
            if (response.redirect_url) {
                window.location.href = response.redirect_url;
                return;
            }

            // Stripe: redirect to hosted checkout
            if (response.checkout_url) {
                window.location.href = response.checkout_url;
                return;
            }

            // Razorpay: open payment modal
            if (response.razorpay) {
                openRazorpay(response.razorpay, response.order_id);
                return;
            }

            btn.disabled = false;
            btn.textContent = 'Place Order';
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            btn.textContent = 'Place Order';
            alert(err.message || 'Something went wrong. Please try again.');
        });
    });
});

function openRazorpay(razorpayData, orderId) {
    const options = {
        key: razorpayData.key,
        amount: razorpayData.amount,
        currency: razorpayData.currency,
        order_id: razorpayData.order_id,
        name: 'ShopSphere',
        description: 'Order Payment',
        handler: function(response) {
            // Verify payment on server
            fetch('/checkout/razorpay/callback', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id: response.razorpay_order_id,
                    razorpay_signature: response.razorpay_signature,
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = `/checkout/success/${orderId}`;
                } else {
                    alert('Payment verification failed.');
                }
            });
        },
        theme: { color: '#0d6efd' }
    };

    const rzp = new Razorpay(options);
    rzp.open();
}
