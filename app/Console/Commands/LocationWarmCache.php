<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class LocationWarmCache extends Command
{
    protected $signature = 'location:warm-cache';
    protected $description = 'Download/refresh location JSON caches (countries/states/cities)';

    public function handle()
    {
        $base = 'https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/master';
        $targets = [
            'countries' => "$base/countries.json",
            'states'    => "$base/states.json",
            'cities'    => "$base/cities.json",
        ];
        Storage::makeDirectory('location');

        foreach ($targets as $name => $url) {
            $this->line("Fetching $name …");
            $resp = Http::timeout(120)->get($url);
            if (!$resp->ok()) { $this->error("Failed: $url"); continue; }

            Storage::put("location/$name.json", $resp->body());
            $this->info("Saved: storage/app/location/$name.json");
        }

        $this->info('Done.');
        return self::SUCCESS;
    }
}
