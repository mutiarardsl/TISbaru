<?php
// database/seeders/EventCategorySeeder.php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Seeder;

class EventCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Seminar'],
            ['name' => 'Workshop'],
            ['name' => 'Kompetisi'],  
            ['name' => 'Kuliah Umum'],
            ['name' => 'Pelatihan'],
            ['name' => 'Konferensi'],
        ];

        foreach ($categories as $category) {
            EventCategory::create($category);
        }
    }
}