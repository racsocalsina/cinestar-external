<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeadquarterMovieFormatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('headquarter_movie_formats')->insert([
            [
                'headquarter_id' => 1,
                'movie_format_id' => 1
            ],
            [
                'headquarter_id' => 1,
                'movie_format_id' => 2
            ],
            [
                'headquarter_id' => 1,
                'movie_format_id' => 3
            ],
            [
                'headquarter_id' => 2,
                'movie_format_id' => 2
            ],
            [
                'headquarter_id' => 2,
                'movie_format_id' => 3
            ],
            [
                'headquarter_id' => 3,
                'movie_format_id' => 2
            ],
            [
                'headquarter_id' => 3,
                'movie_format_id' => 3
            ],
            [
                'headquarter_id' => 4,
                'movie_format_id' => 1
            ],
            [
                'headquarter_id' => 4,
                'movie_format_id' => 3
            ],
            [
                'headquarter_id' => 5,
                'movie_format_id' => 2
            ],
            [
                'headquarter_id' => 5,
                'movie_format_id' => 3
            ]
        ]);
    }
}
