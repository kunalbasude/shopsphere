<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\CartAbandonmentReminder;
use App\Services\FirebaseNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendCartAbandonmentReminders extends Command
{
    protected $signature = 'shopsphere:cart-abandonment';
    protected $description = 'Send reminders for abandoned carts';

    public function handle(FirebaseNotificationService $firebase): int
    {
        $hours = config('shopsphere.cart_abandonment_hours', 24);
        $maxAttempts = 3;

        $abandonedCarts = Cart::whereNotNull('user_id')
            ->whereHas('items')
            ->where('updated_at', '<', now()->subHours($hours))
            ->with(['user', 'items.product'])
            ->get();

        $sent = 0;

        foreach ($abandonedCarts as $cart) {
            $existingReminders = CartAbandonmentReminder::where('cart_id', $cart->id)->count();

            if ($existingReminders >= $maxAttempts) {
                continue;
            }

            $user = $cart->user;
            if (!$user || !$user->email) {
                continue;
            }

            $cartTotal = $cart->items->sum(fn($item) => $item->quantity * $item->unit_price);
            $itemCount = $cart->items->count();

            // Send email
            try {
                Mail::send('emails.cart-abandonment', [
                    'userName' => $user->name,
                    'itemCount' => $itemCount,
                    'cartTotal' => $cartTotal,
                    'cartUrl' => url('/cart'),
                ], function ($message) use ($user) {
                    $message->to($user->email, $user->name)
                        ->subject('You left items in your cart! - ShopSphere');
                });

                CartAbandonmentReminder::create([
                    'cart_id' => $cart->id,
                    'user_id' => $user->id,
                    'channel' => 'email',
                    'attempt' => $existingReminders + 1,
                    'sent_at' => now(),
                ]);

                $sent++;
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$user->email}: {$e->getMessage()}");
            }

            // Send push notification
            try {
                $firebase->sendCartReminder($user);

                CartAbandonmentReminder::create([
                    'cart_id' => $cart->id,
                    'user_id' => $user->id,
                    'channel' => 'push',
                    'attempt' => $existingReminders + 1,
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                $this->warn("Push notification failed for user {$user->id}: {$e->getMessage()}");
            }
        }

        $this->info("Cart abandonment reminders sent: {$sent}");

        return Command::SUCCESS;
    }
}
