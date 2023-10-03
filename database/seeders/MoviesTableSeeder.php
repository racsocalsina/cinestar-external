<?php

namespace Database\Seeders;

use App\Models\Movies\Movie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoviesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Movie::query()->delete();
        DB::table('movies')->insert([
            [
                'code'                => '00001',
                'name'                => '1997',
                'image_path'          => '1997-movie.jpg',
                'url_trailer'         => 'https://www.youtube.com/watch?v=plh31-GExC8',
                'summary'             => 'En lo más crudo de la Primera Guerra Mundial, dos jóvenes soldados británicos, Schofield (George MacKay) y Blake (Dean-Charles Chapman) reciben una misión aparentemente imposible. En una carrera contrarreloj, deberán atravesar el territorio enemigo para entregar un mensaje que evitará un mortífero ataque contra cientos de soldados, entre ellos el propio hermano de Blake. Una impresionante gesta técnica de Sam Mendes, ganadora del Globo de Oro a Mejor Película en drama, que se sirve del plano secuencia (con apenas un par de cortes) para sumergirnos en los horrores de la guerra.',
                'duration_in_minutes' => 120,
                'type_of_censorship'  => 'Todo Público A',
                'movie_gender_id'     => 1,
                'country_id'         => 3,
                'premier_date'        => '2021-01-15'
            ],
            [
                'code'                => '00002',
                'name'                => 'Aves de presa',
                'image_path'          => 'ave-de-presa.jpg',
                'url_trailer'         => 'https://www.youtube.com/watch?v=ogo8AlGVa70',
                'summary'             => 'Harley Quinn y otras tres heroínas, Canario Negro, Cazadora y Renée Montoya, unen sus fuerzas para salvar a una niña del malvado rey del crimen Máscara Negra.',
                'duration_in_minutes' => 120,
                'type_of_censorship'  => 'Todo Público A',
                'movie_gender_id'     => 1,
                'country_id'         => 3,
                'premier_date'        => '2021-01-15'
            ],
            [
                'code'                => '00003',
                'name'                => 'Sonic. La película',
                'image_path'          => 'sonic.jpg',
                'url_trailer'         => 'https://www.youtube.com/watch?v=szby7ZHLnkA',
                'summary'             => 'Sonic intenta atravesar las complejidades de la vida en la Tierra con su nuevo mejor amigo, un humano llamado Tom Wachowski. Deberán unir sus fuerzas para evitar que el malvado Dr. Robotnik capture a Sonic y use sus poderes para dominar el mundo.',
                'duration_in_minutes' => 120,
                'type_of_censorship'  => 'Todo Público A',
                'movie_gender_id'     => 1,
                'country_id'         => 3,
                'premier_date'        => '2021-01-15'
            ],
            [
                'code'                => '00004',
                'name'                => 'Nueva York sin salida',
                'image_path'          => 'manhattan.jpeg',
                'url_trailer'         => 'https://www.youtube.com/watch?v=IH_8NLwmTD8',
                'summary'             => 'Un agente de Nueva York trata de capturar a dos asesinos de policías mientras, por primera vez en la historia, los veintiún puentes de Manhattan se cierran al tránsito para impedir que huyan. Durante la cacería, el agente empieza a dudar de todo.',
                'duration_in_minutes' => 120,
                'type_of_censorship'  => 'Todo Público A',
                'movie_gender_id'     => 1,
                'country_id'         => 3,
                'premier_date'        => '2021-01-15'
            ],
            [
                'code'                => '00005',
                'name'                => 'Color Out of Space',
                'image_path'          => 'color_out_of_space.jpg',
                'url_trailer'         => 'https://www.youtube.com/watch?v=agnpaFLo0to',
                'summary'             => 'Después de que un meteorito aterriza en el patio delantero de su granja, Nathan Gardner y su familia luchan contra un organismo extraterrestre mutante que infecta sus mentes y cuerpos, transformando su tranquila vida en una pesadilla tecnicolor.',
                'duration_in_minutes' => 120,
                'type_of_censorship'  => 'Todo Público A',
                'movie_gender_id'     => 1,
                'country_id'         => 3,
                'premier_date'        => '2020-11-15',
            ],
            [
                'code'                => '00006',
                'name'                => 'Black Widow',
                'image_path'          => 'viuda_negra.jpg',
                'url_trailer'         => 'https://www.youtube.com/watch?v=ybji16u608U',
                'summary'             => 'Al nacer, la Viuda Negra, también conocida como Natasha Romanova, se entrega a la KGB para convertirse en su agente definitivo. Cuando la URSS se separa, el gobierno intenta matarla mientras la acción se traslada a la actual Nueva York.',
                'duration_in_minutes' => 120,
                'type_of_censorship'  => 'Todo Público A',
                'movie_gender_id'     => 1,
                'country_id'         => 3,
                'premier_date'        => '2020-11-15',
            ],
            [
                'code'                => '00007',
                'name'                => 'Wonder Woman',
                'image_path'          => 'wonder_woman.jpeg',
                'url_trailer'         => 'https://www.youtube.com/watch?v=XW2E2Fnh52w',
                'summary'             => 'Diana Prince, conocida como Wonder Woman se enfrenta a Cheetah, una villana que posee fuerza y agilidad sobrehumanas.',
                'duration_in_minutes' => 120,
                'type_of_censorship'  => 'Todo Público A',
                'movie_gender_id'     => 1,
                'country_id'         => 3,
                'premier_date'        => '2020-11-15',
            ]
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
