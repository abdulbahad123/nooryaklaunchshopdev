<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Bcategory;
use App\Models\Blog;
use App\Models\Language;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Fetch all languages
        $languages = Language::all();
        if ($languages->isEmpty()) {
            return;
        }

        foreach ($languages as $lang) {
            // Delete dependent blogs first to prevent foreign key or general data noise
            Blog::where('language_id', $lang->id)->delete();
            // Delete categories
            Bcategory::where('language_id', $lang->id)->delete();

            // 2. Create the new categories
            $categoriesData = [
                ['name' => 'Clothing', 'serial_number' => 1],
                ['name' => 'Electronics', 'serial_number' => 2],
                ['name' => 'Jewelry', 'serial_number' => 3],
                ['name' => 'Grocery', 'serial_number' => 4],
                ['name' => 'Beauty', 'serial_number' => 5],
            ];

            $createdCategories = [];
            foreach ($categoriesData as $cat) {
                $createdCategories[$cat['name']] = Bcategory::create([
                    'language_id' => $lang->id,
                    'name' => $cat['name'],
                    'status' => 1,
                    'serial_number' => $cat['serial_number']
                ]);
            }

            // 3. Create the new blogs
            $blogsData = [
                [
                    'category' => 'Clothing',
                    'image' => 'clothing_theme_blog.png',
                    'title' => 'How to Start a Highly Successful Online Clothing Store',
                    'slug' => 'how-to-start-a-highly-successful-online-clothing-store',
                    'content' => '<p>Starting an online clothing store is one of the most popular and profitable e-commerce ventures today. However, because the apparel industry is highly competitive, success requires careful planning, target audience selection, and a stunning digital storefront.</p><h3>1. Find Your Niche</h3><p>Instead of selling "all clothes to all people," start by focusing on a specific niche. This could be sustainable activewear, vintage streetwear, minimalist professional attire, or custom graphic tees. A defined niche helps you stand out and keeps your marketing costs low.</p><h3>2. Choose the Right eCommerce Platform</h3><p>Your storefront is your digital dressing room. With Launchshop, you get pre-designed clothing templates optimized for high conversion rates. Make sure your store includes features like:</p><ul><li>Interactive size guides to reduce return rates.</li><li>High-resolution image zoom and product videos.</li><li>Variant selectors for color, size, and material.</li></ul><h3>3. Master Your Inventory & Fulfillment</h3><p>Decide whether you will hold physical inventory, use a print-on-demand model, or work with a dropshipping partner. Keeping a clean inventory system prevents overselling and builds customer trust from day one.</p>',
                    'serial_number' => 1
                ],
                [
                    'category' => 'Electronics',
                    'image' => 'electronics_theme_blog.png',
                    'title' => 'Top Tech Themes: Optimizing Gadget Store User Experience',
                    'slug' => 'top-tech-themes-optimizing-gadget-store-user-experience',
                    'content' => '<p>Selling electronics and gadgets online presents unique challenges. Unlike apparel, tech shoppers are highly analytical and focus heavily on specifications, compatibility, and reliability. Here is how you can optimize your electronics storefront for maximum trust and sales.</p><h3>1. High-Detail Specifications Table</h3><p>Tech buyers need to know the exact dimensions, weight, battery capacity, processors, and ports of a device. Ensure your product description includes a clean, easy-to-read specification sheet. Standardizing this format makes comparing products simple.</p><h3>2. Build Trust with Reviews and Q&A</h3><p>Electronics are high-involvement purchases. Integrate verified customer reviews, video unboxings, and a dedicated Questions & Answers section. Answering technical compatibility questions directly on the page removes checkout friction.</p><h3>3. Highlight Warranty & Easy Returns</h3><p>Customers are often hesitant to buy gadgets online due to fears of shipping damage or defective units. Display your warranty policy and easy 10-day return guarantee prominently near the "Add to Cart" button to reassure buyers.</p>',
                    'serial_number' => 2
                ],
                [
                    'category' => 'Jewelry',
                    'image' => 'jewelry_theme_blog.png',
                    'title' => 'Elegant Luxury Themes for Accessories & Jewelry E-commerce',
                    'slug' => 'elegant-luxury-themes-for-accessories-jewelry-ecommerce',
                    'content' => '<p>Jewelry is a highly visual, luxury purchase driven by emotion and premium presentation. To sell premium accessories and jewelry online, your e-commerce store needs to feel like a high-end digital showroom.</p><h3>1. Lean into Sleek and Elegant Aesthetics</h3><p>Use clean typography, soft pastel or premium dark backgrounds, and plenty of white space. A cluttered layout distracts from the fine details of your jewelry pieces. Smooth fade-in animations add a touch of luxury to the user experience.</p><h3>2. Show Macro Photography</h3><p>Shoppers cannot touch or try on your necklaces, rings, or bracelets. Invest in high-quality macro photography that shows the texture, shine, and detailed craftsmanship of metals and gemstones. Show the jewelry worn by real models to give a clear sense of scale.</p><h3>3. Secure & Insured Delivery Badges</h3><p>High-value items require extra shipping reassurance. Include trust badges showcasing secure packaging, insured transit, and discreet delivery options. This builds confidence, leading to larger average order values.</p>',
                    'serial_number' => 3
                ],
                [
                    'category' => 'Grocery',
                    'image' => 'grocery_theme_blog.png',
                    'title' => 'Grocery Store Launch: Fast Checkout & Delivery Optimization',
                    'slug' => 'grocery-store-launch-fast-checkout-delivery-optimization',
                    'content' => '<p>The online grocery market is booming, but it requires highly efficient operations and swift checkouts. Shoppers buying fresh organic produce, dairy products, and pantry staples expect lightning-fast loading speeds and local delivery options.</p><h3>1. Quick Cart Reminders & Simple Reordering</h3><p>Grocery shopping is recurring. Your store should allow customers to quickly add items from their purchase history or saved lists in one click. Make the checkout path as frictionless as possible to prevent abandoned baskets.</p><h3>2. Setup Neighborhood Delivery Scheduling</h3><p>Implement local delivery slot scheduling at checkout. Let customers select the exact day and hour range they want their fresh groceries delivered. Clear communication on temperature-controlled shipping builds long-term loyalty.</p><h3>3. Dynamic Live Inventory Status</h3><p>Nobody wants to order fresh strawberries only to receive an out-of-stock notification later. Utilize dynamic inventory tracking to show live stock counts on the product page, preventing customer disappointment.</p>',
                    'serial_number' => 4
                ],
                [
                    'category' => 'Beauty',
                    'image' => 'beauty_theme_blog.png',
                    'title' => 'Skincare & Cosmetics Themes: Designing Beauty Showrooms',
                    'slug' => 'skincare-cosmetics-themes-designing-beauty-showrooms',
                    'content' => '<p>Skincare, makeup, and beauty products are deeply personal purchases. When designing a beauty e-commerce storefront, your goal is to build deep trust, educate the customer, and showcase products in a clean, vibrant aesthetic.</p><h3>1. Clean, Natural Design System</h3><p>Align your website style with your brand values. If you sell organic vegan skincare, use soft earthy tones (greens, creams, and warm sand colors). Use premium fonts that feel fresh and inviting, matching the premium nature of your beauty products.</p><h3>2. Focus on Skincare Routine Bundles</h3><p>Increase your average order value (AOV) by selling skin routines as curated bundles (e.g., Cleanser + Toner + Moisturizer). Clear step-by-step instructions showing how products work together make purchase decisions easy for shoppers.</p><h3>3. Showcase Ingredient Transparency</h3><p>Modern beauty buyers inspect every ingredient. Provide a detailed, transparent list of what goes into your products. Highlighting certifications like Cruelty-Free, Paraben-Free, and Organic builds immediate authority and trust.</p>',
                    'serial_number' => 5
                ]
            ];

            foreach ($blogsData as $blogItem) {
                $category = $createdCategories[$blogItem['category']];
                Blog::create([
                    'language_id' => $lang->id,
                    'bcategory_id' => $category->id,
                    'title' => $blogItem['title'],
                    'slug' => $blogItem['slug'],
                    'main_image' => $blogItem['image'],
                    'content' => $blogItem['content'],
                    'meta_keywords' => $blogItem['category'] . ', e-commerce, Launchshop',
                    'meta_description' => substr(strip_tags($blogItem['content']), 0, 150),
                    'serial_number' => $blogItem['serial_number']
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No-op
    }
};
