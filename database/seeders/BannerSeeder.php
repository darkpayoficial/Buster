<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Banner Raspadinhas 1',
                'button_link' => '/raspadinhas',
                'image_url' => 'https://ik.imagekit.io/azx3nlpdu/BANNER---RASPAGREEN.png?updatedAt=1752908931897',
                'order' => 1,
                'active' => true,
            ],
            [
                'title' => 'Banner Raspadinhas 2',
                'button_link' => '/raspadinhas',
                'image_url' => 'https://ik.imagekit.io/azx3nlpdu/BANNER---RASPAGREEN2.png?updatedAt=1752908931897',
                'order' => 2,
                'active' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
}
