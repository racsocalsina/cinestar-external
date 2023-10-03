<?php

namespace Database\Seeders;

use App\Models\MovieTimes\MovieTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieTimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        MovieTime::query()->delete();
        DB::table('movie_times')->insert([
            //ESTRENOS SEDE 1
            [
                'room_id'              => 1,
                'movie_id'             => 1,
                'headquarter_id'       => 1,

                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 20:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 2,
                'movie_id'             => 2,
                'headquarter_id'       => 1,

                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 20:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 3,
                'headquarter_id'       => 1,

                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 22:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 2,
                'movie_id'             => 4,
                'headquarter_id'       => 1,

                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 22:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            //ESTRENOS SEDE 2
            [
                'room_id'              => 1,
                'movie_id'             => 1,
                'headquarter_id'       => 2,

                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 20:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 2,
                'movie_id'             => 2,
                'headquarter_id'       => 2,

                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 20:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 3,
                'headquarter_id'       => 2,

                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 22:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 2,
                'movie_id'             => 4,
                'headquarter_id'       => 2,

                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 22:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            //ESTRENOS SEDE 3
            [
                'room_id'              => 1,
                'movie_id'             => 3,
                'headquarter_id'       => 1,

                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 20:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 2,
                'movie_id'             => 2,
                'headquarter_id'       => 3,

                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 20:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 3,
                'headquarter_id'       => 3,

                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 22:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 2,
                'movie_id'             => 4,
                'headquarter_id'       => 3,

                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 22:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            //ESTRENOS SEDE 4
            [
                'room_id'              => 1,
                'movie_id'             => 1,
                'headquarter_id'       => 4,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 20:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 2,
                'movie_id'             => 2,
                'headquarter_id'       => 4,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 20:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            //ESTRENOS SEDE 5
            [
                'room_id'              => 1,
                'movie_id'             => 2,
                'headquarter_id'       => 5,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 22:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 2,
                'movie_id'             => 4,
                'headquarter_id'       => 5,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2021-01-15 22:00:00',
                'date_start'           => '2021-01-15',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            //CARTELERA SEDE 1
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 18:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 20:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 22:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 18:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 20:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 22:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 18:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 20:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 22:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            // CARTELERA SEDE 1 DIA 20
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 18:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 20:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 22:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 18:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 20:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 22:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 18:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 20:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 22:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            // CARTELERA SEDE 1 DIA 21
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 18:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 20:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 22:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 18:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 20:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 22:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 18:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 20:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 22:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            // CARTELERA SEDE 1 DIA 22
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 18:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 20:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 22:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 18:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 20:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 22:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 18:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 20:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 22:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            // CARTELERA SEDE 1 DIA 23
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 18:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 20:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 22:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 18:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 20:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 22:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 18:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 20:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 1,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 22:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            //CARTELERA SEDE 2 DIA 19
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 18:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 20:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 22:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 18:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 20:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 22:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 18:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 20:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 22:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            // CARTELERA SEDE 1 DIA 20
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 18:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 20:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 22:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 18:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 20:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 22:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 18:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 20:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 22:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            // CARTELERA SEDE 1 DIA 21
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 18:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 20:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 22:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 18:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 20:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 22:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 18:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 20:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 22:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            // CARTELERA SEDE 1 DIA 22
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 18:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 20:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 22:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 18:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 20:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 22:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 18:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 20:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 22:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            // CARTELERA SEDE 1 DIA 23
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 18:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 20:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 22:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 18:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 20:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 22:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 18:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 20:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 2,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 22:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            //CARTELERA SEDE 3 DIA 19
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 18:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 20:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 22:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 18:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 20:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 22:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 18:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 20:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-19 22:00:00',
                'date_start'           => '2020-11-19',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            // CARTELERA SEDE 1 DIA 20
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 18:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 20:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 22:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 18:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 20:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 22:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 18:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 20:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-20 22:00:00',
                'date_start'           => '2020-11-20',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            // CARTELERA SEDE 1 DIA 21
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 18:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 20:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 22:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 18:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 20:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 22:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 18:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 20:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-21 22:00:00',
                'date_start'           => '2020-11-21',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            // CARTELERA SEDE 1 DIA 22
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 18:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 20:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 22:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 18:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 20:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 22:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 18:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 20:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-22 22:00:00',
                'date_start'           => '2020-11-22',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            // CARTELERA SEDE 1 DIA 23
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 18:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 20:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 5,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 22:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 18:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 20:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 1,
                'movie_id'             => 6,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 22:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 18:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '18:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 20:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '20:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
            [
                'room_id'              => 3,
                'movie_id'             => 7,
                'headquarter_id'       => 3,


                'remote_funkey'        => '0001',
                'fun_nro'              => '01',
                'start_at'             => '2020-11-23 22:00:00',
                'date_start'           => '2020-11-23',
                'time_start'           => '22:00:00',
                'is_presale'           => false,
                'planner_graph'       => '[0,0,0,0]',
                'is_numerated'         => 0
            ],
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
