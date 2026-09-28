<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\QuestionSeeder; // <-- Tambahkan baris ini

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            QuestionSeeder::class,
        ]);
    }
}