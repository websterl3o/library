<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $subjects = \App\Models\Subject::factory(10)->create();
        $authors = \App\Models\Author::factory(20)->create();
        
        \App\Models\Book::factory(50)->create()->each(function ($book) use ($subjects, $authors) {
            $book->subjects()->attach($subjects->random(rand(1, 3))->pluck('id'));
            $book->authors()->attach($authors->random(rand(1, 2))->pluck('id'));
        });
    }
}
