<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Page;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Wallet;
use App\Models\RewardPoint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ShopSphereSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin User ──────────────────────────────────────
        User::create([
            'name' => 'Admin',
            'email' => 'admin@shopsphere.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // ─── Sample Vendor ───────────────────────────────────
        $vendorUser = User::create([
            'name' => 'Demo Vendor',
            'email' => 'vendor@shopsphere.com',
            'password' => Hash::make('password'),
            'role' => 'vendor',
            'email_verified_at' => now(),
        ]);

        $vendor = Vendor::create([
            'user_id' => $vendorUser->id,
            'shop_name' => 'Demo Shop',
            'slug' => 'demo-shop',
            'description' => 'A demo vendor shop for testing purposes.',
            'address' => '123 Demo Street',
            'city' => 'Demo City',
            'state' => 'Demo State',
            'zip_code' => '12345',
            'country' => 'US',
            'commission_rate' => 10.00,
            'status' => 'approved',
        ]);

        Wallet::create([
            'vendor_id' => $vendor->id,
            'balance' => 0,
            'total_earned' => 0,
            'total_withdrawn' => 0,
            'total_commission_paid' => 0,
        ]);

        // ─── Sample Customer ─────────────────────────────────
        $customer = User::create([
            'name' => 'Demo Customer',
            'email' => 'customer@shopsphere.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        RewardPoint::create([
            'user_id' => $customer->id,
            'balance' => 100,
            'total_earned' => 100,
            'total_redeemed' => 0,
        ]);

        // ─── Categories ──────────────────────────────────────
        $electronics = Category::create(['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic devices and gadgets']);
        Category::create(['name' => 'Smartphones', 'slug' => 'smartphones', 'parent_id' => $electronics->id, 'description' => 'Mobile phones and accessories']);
        Category::create(['name' => 'Laptops', 'slug' => 'laptops', 'parent_id' => $electronics->id, 'description' => 'Laptops and notebooks']);

        $fashion = Category::create(['name' => 'Fashion', 'slug' => 'fashion', 'description' => 'Clothing and accessories']);
        Category::create(['name' => 'Men', 'slug' => 'men-fashion', 'parent_id' => $fashion->id, 'description' => 'Men clothing']);
        Category::create(['name' => 'Women', 'slug' => 'women-fashion', 'parent_id' => $fashion->id, 'description' => 'Women clothing']);

        Category::create(['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home decor and garden supplies']);
        Category::create(['name' => 'Sports & Outdoors', 'slug' => 'sports-outdoors', 'description' => 'Sports equipment and outdoor gear']);
        Category::create(['name' => 'Books', 'slug' => 'books', 'description' => 'Books and educational material']);
        Category::create(['name' => 'Health & Beauty', 'slug' => 'health-beauty', 'description' => 'Health and beauty products']);

        // ─── Subscription Plans ──────────────────────────────
        SubscriptionPlan::create([
            'name' => 'Free',
            'slug' => 'free',
            'description' => 'Get started with basic features',
            'price' => 0,
            'billing_cycle' => 'monthly',
            'product_limit' => 10,
            'commission_rate' => 15.00,
            'featured_products' => false,
            'analytics_access' => false,
            'priority_support' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        SubscriptionPlan::create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => 'Perfect for growing businesses',
            'price' => 29.99,
            'billing_cycle' => 'monthly',
            'product_limit' => 100,
            'commission_rate' => 10.00,
            'featured_products' => false,
            'analytics_access' => true,
            'priority_support' => false,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        SubscriptionPlan::create([
            'name' => 'Professional',
            'slug' => 'professional',
            'description' => 'For established businesses',
            'price' => 79.99,
            'billing_cycle' => 'monthly',
            'product_limit' => 500,
            'commission_rate' => 7.50,
            'featured_products' => true,
            'analytics_access' => true,
            'priority_support' => true,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        SubscriptionPlan::create([
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'description' => 'Unlimited everything for large vendors',
            'price' => 199.99,
            'billing_cycle' => 'monthly',
            'product_limit' => 0,
            'commission_rate' => 5.00,
            'featured_products' => true,
            'analytics_access' => true,
            'priority_support' => true,
            'is_active' => true,
            'sort_order' => 4,
        ]);

        // ─── CMS Pages ──────────────────────────────────────
        Page::create([
            'title' => 'About Us',
            'slug' => 'about-us',
            'content' => '<h2>About ShopSphere</h2><p>ShopSphere is a modern multi-vendor eCommerce platform that connects buyers with trusted sellers worldwide. Our mission is to provide a seamless shopping experience with the widest selection of products at competitive prices.</p><p>Founded with the vision of empowering small businesses, ShopSphere gives vendors the tools they need to succeed in the digital marketplace.</p>',
            'meta_title' => 'About Us - ShopSphere',
            'meta_description' => 'Learn about ShopSphere, the modern multi-vendor eCommerce platform.',
            'is_active' => true,
        ]);

        Page::create([
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'content' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. This Privacy Policy explains how ShopSphere collects, uses, and protects your personal information.</p><h3>Information We Collect</h3><p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.</p><h3>How We Use Your Information</h3><p>We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.</p>',
            'meta_title' => 'Privacy Policy - ShopSphere',
            'meta_description' => 'ShopSphere privacy policy and data protection information.',
            'is_active' => true,
        ]);

        Page::create([
            'title' => 'Terms of Service',
            'slug' => 'terms-of-service',
            'content' => '<h2>Terms of Service</h2><p>By using ShopSphere, you agree to these Terms of Service. Please read them carefully.</p><h3>Account Terms</h3><p>You must be at least 18 years old to use this service. You are responsible for maintaining the security of your account.</p><h3>Vendor Terms</h3><p>Vendors are responsible for the accuracy of their product listings and for fulfilling orders in a timely manner.</p>',
            'meta_title' => 'Terms of Service - ShopSphere',
            'meta_description' => 'ShopSphere terms of service and usage conditions.',
            'is_active' => true,
        ]);

        Page::create([
            'title' => 'Contact Us',
            'slug' => 'contact-us',
            'content' => '<h2>Contact Us</h2><p>We would love to hear from you! Get in touch with our team.</p><p><strong>Email:</strong> support@shopsphere.com</p><p><strong>Phone:</strong> +1 (555) 123-4567</p><p><strong>Address:</strong> 123 Commerce Street, Tech City, TC 12345</p>',
            'meta_title' => 'Contact Us - ShopSphere',
            'meta_description' => 'Get in touch with ShopSphere support team.',
            'is_active' => true,
        ]);
    }
}
