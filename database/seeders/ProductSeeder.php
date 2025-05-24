<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->info('No categories found. Please run CategorySeeder first.');
            return;
        }

        $products = [
            [
                'name' => 'Ocean Breeze',
                'description' => 'A fresh and invigorating scent reminiscent of an ocean breeze on a summer day. Notes of sea salt, citrus, and cedarwood create a refreshing and long-lasting fragrance that transports you to coastal shores.',
                'price' => 79.99,
                'image' => 'ocean-breeze.jpg',
                'stock' => 50,
                'featured' => true,
                'fragrance_notes' => 'Sea salt, Citrus, Cedarwood',
                'volume' => '100ml'
            ],
            [
                'name' => 'Midnight Rose',
                'description' => 'A seductive blend of rose and spices that evokes the mystery of midnight. This captivating fragrance combines luxurious rose with warm amber and vanilla, creating an elegant scent that lingers beautifully.',
                'price' => 89.99,
                'image' => 'midnight-rose.jpg',
                'stock' => 30,
                'featured' => true,
                'fragrance_notes' => 'Rose, Amber, Vanilla',
                'volume' => '75ml'
            ],
            [
                'name' => 'Citrus Horizon',
                'description' => 'An energizing blend of citrus fruits designed to invigorate your senses. Bright notes of lemon, bergamot, and mandarin.',
                'price' => 69.99,
                'image' => 'citrus-horizon.jpg',
                'stock' => 60,
                'featured' => false,
                'fragrance_notes' => 'Lemon, Bergamot, Mandarin',
                'volume' => '100ml'
            ],
            [
                'name' => 'Velvet Noir',
                'description' => 'A deep, rich fragrance for the mysterious and sophisticated. Intense blend of black pepper, leather, and tobacco.',
                'price' => 109.99,
                'image' => 'velvet-noir.jpg',
                'stock' => 25,
                'featured' => true,
                'fragrance_notes' => 'Black Pepper, Leather, Tobacco',
                'volume' => '50ml'
            ]
        ];

        // Create the public images directory if it doesn't exist
        $imageDir = public_path('images');
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }
        // Create a placeholder image function if you don't have actual images
        if (!function_exists('create_placeholder_image')) {
            function create_placeholder_image($path, $filename, $text = 'Placeholder') {
                if (!file_exists($path . '/' . $filename)) {
                    $img = imagecreatetruecolor(400, 500);
                    $bgColor = imagecolorallocate($img, 50, 50, 60); // Dark background
                    $textColor = imagecolorallocate($img, 200, 200, 220); // Light text
                    imagefill($img, 0, 0, $bgColor);
                    imagettftext($img, 20, 0, 110, 250, $textColor, public_path('fonts/arial.ttf'), $text); // Assumes an arial.ttf in public/fonts
                    if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'jpg' || strtolower(pathinfo($filename, PATHINFO_EXTENSION)) === 'jpeg') {
                        imagejpeg($img, $path . '/' . $filename);
                    } else {
                        imagepng($img, $path . '/' . $filename);
                    }
                    imagedestroy($img);
                }
            }
        }
        // Create public/fonts directory and add a default font like Arial.ttf if it doesn't exist
        // This is for the placeholder image generation.
        // You'll need to manually add a font file (e.g., arial.ttf) to `public/fonts/`
        // Or remove the imagettftext line and use imagestring for a basic text.


        foreach ($products as $index => $productData) {
            // Create placeholder image if it doesn't exist
            // For a real project, you would upload images or have them ready.
            // Ensure you have a font file in public/fonts/arial.ttf or similar for this to work.
             if (!file_exists(public_path('fonts/arial.ttf'))) {
                if (!is_dir(public_path('fonts'))) mkdir(public_path('fonts'), 0755, true);
                // Attempt to copy a system font if available, or notify user to add one.
                // This is a simplified approach. For robust solution, ensure font exists.
                // For now, this might fail if arial.ttf isn't manually placed.
             }
            create_placeholder_image($imageDir, $productData['image'], $productData['name']);


            Product::create([
                'category_id' => $categories->random()->id,
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => $productData['description'],
                'price' => $productData['price'],
                'image' => $productData['image'], // Path relative to public/images
                'stock' => $productData['stock'],
                'featured' => $productData['featured'],
                'fragrance_notes' => $productData['fragrance_notes'],
                'volume' => $productData['volume'],
            ]);
        }
    }
}