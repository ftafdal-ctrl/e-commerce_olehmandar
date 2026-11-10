<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('production')) {
            User::create([
                'name' => 'Admin olehMandar',
                'email' => 'admin@olehmandar.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
            ]);

            User::create([
                'name' => 'Pembeli Demo',
                'email' => 'user@olehmandar.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'phone' => '082345678901',
            ]);
        }

        // ── Kategori ────────────────────────────────────────────
        $categories = [
            ['name' => 'Baje',        'slug' => 'baje',        'description' => 'Kudapan khas Mandar dari beras ketan yang dimasak dengan santan dan gula merah, kenyal dan manis.'],
            ['name' => 'Golla Kambu', 'slug' => 'golla-kambu', 'description' => 'Permen tradisional Mandar berbahan gula aren dan kacang tanah, rasa karamel yang khas.'],
            ['name' => 'Kasippi',     'slug' => 'kasippi',     'description' => 'Kerupuk ikan khas Mandar yang renyah, terbuat dari ikan segar pesisir Sulawesi Barat.'],
            ['name' => 'Makanan Khas Lainnya', 'slug' => 'lainnya', 'description' => 'Berbagai produk oleh-oleh khas Mandar lainnya.'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $baje = Category::where('slug', 'baje')->first();
        $gollaKambu = Category::where('slug', 'golla-kambu')->first();
        $kasippi = Category::where('slug', 'kasippi')->first();
        $lainnya = Category::where('slug', 'lainnya')->first();

        // ── Produk ──────────────────────────────────────────────
        $products = [
            // Baje
            [
                'category_id' => $baje->id,
                'name' => 'Baje Original',
                'slug' => 'baje-original',
                'description' => 'Baje ketan asli khas Mandar dengan cita rasa manis gurih dari santan kelapa segar dan gula merah pilihan. Dibungkus daun pisang, harum dan lezat.',
                'price' => 25000,
                'stock' => 50,
                'weight' => '250',
                'is_active' => true,
            ],
            [
                'category_id' => $baje->id,
                'name' => 'Baje Wijen',
                'slug' => 'baje-wijen',
                'description' => 'Baje ketan dengan taburan biji wijen panggang, menambah aroma dan rasa yang lebih kaya. Oleh-oleh favorit dari Mandar.',
                'price' => 28000,
                'stock' => 35,
                'weight' => '250',
                'is_active' => true,
            ],
            [
                'category_id' => $baje->id,
                'name' => 'Baje Pandan',
                'slug' => 'baje-pandan',
                'description' => 'Baje ketan dengan aroma pandan segar, berwarna hijau alami dari daun pandan pilihan. Nikmat disantap saat santai.',
                'price' => 27000,
                'stock' => 40,
                'weight' => '250',
                'is_active' => true,
            ],
            // Golla Kambu
            [
                'category_id' => $gollaKambu->id,
                'name' => 'Golla Kambu Original',
                'slug' => 'golla-kambu-original',
                'description' => 'Permen gula aren khas Mandar dengan campuran kacang tanah sangrai. Tekstur renyah di luar, lembut di dalam. Cocok untuk oleh-oleh.',
                'price' => 20000,
                'stock' => 60,
                'weight' => '200',
                'is_active' => true,
            ],
            [
                'category_id' => $gollaKambu->id,
                'name' => 'Golla Kambu Kemiri',
                'slug' => 'golla-kambu-kemiri',
                'description' => 'Variasi Golla Kambu dengan kemiri panggang yang harum, memberikan cita rasa unik dan khas Mandar yang autentik.',
                'price' => 22000,
                'stock' => 45,
                'weight' => '200',
                'is_active' => true,
            ],
            // Kasippi
            [
                'category_id' => $kasippi->id,
                'name' => 'Kasippi Ikan Tenggiri',
                'slug' => 'kasippi-tenggiri',
                'description' => 'Kerupuk ikan tenggiri segar khas Mandar. Digoreng renyah sempurna, tanpa pengawet, kaya akan protein ikan laut.',
                'price' => 35000,
                'stock' => 30,
                'weight' => '300',
                'is_active' => true,
            ],
            [
                'category_id' => $kasippi->id,
                'name' => 'Kasippi Campur',
                'slug' => 'kasippi-campur',
                'description' => 'Paket Kasippi isi campuran ikan tenggiri dan ikan layang. Lebih hemat, lebih variatif. Cocok untuk camilan keluarga.',
                'price' => 45000,
                'stock' => 25,
                'weight' => '500',
                'is_active' => true,
            ],
            // Lainnya
            [
                'category_id' => $lainnya->id,
                'name' => 'Bolu Mandar',
                'slug' => 'bolu-mandar',
                'description' => 'Kue bolu lembut khas Mandar dengan aroma vanili dan pandan. Dibuat dari bahan-bahan alami tanpa pewarna buatan.',
                'price' => 40000,
                'stock' => 20,
                'weight' => '400',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
