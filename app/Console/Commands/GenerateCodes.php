<?php

namespace App\Console\Commands;

use App\Models\PromotionCorporative\PromotionCorporative;
use Illuminate\Console\Command;

class GenerateCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        for ($i = 0; $i < 50; $i++) {
            PromotionCorporative::create([
                'codigo' => substr(\Str::random(), 0, 3),
                'fecha_creacion' => now(),
                'estado' => 0,
                "generado" => 0,
                "promotion_code" => 150
            ]);
        }
        return 0;
    }
}
