<?php

namespace App\Console\Commands;

use App\Allergen;
use App\DiningCenter;
use App\Food;
use App\Menu;
use App\Nutrition;
use App\Station;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use function array_key_exists;
use function urldecode;
use function var_dump;

class ImportScrapedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:netnutrition';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape the net nutrition web site';

    /** @var \Symfony\Component\Console\Helper\ProgressBar */
    protected $bar;

    /**
     * Execute the command
     *
     * @return int
     */
    public function handle()
    {
        /**
         * $bar = $this->output->createProgressBar(count($users));
         *
         * foreach ($items as $item) {
         * $this->performTask($item);
         *
         * $bar->advance();
         * }
         *
         * $bar->finish();
         */
        $webScraperData = $this->loadJson();

        $this->bar = $this->output->createProgressBar(count($webScraperData));
        $this->bar->setEmptyBarCharacter('-');
        $this->bar->setProgressCharacter('>');
        $this->bar->setBarCharacter('=');

        $this->foreachDiningCenter($webScraperData);

        $this->bar->finish();

        return 0;
    }

    /**
     * @return mixed
     */
    public function loadJson()
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '1000000000');

        return json_decode(File::get('app/Console/PythonWebScraper/data.json'), true, 2048);

    }

    /**
     * @param $diningCenters array
     */
    public function foreachDiningCenter($diningCenters)
    {
        foreach ($diningCenters as $diningCenterName => $diningCenterInformation) {
            $this->bar->advance();
            $diningCenter = DiningCenter::whereName($diningCenterName)
                ->firstOrCreate([
                    'name' => $diningCenterName,
                    'latitude' => 0,
                    'longitude' => 0,
                ]);

            if (array_key_exists('dates', $diningCenterInformation)) {
                $this->foreachDates($diningCenterInformation['dates'], $diningCenter);
            } elseif (array_key_exists('stations', $diningCenterInformation)) {
                $this->foreachStations($diningCenterInformation['stations'], $diningCenter);
            } else {
                abort(500, 'sjpipho@iastate managed to **** this up again');
            }
        }
    }

    /**
     * @param array $dates
     * @param DiningCenter $diningCenter
     * @param Station|null $station
     */
    public function foreachDates($dates, $diningCenter, $station = null)
    {
        foreach ($dates as $date => $currentMenu) {
            $start = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($date)));
            $end = Carbon::createFromFormat('Y-m-d', date('Y-m-d', strtotime($date)));
            $start->second(0)
                ->minute(0)
                ->hour(0);
            $end->second(0)
                ->minute(0)
                ->hour(0);

            $this->foreachMenus($currentMenu['meals'], $diningCenter, $start, $end, $station);
        }
    }

    /**
     * @param array $menu
     * @param DiningCenter $diningCenter
     * @param Carbon $start
     * @param Carbon $end
     * @param Station|null $station
     */
    public function foreachMenus($menu, $diningCenter, $start, $end, $station = null)
    {
        foreach ($menu as $menuName => $foods) {
            switch (strtolower($menuName)) {
                case "breakfast":
                    $start->hour(Menu::BREAKFAST_START);
                    $end->hour(Menu::BREAKFAST_END - 1)
                        ->minute(59)
                        ->second(59);
                    break;
                case "lunch":
                    $start->hour(Menu::LUNCH_START);
                    $end->hour(Menu::LUNCH_END - 1)
                        ->minute(59)
                        ->second(59);
                    break;
                case "dinner":
                    $start->hour(Menu::DINNER_START);
                    $end->hour(Menu::DINNER_END - 1)
                        ->minute(59)
                        ->second(59);
                    break;
                case "late night":
                    $start->hour(Menu::LATENIGHT_START);
                    $end->hour(Menu::LATENIGHT_END - 1)
                        ->minute(59)
                        ->second(59);
                    break;
                case "daily":
                default:
                    $end->hour(23)
                        ->minute(59)
                        ->second(59);
                    break;
            }
            $menu = Menu::where('dining_center_id', $diningCenter->id)
                ->where('station_id', $station ? $station->id : null)
                ->where('name', $menuName)
                ->where('start', $start)
                ->where('end', $end)
                ->firstOrCreate([
                    'dining_center_id' => $diningCenter->id,
                    'name' => $menuName,
                    'station_id' => $station ? $station->id : null,
                    'start' => $start,
                    'end' => $end,
                ]);

            $this->foreachFoods($foods['foods'], $menu);
        }
    }

    /**
     * @param array $foods
     * @param Menu $menu
     */
    public function foreachFoods($foods, $menu)
    {
        foreach ($foods as $foodInfo) {
            if (array_key_exists('name', $foodInfo)) {
                $food = Food::where('name', $foodInfo['name'])
                    ->firstOrCreate([
                        'name' => $foodInfo['name'],
                    ]);

                $this->filterNutritionInformation($food, $foodInfo);

                if (!$food->menus->contains($menu->id)) {
                    $food->menus()->attach($menu->id, [
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
    }

    /**
     * @param Food $foodItem
     * @param array $foodNutrition
     */
    public function filterNutritionInformation($foodItem, $foodNutrition)
    {
        foreach (Nutrition::TYPES as $type) {
            if (array_key_exists($type, $foodNutrition)) {
                Nutrition::where('name', $type)
                    ->where('food_id', $foodItem->id)
                    ->firstOrCreate([
                        'name' => $type,
                        'value' => trim(urldecode(str_replace('%C2%A0', '', urlencode($foodNutrition[$type])))),
                        'food_id' => $foodItem->id,
                    ])
                    ->fill([
                        'value' => trim(urldecode(str_replace('%C2%A0', '', urlencode($foodNutrition[$type])))),
                    ])
                    ->save();
            }
        }

        if (array_key_exists('allergens', $foodNutrition)) {
            foreach (explode(',', $foodNutrition['allergens']) as $allergen) {
                $allergen = trim(urldecode(str_replace('%C2%A0', '', urlencode($allergen))));

                $allergen = tap(Allergen::where('name', $allergen)
                    ->firstOrCreate([
                        'name' => $allergen,
                    ])
                    ->fill([
                        'name' => $allergen,
                    ]), function ($item) {
                    $item->save();
                });

                if (!$foodItem->allergens->map(function ($allergen) {
                    return $allergen->id;
                })->contains($allergen->id)) {
                    $foodItem->allergens()->attach($allergen->id, [
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
    }

    public function foreachStations($stations, $diningCenter)
    {
        foreach ($stations as $stationName => $stationInformation) {
            $station = Station::whereName($stationName)
                ->firstOrCreate([
                    'name' => $stationName,
                    'dining_center_id' => $diningCenter->id,
                ]);

            $this->foreachDates($stationInformation['dates'], $diningCenter, $station);
        }
    }
}