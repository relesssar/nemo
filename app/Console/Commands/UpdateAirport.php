<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\NemoController;
use Illuminate\Support\Facades\Log;
use App\Models\Airport;

class UpdateAirport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-airport';

    /**
     * Download airports data from source.
     *
     * @var string
     */
    protected $description = 'Update airports list from source';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $result = NemoController::get_airports();
            $array = json_decode($result, true);

            Airport::truncate();
            foreach ($array as $key => $item) {
                $new_airopt = new Airport();
                $new_airopt->iata = $key;
                $new_airopt->name_ru = $item['cityName']['ru'];
                $new_airopt->name_en = $item['cityName']['en'];
                $new_airopt->country = $item['country'];
                $new_airopt->save();
            }
        } catch (Exception $e) {
            Log::error("Error: " . $e->getMessage());
        }
    }
}
