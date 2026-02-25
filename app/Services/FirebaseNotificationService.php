<?php

namespace App\Services;

use App\Models\User;
use App\Models\PushSubscription;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseNotificationService
{
    public function sendToUser(User $user, string $title, string $body, array $data = []): void
    {
        $tokens = $user->pushSubscriptions()->where('is_active', true)->pluck('fcm_token')->toArray();

        if (empty($tokens)) return;

        $notification = Notification::create($title, $body);

        foreach ($tokens as $token) {
            try {
                $message = CloudMessage::withTarget('token', $token)
                    ->withNotification($notification)
                    ->withData($data);

                Firebase::messaging()->send($message);
            } catch (\Exception $e) {
                // Deactivate invalid tokens
                PushSubscription::where('fcm_token', $token)->update(['is_active' => false]);
            }
        }
    }

    public function sendToMultipleUsers(array $userIds, string $title, string $body, array $data = []): void
    {
        $tokens = PushSubscription::whereIn('user_id', $userIds)
            ->where('is_active', true)
            ->pluck('fcm_token')
            ->toArray();

        if (empty($tokens)) return;

        $notification = Notification::create($title, $body);

        foreach (array_chunk($tokens, 500) as $chunk) {
            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($data);

            try {
                Firebase::messaging()->sendMulticast($message, $chunk);
            } catch (\Exception $e) {
                report($e);
            }
        }
    }

    public function sendOrderStatusUpdate(int $userId, string $orderNumber, string $status): void
    {
        $user = User::find($userId);
        if (!$user) return;

        $this->sendToUser($user, 'Order Update', "Your order #{$orderNumber} is now {$status}.", [
            'type' => 'order_status',
            'order_number' => $orderNumber,
            'status' => $status,
        ]);
    }

    public function sendCartReminder(User $user): void
    {
        $this->sendToUser($user, 'Items in your cart!', 'You have items waiting in your cart. Complete your purchase now!', [
            'type' => 'cart_reminder',
        ]);
    }

    public function sendCouponAlert(array $userIds, string $code, string $description): void
    {
        $this->sendToMultipleUsers($userIds, 'New Coupon Available!', "Use code {$code}: {$description}", [
            'type' => 'coupon_alert',
            'coupon_code' => $code,
        ]);
    }
}
