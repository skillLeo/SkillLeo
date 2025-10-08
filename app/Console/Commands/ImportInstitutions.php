<?php

namespace App\Console\Commands;

use App\Models\Institution;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class ImportInstitutions extends Command
{
    protected $signature = 'institutions:import
        {--local= : Path to local JSON file (optional)}
        {--truncate : Truncate table before import}';

    protected $description = 'Import universities/colleges list (Hipolabs dataset) into institutions table';

    public function handle(): int
    {
        if ($this->option('truncate')) {
            \DB::table('institutions')->truncate();
            $this->info('institutions table truncated.');
        }

        $data = $this->loadData();
        if (empty($data) || !is_array($data)) {
            $this->error('No data found to import.');
            return self::FAILURE;
        }

        $bar = $this->output->createProgressBar(count($data));
        $bar->start();

        foreach ($data as $row) {
            // Hipolabs keys: name, country, alpha_two_code, state-province, web_pages, domains
            $name   = trim(Arr::get($row, 'name', ''));
            if ($name === '') { $bar->advance(); continue; }

            $domains = Arr::get($row, 'domains', []);
            $webs    = Arr::get($row, 'web_pages', []);
            $domain  = is_array($domains) && !empty($domains) ? $domains[0] : null;
            $website = is_array($webs) && !empty($webs) ? $webs[0] : ( $domain ? 'https://' . $domain : null );

            $logoUrl = $domain ? "https://www.google.com/s2/favicons?sz=64&domain={$domain}" : null;

            Institution::updateOrCreate(
                [
                    'name'        => $name,
                    'country'     => Arr::get($row, 'country'),
                ],
                [
                    'country_code'=> Arr::get($row, 'alpha_two_code'),
                    'city'        => Arr::get($row, 'state-province'),
                    'domains'     => $domains,
                    'website'     => $website,
                    'logo_url'    => $logoUrl,
                    'aliases'     => [],          // fill later if you enrich from Wikidata
                    'kind'        => 'university' // Hipolabs is mostly higher-ed
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Import complete.');

        return self::SUCCESS;
    }

    protected function loadData(): array
    {
        if ($local = $this->option('local')) {
            if (is_file($local)) {
                return json_decode(file_get_contents($local), true) ?: [];
            }
        }

        // Public canonical JSON (small, stable)
        $url = 'https://raw.githubusercontent.com/Hipo/university-domains-list/master/world_universities_and_domains.json';

        try {
            $resp = Http::timeout(30)->get($url);
            if ($resp->ok()) return $resp->json();
        } catch (\Throwable $e) {
            $this->warn('Remote fetch failed: ' . $e->getMessage());
        }

        return [];
    }
}
