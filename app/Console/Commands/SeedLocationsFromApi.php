<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class SeedLocationsFromApi extends Command
{
    protected $signature = 'locations:seed {--limit=10 : Number of countries to process}';
    protected $description = 'Seed locations from CountriesNow API';

    private const API_BASE = 'https://countriesnow.space/api/v0.1/countries';

    public function handle()
    {
        $this->info('Starting location seeding...');
        $limit = (int) $this->option('limit');

        try {
            DB::beginTransaction();

            // Fetch and seed countries
            $countries = $this->seedCountries($limit);
            
            // Seed states and cities for each country
            foreach ($countries as $country) {
                $this->seedStatesAndCities($country);
            }

            DB::commit();
            $this->info('✓ Location seeding completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    private function seedCountries(int $limit): array
    {
        $this->info('Fetching countries...');
        
        $response = Http::timeout(30)->get(self::API_BASE . '/iso');
        
        if (!$response->ok()) {
            throw new \Exception('Failed to fetch countries');
        }

        $data = $response->json()['data'] ?? [];
        
        // Priority countries
        $priority = ['Pakistan', 'United States', 'United Kingdom', 'Canada', 'India', 'Australia'];
        
        $priorityCountries = collect($data)->filter(fn($c) => 
            in_array($c['name'] ?? '', $priority)
        )->take(count($priority));

        $otherCountries = collect($data)->filter(fn($c) => 
            !in_array($c['name'] ?? '', $priority)
        )->take($limit - $priorityCountries->count());

        $allCountries = $priorityCountries->concat($otherCountries);

        $bar = $this->output->createProgressBar($allCountries->count());
        $countries = [];

        foreach ($allCountries as $countryData) {
            $country = Country::updateOrCreate(
                ['iso2' => strtoupper($countryData['Iso2'] ?? $countryData['iso2'] ?? '')],
                [
                    'name' => $countryData['name'],
                    'iso3' => strtoupper($countryData['Iso3'] ?? $countryData['iso3'] ?? ''),
                ]
            );
            $countries[] = $country;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        return $countries;
    }

    private function seedStatesAndCities(Country $country): void
    {
        $this->info("Processing {$country->name}...");

        try {
            // Fetch states
            $response = Http::timeout(30)->post(self::API_BASE . '/states', [
                'country' => $country->name
            ]);

            if (!$response->ok()) {
                $this->warn("  ⚠ Failed to fetch states for {$country->name}");
                return;
            }

            $statesData = $response->json()['data']['states'] ?? [];

            if (empty($statesData)) {
                $this->warn("  ⚠ No states found for {$country->name}");
                return;
            }

            foreach ($statesData as $stateData) {
                $state = State::updateOrCreate(
                    [
                        'country_id' => $country->id,
                        'name' => $stateData['name']
                    ],
                    [
                        'state_code' => strtoupper($stateData['state_code'] ?? '')
                    ]
                );

                // Fetch cities for this state
                $this->seedCities($country, $state);
            }

        } catch (\Exception $e) {
            $this->warn("  ⚠ Error processing {$country->name}: " . $e->getMessage());
        }
    }

    private function seedCities(Country $country, State $state): void
    {
        try {
            $response = Http::timeout(30)->post(self::API_BASE . '/state/cities', [
                'country' => $country->name,
                'state' => $state->name
            ]);

            if (!$response->ok()) {
                return;
            }

            $citiesData = $response->json()['data'] ?? [];

            if (empty($citiesData)) {
                return;
            }

            $cities = collect($citiesData)->map(fn($cityName) => [
                'state_id' => $state->id,
                'name' => $cityName,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            // Bulk insert cities
            City::upsert($cities, ['state_id', 'name'], ['updated_at']);

            $this->info("  ✓ {$state->name}: " . count($cities) . " cities");

        } catch (\Exception $e) {
            // Silent fail for cities
        }
    }
}