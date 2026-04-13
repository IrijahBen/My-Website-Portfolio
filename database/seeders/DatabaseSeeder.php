<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
    // 1. Insert Default Categories
        $categories = [
            ['name' => 'All', 'slug' => 'all', 'display_order' => 0],
            ['name' => 'Landing Page', 'slug' => 'landing-page', 'display_order' => 1],
            ['name' => 'Multipage', 'slug' => 'multipage', 'display_order' => 2],
            ['name' => 'Data Analytics', 'slug' => 'data-analytics', 'display_order' => 3],
            ['name' => 'Agentic AI', 'slug' => 'agentic-ai', 'display_order' => 4],
            ['name' => 'Automation', 'slug' => 'automation', 'display_order' => 5],
            ['name' => 'Data Visualization', 'slug' => 'data-visualization', 'display_order' => 6],
            
            // YOUR NEW CATEGORY:
            ['name' => 'Management System', 'slug' => 'management-system', 'display_order' => 7],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insertOrIgnore(array_merge($category, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // 2. Insert Default Admin User
        DB::table('admin_users')->insertOrIgnore([
            'username' => 'admin',
            'password' => Hash::make('admin123'), // Laravel's secure password hasher
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
