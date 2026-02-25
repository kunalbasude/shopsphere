<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductImage;
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
        $electronics = Category::create(['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic devices and gadgets', 'image' => 'images/categories/electronics.jpg']);
        $smartphones = Category::create(['name' => 'Smartphones', 'slug' => 'smartphones', 'parent_id' => $electronics->id, 'description' => 'Mobile phones and accessories', 'image' => 'images/categories/smartphones.jpg']);
        $laptops = Category::create(['name' => 'Laptops', 'slug' => 'laptops', 'parent_id' => $electronics->id, 'description' => 'Laptops and notebooks', 'image' => 'images/categories/laptops.jpg']);
        $headphones = Category::create(['name' => 'Headphones', 'slug' => 'headphones', 'parent_id' => $electronics->id, 'description' => 'Headphones and earbuds', 'image' => 'images/categories/headphones.jpg']);
        $tablets = Category::create(['name' => 'Tablets', 'slug' => 'tablets', 'parent_id' => $electronics->id, 'description' => 'Tablets and e-readers', 'image' => 'images/categories/tablets.jpg']);
        $smartwatches = Category::create(['name' => 'Smartwatches', 'slug' => 'smartwatches', 'parent_id' => $electronics->id, 'description' => 'Smartwatches and wearables', 'image' => 'images/categories/smartwatches.jpg']);
        $cameras = Category::create(['name' => 'Cameras', 'slug' => 'cameras', 'parent_id' => $electronics->id, 'description' => 'Digital cameras and accessories', 'image' => 'images/categories/cameras.jpg']);

        $fashion = Category::create(['name' => 'Fashion', 'slug' => 'fashion', 'description' => 'Clothing and accessories', 'image' => 'images/categories/fashion.jpg']);
        Category::create(['name' => 'Men', 'slug' => 'men-fashion', 'parent_id' => $fashion->id, 'description' => 'Men clothing']);
        Category::create(['name' => 'Women', 'slug' => 'women-fashion', 'parent_id' => $fashion->id, 'description' => 'Women clothing']);

        Category::create(['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home decor and garden supplies', 'image' => 'images/categories/home-garden.jpg']);
        Category::create(['name' => 'Sports & Outdoors', 'slug' => 'sports-outdoors', 'description' => 'Sports equipment and outdoor gear', 'image' => 'images/categories/sports.jpg']);
        Category::create(['name' => 'Books', 'slug' => 'books', 'description' => 'Books and educational material', 'image' => 'images/categories/books.jpg']);
        Category::create(['name' => 'Health & Beauty', 'slug' => 'health-beauty', 'description' => 'Health and beauty products', 'image' => 'images/categories/health-beauty.jpg']);

        // ─── Demo Electronics Products ─────────────────────────
        $products = [
            // Smartphones
            ['name' => 'iPhone 15 Pro Max', 'slug' => 'iphone-15-pro-max', 'sku' => 'ELEC-SPH-001', 'category_id' => $smartphones->id, 'vendor_id' => $vendor->id, 'price' => 1199.99, 'compare_price' => 1299.99, 'cost_price' => 900.00, 'quantity' => 50, 'status' => 'active', 'is_featured' => true, 'short_description' => 'Titanium design. A17 Pro chip. 48MP camera system.', 'description' => "The iPhone 15 Pro Max features a strong and lightweight titanium design with a textured matte glass back. It's powered by the A17 Pro chip, the first chip in the industry to use a 3-nanometer process. The 48MP main camera with a quad-pixel sensor delivers stunning detail. Action button gives you quick access to your favorite feature."],
            ['name' => 'Samsung Galaxy S24 Ultra', 'slug' => 'samsung-galaxy-s24-ultra', 'sku' => 'ELEC-SPH-002', 'category_id' => $smartphones->id, 'vendor_id' => $vendor->id, 'price' => 1299.99, 'compare_price' => 1419.99, 'cost_price' => 950.00, 'quantity' => 45, 'status' => 'active', 'is_featured' => true, 'short_description' => 'Galaxy AI built in. 200MP camera. Titanium frame.', 'description' => "Meet Galaxy S24 Ultra, the ultimate form of Galaxy Ultra with a new titanium exterior and a 6.8-inch flat display. It features Galaxy AI for real-time translation, Circle to Search, and a 200MP adaptive pixel sensor camera. The embedded S Pen provides precision control for creativity."],
            ['name' => 'Google Pixel 8 Pro', 'slug' => 'google-pixel-8-pro', 'sku' => 'ELEC-SPH-003', 'category_id' => $smartphones->id, 'vendor_id' => $vendor->id, 'price' => 999.00, 'compare_price' => 1099.00, 'cost_price' => 720.00, 'quantity' => 35, 'status' => 'active', 'is_featured' => false, 'short_description' => 'Google Tensor G3 chip. Best Pixel camera ever. 7 years of updates.', 'description' => "Pixel 8 Pro is the most powerful and intelligent Pixel phone yet, with Google Tensor G3 and the Titan M2 security chip. Its pro-level camera with a 50MP main sensor, 48MP ultrawide, and 48MP telephoto captures incredible photos and videos. With 7 years of OS, security, and Feature Drops."],
            ['name' => 'OnePlus 12', 'slug' => 'oneplus-12', 'sku' => 'ELEC-SPH-004', 'category_id' => $smartphones->id, 'vendor_id' => $vendor->id, 'price' => 799.99, 'compare_price' => 899.99, 'cost_price' => 580.00, 'quantity' => 40, 'status' => 'active', 'is_featured' => false, 'short_description' => 'Snapdragon 8 Gen 3. Hasselblad camera. 100W SUPERVOOC.', 'description' => "The OnePlus 12 is powered by the Snapdragon 8 Gen 3 processor with up to 16GB RAM. It features a 4th Gen Hasselblad camera system with a 50MP main sensor, 64MP periscope telephoto, and 48MP ultrawide. The 5400mAh battery supports 100W SUPERVOOC charging."],

            // Laptops
            ['name' => 'MacBook Pro 16" M3 Max', 'slug' => 'macbook-pro-16-m3-max', 'sku' => 'ELEC-LAP-001', 'category_id' => $laptops->id, 'vendor_id' => $vendor->id, 'price' => 2499.00, 'compare_price' => 2799.00, 'cost_price' => 1900.00, 'quantity' => 25, 'status' => 'active', 'is_featured' => true, 'short_description' => 'Apple M3 Max chip. 16-inch Liquid Retina XDR display. Up to 22 hours battery.', 'description' => "MacBook Pro 16-inch with M3 Max delivers extraordinary performance for pro workflows. The M3 Max chip features up to 16-core CPU and 40-core GPU with hardware-accelerated ray tracing. The stunning 16.2-inch Liquid Retina XDR display provides extreme dynamic range and contrast ratio."],
            ['name' => 'Dell XPS 15', 'slug' => 'dell-xps-15', 'sku' => 'ELEC-LAP-002', 'category_id' => $laptops->id, 'vendor_id' => $vendor->id, 'price' => 1499.99, 'compare_price' => 1699.99, 'cost_price' => 1100.00, 'quantity' => 30, 'status' => 'active', 'is_featured' => false, 'short_description' => 'Intel Core i7-13700H. 15.6" OLED 3.5K display. 16GB RAM.', 'description' => "The Dell XPS 15 combines stunning design with serious performance. Featuring a 13th Gen Intel Core i7 processor, 15.6-inch OLED 3.5K InfinityEdge display, and NVIDIA GeForce RTX 4060. The CNC machined aluminum chassis with carbon fiber palm rest is both durable and beautiful."],
            ['name' => 'HP Spectre x360 14', 'slug' => 'hp-spectre-x360-14', 'sku' => 'ELEC-LAP-003', 'category_id' => $laptops->id, 'vendor_id' => $vendor->id, 'price' => 1349.99, 'compare_price' => 1549.99, 'cost_price' => 980.00, 'quantity' => 20, 'status' => 'active', 'is_featured' => false, 'short_description' => 'Intel Core Ultra 7. 14" 2.8K OLED touchscreen. 360-degree hinge.', 'description' => "The HP Spectre x360 14 is a premium 2-in-1 convertible laptop with Intel Core Ultra 7 processor and a gorgeous 14-inch 2.8K OLED touchscreen. The 360-degree hinge allows four flexible modes. Precision-crafted from CNC aluminum with dual-tone design."],
            ['name' => 'ASUS ROG Zephyrus G16', 'slug' => 'asus-rog-zephyrus-g16', 'sku' => 'ELEC-LAP-004', 'category_id' => $laptops->id, 'vendor_id' => $vendor->id, 'price' => 1899.99, 'compare_price' => 2099.99, 'cost_price' => 1400.00, 'quantity' => 15, 'status' => 'active', 'is_featured' => true, 'short_description' => 'Intel Core i9-14900HX. RTX 4070. 16" ROG Nebula OLED.', 'description' => "The ROG Zephyrus G16 is an ultra-slim gaming powerhouse featuring the Intel Core i9-14900HX processor and NVIDIA GeForce RTX 4070 graphics. The 16-inch ROG Nebula OLED display delivers vivid colors and 240Hz refresh rate for buttery-smooth gaming."],

            // Headphones
            ['name' => 'Sony WH-1000XM5', 'slug' => 'sony-wh-1000xm5', 'sku' => 'ELEC-HP-001', 'category_id' => $headphones->id, 'vendor_id' => $vendor->id, 'price' => 349.99, 'compare_price' => 399.99, 'cost_price' => 220.00, 'quantity' => 60, 'status' => 'active', 'is_featured' => true, 'short_description' => 'Industry-leading noise cancellation. 30-hour battery. Crystal clear calls.', 'description' => "The Sony WH-1000XM5 features industry-leading noise cancellation with two processors controlling 8 microphones. The newly designed 30mm driver unit delivers exceptional sound quality. With 30 hours of battery life, multipoint connection, and speak-to-chat technology."],
            ['name' => 'Apple AirPods Pro 2', 'slug' => 'apple-airpods-pro-2', 'sku' => 'ELEC-HP-002', 'category_id' => $headphones->id, 'vendor_id' => $vendor->id, 'price' => 249.00, 'compare_price' => 279.00, 'cost_price' => 160.00, 'quantity' => 80, 'status' => 'active', 'is_featured' => true, 'short_description' => 'Active Noise Cancellation. Adaptive Audio. USB-C MagSafe case.', 'description' => "AirPods Pro 2 feature the Apple H2 chip for smarter noise cancellation and immersive Adaptive Audio. Personalized Spatial Audio with dynamic head tracking creates a theater-like experience. The USB-C MagSafe charging case offers up to 6 hours of battery life."],
            ['name' => 'Bose QuietComfort Ultra', 'slug' => 'bose-quietcomfort-ultra', 'sku' => 'ELEC-HP-003', 'category_id' => $headphones->id, 'vendor_id' => $vendor->id, 'price' => 429.00, 'compare_price' => 479.00, 'cost_price' => 280.00, 'quantity' => 35, 'status' => 'active', 'is_featured' => false, 'short_description' => 'Immersive spatial audio. World-class noise cancellation. Premium comfort.', 'description' => "Bose QuietComfort Ultra headphones deliver the ultimate audio experience with Bose Immersive Audio for spatial sound. World-class noise cancellation adapts to any environment. The premium materials and plush cushions provide all-day comfort. Up to 24 hours of battery life."],

            // Tablets
            ['name' => 'iPad Pro 12.9" M2', 'slug' => 'ipad-pro-12-9-m2', 'sku' => 'ELEC-TAB-001', 'category_id' => $tablets->id, 'vendor_id' => $vendor->id, 'price' => 1099.00, 'compare_price' => 1199.00, 'cost_price' => 800.00, 'quantity' => 30, 'status' => 'active', 'is_featured' => true, 'short_description' => 'M2 chip. 12.9" Liquid Retina XDR display. Apple Pencil hover.', 'description' => "iPad Pro with the M2 chip is the most advanced iPad ever. The 12.9-inch Liquid Retina XDR display with ProMotion technology delivers an incredible visual experience. Apple Pencil hover lets you see a preview of your mark before you make it. Works with Magic Keyboard and Smart Keyboard Folio."],
            ['name' => 'Samsung Galaxy Tab S9 Ultra', 'slug' => 'samsung-galaxy-tab-s9-ultra', 'sku' => 'ELEC-TAB-002', 'category_id' => $tablets->id, 'vendor_id' => $vendor->id, 'price' => 1199.99, 'compare_price' => 1319.99, 'cost_price' => 850.00, 'quantity' => 20, 'status' => 'active', 'is_featured' => false, 'short_description' => 'Snapdragon 8 Gen 2. 14.6" Dynamic AMOLED 2X. S Pen included.', 'description' => "The Galaxy Tab S9 Ultra features a massive 14.6-inch Dynamic AMOLED 2X display with 120Hz refresh rate. Powered by Snapdragon 8 Gen 2 for Galaxy with 12GB RAM. The included S Pen provides natural writing and drawing. IP68 water resistance makes it the most versatile tablet."],

            // Smartwatches
            ['name' => 'Apple Watch Ultra 2', 'slug' => 'apple-watch-ultra-2', 'sku' => 'ELEC-SW-001', 'category_id' => $smartwatches->id, 'vendor_id' => $vendor->id, 'price' => 799.00, 'compare_price' => 849.00, 'cost_price' => 550.00, 'quantity' => 40, 'status' => 'active', 'is_featured' => true, 'short_description' => 'S9 SiP chip. 49mm titanium case. Up to 36 hours battery.', 'description' => "Apple Watch Ultra 2 is the most rugged and capable Apple Watch ever. Built with a 49mm aerospace-grade titanium case and sapphire crystal display. The S9 SiP chip enables the magical double-tap gesture. Precision dual-frequency GPS and up to 36 hours of battery life."],
            ['name' => 'Samsung Galaxy Watch 6 Classic', 'slug' => 'samsung-galaxy-watch-6-classic', 'sku' => 'ELEC-SW-002', 'category_id' => $smartwatches->id, 'vendor_id' => $vendor->id, 'price' => 399.99, 'compare_price' => 449.99, 'cost_price' => 260.00, 'quantity' => 45, 'status' => 'active', 'is_featured' => false, 'short_description' => 'Rotating bezel. Sapphire crystal. Advanced health monitoring.', 'description' => "The Galaxy Watch 6 Classic brings back the iconic rotating bezel for intuitive navigation. Features a sapphire crystal display and stainless steel case. Advanced health monitoring includes heart rate, blood pressure, body composition analysis, and sleep coaching."],

            // Cameras
            ['name' => 'Sony Alpha A7 IV', 'slug' => 'sony-alpha-a7-iv', 'sku' => 'ELEC-CAM-001', 'category_id' => $cameras->id, 'vendor_id' => $vendor->id, 'price' => 2498.00, 'compare_price' => 2698.00, 'cost_price' => 1800.00, 'quantity' => 15, 'status' => 'active', 'is_featured' => false, 'short_description' => '33MP full-frame sensor. 4K 60p video. Real-time Eye AF.', 'description' => "The Sony A7 IV is a versatile full-frame mirrorless camera with a 33MP Exmor R CMOS sensor and BIONZ XR processor. It captures stunning 4K 60p video and features advanced Real-time Eye AF for both photo and video. The 3.0-inch vari-angle LCD touchscreen and 3.69M-dot OLED viewfinder provide exceptional framing."],
            ['name' => 'Canon EOS R6 Mark II', 'slug' => 'canon-eos-r6-mark-ii', 'sku' => 'ELEC-CAM-002', 'category_id' => $cameras->id, 'vendor_id' => $vendor->id, 'price' => 2499.00, 'compare_price' => 2699.00, 'cost_price' => 1850.00, 'quantity' => 12, 'status' => 'active', 'is_featured' => false, 'short_description' => '24.2MP full-frame CMOS. 40fps burst. 6K RAW video.', 'description' => "The Canon EOS R6 Mark II features a 24.2MP full-frame CMOS sensor with DIGIC X processor. Capture up to 40fps with the electronic shutter and record stunning 6K RAW video. The advanced Dual Pixel CMOS AF II with deep learning provides subject detection for people, animals, and vehicles."],

            // More Smartphones
            ['name' => 'Samsung Galaxy Z Fold 5', 'slug' => 'samsung-galaxy-z-fold-5', 'sku' => 'ELEC-SPH-005', 'category_id' => $smartphones->id, 'vendor_id' => $vendor->id, 'price' => 1799.99, 'compare_price' => 1999.99, 'cost_price' => 1300.00, 'quantity' => 20, 'status' => 'active', 'is_featured' => true, 'short_description' => 'Foldable 7.6" display. Snapdragon 8 Gen 2. Flex Mode.', 'description' => "Galaxy Z Fold 5 unfolds a massive 7.6-inch Dynamic AMOLED 2X main screen with 120Hz adaptive refresh rate. Powered by Snapdragon 8 Gen 2 for Galaxy. The new Flex hinge creates a seamless folding experience. Multi-window support lets you run up to three apps simultaneously."],

            // More Laptops
            ['name' => 'Lenovo ThinkPad X1 Carbon Gen 11', 'slug' => 'lenovo-thinkpad-x1-carbon-gen11', 'sku' => 'ELEC-LAP-005', 'category_id' => $laptops->id, 'vendor_id' => $vendor->id, 'price' => 1649.00, 'compare_price' => 1849.00, 'cost_price' => 1200.00, 'quantity' => 25, 'status' => 'active', 'is_featured' => false, 'short_description' => 'Intel Core i7 vPro. 14" 2.8K OLED. MIL-STD 810H tested.', 'description' => "The ThinkPad X1 Carbon Gen 11 is the ultimate business ultrabook. Weighing just 2.48 lbs with Intel Core i7 vPro processor and a stunning 14-inch 2.8K OLED display. MIL-STD 810H tested for durability. Features a legendary ThinkPad keyboard, fingerprint reader, and IR camera."],
        ];

        foreach ($products as $productData) {
            $product = Product::create($productData);

            // Create product image from SVG placeholder
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'images/products/' . $product->slug . '.svg',
                'alt_text' => $product->name,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }

        // ─── Demo Coupons ──────────────────────────────────
        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percentage',
            'value' => 10,
            'min_cart_value' => 50,
            'max_discount' => 100,
            'usage_limit' => 1000,
            'per_user_limit' => 1,
            'is_active' => true,
            'starts_at' => now(),
            'expires_at' => now()->addYear(),
        ]);

        Coupon::create([
            'code' => 'FLAT20',
            'type' => 'fixed',
            'value' => 20,
            'min_cart_value' => 100,
            'usage_limit' => 500,
            'per_user_limit' => 2,
            'is_active' => true,
            'starts_at' => now(),
            'expires_at' => now()->addMonths(6),
        ]);

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
