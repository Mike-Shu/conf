<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(TenantSeeder::class);
        $this->call(PlayerSeeder::class);
        $this->call(ChatSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(QuizSeeder::class);
        $this->call(TimetableSeeder::class);
        $this->call(ShopProductSeeder::class);
        $this->call(ShopOrderSeeder::class);
        $this->call(ArticleSeeder::class);
    }
}
