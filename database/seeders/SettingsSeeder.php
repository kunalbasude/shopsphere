<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Reward Points Settings
            ['key' => 'reward_points_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'reward_points', 'sort_order' => 1],
            ['key' => 'reward_points_per_dollar', 'value' => '10', 'type' => 'number', 'group' => 'reward_points', 'sort_order' => 2],
            ['key' => 'reward_points_minimum_order', 'value' => '50', 'type' => 'number', 'group' => 'reward_points', 'sort_order' => 3],
            ['key' => 'reward_points_redemption_rate', 'value' => '100', 'type' => 'number', 'group' => 'reward_points', 'sort_order' => 4],
            ['key' => 'reward_points_minimum_redeem', 'value' => '500', 'type' => 'number', 'group' => 'reward_points', 'sort_order' => 5],
            
            // Wallet Settings
            ['key' => 'wallet_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'wallet', 'sort_order' => 1],
            ['key' => 'wallet_minimum_balance', 'value' => '0', 'type' => 'number', 'group' => 'wallet', 'sort_order' => 2],
            ['key' => 'wallet_maximum_balance', 'value' => '10000', 'type' => 'number', 'group' => 'wallet', 'sort_order' => 3],
            ['key' => 'wallet_minimum_withdrawal', 'value' => '10', 'type' => 'number', 'group' => 'wallet', 'sort_order' => 4],
            
            // General Settings
            ['key' => 'site_name', 'value' => 'ShopSphere', 'type' => 'text', 'group' => 'general', 'sort_order' => 1],
            ['key' => 'site_description', 'value' => 'Your Ultimate Shopping Destination', 'type' => 'textarea', 'group' => 'general', 'sort_order' => 2],
            ['key' => 'contact_email', 'value' => 'support@shopsphere.com', 'type' => 'text', 'group' => 'general', 'sort_order' => 3],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
